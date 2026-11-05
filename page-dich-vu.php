<?php
/* Template Name: Trang Dịch Vụ */
get_header();

// Đã dựng bằng Elementor: để Elementor tự hiển thị toàn bộ nội dung
if ( have_posts() && saybencode_is_elementor_built( get_queried_object_id() ) ) {
    while ( have_posts() ) { the_post(); the_content(); }
    get_footer();
    return;
}

// (Bản dự phòng khi chưa cài Elementor) 3 trụ cột dịch vụ (khớp site thật)
$groups = array(
    array( '01', 'Giải pháp Tiếp thị (Marketing)', array(
        array( 'search',    'SEO & SEM',              'Tối ưu công cụ tìm kiếm và quảng cáo trả phí, tăng lưu lượng truy cập chất lượng.' ),
        array( 'pen',       'Content Marketing',      'Xây dựng nội dung có chiều sâu, đúng insight khách hàng mục tiêu.' ),
        array( 'users',     'Social Media Marketing', 'Quản trị và phát triển kênh mạng xã hội, tăng tương tác và nhận diện.' ),
        array( 'mail',      'Email Marketing',        'Xây dựng chuỗi email tự động chăm sóc và chuyển đổi khách hàng.' ),
    ) ),
    array( '02', 'Giải pháp Công nghệ số (Technology)', array(
        array( 'monitor',   'Thiết kế Website',       'Website chuẩn UX/UI, tối ưu tốc độ và thân thiện SEO.' ),
        array( 'phone',     'Phát triển ứng dụng',    'Xây dựng Web App/Mobile App theo yêu cầu vận hành doanh nghiệp.' ),
        array( 'database',  'Hệ thống quản lý',       'Triển khai CRM, ERP giúp quản trị dữ liệu và quy trình hiệu quả.' ),
        array( 'zap',       'Tối ưu vận hành',        'Tự động hoá quy trình, tích hợp hệ thống, giảm chi phí vận hành.' ),
    ) ),
    array( '03', 'Sản xuất Media & Thiết kế sáng tạo (Media)', array(
        array( 'video',     'Sản xuất Video quảng cáo', 'Video TVC, viral clip chuẩn thương hiệu, tối ưu cho từng nền tảng.' ),
        array( 'camera',    'Chụp ảnh thương hiệu',     'Sản xuất hình ảnh sản phẩm, campaign chuyên nghiệp, giàu cảm xúc.' ),
        array( 'palette',   'Thiết kế đồ hoạ',          'Bộ nhận diện thương hiệu, ấn phẩm truyền thông đồng bộ, sáng tạo.' ),
        array( 'megaphone', 'Truyền thông tổng lực',    'Lên kế hoạch và triển khai chiến dịch truyền thông đa kênh.' ),
    ) ),
);

$services = get_posts( array(
    'post_type' => 'dich_vu', 'post_status' => 'publish', 'numberposts' => 24,
    'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
) );
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Dịch vụ</div>
    <h1>Dịch vụ của chúng tôi</h1>
    <p>Giải pháp toàn diện xoay quanh 3 trụ cột: Marketing, Công nghệ và Media — giúp thương hiệu bạn tăng trưởng bền vững.</p>
  </div>
</section>

<section class="section">
  <div class="container">

    <?php foreach ( $groups as $g ) : ?>
      <div class="service-group">
        <div class="service-group-head">
          <span class="num"><?php echo esc_html( $g[0] ); ?></span>
          <h2><?php echo esc_html( $g[1] ); ?></h2>
        </div>
        <div class="svc-grid">
          <?php foreach ( $g[2] as $it ) : ?>
            <div class="svc-item">
              <span class="svc-tile"><?php echo saybencode_icon( $it[0], 26 ); ?></span>
              <h3><?php echo esc_html( $it[1] ); ?></h3>
              <p><?php echo esc_html( $it[2] ); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if ( $services ) : ?>
      <div class="service-detail-wrap">
        <span class="section-label">Chi tiết từng hạng mục</span>
        <h2 class="section-title">Danh mục dịch vụ chi tiết</h2>

        <div class="detail-grid">
          <?php foreach ( $services as $sv ) :
            $icon = get_post_meta( $sv->ID, '_sv_icon', true );
            $card = get_post_meta( $sv->ID, '_sv_card', true );
            if ( ! $card ) $card = get_the_excerpt( $sv );
          ?>
            <a class="detail-card" href="<?php echo esc_url( get_permalink( $sv ) ); ?>">
              <span class="svc-tile"><?php echo saybencode_icon( $icon ? $icon : 'megaphone', 24 ); ?></span>
              <h3><?php echo esc_html( get_the_title( $sv ) ); ?></h3>
              <p><?php echo esc_html( $card ); ?></p>
              <span class="link">Xem chi tiết <?php echo saybencode_icon( 'arrow', 15 ); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="process-wrap">
      <span class="section-label">Quy trình làm việc</span>
      <h2 class="section-title">Quy trình hợp tác chặt chẽ &amp; tối ưu</h2>
      <ol class="process-row">
        <li class="process-step"><span class="circle">1</span><h3>Tìm hiểu</h3></li>
        <li class="process-step"><span class="circle">2</span><h3>Lên kế hoạch</h3></li>
        <li class="process-step"><span class="circle">3</span><h3>Triển khai</h3></li>
        <li class="process-step"><span class="circle">4</span><h3>Đánh giá</h3></li>
        <li class="process-step"><span class="circle">5</span><h3>Tối ưu</h3></li>
      </ol>
    </div>

    <div class="services-cta">
      <div class="content">
        <h3>Bạn cần tư vấn chi tiết cho dự án sắp tới?</h3>
        <p>Đội ngũ chuyên gia của <?php echo esc_html( saybencode_brand() ); ?> sẵn sàng lắng nghe và đề xuất giải pháp phù hợp nhất với ngân sách của bạn.</p>
        <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-primary">Liên hệ tư vấn</a>
        <a class="phone" href="tel:<?php echo esc_attr( saybencode_tel( saybencode_info( 'hotline_1' ) ) ); ?>"><?php echo saybencode_icon( 'phone', 18 ); ?> Hotline: <?php echo esc_html( saybencode_info( 'hotline_1' ) ); ?></a>
      </div>
      <div class="img-wrap"><img src="<?php echo esc_url( saybencode_img_url( 'office.svg' ) ); ?>" alt="Văn phòng <?php echo esc_attr( saybencode_brand() ); ?>" loading="lazy"></div>
    </div>

  </div>
</section>

<?php get_footer(); ?>
