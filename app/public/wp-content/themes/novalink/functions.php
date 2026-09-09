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

// ---------------------------------------------------------------
// CPT: Sản phẩm — cho phép admin thêm/sửa/xoá sản phẩm hiển thị
// tại /san-pham/ (danh sách) và /san-pham/ten-san-pham/ (chi tiết)
// ---------------------------------------------------------------
function novalink_register_san_pham() {
    register_post_type( 'san_pham', array(
        'labels' => array(
            'name'               => 'Sản phẩm',
            'singular_name'      => 'Sản phẩm',
            'add_new'            => 'Thêm sản phẩm',
            'add_new_item'       => 'Thêm sản phẩm mới',
            'edit_item'          => 'Sửa sản phẩm',
            'new_item'           => 'Sản phẩm mới',
            'view_item'          => 'Xem sản phẩm',
            'view_items'         => 'Xem sản phẩm',
            'search_items'       => 'Tìm sản phẩm',
            'not_found'          => 'Không tìm thấy sản phẩm nào',
            'not_found_in_trash' => 'Không có sản phẩm nào trong thùng rác',
            'all_items'          => 'Tất cả sản phẩm',
            'menu_name'          => 'Sản phẩm',
            'featured_image'     => 'Ảnh sản phẩm',
            'set_featured_image' => 'Đặt ảnh sản phẩm',
        ),
        'public'        => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-archive',
        'menu_position' => 5,
        'has_archive'   => 'san-pham',
        'rewrite'       => array( 'slug' => 'san-pham', 'with_front' => false ),
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
        'show_in_rest'  => true,
    ) );

    register_taxonomy( 'danh_muc_sp', 'san_pham', array(
        'labels' => array(
            'name'          => 'Danh mục sản phẩm',
            'singular_name' => 'Danh mục sản phẩm',
            'search_items'  => 'Tìm danh mục',
            'all_items'     => 'Tất cả danh mục',
            'edit_item'     => 'Sửa danh mục',
            'add_new_item'  => 'Thêm danh mục mới',
            'menu_name'     => 'Danh mục sản phẩm',
        ),
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => array( 'slug' => 'danh-muc-san-pham', 'with_front' => false ),
    ) );
}
add_action( 'init', 'novalink_register_san_pham' );

// "Migration" nhẹ: tự flush rewrite rules đúng 1 lần sau khi code này lên
// server, để /san-pham/ hoạt động ngay mà không cần vào Cài đặt > Đường dẫn tĩnh.
function novalink_maybe_flush_rewrite_for_san_pham() {
    if ( get_option( 'novalink_san_pham_flushed_v1' ) !== '1' ) {
        flush_rewrite_rules();
        update_option( 'novalink_san_pham_flushed_v1', '1' );
    }
}
add_action( 'init', 'novalink_maybe_flush_rewrite_for_san_pham', 20 );

// ---------------------------------------------------------------
// Meta box: Thông tin sản phẩm (giá, mô tả ngắn, nổi bật)
// ---------------------------------------------------------------
function novalink_sp_add_meta_box() {
    add_meta_box(
        'novalink_sp_info',
        'Thông tin sản phẩm',
        'novalink_sp_render_meta_box',
        'san_pham',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'novalink_sp_add_meta_box' );

function novalink_sp_render_meta_box( $post ) {
    wp_nonce_field( 'novalink_sp_save', 'novalink_sp_nonce' );
    $gia     = get_post_meta( $post->ID, '_sp_gia', true );
    $mo_ta   = get_post_meta( $post->ID, '_sp_mo_ta_ngan', true );
    $noi_bat = get_post_meta( $post->ID, '_sp_noi_bat', true );
    ?>
    <p>
        <label for="sp_gia"><strong>Giá hiển thị</strong> (vd: 1.200.000đ hoặc "Liên hệ")</label><br>
        <input type="text" id="sp_gia" name="sp_gia" value="<?php echo esc_attr( $gia ); ?>" style="width:100%;max-width:360px;">
    </p>
    <p>
        <label for="sp_mo_ta_ngan"><strong>Mô tả ngắn</strong> (hiển thị ở danh sách sản phẩm, để trống sẽ lấy từ đoạn trích)</label><br>
        <textarea id="sp_mo_ta_ngan" name="sp_mo_ta_ngan" rows="3" style="width:100%;max-width:560px;"><?php echo esc_textarea( $mo_ta ); ?></textarea>
    </p>
    <p>
        <label>
            <input type="checkbox" name="sp_noi_bat" value="1" <?php checked( $noi_bat, '1' ); ?>>
            <strong>Sản phẩm nổi bật</strong> (hiển thị nhãn "Nổi bật" trên thẻ sản phẩm)
        </label>
    </p>
    <p style="color:#666;">Thứ tự hiển thị: dùng ô <strong>Thứ tự</strong> trong khung "Thuộc tính trang" (Page Attributes) bên phải — số nhỏ hơn hiện trước.</p>
    <?php
}

function novalink_sp_save_meta( $post_id ) {
    if ( ! isset( $_POST['novalink_sp_nonce'] ) || ! wp_verify_nonce( $_POST['novalink_sp_nonce'], 'novalink_sp_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['sp_gia'] ) ) {
        update_post_meta( $post_id, '_sp_gia', sanitize_text_field( wp_unslash( $_POST['sp_gia'] ) ) );
    }
    if ( isset( $_POST['sp_mo_ta_ngan'] ) ) {
        update_post_meta( $post_id, '_sp_mo_ta_ngan', sanitize_textarea_field( wp_unslash( $_POST['sp_mo_ta_ngan'] ) ) );
    }
    update_post_meta( $post_id, '_sp_noi_bat', isset( $_POST['sp_noi_bat'] ) ? '1' : '0' );
}
add_action( 'save_post_san_pham', 'novalink_sp_save_meta' );

// Trang danh sách / danh mục sản phẩm: sắp theo Thứ tự rồi tới ngày đăng, 12 sản phẩm/trang
function novalink_sp_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'san_pham' ) || is_tax( 'danh_muc_sp' ) ) ) {
        $query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
        $query->set( 'posts_per_page', 12 );
    }
}
add_action( 'pre_get_posts', 'novalink_sp_archive_query' );