<?php
/**
 * Tích hợp Elementor: trang Dịch vụ + các trang dịch vụ con sửa được bằng page builder.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/elementor-layouts.php';

function saybencode_elementor_active() {
    return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
}

/** Bài/trang này đã được dựng bằng Elementor chưa? */
function saybencode_is_elementor_built( $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return saybencode_elementor_active() && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/* ---------- Shortcode: lưới thẻ "Danh mục dịch vụ chi tiết" (tự lấy từ mục Dịch vụ) ---------- */
function saybencode_render_service_cards() {
    $services = get_posts( array(
        'post_type' => 'dich_vu', 'post_status' => 'publish', 'numberposts' => 24,
        'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
    ) );
    if ( ! $services ) return '';
    ob_start();
    echo '<div class="detail-grid">';
    foreach ( $services as $sv ) {
        $icon = get_post_meta( $sv->ID, '_sv_icon', true );
        $card = get_post_meta( $sv->ID, '_sv_card', true );
        if ( ! $card ) $card = get_the_excerpt( $sv );
        echo '<a class="detail-card" href="' . esc_url( get_permalink( $sv ) ) . '">';
        echo '<span class="svc-tile">' . saybencode_icon( $icon ? $icon : 'megaphone', 24 ) . '</span>';
        echo '<h3>' . esc_html( get_the_title( $sv ) ) . '</h3>';
        echo '<p>' . esc_html( $card ) . '</p>';
        echo '<span class="link">Xem chi tiết ' . saybencode_icon( 'arrow', 15 ) . '</span></a>';
    }
    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'saybencode_services', 'saybencode_render_service_cards' );

/* ---------- Ghi dữ liệu Elementor cho 1 bài/trang ---------- */
function saybencode_el_save( $post_id, $elements, $template_type = 'wp-page' ) {
    update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $elements ) ) );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    update_post_meta( $post_id, '_elementor_template_type', $template_type );
    if ( defined( 'ELEMENTOR_VERSION' ) ) update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
    delete_post_meta( $post_id, '_elementor_css' ); // buộc Elementor tạo lại CSS
}

/**
 * Dựng sẵn bố cục Elementor. Chỉ ghi vào bài CHƯA có dữ liệu Elementor,
 * nên không bao giờ đè lên nội dung chị Trà đã chỉnh.
 */
function saybencode_seed_elementor() {
    if ( ! saybencode_elementor_active() ) return;

    // Cho phép Elementor sửa loại bài "Dịch vụ"
    $cpts = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
    if ( ! is_array( $cpts ) ) $cpts = array( 'page', 'post' );
    if ( ! in_array( 'dich_vu', $cpts, true ) ) {
        $cpts[] = 'dich_vu';
        update_option( 'elementor_cpt_support', $cpts );
    }

    // Trang Dịch vụ
    $page = get_page_by_path( 'dich-vu' );
    if ( $page && ! get_post_meta( $page->ID, '_elementor_data', true ) ) {
        saybencode_el_save( $page->ID, saybencode_el_layout_services_page(), 'wp-page' );
        update_post_meta( $page->ID, '_wp_page_template', 'elementor_header_footer' );
    }

    // 9 trang dịch vụ con
    foreach ( saybencode_services_seed_data() as $s ) {
        $found = get_posts( array( 'post_type' => 'dich_vu', 'name' => $s['slug'], 'post_status' => 'any', 'numberposts' => 1 ) );
        if ( ! $found ) continue;
        if ( ! get_post_meta( $found[0]->ID, '_elementor_data', true ) ) {
            saybencode_el_save( $found[0]->ID, saybencode_el_layout_service( $s ), 'wp-post' );
        }
    }

    if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
    update_option( 'saybencode_elementor_seeded', '1' );
}
add_action( 'after_switch_theme', 'saybencode_seed_elementor', 30 );
add_action( 'admin_init', function () {
    if ( saybencode_elementor_active() && ! get_option( 'saybencode_elementor_seeded' ) && current_user_can( 'manage_options' ) ) {
        // chạy sau khi các trang/dịch vụ đã được tạo
        if ( ! get_option( 'saybencode_seeded' ) ) saybencode_seed_content();
        saybencode_seed_elementor();
    }
}, 30 );

/* ---------- Giữ font/màu của theme, không để Elementor dùng mặc định (xanh nhạt + Roboto) ---------- */
add_action( 'wp_enqueue_scripts', function () {
    // style.css đã chứa phần ghi đè; đảm bảo nạp SAU CSS của Elementor
    if ( saybencode_elementor_active() ) {
        wp_dequeue_style( 'saybencode-style' );
        wp_enqueue_style( 'saybencode-style', get_stylesheet_uri(), wp_style_is( 'elementor-frontend', 'registered' ) ? array( 'elementor-frontend' ) : array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
    }
}, 99 );


/**
 * Nâng cấp 1 lần: 9 trang dịch vụ đã dựng sẵn bằng bố cục cũ (không có thanh bên) sẽ được dựng lại
 * theo bố cục mới (nội dung bên trái + thanh bên phải). Chỉ động vào trang còn đúng bố cục do theme tạo
 * (có class "sb-service-body", chưa có "sb-service-left"). Chạy đúng 1 lần rồi ghi nhớ.
 */
function saybencode_upgrade_service_layouts() {
    if ( ! saybencode_elementor_active() || ! current_user_can( 'manage_options' ) ) return;
    if ( (int) get_option( 'saybencode_service_layout_ver' ) >= 3 ) return;

    foreach ( saybencode_services_seed_data() as $s ) {
        $found = get_posts( array( 'post_type' => 'dich_vu', 'name' => $s['slug'], 'post_status' => 'any', 'numberposts' => 1 ) );
        if ( ! $found ) continue;
        $data = get_post_meta( $found[0]->ID, '_elementor_data', true );
        if ( $data && false !== strpos( $data, 'sb-service-body' ) && false === strpos( $data, 'sb-service-left' ) ) {
            saybencode_el_save( $found[0]->ID, saybencode_el_layout_service( $s ), 'wp-post' );
        }
    }
    if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
    update_option( 'saybencode_service_layout_ver', 3 );
}
add_action( 'admin_init', 'saybencode_upgrade_service_layouts', 40 );
