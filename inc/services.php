<?php
/**
 * Loại bài "Dịch vụ" (URL: /dich-vu/ten-dich-vu/) + tự tạo nội dung mẫu.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/services-data.php';

/* ---------- Custom post type ---------- */
function saybencode_register_service_cpt() {
    register_post_type( 'dich_vu', array(
        'labels' => array(
            'name'          => 'Dịch vụ',
            'singular_name' => 'Dịch vụ',
            'add_new_item'  => 'Thêm dịch vụ mới',
            'edit_item'     => 'Sửa dịch vụ',
            'all_items'     => 'Tất cả dịch vụ',
            'menu_name'     => 'Dịch vụ',
        ),
        'public'       => true,
        'has_archive'  => false,               // /dich-vu/ là một Trang (Page), không phải archive
        'rewrite'      => array( 'slug' => 'dich-vu', 'with_front' => false ),
        'menu_icon'    => 'dashicons-megaphone',
        'menu_position'=> 21,
        'show_in_rest' => true,
        'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ),
    ) );
}
add_action( 'init', 'saybencode_register_service_cpt' );

/* ---------- Meta box: mô tả ngắn + icon ---------- */
function saybencode_service_metabox() {
    add_meta_box( 'saybencode_service_meta', 'Hiển thị trong danh sách', function ( $post ) {
        wp_nonce_field( 'saybencode_service_meta', 'saybencode_service_nonce' );
        $card = get_post_meta( $post->ID, '_sv_card', true );
        $icon = get_post_meta( $post->ID, '_sv_icon', true );
        $icons = array( 'bot','workflow','search','pen','users','video','camera','palette','megaphone','cart','chart','layout','monitor','phone','database','zap','mail','message','image','file' );
        echo '<p><label for="sv_card"><strong>Mô tả ngắn (thẻ ở trang Dịch vụ)</strong></label><br>';
        echo '<textarea id="sv_card" name="sv_card" rows="3" style="width:100%">' . esc_textarea( $card ) . '</textarea></p>';
        echo '<p><label for="sv_icon"><strong>Icon</strong></label><br><select id="sv_icon" name="sv_icon">';
        foreach ( $icons as $i ) {
            printf( '<option value="%1$s"%2$s>%1$s</option>', esc_attr( $i ), selected( $icon, $i, false ) );
        }
        echo '</select></p>';
        echo '<p class="description">Tiêu đề lớn ở đầu trang lấy từ "Tiêu đề", dòng giới thiệu lấy từ "Trích dẫn (Excerpt)", ảnh bên phải lấy từ "Ảnh đại diện".</p>';
    }, 'dich_vu', 'side' );
}
add_action( 'add_meta_boxes', 'saybencode_service_metabox' );

function saybencode_service_save_meta( $post_id ) {
    if ( ! isset( $_POST['saybencode_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['saybencode_service_nonce'] ) ), 'saybencode_service_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['sv_card'] ) ) update_post_meta( $post_id, '_sv_card', sanitize_textarea_field( wp_unslash( $_POST['sv_card'] ) ) );
    if ( isset( $_POST['sv_icon'] ) ) update_post_meta( $post_id, '_sv_icon', sanitize_key( wp_unslash( $_POST['sv_icon'] ) ) );
}
add_action( 'save_post_dich_vu', 'saybencode_service_save_meta' );

/* ---------- Tự tạo nội dung mẫu (an toàn: chỉ tạo cái chưa có) ---------- */
function saybencode_seed_content() {
    // 1) Các trang cần thiết
    $pages = array(
        'trang-chu' => array( 'Trang chủ', '' ),
        'dich-vu'   => array( 'Dịch vụ', 'page-dich-vu.php' ),
        'lien-he'   => array( 'Liên hệ', 'page-lien-he.php' ),
        'blog'      => array( 'Blog', '' ),
    );
    $ids = array();
    foreach ( $pages as $slug => $def ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            $ids[ $slug ] = $existing->ID;
            if ( $def[1] && ! get_post_meta( $existing->ID, '_elementor_edit_mode', true ) && get_page_template_slug( $existing->ID ) !== $def[1] && in_array( $existing->post_status, array( 'publish', 'draft' ), true ) ) {
                update_post_meta( $existing->ID, '_wp_page_template', $def[1] );
            }
            continue;
        }
        $id = wp_insert_post( array(
            'post_type' => 'page', 'post_status' => 'publish',
            'post_title' => $def[0], 'post_name' => $slug,
            'page_template' => $def[1],
        ) );
        if ( $id && ! is_wp_error( $id ) ) $ids[ $slug ] = $id;
    }

    // 2) Trang chủ tĩnh + trang Blog (chỉ khi chưa cấu hình)
    if ( 'page' !== get_option( 'show_on_front' ) && ! empty( $ids['trang-chu'] ) && ! empty( $ids['blog'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $ids['trang-chu'] );
        update_option( 'page_for_posts', $ids['blog'] );
    } elseif ( ! get_option( 'page_for_posts' ) && ! empty( $ids['blog'] ) ) {
        update_option( 'page_for_posts', $ids['blog'] );
    }

    // 3) 8 dịch vụ chi tiết
    foreach ( saybencode_services_seed_data() as $order => $s ) {
        $exists = get_posts( array( 'post_type' => 'dich_vu', 'name' => $s['slug'], 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
        if ( $exists ) continue;

        $html  = '<p>' . esc_html( $s['intro'] ) . '</p>';
        $html .= '<h2>Hạng mục triển khai</h2><ul>';
        foreach ( $s['items'] as $it ) $html .= '<li>' . esc_html( $it ) . '</li>';
        $html .= '</ul>';
        $html .= '<h2>' . esc_html( $s['extra_title'] ) . '</h2><p>' . esc_html( $s['extra'] ) . '</p>';
        $html .= '<h2>Phù hợp với</h2><p>' . esc_html( $s['fit'] ) . '</p>';

        $pid = wp_insert_post( array(
            'post_type' => 'dich_vu', 'post_status' => 'publish',
            'post_title' => $s['title'], 'post_name' => $s['slug'],
            'post_excerpt' => $s['lead'], 'post_content' => $html,
            'menu_order' => $order,
        ) );
        if ( $pid && ! is_wp_error( $pid ) ) {
            update_post_meta( $pid, '_sv_card', $s['card'] );
            update_post_meta( $pid, '_sv_icon', $s['icon'] );
        }
    }

    update_option( 'saybencode_seeded', '1' );
}

function saybencode_on_activate() {
    saybencode_register_service_cpt();
    saybencode_seed_content();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'saybencode_on_activate' );

// Nếu theme được copy đè lên site đang chạy (không "switch"), vẫn tự seed 1 lần khi vào admin
add_action( 'admin_init', function () {
    if ( ! get_option( 'saybencode_seeded' ) && current_user_can( 'manage_options' ) ) {
        saybencode_seed_content();
        flush_rewrite_rules();
    }
} );


/** Shortcode [saybencode_service_sidebar]: dùng trong Elementor để đặt thanh bên của dịch vụ. */
add_shortcode( 'saybencode_service_sidebar', function () {
    ob_start();
    get_template_part( 'template-parts/service-sidebar' );
    return ob_get_clean();
} );


/**
 * Dịch vụ liền trước / liền sau (theo đúng thứ tự hiển thị ở trang Dịch vụ).
 * Đi vòng tròn: dịch vụ đầu tiên có "trước" là dịch vụ cuối, và ngược lại,
 * nên luôn có đủ 2 thẻ để bấm khi có từ 3 dịch vụ trở lên.
 *
 * @return array [ WP_Post|null $prev, WP_Post|null $next ]
 */
function saybencode_service_neighbors( $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $ids = get_posts( array(
        'post_type' => 'dich_vu', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids',
        'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'suppress_filters' => true,
    ) );
    $n   = count( $ids );
    $pos = array_search( (int) $post_id, array_map( 'intval', $ids ), true );
    if ( $n < 2 || false === $pos ) return array( null, null );
    $prev_id = $ids[ ( $pos - 1 + $n ) % $n ];
    $next_id = $ids[ ( $pos + 1 ) % $n ];
    return array(
        ( $n > 1 ) ? get_post( $prev_id ) : null,
        ( $n > 1 ) ? get_post( $next_id ) : null,
    );
}

/** Shortcode [saybencode_service_nav]: thẻ Dịch vụ trước/kế tiếp (dùng trong Elementor). */
add_shortcode( 'saybencode_service_nav', function () {
    ob_start();
    get_template_part( 'template-parts/post-nav' );
    return ob_get_clean();
} );
