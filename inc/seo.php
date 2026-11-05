<?php
/**
 * SEO cơ bản: thẻ canonical cho MỌI loại trang + robots cho trang tìm kiếm.
 * Tự nhường lại nếu site đã cài plugin SEO (Yoast, Rank Math, AIOSEO, SEOPress, TSF)
 * để không bị in canonical 2 lần.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function saybencode_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' )
        || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/** Trả về URL canonical của trang hiện tại, hoặc '' nếu không nên có. */
function saybencode_canonical_url() {
    if ( is_404() || is_search() ) return '';

    $paged = max( 1, (int) get_query_var( 'paged' ) );
    $url   = '';

    if ( is_singular() ) {
        $url = wp_get_canonical_url();
        if ( ! $url ) $url = get_permalink();
    } elseif ( is_front_page() ) {
        $url = home_url( '/' );
        if ( $paged > 1 ) $url = get_pagenum_link( $paged );
    } elseif ( is_home() ) {
        $page_id = (int) get_option( 'page_for_posts' );
        $url     = $page_id ? get_permalink( $page_id ) : home_url( '/' );
        if ( $paged > 1 ) $url = get_pagenum_link( $paged );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $url = get_term_link( get_queried_object() );
        if ( is_wp_error( $url ) ) return '';
        if ( $paged > 1 ) $url = get_pagenum_link( $paged );
    } elseif ( is_author() || is_date() || is_post_type_archive() ) {
        $url = get_pagenum_link( $paged );
    }

    if ( ! $url ) return '';
    // Bỏ tham số tracking/trạng thái (?utm_..., ?gui=thanh-cong, ?dang-ky=ok ...)
    return strtok( $url, '?' );
}

function saybencode_print_canonical() {
    if ( saybencode_seo_plugin_active() ) return;
    $url = saybencode_canonical_url();
    if ( $url ) echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
}
// Thay thế canonical mặc định của WP (chỉ có ở bài viết/trang) bằng bản đầy đủ
remove_action( 'wp_head', 'rel_canonical' );
add_action( 'wp_head', 'saybencode_print_canonical', 2 );

/** Trang kết quả tìm kiếm: không lập chỉ mục (tránh trùng lặp nội dung). */
add_filter( 'wp_robots', function ( $robots ) {
    if ( ! saybencode_seo_plugin_active() && is_search() ) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
        unset( $robots['index'] );
    }
    return $robots;
} );
