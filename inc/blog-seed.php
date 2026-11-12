<?php
/**
 * Tự tạo bài blog mẫu (chạy 1 lần) để trang Blog đủ 1 trang: 1 bài nổi bật + 4 bài, các bài còn lại nằm ở chuyên mục.
 *
 * - Chỉ tạo bài chưa có (so theo slug) nên an toàn khi chạy lại.
 * - Nội dung dựng từ mẫu trong blog-content.php, ghi thẳng vào bài -> mở ra sửa như bình thường.
 * - Bài "Hello world!" mặc định của WordPress (chưa sửa) được chuyển vào thùng rác; có thể khôi phục.
 * - Muốn tạo lại: xoá option "saybencode_blog_seeded" hoặc thêm ?sb_seed_blog=1 khi đang đăng nhập quản trị.
 * Đây là NỘI DUNG MẪU — hãy thay bằng bài thật trước khi đăng chính thức.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function saybencode_seed_blog_data() {
    // slug => [tiêu đề, chuyên mục, mô tả ngắn (excerpt + meta description)]
    return array(
        array( 'seo-nam-2026-5-huong-di-giup-website-len-top-ben-vung', 'SEO năm 2026: 5 hướng đi giúp website lên top bền vững', 'Marketing',
            'Thay vì chạy theo mẹo ngắn hạn, hãy tập trung vào nội dung đúng nhu cầu, nền tảng kỹ thuật vững và đo lường liên tục để giữ thứ hạng lâu dài.' ),
        array( 'ai-tao-sinh-trong-marketing-bat-dau-tu-dau-cho-dung', 'AI tạo sinh trong marketing: bắt đầu từ đâu cho đúng?', 'Công nghệ & Media',
            'AI giúp rút ngắn các công đoạn lặp lại trong làm nội dung và chăm sóc khách hàng. Cùng xem cách thử nghiệm nhỏ, rõ lợi ích và kiểm soát chất lượng.' ),
        array( 'quang-cao-facebook-google-ads-cach-toi-uu-ngan-sach', 'Quảng cáo Facebook & Google Ads: cách tối ưu ngân sách', 'Marketing',
            'Mục tiêu rõ ràng, thông điệp được thử nghiệm và số liệu được xem lại hằng tuần là ba yếu tố giúp mỗi đồng quảng cáo mang lại nhiều khách hàng hơn.' ),
        array( 'social-media-xay-dung-tru-cot-noi-dung-cho-thuong-hieu', 'Social media: xây dựng trụ cột nội dung cho thương hiệu', 'Marketing',
            'Chia nội dung thành vài nhóm chính, hiểu thói quen từng nền tảng và phản hồi khán giả kịp thời để kênh mạng xã hội phát triển đều đặn.' ),
        array( 'chuyen-doi-so-cho-doanh-nghiep-vua-va-nho-lo-trinh-4-buoc', 'Chuyển đổi số cho doanh nghiệp vừa và nhỏ: lộ trình 4 bước', 'Công nghệ & Media',
            'Bắt đầu từ công đoạn tốn thời gian nhất, số hoá từng bước và đo lường kết quả để chuyển đổi số mang lại giá trị thật thay vì chỉ mua thêm công cụ.' ),
        array( 'email-marketing-gui-dung-noi-dung-cho-dung-nguoi-nhan', 'Email marketing: gửi đúng nội dung cho đúng người nhận', 'Marketing',
            'Phân nhóm danh sách, viết tiêu đề cụ thể và theo dõi tỷ lệ mở, nhấp chuột giúp email trở thành kênh chăm sóc khách hàng hiệu quả, chi phí hợp lý.' ),
        array( 'content-marketing-lap-ke-hoach-hieu-qua-cho-doanh-nghiep-nho', 'Content marketing: lập kế hoạch nội dung hiệu quả cho doanh nghiệp nhỏ', 'Marketing',
            'Từ chân dung khách hàng đến lịch nội dung theo tháng: cách đăng đúng, đăng đều và tái sử dụng một bài chuyên sâu thành nhiều định dạng.' ),
        array( 'saybencode-ra-mat-bo-nhan-dien-thuong-hieu-moi', 'Saybencode ra mắt bộ nhận diện thương hiệu mới', 'Tin tức tổng hợp',
            'Bộ nhận diện mới thể hiện tinh thần kết nối giá trị thực qua logo, bảng màu và ngôn ngữ hình ảnh, được áp dụng đồng bộ trên mọi điểm chạm.' ),
    );
}

function saybencode_seed_blog( $force = false ) {
    if ( ! $force && get_option( 'saybencode_blog_seeded' ) ) return 0;
    if ( ! function_exists( 'saybencode_build_content' ) ) return 0;

    $admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID', 'orderby' => 'ID' ) );
    $author = $admins ? (int) $admins[0] : 1;

    // Chuyên mục
    $cat_ids = array();
    foreach ( array( 'Marketing', 'Công nghệ & Media', 'Tin tức tổng hợp' ) as $name ) {
        $t = term_exists( $name, 'category' );
        if ( ! $t ) $t = wp_insert_term( $name, 'category' );
        if ( ! is_wp_error( $t ) ) $cat_ids[ $name ] = (int) ( is_array( $t ) ? $t['term_id'] : $t );
    }

    $created = 0;
    $now     = current_time( 'timestamp' );
    foreach ( saybencode_seed_blog_data() as $i => $row ) {
        list( $slug, $title, $cat, $excerpt ) = $row;
        if ( get_page_by_path( $slug, OBJECT, 'post' ) ) continue;

        $when = $now - ( $i * 2 * DAY_IN_SECONDS ) - ( $i * 3600 ); // bài đầu tiên mới nhất -> thành bài nổi bật
        $id   = wp_insert_post( wp_slash( array(
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'post_title'    => $title,
            'post_name'     => $slug,
            'post_excerpt'  => $excerpt,
            'post_author'   => $author,
            'post_date'     => wp_date( 'Y-m-d H:i:s', $when ),
            'post_category' => isset( $cat_ids[ $cat ] ) ? array( $cat_ids[ $cat ] ) : array(),
        ) ), true );
        if ( is_wp_error( $id ) || ! $id ) continue;

        wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => saybencode_build_content( $id ) ) ) );
        update_post_meta( $id, '_sb_std_content', 1 );
        update_post_meta( $id, SAYBENCODE_META_TITLE, mb_substr( $title, 0, 60 ) . ' | ' . saybencode_brand() );
        update_post_meta( $id, SAYBENCODE_META_DESC, $excerpt );
        $created++;
    }

    // Bài mẫu mặc định của WordPress (chưa ai sửa) -> thùng rác, để không chiếm vị trí bài nổi bật
    $hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
    if ( $hello && 'publish' === $hello->post_status && false !== strpos( $hello->post_content, 'Welcome to WordPress' ) ) {
        wp_trash_post( $hello->ID );
    }

    update_option( 'saybencode_blog_seeded', 1, false );
    return $created;
}

// Chạy 1 lần ở lần tải trang đầu tiên (không chạy trong REST/AJAX/cron)
add_action( 'init', function () {
    if ( wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) return;
    $force = isset( $_GET['sb_seed_blog'] ) && current_user_can( 'manage_options' );
    saybencode_seed_blog( $force );
}, 30 );
