<?php
/**
 * Template: Chi tiết Sản phẩm — /san-pham/ten-san-pham/
 */
get_header();

while ( have_posts() ) :
  the_post();
  $sp_gia   = get_post_meta( get_the_ID(), '_sp_gia', true );
  $sp_mo_ta = get_post_meta( get_the_ID(), '_sp_mo_ta_ngan', true );
  $sp_terms = get_the_terms( get_the_ID(), 'danh_muc_sp' );
  ?>

  <section class="hero-dark">
    <div class="container page-hero">
      <div class="breadcrumb">
        Trang chủ <span>›</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'san_pham' ) ); ?>" style="color:inherit;">Sản phẩm</a>
        <span>›</span> <?php the_title(); ?>
      </div>
      <h1 style="font-size:26px;"><?php the_title(); ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="product-detail">
        <div class="product-detail-media">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail(); ?>
          <?php else : ?>
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=700&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>">
          <?php endif; ?>
        </div>
        <div class="product-detail-info">
          <?php if ( ! empty( $sp_terms ) && ! is_wp_error( $sp_terms ) ) : ?>
            <span class="tag-static"><?php echo esc_html( $sp_terms[0]->name ); ?></span>
          <?php endif; ?>

          <?php if ( $sp_gia ) : ?>
            <div class="price-tag-lg"><?php echo esc_html( $sp_gia ); ?></div>
          <?php endif; ?>

          <?php if ( $sp_mo_ta ) : ?>
            <p class="lead-desc"><?php echo esc_html( $sp_mo_ta ); ?></p>
          <?php endif; ?>

          <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-red">Liên hệ tư vấn</a>
        </div>
      </div>

      <div class="entry-content" style="max-width:760px;margin:44px auto 0;">
        <?php the_content(); ?>
      </div>

    </div>
  </section>

<?php endwhile; ?>

<?php get_footer(); ?>
