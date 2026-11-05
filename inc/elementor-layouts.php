<?php
/**
 * Dựng sẵn bố cục Elementor (JSON) cho trang Dịch vụ + 9 trang dịch vụ con.
 * Chỉ dùng widget MIỄN PHÍ của Elementor (container, heading, text-editor, button,
 * icon-box, icon-list, icon, image, shortcode) => không cần Elementor Pro.
 * Kiểu dáng nằm ở class .sb-* trong style.css, nội dung/ảnh/link sửa thẳng trong Elementor.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---------- Hàm dựng phần tử ---------- */
function sb_el_id() {
    return substr( md5( uniqid( '', true ) . mt_rand() ), 0, 7 );
}
function sb_size( $n, $unit = 'px' ) {
    return array( 'unit' => $unit, 'size' => $n, 'sizes' => array() );
}
function sb_pad( $t, $r = null, $b = null, $l = null ) {
    $r = null === $r ? $t : $r; $b = null === $b ? $t : $b; $l = null === $l ? $r : $l;
    return array( 'unit' => 'px', 'top' => (string) $t, 'right' => (string) $r, 'bottom' => (string) $b, 'left' => (string) $l, 'isLinked' => false );
}
/** Container (flexbox). $inner = true khi nằm trong container khác. */
function sb_c( $settings, $children = array(), $inner = false ) {
    return array( 'id' => sb_el_id(), 'elType' => 'container', 'settings' => $settings, 'elements' => $children, 'isInner' => $inner );
}
function sb_w( $type, $settings = array() ) {
    return array( 'id' => sb_el_id(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $settings, 'elements' => array() );
}
function sb_heading( $text, $tag = 'h2', $class = '' ) {
    return sb_w( 'heading', array( 'title' => $text, 'header_size' => $tag, '_css_classes' => $class ) );
}
function sb_text( $html, $class = '' ) {
    return sb_w( 'text-editor', array( 'editor' => $html, '_css_classes' => $class ) );
}
function sb_button( $text, $url, $class = '' ) {
    return sb_w( 'button', array(
        'text' => $text,
        'link' => array( 'url' => $url, 'is_external' => '', 'nofollow' => '', 'custom_attributes' => '' ),
        '_css_classes' => $class,
    ) );
}
function sb_icon_val( $name ) {
    return array( 'value' => $name, 'library' => 'fa-solid' );
}
/** Section toàn chiều rộng, nội dung giới hạn $width px. */
function sb_section( $class, $bg, $pad_y, $children, $width = 1160 ) {
    $s = array(
        'content_width' => 'boxed', 'boxed_width' => sb_size( $width ),
        'flex_direction' => 'column', 'padding' => sb_pad( $pad_y, 24 ),
        'padding_mobile' => sb_pad( (int) ( $pad_y * .6 ), 18 ),
        '_css_classes' => $class,
    );
    if ( $bg ) { $s['background_background'] = 'classic'; $s['background_color'] = $bg; }
    return sb_c( $s, $children );
}
function sb_row( $class, $children, $gap = 20 ) {
    return sb_c( array(
        'content_width' => 'full', 'flex_direction' => 'row', 'flex_wrap' => 'wrap',
        'flex_direction_mobile' => 'column',
        'flex_gap' => array( 'unit' => 'px', 'size' => $gap, 'column' => (string) $gap, 'row' => (string) $gap, 'isLinked' => true ),
        '_css_classes' => $class,
    ), $children, true );
}

/** Bảng đổi tên icon (bộ SVG của theme) -> Font Awesome 5 (có sẵn trong Elementor). */
function sb_fa( $name ) {
    $map = array(
        'search' => 'fas fa-search', 'pen' => 'fas fa-pen-nib', 'users' => 'fas fa-users', 'mail' => 'fas fa-envelope',
        'monitor' => 'fas fa-desktop', 'phone' => 'fas fa-mobile-alt', 'database' => 'fas fa-database', 'zap' => 'fas fa-bolt',
        'video' => 'fas fa-video', 'camera' => 'fas fa-camera', 'palette' => 'fas fa-palette', 'megaphone' => 'fas fa-bullhorn',
        'bot' => 'fas fa-robot', 'workflow' => 'fas fa-project-diagram', 'cart' => 'fas fa-shopping-cart',
        'chart' => 'fas fa-chart-line', 'layout' => 'fas fa-laptop-code', 'file' => 'fas fa-file-alt', 'message' => 'fas fa-comments',
        'image' => 'fas fa-image',
    );
    return isset( $map[ $name ] ) ? $map[ $name ] : 'fas fa-bullhorn';
}

/* ---------- Trang "Dịch vụ" ---------- */
function saybencode_el_layout_services_page() {
    $contact = home_url( '/lien-he/' );
    $groups = array(
        array( '01. Giải pháp Tiếp thị (Marketing)', array(
            array( 'search', 'SEO & SEM', 'Tối ưu công cụ tìm kiếm và quảng cáo trả phí, tăng lưu lượng truy cập chất lượng.' ),
            array( 'pen', 'Content Marketing', 'Xây dựng nội dung có chiều sâu, đúng insight khách hàng mục tiêu.' ),
            array( 'users', 'Social Media Marketing', 'Quản trị và phát triển kênh mạng xã hội, tăng tương tác và nhận diện.' ),
            array( 'mail', 'Email Marketing', 'Xây dựng chuỗi email tự động chăm sóc và chuyển đổi khách hàng.' ),
        ) ),
        array( '02. Giải pháp Công nghệ số (Technology)', array(
            array( 'monitor', 'Thiết kế Website', 'Website chuẩn UX/UI, tối ưu tốc độ và thân thiện SEO.' ),
            array( 'phone', 'Phát triển ứng dụng', 'Xây dựng Web App/Mobile App theo yêu cầu vận hành doanh nghiệp.' ),
            array( 'database', 'Hệ thống quản lý', 'Triển khai CRM, ERP giúp quản trị dữ liệu và quy trình hiệu quả.' ),
            array( 'zap', 'Tối ưu vận hành', 'Tự động hoá quy trình, tích hợp hệ thống, giảm chi phí vận hành.' ),
        ) ),
        array( '03. Sản xuất Media & Thiết kế sáng tạo (Media)', array(
            array( 'video', 'Sản xuất Video quảng cáo', 'Video TVC, viral clip chuẩn thương hiệu, tối ưu cho từng nền tảng.' ),
            array( 'camera', 'Chụp ảnh thương hiệu', 'Sản xuất hình ảnh sản phẩm, campaign chuyên nghiệp, giàu cảm xúc.' ),
            array( 'palette', 'Thiết kế đồ hoạ', 'Bộ nhận diện thương hiệu, ấn phẩm truyền thông đồng bộ, sáng tạo.' ),
            array( 'megaphone', 'Truyền thông tổng lực', 'Lên kế hoạch và triển khai chiến dịch truyền thông đa kênh.' ),
        ) ),
    );

    // 1) Hero
    $hero = sb_section( 'sb-hero sb-hero-center', '#0a1330', 72, array(
        sb_text( '<a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a> › Dịch vụ', 'sb-breadcrumb' ),
        sb_heading( 'Dịch vụ của chúng tôi', 'h1' ),
        sb_text( '<p>Giải pháp toàn diện xoay quanh 3 trụ cột: Marketing, Công nghệ và Media — giúp thương hiệu bạn tăng trưởng bền vững.</p>', 'sb-lead' ),
    ), 900 );

    // 2) 3 nhóm dịch vụ
    $blocks = array();
    foreach ( $groups as $g ) {
        $cards = array();
        foreach ( $g[1] as $it ) {
            $cards[] = sb_w( 'icon-box', array(
                'selected_icon' => sb_icon_val( sb_fa( $it[0] ) ), 'position' => 'top',
                'title_text' => $it[1], 'description_text' => $it[2], 'title_size' => 'h3',
                '_css_classes' => 'sb-icon-card',
            ) );
        }
        $blocks[] = sb_heading( $g[0], 'h2', 'sb-group-title' );
        $blocks[] = sb_row( 'sb-card-row', $cards );
    }
    $groups_section = sb_section( 'sb-section sb-groups', '#f5f7fc', 64, $blocks );

    // 3) Danh mục dịch vụ chi tiết (tự lấy từ mục "Dịch vụ" trong WP Admin)
    $detail = sb_section( 'sb-section sb-detail', '#f5f7fc', 24, array(
        sb_text( 'Chi tiết từng hạng mục', 'sb-label' ),
        sb_heading( 'Danh mục dịch vụ chi tiết', 'h2', 'sb-section-title' ),
        sb_w( 'shortcode', array( 'shortcode' => '[saybencode_services]', '_css_classes' => 'sb-services-embed' ) ),
    ) );

    // 4) Quy trình 5 bước
    $steps = array();
    foreach ( array( 'Tìm hiểu', 'Lên kế hoạch', 'Triển khai', 'Đánh giá', 'Tối ưu' ) as $i => $name ) {
        $steps[] = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', 'align_items' => 'center', '_css_classes' => 'sb-step' ), array(
            sb_heading( (string) ( $i + 1 ), 'div', 'sb-step-num' ),
            sb_heading( $name, 'h3', 'sb-step-name' ),
        ), true );
    }
    $process = sb_section( 'sb-section sb-process', '#f5f7fc', 40, array(
        sb_text( 'Quy trình làm việc', 'sb-label' ),
        sb_heading( 'Quy trình hợp tác chặt chẽ & tối ưu', 'h2', 'sb-section-title' ),
        sb_row( 'sb-steps-row', $steps, 12 ),
    ) );

    // 5) Khối kêu gọi liên hệ
    $cta_text = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-cta-text' ), array(
        sb_heading( 'Bạn cần tư vấn chi tiết cho dự án sắp tới?', 'h3' ),
        sb_text( '<p>Đội ngũ chuyên gia của Saybencode sẵn sàng lắng nghe và đề xuất giải pháp phù hợp nhất với ngân sách của bạn.</p>' ),
        sb_button( 'Liên hệ tư vấn', $contact, 'sb-btn-primary' ),
        sb_text( '<a href="tel:' . esc_attr( saybencode_tel( saybencode_info( 'hotline_1' ) ) ) . '">📞 Hotline: ' . esc_html( saybencode_info( 'hotline_1' ) ) . '</a>', 'sb-hotline' ),
    ), true );
    $cta_img = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-cta-img' ), array(
        sb_w( 'image', array( 'image' => array( 'url' => saybencode_img_url( 'office.svg' ), 'id' => '' ), 'image_size' => 'full' ) ),
    ), true );
    $cta = sb_section( 'sb-section sb-cta-wrap', '#f5f7fc', 24, array(
        sb_c( array( 'content_width' => 'full', 'flex_direction' => 'row', 'flex_direction_mobile' => 'column', '_css_classes' => 'sb-cta-card' ), array( $cta_text, $cta_img ), true ),
    ) );
    // phần đệm cuối trang
    $cta['settings']['padding'] = sb_pad( 24, 24, 72, 24 );

    return array( $hero, $groups_section, $detail, $process, $cta );
}

/* ---------- Trang chi tiết một dịch vụ ---------- */
function saybencode_el_layout_service( $s ) {
    $contact = home_url( '/lien-he/' );

    $left = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-hero-text' ), array(
        sb_text( 'Dịch vụ Saybencode', 'sb-eyebrow' ),
        sb_heading( $s['title'], 'h1' ),
        sb_text( '<p>' . esc_html( $s['lead'] ) . '</p>', 'sb-lead' ),
        sb_text( '<span class="sb-stars">★★★★★</span> <strong>4.9/5</strong> từ 140+ triển khai', 'sb-rating' ),
        sb_button( 'Nhận tư vấn miễn phí', $contact, 'sb-btn-amber' ),
    ), true );

    // Khung bên phải: đang là icon, muốn dùng ảnh thì xoá icon và kéo widget "Hình ảnh" vào
    $right = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', 'justify_content' => 'center', 'align_items' => 'center', '_css_classes' => 'sb-hero-media' ), array(
        sb_w( 'icon', array( 'selected_icon' => sb_icon_val( sb_fa( $s['icon'] ) ), 'view' => 'default', 'primary_color' => '#ffffff', 'size' => sb_size( 90 ), 'align' => 'center' ) ),
    ), true );

    $hero = sb_section( 'sb-hero sb-service-hero', '#0a1330', 64, array(
        sb_c( array( 'content_width' => 'full', 'flex_direction' => 'row', 'flex_direction_mobile' => 'column', 'flex_gap' => array( 'unit' => 'px', 'size' => 48, 'column' => '48', 'row' => '32', 'isLinked' => false ), 'align_items' => 'center', '_css_classes' => 'sb-service-hero-grid' ), array( $left, $right ), true ),
    ) );

    // Danh sách hạng mục: chữ thường (gạch đầu dòng), không khung
    $li = '';
    foreach ( $s['items'] as $it ) $li .= '<li>' . esc_html( $it ) . '</li>';

    $main = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-service-main' ), array(
        sb_text( '<p>' . esc_html( $s['intro'] ) . '</p>', 'sb-intro' ),
        sb_heading( 'Hạng mục triển khai', 'h2' ),
        sb_text( '<ul>' . $li . '</ul>', 'sb-plain-list' ),
        sb_heading( $s['extra_title'], 'h2' ),
        sb_text( '<p>' . esc_html( $s['extra'] ) . '</p>' ),
        sb_heading( 'Phù hợp với', 'h2' ),
        sb_text( '<p>' . esc_html( $s['fit'] ) . '</p>' ),
    ), true );

    // Cột trái: khung nội dung + 2 thẻ Dịch vụ trước/kế tiếp ngay dưới khung
    $left_col = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-service-left' ), array(
        $main,
        sb_w( 'shortcode', array( 'shortcode' => '[saybencode_service_nav]', '_css_classes' => 'sb-service-nav' ) ),
    ), true );

    // Thanh bên bên phải (Liên hệ ngay, Tìm kiếm, Dịch vụ gần đây, Đăng ký nhận tin)
    $side = sb_c( array( 'content_width' => 'full', 'flex_direction' => 'column', '_css_classes' => 'sb-service-side' ), array(
        sb_w( 'shortcode', array( 'shortcode' => '[saybencode_service_sidebar]' ) ),
    ), true );

    $body = sb_section( 'sb-section sb-service-body', '#f5f7fc', 48, array(
        sb_c( array(
            'content_width' => 'full', 'flex_direction' => 'row', 'flex_direction_mobile' => 'column', 'align_items' => 'flex-start',
            'flex_gap' => array( 'unit' => 'px', 'size' => 30, 'column' => '30', 'row' => '30', 'isLinked' => true ),
            '_css_classes' => 'sb-service-grid',
        ), array( $left_col, $side ), true ),
    ), 1160 );

    $cta = sb_section( 'sb-cta-dark', '#0a1330', 64, array(
        sb_heading( 'Quan tâm dịch vụ này cho doanh nghiệp bạn?', 'h2' ),
        sb_text( '<p>Đội ngũ Saybencode sẵn sàng tư vấn giải pháp phù hợp và báo giá chi tiết trong 24 giờ.</p>' ),
        sb_button( 'Liên hệ ngay', $contact, 'sb-btn-amber' ),
    ), 760 );

    return array( $hero, $body, $cta );
}
