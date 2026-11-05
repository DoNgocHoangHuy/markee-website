<?php
/**
 * Thông tin công ty — MỘT nơi duy nhất để sửa.
 * Vào: Giao diện -> Tuỳ biến -> "Thông tin công ty". Footer, trang Liên hệ, trang Dịch vụ tự cập nhật theo.
 * Mạng xã hội để trống thì nút/liên kết đó tự ẩn (không còn link "#" chết).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function saybencode_info_defaults() {
    return array(
        'address_full'  => 'Tầng 12, Toà nhà Innovation, 15 Nguyễn Huệ, Quận 1, Thành phố Hồ Chí Minh',
        'address_short' => 'Tầng 12, Toà nhà Innovation, 15 Nguyễn Huệ, Q.1, TP.HCM',
        'hotline_1'     => '1900 1234',
        'hotline_2'     => '0906 123 4567',
        'email'         => 'contact@saybencodes.com',
        'hours'         => 'Thứ 2 – Thứ 7, 8:30 – 18:00',
        'facebook'      => '',
        'linkedin'      => '',
        'instagram'     => '',
        'youtube'       => '',
    );
}

/** Lấy 1 giá trị thông tin công ty. */
function saybencode_info( $key ) {
    $d   = saybencode_info_defaults();
    $def = isset( $d[ $key ] ) ? $d[ $key ] : '';
    $val = trim( (string) get_theme_mod( 'sb_' . $key, $def ) );
    // Ô bắt buộc để trống -> dùng mặc định; riêng mạng xã hội cho phép trống (để ẩn)
    if ( '' === $val && ! in_array( $key, array( 'facebook', 'linkedin', 'instagram', 'youtube', 'hotline_2' ), true ) ) $val = $def;
    return $val;
}

/** Số điện thoại -> chỉ giữ chữ số (dùng cho tel:). */
function saybencode_tel( $phone ) {
    return preg_replace( '/[^0-9+]/', '', (string) $phone );
}

/** Các mạng xã hội đã điền: mảng [ [nhãn, ký hiệu ngắn, url], ... ]. */
function saybencode_socials() {
    $all = array(
        array( 'Facebook',  'f',  'facebook' ),
        array( 'LinkedIn',  'in', 'linkedin' ),
        array( 'Instagram', 'ig', 'instagram' ),
        array( 'YouTube',   'yt', 'youtube' ),
    );
    $out = array();
    foreach ( $all as $s ) {
        $url = saybencode_info( $s[2] );
        if ( $url ) $out[] = array( $s[0], $s[1], $url );
    }
    return $out;
}

/** Link tới trang pháp lý nếu trang tồn tại (slug), ngược lại trả '' . */
function saybencode_page_link( $slug ) {
    $p = get_page_by_path( $slug );
    return ( $p && 'publish' === $p->post_status ) ? get_permalink( $p ) : '';
}

add_action( 'customize_register', function ( $wp ) {
    $wp->add_section( 'sb_company', array(
        'title'       => 'Thông tin công ty',
        'priority'    => 30,
        'description' => 'Hiển thị ở footer, trang Liên hệ và trang Dịch vụ. Mạng xã hội để trống sẽ tự ẩn.',
    ) );
    $fields = array(
        'address_full'  => array( 'Địa chỉ đầy đủ (dùng cho Google Maps)', 'text',  'sanitize_text_field' ),
        'address_short' => array( 'Địa chỉ ngắn (footer, trang Liên hệ)',   'text',  'sanitize_text_field' ),
        'hotline_1'     => array( 'Hotline 1',                              'text',  'sanitize_text_field' ),
        'hotline_2'     => array( 'Hotline 2 (không bắt buộc)',             'text',  'sanitize_text_field' ),
        'email'         => array( 'Email liên hệ',                          'text',  'sanitize_email' ),
        'hours'         => array( 'Giờ làm việc',                           'text',  'sanitize_text_field' ),
        'facebook'      => array( 'Link Facebook',                          'url',   'esc_url_raw' ),
        'linkedin'      => array( 'Link LinkedIn',                          'url',   'esc_url_raw' ),
        'instagram'     => array( 'Link Instagram',                         'url',   'esc_url_raw' ),
        'youtube'       => array( 'Link YouTube',                           'url',   'esc_url_raw' ),
    );
    $d = saybencode_info_defaults();
    foreach ( $fields as $key => $f ) {
        $wp->add_setting( 'sb_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => $f[2], 'transport' => 'refresh' ) );
        $wp->add_control( 'sb_' . $key, array( 'label' => $f[0], 'section' => 'sb_company', 'type' => $f[1] ) );
    }
} );

/** Nhắc khi site đang chặn công cụ tìm kiếm (bỏ qua môi trường thử nghiệm .local/.test/localhost). */
add_action( 'admin_notices', function () {
    if ( '0' !== (string) get_option( 'blog_public' ) || ! current_user_can( 'manage_options' ) ) return;
    $host = wp_parse_url( home_url(), PHP_URL_HOST );
    if ( ! $host || preg_match( '/(\.local|\.test|\.localhost|^localhost)$/i', $host ) ) return;
    printf( '<div class="notice notice-warning"><p><strong>Website đang chặn Google lập chỉ mục.</strong> Khi chạy thật, vào <a href="%s">Cài đặt → Đọc</a> và bỏ tích ô "Ngăn công cụ tìm kiếm lập chỉ mục trang web này".</p></div>', esc_url( admin_url( 'options-reading.php' ) ) );
} );
