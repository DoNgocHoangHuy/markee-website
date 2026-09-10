<?php
/* Template Name: Chi Tiết Dịch Vụ */
get_header();
while ( have_posts() ) : the_post();

// Tách chữ đầu tiên của tiêu đề để tô màu đỏ (đồng bộ điểm nhấn thương hiệu)
$full_title  = get_the_title();
$title_parts = explode( ' ', $full_title, 2 );
$first_word  = $title_parts[0];
$rest_title  = isset( $title_parts[1] ) ? $title_parts[1] : '';
?>

<section class="service-detail-hero">
  <div class="container">
    <span class="eyebrow">Dịch vụ Novalink</span>
    <h1><span class="accent"><?php echo esc_html( $first_word ); ?></span> <?php echo esc_html( $rest_title ); ?></h1>

    <?php if ( has_excerpt() ) : ?>
      <p class="sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <?php endif; ?>

    <div class="meta-row">
      <span class="rating-pill"><span class="stars">★★★★★</span> 4.9/5 từ 140+ triển khai</span>
      <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-red">Nhận tư vấn miễn phí</a>
    </div>

    <?php if ( has_post_thumbnail() ) : ?>
      <div class="hero-img"><?php the_post_thumbnail( 'large' ); ?></div>
    <?php endif; ?>
  </div>
</section>

<section class="service-detail-body" id="chi-tiet">
  <div class="entry-content">
    <?php the_content(); ?>
  </div>
</section>

<section class="cta-dark">
  <div class="container">
    <h2>Quan tâm dịch vụ này cho doanh nghiệp bạn?</h2>
    <p>Đội ngũ Novalink sẵn sàng tư vấn giải pháp phù hợp và báo giá chi tiết trong 24 giờ.</p>
    <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-amber">Liên hệ ngay</a>
  </div>
</section>

<?php endwhile; get_footer(); ?>
