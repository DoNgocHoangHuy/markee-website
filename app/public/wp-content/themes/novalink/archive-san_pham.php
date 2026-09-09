<?php
/**
 * Template: Danh sách Sản phẩm — /san-pham/
 */
get_header();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Sản phẩm</div>
    <h1>Sản phẩm của chúng tôi</h1>
    <p>Khám phá các sản phẩm &amp; giải pháp mà Novalink cung cấp cho doanh nghiệp của bạn.</p>
  </div>
</section>

<section class="section">
  <div class="container">

    <?php
    $danh_muc_terms = get_terms( array( 'taxonomy' => 'danh_muc_sp', 'hide_empty' => true ) );
    if ( ! empty( $danh_muc_terms ) && ! is_wp_error( $danh_muc_terms ) ) :
    ?>
      <div class="category-tabs">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'san_pham' ) ); ?>" class="active">Tất cả</a>
        <?php foreach ( $danh_muc_terms as $term ) : ?>
          <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>

      <div class="products-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php get_template_part( 'template-parts/product-card' ); ?>
        <?php endwhile; ?>
      </div>

      <div class="pagination">
        <?php echo paginate_links( array( 'prev_text' => '‹', 'next_text' => '›' ) ); ?>
      </div>

    <?php else : ?>

      <p style="text-align:center;color:var(--ink-soft);">
        Chưa có sản phẩm nào. Vào <strong>Sản phẩm → Thêm sản phẩm</strong> trong khu vực quản trị để đăng sản phẩm đầu tiên.
      </p>

    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
