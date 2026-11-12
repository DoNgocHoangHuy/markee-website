<?php
/**
 * Ô nhập "Tiêu đề SEO" + "Mô tả meta (Meta Description)" cho Bài viết, Trang và Dịch vụ.
 *
 * - Trình soạn thảo khối (Gutenberg): hiện thành panel "SEO" trong thanh bên phải, tab "Page"/"Post"
 *   (kéo xuống dưới cùng, nằm cạnh Status / Publish / Slug...).
 * - Trình soạn thảo cổ điển: hiện thành khung "SEO" bên dưới nội dung.
 * - Ra ngoài website: in <title>, <meta name="description"> và thẻ Open Graph / Twitter.
 *
 * Tự nhường lại nếu site đã cài plugin SEO (Yoast, Rank Math, AIOSEO, SEOPress, TSF).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SAYBENCODE_META_TITLE = '_sb_seo_title';
const SAYBENCODE_META_DESC  = '_sb_seo_desc';
const SAYBENCODE_META_KW    = '_sb_seo_kw';

/** Các loại nội dung có ô SEO. */
function saybencode_seo_post_types() {
    return apply_filters( 'saybencode_seo_post_types', array( 'post', 'page', 'dich_vu' ) );
}

/* ---------- Đăng ký meta (để Gutenberg đọc/ghi qua REST) ---------- */
add_action( 'init', function () {
    foreach ( saybencode_seo_post_types() as $pt ) {
        foreach ( array( SAYBENCODE_META_TITLE => 'sanitize_text_field', SAYBENCODE_META_DESC => 'sanitize_textarea_field', SAYBENCODE_META_KW => 'sanitize_text_field' ) as $key => $cb ) {
            register_post_meta( $pt, $key, array(
                'type'              => 'string',
                'single'            => true,
                'default'           => '',
                'show_in_rest'      => true,
                'sanitize_callback' => $cb,
                'auth_callback'     => function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
            ) );
        }
    }
}, 20 );

/* ---------- Gutenberg: panel trong thanh bên ---------- */
add_action( 'enqueue_block_editor_assets', function () {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || ! in_array( $screen->post_type, saybencode_seo_post_types(), true ) ) return;
    $file = get_stylesheet_directory() . '/assets/js/seo-panel.js';
    wp_enqueue_script(
        'saybencode-seo-panel',
        get_stylesheet_directory_uri() . '/assets/js/seo-panel.js',
        array( 'wp-plugins', 'wp-element', 'wp-data', 'wp-components', 'wp-editor', 'wp-edit-post' ),
        file_exists( $file ) ? filemtime( $file ) : null,
        true
    );
    wp_localize_script( 'saybencode-seo-panel', 'saybSeo', array(
        'site'   => saybencode_brand(),
        'host'   => wp_parse_url( home_url(), PHP_URL_HOST ),
        'titleK' => SAYBENCODE_META_TITLE,
        'descK'  => SAYBENCODE_META_DESC,
        'kwK'    => SAYBENCODE_META_KW,
    ) );
} );

/* ---------- Trình soạn thảo cổ điển: khung bên dưới nội dung ---------- */
add_action( 'add_meta_boxes', function () {
    foreach ( saybencode_seo_post_types() as $pt ) {
        add_meta_box( 'saybencode_seo', 'SEO — Tiêu đề &amp; Mô tả meta', 'saybencode_seo_metabox', $pt, 'normal', 'high',
            array( '__back_compat_meta_box' => true ) ); // chỉ hiện ở trình soạn cổ điển (Gutenberg đã có panel riêng)
    }
} );

function saybencode_seo_metabox( $post ) {
    wp_nonce_field( 'saybencode_seo_meta', 'saybencode_seo_nonce' );
    $t = get_post_meta( $post->ID, SAYBENCODE_META_TITLE, true );
    $d = get_post_meta( $post->ID, SAYBENCODE_META_DESC, true );
    $k = get_post_meta( $post->ID, SAYBENCODE_META_KW, true );
    echo '<p><label for="sb_seo_kw"><strong>Từ khóa chính</strong> <span class="description">(1 cụm từ; nên có trong tiêu đề, mô tả, slug và đoạn đầu bài)</span></label>';
    echo '<input type="text" id="sb_seo_kw" name="sb_seo_kw" value="' . esc_attr( $k ) . '" style="width:100%" maxlength="120"></p>';
    echo '<p><label for="sb_seo_title"><strong>Tiêu đề SEO</strong> <span class="description">(nên ≤ 60 ký tự; để trống sẽ dùng tiêu đề bài)</span></label>';
    echo '<input type="text" id="sb_seo_title" name="sb_seo_title" value="' . esc_attr( $t ) . '" style="width:100%" maxlength="120"></p>';
    echo '<p><label for="sb_seo_desc"><strong>Mô tả meta (Meta Description)</strong> <span class="description">(nên 120–160 ký tự; để trống sẽ lấy từ phần trích dẫn)</span></label>';
    echo '<textarea id="sb_seo_desc" name="sb_seo_desc" rows="3" style="width:100%" maxlength="320">' . esc_textarea( $d ) . '</textarea></p>';
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['saybencode_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['saybencode_seo_nonce'] ) ), 'saybencode_seo_meta' ) ) return;
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['sb_seo_title'] ) ) update_post_meta( $post_id, SAYBENCODE_META_TITLE, sanitize_text_field( wp_unslash( $_POST['sb_seo_title'] ) ) );
    if ( isset( $_POST['sb_seo_kw'] ) )    update_post_meta( $post_id, SAYBENCODE_META_KW, sanitize_text_field( wp_unslash( $_POST['sb_seo_kw'] ) ) );
    if ( isset( $_POST['sb_seo_desc'] ) )  update_post_meta( $post_id, SAYBENCODE_META_DESC, sanitize_textarea_field( wp_unslash( $_POST['sb_seo_desc'] ) ) );
} );

/* ---------- Ra ngoài website ---------- */

/** ID bài/trang đang xem (kể cả trang "Blog" = trang bài viết). 0 nếu không có. */
function saybencode_seo_current_id() {
    if ( is_singular() ) return (int) get_queried_object_id();
    if ( is_home() && ! is_front_page() ) return (int) get_option( 'page_for_posts' );
    return 0;
}

function saybencode_seo_title_for( $id ) {
    return $id ? trim( (string) get_post_meta( $id, SAYBENCODE_META_TITLE, true ) ) : '';
}

function saybencode_seo_desc_for_current() {
    $id = saybencode_seo_current_id();
    if ( $id ) {
        $d = trim( (string) get_post_meta( $id, SAYBENCODE_META_DESC, true ) );
        if ( '' === $d ) {
            $p = get_post( $id );
            $d = $p ? trim( $p->post_excerpt ) : '';
            if ( '' === $d && $p && ! post_password_required( $p ) ) {
                $d = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $p->post_content ) ), 28, '…' );
            }
        }
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $d = trim( wp_strip_all_tags( term_description() ) );
    } else {
        $d = '';
    }
    if ( '' === $d && ( is_front_page() || is_home() || is_category() ) ) $d = trim( get_bloginfo( 'description' ) );
    return trim( preg_replace( '/\s+/u', ' ', (string) $d ) );
}

add_filter( 'pre_get_document_title', function ( $title ) {
    if ( saybencode_seo_plugin_active() ) return $title;
    $t = saybencode_seo_title_for( saybencode_seo_current_id() );
    return '' !== $t ? $t : $title;
}, 20 );

add_action( 'wp_head', function () {
    if ( saybencode_seo_plugin_active() || is_404() ) return;
    $desc = saybencode_seo_desc_for_current();
    if ( '' !== $desc ) echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";

    // Open Graph / Twitter (chia sẻ Facebook, Zalo...)
    $title = wp_get_document_title();
    $url   = function_exists( 'saybencode_canonical_url' ) ? saybencode_canonical_url() : '';
    $id    = saybencode_seo_current_id();
    $img   = $id ? get_the_post_thumbnail_url( $id, 'large' ) : '';
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
    echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( saybencode_brand() ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    if ( '' !== $desc ) echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
    if ( $url )         echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    if ( $img )         echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
    echo '<meta name="twitter:card" content="' . ( $img ? 'summary_large_image' : 'summary' ) . '">' . "\n";
}, 3 );
