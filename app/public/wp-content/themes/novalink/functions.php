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

// Excerpt length for blog cards
function novalink_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'novalink_excerpt_length' );

// Trang Blog (posts page) hiển thị 5 bài/trang trong home.php — đặt cùng số lượng
// cho main query để WordPress không tạo link phân trang vượt quá số trang thực có bài.
function novalink_blog_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_home() ) {
        $query->set( 'posts_per_page', 5 );
    }
}
add_action( 'pre_get_posts', 'novalink_blog_posts_per_page' );

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


/* =========================================================================
   SEO FIXES — theo báo cáo seocheck.py (dòng LUU / THIEU cần sửa)
   ========================================================================= */

// ---------------------------------------------------------------
// [SỬA MỤC 11 - LUU] Ngôn ngữ HTML: ép về "vi" vì site tiếng Việt
// WordPress mặc định lấy theo Site Language (đang là English -> en-US)
// ---------------------------------------------------------------
add_filter( 'language_attributes', function( $output ) {
    if ( strpos( $output, 'lang=' ) !== false ) {
        $output = preg_replace( '/lang="[^"]*"/', 'lang="vi"', $output );
    } else {
        $output .= ' lang="vi"';
    }
    return $output;
} );

// ---------------------------------------------------------------
// Hàm dùng chung: tính URL chuẩn (canonical) của trang hiện tại
// Tách riêng để canonical + Open Graph + Twitter Card đều dùng chung 1 URL
// ---------------------------------------------------------------
function novalink_get_canonical_url() {
    $url = '';

    if ( is_front_page() ) {
        $url = home_url( '/' );

    } elseif ( is_singular() ) {
        $url = get_permalink();

    } elseif ( is_home() ) {
        $posts_page_id = get_option( 'page_for_posts' );
        $url = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
        $paged = get_query_var( 'paged' );
        if ( $paged && $paged > 1 ) {
            $url = get_pagenum_link( $paged );
        }

    } elseif ( is_category() ) {
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
        $url = home_url( add_query_arg( null, null ) );
    }

    return $url;
}

// ---------------------------------------------------------------
// [ĐÃ ĐẠT - giữ nguyên] Thẻ Canonical cho mọi loại trang
// ---------------------------------------------------------------
remove_action( 'wp_head', 'rel_canonical' );
add_action( 'wp_head', 'novalink_canonical_tag', 1 );
function novalink_canonical_tag() {
    $url = novalink_get_canonical_url();
    if ( $url ) {
        echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";
    }
}

// ---------------------------------------------------------------
// [SỬA MỤC 8a - LUU] Meta Title: trang chủ trước đây chỉ ra "Novalink"
// (8 ký tự, quá ngắn) -> đặt tiêu đề mô tả đầy đủ hơn cho trang chủ.
// Các trang khác vẫn dùng chuẩn WordPress: "{Tên trang} - Novalink"
// ---------------------------------------------------------------
add_filter( 'pre_get_document_title', function( $title ) {
    if ( is_front_page() ) {
        return 'Novalink - Đối tác Marketing, Công nghệ & Media doanh nghiệp';
    }
    return $title;
} );

// ---------------------------------------------------------------
// Hàm dùng chung: tính Meta Description theo từng loại trang
// ---------------------------------------------------------------
function novalink_get_meta_description() {
    if ( is_front_page() ) {
        return 'Novalink là đối tác chiến lược Marketing, Công nghệ & Media cho doanh nghiệp Việt Nam — SEO, quảng cáo, phát triển web/app và sản xuất nội dung sáng tạo.';
    }

    if ( is_singular() ) {
        global $post;
        if ( has_excerpt( $post ) ) {
            return wp_strip_all_tags( get_the_excerpt( $post ) );
        }
        $content = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post ) ) );
        $trimmed = wp_trim_words( $content, 30, '...' );
        return $trimmed ? $trimmed : get_bloginfo( 'name' ) . ' - Đối tác chiến lược Marketing, Công nghệ & Media.';
    }

    if ( is_category() || is_tag() ) {
        $term = get_queried_object();
        if ( ! empty( $term->description ) ) {
            return wp_strip_all_tags( $term->description );
        }
        return 'Tổng hợp bài viết thuộc chuyên mục ' . $term->name . ' từ Novalink.';
    }

    if ( is_home() ) {
        return 'Cập nhật tin tức, kiến thức Marketing, Công nghệ & Media mới nhất từ đội ngũ Novalink.';
    }

    if ( is_search() ) {
        return 'Kết quả tìm kiếm cho "' . get_search_query() . '" trên Novalink.';
    }

    $tagline = get_bloginfo( 'description' );
    return $tagline ? $tagline : 'Novalink - Đối tác chiến lược Marketing, Công nghệ & Media.';
}

// ---------------------------------------------------------------
// [SỬA MỤC 8b - THIEU] Meta Description
// [SỬA MỤC 12 - THIEU] Open Graph (og:title, og:type, og:url, og:image, og:description)
// [SỬA MỤC 13 - THIEU] Twitter Card
// ---------------------------------------------------------------
add_action( 'wp_head', 'novalink_seo_meta_tags', 2 );
function novalink_seo_meta_tags() {
    $description = novalink_get_meta_description();
    $title       = wp_get_document_title();
    $url         = novalink_get_canonical_url();
    $og_type     = is_singular( 'post' ) ? 'article' : 'website';

    $image = '';
    if ( is_singular() && has_post_thumbnail() ) {
        $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    }
    if ( ! $image ) {
        // Ảnh mặc định khi trang không có ảnh riêng.
        // Nên thay bằng 1 ảnh đại diện thương hiệu Novalink thật (1200x630px) khi lên domain chính thức.
        $image = get_stylesheet_directory_uri() . '/screenshot.png';
    }

    echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";

    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
    echo '<meta property="og:locale" content="vi_VN" />' . "\n";

    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
    echo '<meta name="twitter:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
}
