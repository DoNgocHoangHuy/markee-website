<?php
/** Thẻ bài viết nhỏ (dùng ở Blog, Chuyên mục, Tìm kiếm). */
if ( ! defined( 'ABSPATH' ) ) exit;
$cat = saybencode_primary_cat();
?>
<article <?php post_class( 'post-card' ); ?>>
  <div class="thumb">
    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>" tabindex="-1">
      <?php if ( $cat ) : ?><span class="tag"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
      <?php saybencode_thumb( 'medium_large', array( 'alt' => '' ) ); ?>
    </a>
  </div>
  <div class="body">
    <div class="date"><?php echo esc_html( get_the_date() ); ?></div>
    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    <a href="<?php the_permalink(); ?>" class="link">Đọc thêm →</a>
  </div>
</article>
