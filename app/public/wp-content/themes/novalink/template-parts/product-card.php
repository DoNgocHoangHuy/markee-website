<?php
/**
 * Template part: 1 thẻ sản phẩm dùng chung cho archive-san_pham.php
 * và taxonomy-danh_muc_sp.php
 */
$sp_gia     = get_post_meta( get_the_ID(), '_sp_gia', true );
$sp_mo_ta   = get_post_meta( get_the_ID(), '_sp_mo_ta_ngan', true );
$sp_noi_bat = get_post_meta( get_the_ID(), '_sp_noi_bat', true );
$sp_terms   = get_the_terms( get_the_ID(), 'danh_muc_sp' );
?>
<article class="product-card">
  <div class="thumb">
    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
      <?php if ( '1' === $sp_noi_bat ) : ?>
        <span class="badge-featured">Nổi bật</span>
      <?php elseif ( ! empty( $sp_terms ) && ! is_wp_error( $sp_terms ) ) : ?>
        <span class="tag"><?php echo esc_html( $sp_terms[0]->name ); ?></span>
      <?php endif; ?>

      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail(); ?>
      <?php else : ?>
        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>">
      <?php endif; ?>
    </a>
  </div>
  <div class="body">
    <h4>
      <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
    </h4>
    <p class="excerpt">
      <?php echo esc_html( wp_trim_words( $sp_mo_ta ? $sp_mo_ta : get_the_excerpt(), 18 ) ); ?>
    </p>
    <div class="product-card-footer">
      <?php if ( $sp_gia ) : ?>
        <span class="price-tag"><?php echo esc_html( $sp_gia ); ?></span>
      <?php else : ?>
        <span></span>
      <?php endif; ?>
      <a href="<?php the_permalink(); ?>" class="link">Xem chi tiết →</a>
    </div>
  </div>
</article>
