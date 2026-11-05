<?php
/* Trang chi tiết một dịch vụ (loại bài "Dịch vụ") */
get_header();

while ( have_posts() ) : the_post();

    // Đã dựng bằng Elementor: chỉ in nội dung Elementor (sửa trực tiếp trong page builder)
    if ( saybencode_is_elementor_built( get_the_ID() ) ) {
        the_content();
        continue;
    }

    $icon = get_post_meta( get_the_ID(), '_sv_icon', true );
    $lead = get_the_excerpt();
?>

<section class="hero-dark service-hero">
  <div class="container service-hero-grid">
    <div class="service-hero-text">
      <span class="eyebrow">Dịch vụ <?php echo esc_html( saybencode_brand() ); ?></span>
      <h1><?php the_title(); ?></h1>
      <?php if ( $lead ) : ?><p class="lead"><?php echo esc_html( $lead ); ?></p><?php endif; ?>
      <p class="rating"><span class="stars" aria-hidden="true">★★★★★</span> <strong>4.9/5</strong> từ 140+ triển khai</p>
      <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-amber">Nhận tư vấn miễn phí</a>
    </div>

    <div class="service-hero-media">
      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
      <?php else : ?>
        <div class="service-hero-fallback" aria-hidden="true"><?php echo saybencode_icon( $icon ? $icon : 'megaphone', 96 ); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section service-section">
  <div class="container single-layout service-layout">
    <div class="single-main">
      <article class="service-body entry-content">
        <?php the_content(); ?>
      </article>

      <?php get_template_part( 'template-parts/post-nav' ); ?>
    </div>
    <?php get_template_part( 'template-parts/service-sidebar' ); ?>
  </div>
</section>

<section class="cta-dark">
  <div class="container">
    <h2>Quan tâm dịch vụ này cho doanh nghiệp bạn?</h2>
    <p>Đội ngũ <?php echo esc_html( saybencode_brand() ); ?> sẵn sàng tư vấn giải pháp phù hợp và báo giá chi tiết trong 24 giờ.</p>
    <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-amber">Liên hệ ngay</a>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
