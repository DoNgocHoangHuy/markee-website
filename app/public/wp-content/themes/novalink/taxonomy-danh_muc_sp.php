<?php
/**
 * Template: Sản phẩm theo danh mục — /danh-muc-san-pham/ten-danh-muc/
 */
get_header();
$term = get_queried_object();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      Trang chủ <span>›</span>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'san_pham' ) ); ?>" style="color:inherit;">Sản phẩm</a>
      <span>›</span> <?php echo esc_html( $term->name ); ?>
    </div>
    <h1><?php echo esc_html( $term->name ); ?></h1>
    <?php if ( ! empty( $term->description ) ) : ?>
      <p><?php echo esc_html( $term->description ); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">

    <div class="category-tabs">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'san_pham' ) ); ?>">Tất cả</a>
      <?php
      $all_terms = get_terms( array( 'taxonomy' => 'danh_muc_sp', 'hide_empty' => true ) );
      if ( ! empty( $all_terms ) && ! is_wp_error( $all_terms ) ) :
        foreach ( $all_terms as $t ) :
      ?>
        <a
          href="<?php echo esc_url( get_term_link( $t ) ); ?>"
          class="<?php echo ( $t->term_id === $term->term_id ) ? 'active' : ''; ?>"
        ><?php echo esc_html( $t->name ); ?></a>
      <?php
        endforeach;
      endif;
      ?>
    </div>

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

      <p style="text-align:center;color:var(--ink-soft);">Chưa có sản phẩm nào trong danh mục này.</p>

    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
