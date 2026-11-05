<?php
/**
 * Saybencode Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Tên thương hiệu hiển thị (logo, footer). Có thể đổi bằng filter. */
function saybencode_brand() {
    return apply_filters( 'saybencode_brand', 'Saybencode' );
}

// Theme support
function saybencode_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Menu chính', 'saybencode' ),
    ) );
}
add_action( 'after_setup_theme', 'saybencode_theme_setup' );

// Enqueue styles + scripts (font Be Vietnam Pro được tự host trong style.css)
function saybencode_enqueue_assets() {
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();

    wp_enqueue_style( 'saybencode-style', get_stylesheet_uri(), array(), filemtime( $dir . '/style.css' ) );
    wp_enqueue_script( 'saybencode-site', $uri . '/assets/js/site.js', array(), filemtime( $dir . '/assets/js/site.js' ), true );

    if ( is_page( 'lien-he' ) || is_page_template( 'page-lien-he.php' ) ) {
        wp_enqueue_script( 'saybencode-contact', $uri . '/assets/js/contact.js', array(), filemtime( $dir . '/assets/js/contact.js' ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'saybencode_enqueue_assets' );

// Preload các file font dùng nhiều nhất để tránh nháy font
function saybencode_preload_fonts() {
    $base = get_stylesheet_directory_uri() . '/assets/fonts/';
    foreach ( array( 'be-vietnam-pro-vietnamese-400-normal.woff2', 'be-vietnam-pro-latin-400-normal.woff2', 'be-vietnam-pro-latin-700-normal.woff2' ) as $f ) {
        printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $base . $f ) );
    }
}
add_action( 'wp_head', 'saybencode_preload_fonts', 1 );

// Excerpt length for blog cards
function saybencode_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'saybencode_excerpt_length' );


/**
 * Hình ảnh của theme: lấy từ assets/img/ trong theme (không phụ thuộc Unsplash/hotlink).
 * Muốn dùng hình trên GitHub: thêm vào wp-config.php
 *   define( 'SAYBENCODE_IMG_BASE', 'https://raw.githubusercontent.com/USER/REPO/main/img/' );
 * (các file cùng tên: team.svg, office.svg, post-fallback.svg — hoặc .jpg nếu bạn đổi tên ở filter bên dưới)
 */
function saybencode_img_url( $file ) {
    if ( defined( 'SAYBENCODE_IMG_BASE' ) && SAYBENCODE_IMG_BASE ) {
        // Ảnh trên GitHub dùng đuôi .jpg (ảnh thật) -> tự đổi .svg thành .jpg
        $base = trailingslashit( SAYBENCODE_IMG_BASE );
        $file = preg_replace( '/\.svg$/', '.jpg', $file );
    } else {
        // Ưu tiên ảnh thật .jpg đặt trong theme (cùng tên) thay cho hình vẽ .svg
        $jpg = preg_replace( '/\.svg$/', '.jpg', $file );
        if ( $jpg !== $file && file_exists( get_stylesheet_directory() . '/assets/img/' . $jpg ) ) $file = $jpg;
        $base = get_stylesheet_directory_uri() . '/assets/img/';
    }
    return apply_filters( 'saybencode_img_url', $base . ltrim( $file, '/' ), $file );
}

/** Khoá chủ đề của bài (marketing | cong-nghe | tin-tuc | chung) theo chuyên mục. */
function saybencode_topic_key( $post_id = 0 ) {
    $cat  = saybencode_primary_cat( $post_id );
    $slug = $cat ? $cat->slug : '';
    if ( false !== strpos( $slug, 'marketing' ) ) return 'marketing';
    if ( false !== strpos( $slug, 'cong-nghe' ) || false !== strpos( $slug, 'media' ) ) return 'cong-nghe';
    if ( false !== strpos( $slug, 'tin-tuc' ) ) return 'tin-tuc';
    return 'chung';
}

/** Chọn tên file ảnh cho 1 bài: ưu tiên theo tên bài, không khớp thì xoay vòng trong nhóm chuyên mục. */
function saybencode_blog_pick( $post_id ) {
    $dir  = get_stylesheet_directory() . '/assets/img/blog/';
    $hay  = get_post_field( 'post_name', $post_id ) . ' ' . sanitize_title( get_the_title( $post_id ) );
    $rules = array(
        '/bo-nhan-dien|nhan-dien-thuong-hieu/'  => 'special-nhan-dien.jpg',
        '/(^|[\s-])seo([\s-]|$)/'             => 'marketing-1.jpg',
        '/ai-tao-sinh|(^|[\s-])ai([\s-]|$)/'  => 'cong-nghe-1.jpg',
        '/social/'                              => 'marketing-2.jpg',
        '/facebook|google-ads/'                 => 'marketing-5.jpg',
        '/email/'                               => 'marketing-3.jpg',
        '/content/'                             => 'marketing-4.jpg',
    );
    foreach ( $rules as $re => $file ) {
        if ( preg_match( $re, $hay ) && file_exists( $dir . $file ) ) return $file;
    }
    $key   = saybencode_topic_key( $post_id );
    $files = glob( $dir . $key . '-*.jpg' );
    $count = $files ? count( $files ) : 0;
    if ( ! $count ) return $key . '-1.jpg';
    return $key . '-' . ( ( (int) $post_id % $count ) + 1 ) . '.jpg';
}

/** Ảnh minh hoạ theo chuyên mục (luôn lấy từ theme, không phụ thuộc GitHub). */
function saybencode_topic_img_url( $post_id = 0 ) {
    $key = saybencode_topic_key( $post_id );
    $dir = get_stylesheet_directory() . '/assets/img/';
    $uri = get_stylesheet_directory_uri() . '/assets/img/';
    // Ảnh thật: xoay vòng trong nhóm chuyên mục (mỗi bài một ảnh, hết thì lặp lại)
    $pid  = $post_id ? $post_id : get_the_ID();
    $file = function_exists( 'saybencode_blog_pick' ) ? saybencode_blog_pick( $pid ) : $key . '-1.jpg';
    if ( file_exists( $dir . 'blog/' . $file ) ) return $uri . 'blog/' . $file;
    $map = array( 'marketing' => 'marketing.svg', 'cong-nghe' => 'cong-nghe.svg', 'tin-tuc' => 'tin-tuc.svg', 'chung' => 'post-fallback.svg' );
    return $uri . $map[ $key ];
}

/** Ảnh đại diện bài viết; chưa có ảnh thì dùng hình minh hoạ đúng chuyên mục (không bao giờ ô trống/vỡ). */
function saybencode_thumb( $size = 'medium_large', $attr = array() ) {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( $size, $attr );
        return;
    }
    $alt = isset( $attr['alt'] ) ? $attr['alt'] : '';
    printf( '<img class="thumb-fallback" src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( saybencode_topic_img_url() ), esc_attr( $alt ) );
}

/** Chuyên mục chính của bài (bỏ qua "Uncategorized"/"Chưa phân loại"). */
function saybencode_primary_cat( $post_id = 0 ) {
    foreach ( get_the_category( $post_id ?: get_the_ID() ) as $c ) {
        if ( 'uncategorized' !== $c->slug ) return $c;
    }
    return null;
}

/** Thời gian đọc ước tính (~220 từ/phút). */
function saybencode_reading_time( $post_id = 0 ) {
    $text  = wp_strip_all_tags( get_post_field( 'post_content', $post_id ?: get_the_ID() ) );
    $words = preg_match_all( '/\p{L}+/u', $text );
    return max( 1, (int) ceil( $words / 220 ) );
}

/** Dấu cắt đoạn trích gọn gàng. */
add_filter( 'excerpt_more', function () { return '…'; } );

// Chuyên mục mặc định cho Blog
function saybencode_register_default_categories() {
    $cats = array( 'Marketing', 'Công nghệ & Media', 'Tin tức tổng hợp' );
    foreach ( $cats as $cat ) {
        if ( ! term_exists( $cat, 'category' ) ) {
            wp_insert_term( $cat, 'category' );
        }
    }
}
add_action( 'after_switch_theme', 'saybencode_register_default_categories' );

require_once get_stylesheet_directory() . '/inc/site-info.php';
require_once get_stylesheet_directory() . '/inc/legal-pages.php';
require_once get_stylesheet_directory() . '/inc/icons.php';
require_once get_stylesheet_directory() . '/inc/services.php';
require_once get_stylesheet_directory() . '/inc/elementor.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/contact-form.php';
require_once get_stylesheet_directory() . '/inc/blog-content.php';

/** URL trang Blog (/blog/). */
function saybencode_blog_url() {
    $id = (int) get_option( 'page_for_posts' );
    return $id ? get_permalink( $id ) : home_url( '/blog/' );
}

/** Đăng ký nhận tin (sidebar Blog): kiểm tra rồi gửi email báo cho quản trị. */
function saybencode_handle_subscribe() {
    $back = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );
    $back = wp_validate_redirect( $back, home_url( '/' ) );
    $nonce = isset( $_POST['sub_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sub_nonce'] ) ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    if ( ! empty( $_POST['website'] ) ) { // bot
        wp_safe_redirect( add_query_arg( 'dang-ky', 'ok', $back ) . '#dang-ky-nhan-tin' ); exit;
    }
    if ( ! wp_verify_nonce( $nonce, 'saybencode_subscribe' ) || ! is_email( $email ) ) {
        wp_safe_redirect( add_query_arg( 'dang-ky', 'loi', $back ) . '#dang-ky-nhan-tin' ); exit;
    }

    // Lưu danh sách (không trùng) + báo cho quản trị
    $list = get_option( 'saybencode_subscribers', array() );
    if ( ! in_array( $email, $list, true ) ) {
        $list[] = $email;
        update_option( 'saybencode_subscribers', $list, false );
        wp_mail( get_option( 'admin_email' ), 'Đăng ký nhận tin mới', "Email: $email\nWebsite: " . home_url( '/' ) );
    }
    wp_safe_redirect( add_query_arg( 'dang-ky', 'ok', $back ) . '#dang-ky-nhan-tin' ); exit;
}
add_action( 'admin_post_nopriv_saybencode_subscribe', 'saybencode_handle_subscribe' );
add_action( 'admin_post_saybencode_subscribe', 'saybencode_handle_subscribe' );

/** Số bài mỗi trang: Blog 5 (1 nổi bật + 4), chuyên mục/tìm kiếm 6 (lưới 2 cột). */
add_action( 'pre_get_posts', function ( $q ) {
    if ( is_admin() || ! $q->is_main_query() ) return;
    if ( $q->is_home() )                                  $q->set( 'posts_per_page', 5 );
    elseif ( $q->is_category() || $q->is_tag() || $q->is_author() || $q->is_date() || $q->is_search() ) $q->set( 'posts_per_page', 6 );
} );

/** Phân trang dùng chung (query chính). */
function saybencode_pagination() {
    global $wp_query;
    $total = (int) $wp_query->max_num_pages;
    if ( $total < 2 ) return;
    echo '<nav class="pagination" aria-label="Phân trang">';
    echo paginate_links( array(
        'current'   => max( 1, (int) get_query_var( 'paged' ) ),
        'total'     => $total,
        'mid_size'  => 1,
        'prev_text' => '‹',
        'next_text' => '›',
    ) );
    echo '</nav>';
}


/** Blog chỉ có trang 1: /blog/page/2/ ... chuyển hướng 301 về /blog/ (tránh trang trống/trùng nội dung). */
add_action( 'template_redirect', function () {
    if ( is_home() && ! is_front_page() && is_paged() ) {
        wp_safe_redirect( saybencode_blog_url(), 301 );
        exit;
    }
} );
