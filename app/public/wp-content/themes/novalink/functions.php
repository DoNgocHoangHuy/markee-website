<?php
/**
 * Novalink Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Theme support
function novalink_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Menu chính', 'novalink' ),
    ) );
}
add_action( 'after_setup_theme', 'novalink_theme_setup' );

// Enqueue styles + Google Font
function novalink_enqueue_assets() {
    wp_enqueue_style( 'novalink-google-font', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'novalink-style', get_stylesheet_uri(), array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'novalink_enqueue_assets' );

// ---------------------------------------------------------------
// FIX: Thẻ Canonical đầy đủ cho MỌI loại trang
// WordPress mặc định (rel_canonical) chỉ xử lý trang đơn (is_singular),
// KHÔNG tự thêm canonical cho: trang category, trang Blog (posts page),
// trang tag, trang tìm kiếm... => gây thiếu thẻ canonical, ảnh hưởng SEO.
// ---------------------------------------------------------------
remove_action( 'wp_head', 'rel_canonical' );
add_action( 'wp_head', 'novalink_canonical_tag', 1 );
function novalink_canonical_tag() {
    $url = '';

    if ( is_front_page() ) {
        // Trang chủ (static front page hoặc mặc định)
        $url = home_url( '/' );

    } elseif ( is_singular() ) {
        // Trang / Bài viết đơn (Home, Dịch vụ, Liên hệ, single post...)
        $url = get_permalink();

    } elseif ( is_home() ) {
        // Trang Blog (Posts page) - kể cả các trang phân trang /page/2/
        $posts_page_id = get_option( 'page_for_posts' );
        $url = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
        $paged = get_query_var( 'paged' );
        if ( $paged && $paged > 1 ) {
            $url = get_pagenum_link( $paged );
        }

    } elseif ( is_category() ) {
        // Trang chuyên mục blog (vd: /category/marketing/)
        $url = get_category_link( get_queried_object_id() );
        $paged = get_query_var( 'paged' );
        if ( $paged && $paged > 1 ) {
            $url = get_pagenum_link( $paged );
        }

    } elseif ( is_tag() ) {
        $url = get_tag_link( get_queried_object_id() );

    } elseif ( is_author() ) {
        $url = get_author_posts_url( get_queried_object_id() );

    } elseif ( is_search() ) {
        $url = get_search_link();

    } elseif ( is_archive() ) {
        $url = get_permalink( get_queried_object_id() );

    } else {
        // Fallback an toàn: dùng URL hiện tại
        $url = home_url( add_query_arg( null, null ) );
    }

    if ( $url ) {
        echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";
    }
}

// Excerpt length for blog cards
function novalink_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'novalink_excerpt_length' );

// Register Blog categories used on Home (Marketing / Cong nghe & Media / Tin tuc tong hop)
function novalink_register_default_categories() {
    $cats = array( 'Marketing', 'Công nghệ & Media', 'Tin tức tổng hợp' );
    foreach ( $cats as $cat ) {
        if ( ! term_exists( $cat, 'category' ) ) {
            wp_insert_term( $cat, 'category' );
        }
    }
}
add_action( 'after_switch_theme', 'novalink_register_default_categories' );
