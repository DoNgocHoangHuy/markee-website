<?php
/**
 * Form liên hệ — xử lý dữ liệu gửi lên.
 *
 * Gọi saybencode_contact_process() ở đầu page-lien-he.php (trước get_header)
 * để có thể redirect sau khi gửi thành công (Post/Redirect/Get),
 * tránh việc F5 gửi lại form.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Các lựa chọn "Dịch vụ quan tâm" (key => nhãn). */
function saybencode_contact_services() {
    return array(
        'marketing' => 'Marketing',
        'cong-nghe' => 'Công nghệ',
        'media'     => 'Media',
        'chua-ro'   => 'Chưa rõ, cần tư vấn',
    );
}

/** Chuẩn hoá số điện thoại: bỏ khoảng trắng, dấu chấm, gạch ngang. */
function saybencode_contact_normalize_phone( $phone ) {
    return preg_replace( '/[\s\-.]+/', '', $phone );
}

/**
 * Xử lý request. Trả về mảng trạng thái cho template:
 *  - status : 'idle' | 'success' | 'error'
 *  - errors : [ field => message ]  (lỗi từng ô)
 *  - notice : lỗi chung (không gắn với ô nào, vd: gửi mail thất bại)
 *  - values : giá trị đã nhập (để giữ lại khi có lỗi)
 */
function saybencode_contact_process() {
    $state = array(
        'status' => 'idle',
        'errors' => array(),
        'notice' => '',
        'values' => array(
            'ho_ten'   => '',
            'sdt'      => '',
            'email'    => '',
            'dich_vu'  => '',
            'noi_dung' => '',
        ),
    );

    // Trang hiển thị sau khi redirect thành công
    if ( isset( $_GET['gui'] ) && 'thanh-cong' === $_GET['gui'] ) {
        $state['status'] = 'success';
        return $state;
    }

    if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['saybencode_contact'] ) ) {
        return $state;
    }

    // ---- Nonce ----
    $nonce = isset( $_POST['contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'contact_form_submit' ) ) {
        $state['status'] = 'error';
        $state['notice'] = 'Phiên gửi biểu mẫu đã hết hạn. Vui lòng tải lại trang và thử lại.';
        return $state;
    }

    // ---- Honeypot: bot điền ô ẩn -> giả vờ thành công, không gửi mail ----
    if ( ! empty( $_POST['website'] ) ) {
        saybencode_contact_redirect_success();
    }

    // ---- Lấy dữ liệu ----
    $v = array(
        'ho_ten'   => isset( $_POST['ho_ten'] )   ? sanitize_text_field( wp_unslash( $_POST['ho_ten'] ) )         : '',
        'sdt'      => isset( $_POST['sdt'] )      ? sanitize_text_field( wp_unslash( $_POST['sdt'] ) )           : '',
        'email'    => isset( $_POST['email'] )    ? sanitize_email( wp_unslash( $_POST['email'] ) )              : '',
        'dich_vu'  => isset( $_POST['dich_vu'] )  ? sanitize_key( wp_unslash( $_POST['dich_vu'] ) )              : '',
        'noi_dung' => isset( $_POST['noi_dung'] ) ? sanitize_textarea_field( wp_unslash( $_POST['noi_dung'] ) ) : '',
    );
    $state['values'] = $v;

    // ---- Validate từng ô ----
    $errors = array();

    if ( '' === $v['ho_ten'] ) {
        $errors['ho_ten'] = 'Vui lòng nhập họ và tên.';
    }

    $phone = saybencode_contact_normalize_phone( $v['sdt'] );
    if ( '' === $v['sdt'] ) {
        $errors['sdt'] = 'Vui lòng nhập số điện thoại.';
    } elseif ( ! preg_match( '/^(0[0-9]{9}|\+84[0-9]{9})$/', $phone ) ) {
        $errors['sdt'] = 'Số điện thoại chưa đúng. Ví dụ: 0906 123 456 hoặc +84 906 123 456.';
    }

    if ( '' === $v['email'] ) {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif ( ! is_email( $v['email'] ) ) {
        $errors['email'] = 'Email chưa đúng định dạng. Ví dụ: ban@congty.com';
    }

    if ( '' === $v['noi_dung'] ) {
        $errors['noi_dung'] = 'Vui lòng mô tả ngắn gọn nhu cầu của bạn.';
    } elseif ( mb_strlen( $v['noi_dung'] ) < 10 ) {
        $errors['noi_dung'] = 'Nội dung hơi ngắn. Hãy mô tả thêm để chúng tôi tư vấn đúng hơn.';
    }

    if ( $v['dich_vu'] && ! array_key_exists( $v['dich_vu'], saybencode_contact_services() ) ) {
        $v['dich_vu'] = '';
        $state['values']['dich_vu'] = '';
    }

    if ( $errors ) {
        $state['status'] = 'error';
        $state['errors'] = $errors;
        return $state;
    }

    // ---- Giới hạn tần suất: 1 lần / 30 giây / IP ----
    $ip_key = 'saybencode_cf_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' );
    if ( get_transient( $ip_key ) ) {
        $state['status'] = 'error';
        $state['notice'] = 'Bạn vừa gửi yêu cầu. Vui lòng chờ ít phút rồi gửi lại.';
        return $state;
    }

    // ---- Gửi mail ----
    $to = get_option( 'admin_email' );
    if ( ! is_email( $to ) ) {
        $state['status'] = 'error';
        $state['notice'] = 'Email nhận thông báo của website chưa được cấu hình. Vui lòng liên hệ qua hotline.';
        return $state;
    }

    $services = saybencode_contact_services();
    $service_label = $v['dich_vu'] ? $services[ $v['dich_vu'] ] : 'Không chọn';

    $body  = "CÓ YÊU CẦU TƯ VẤN MỚI\n";
    $body .= "------------------------------------\n";
    $body .= 'Họ và tên     : ' . $v['ho_ten'] . "\n";
    $body .= 'Số điện thoại : ' . $phone . "\n";
    $body .= 'Email         : ' . $v['email'] . "\n";
    $body .= 'Dịch vụ       : ' . $service_label . "\n";
    $body .= "------------------------------------\n\n";
    $body .= "NỘI DUNG\n" . $v['noi_dung'] . "\n\n";
    $body .= "------------------------------------\n";
    $body .= 'Thời gian: ' . current_time( 'd/m/Y H:i:s' ) . "\n";
    $body .= 'Website  : ' . home_url( '/' ) . "\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $v['ho_ten'] . ' <' . $v['email'] . '>',
    );

    $mail_error = '';
    $on_fail = function ( $err ) use ( &$mail_error ) {
        if ( $err instanceof WP_Error ) {
            $mail_error = $err->get_error_message();
        }
    };
    add_action( 'wp_mail_failed', $on_fail );
    $sent = wp_mail( $to, 'Yêu cầu tư vấn từ ' . $v['ho_ten'], $body, $headers );
    remove_action( 'wp_mail_failed', $on_fail );

    if ( ! $sent ) {
        $state['status'] = 'error';
        $state['notice'] = 'Chưa gửi được yêu cầu. Vui lòng thử lại hoặc gọi hotline.';
        // Chi tiết kỹ thuật chỉ cho quản trị viên
        if ( current_user_can( 'manage_options' ) ) {
            $state['notice'] .= ' [Admin: ' . ( $mail_error ? $mail_error : 'wp_mail() trả về false, kiểm tra cấu hình WP Mail SMTP.' ) . ']';
        }
        return $state;
    }

    set_transient( $ip_key, 1, 30 );
    saybencode_contact_redirect_success();
}

/** Redirect về chính trang liên hệ với ?gui=thanh-cong (Post/Redirect/Get). */
function saybencode_contact_redirect_success() {
    $url = add_query_arg( 'gui', 'thanh-cong', get_permalink() ) . '#lien-he-form';
    wp_safe_redirect( $url, 303 );
    exit;
}

/** Thuộc tính aria cho một ô: aria-invalid + aria-describedby */
function saybencode_cf_aria( $field, $errors ) {
    $desc = isset( $errors[ $field ] ) ? ' aria-invalid="true" aria-describedby="err-' . esc_attr( $field ) . '"' : '';
    return $desc;
}
