<?php
/** Bài nổi bật (to) ở đầu trang Blog. */
if ( ! defined( 'ABSPATH' ) ) exit;
$cat = saybencode_primary_cat();
?>
<article <?php post_class( 'featured-post' ); ?>>
  <div class="thumb">
    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>" tabindex="-1">
      <?php saybencode_thumb( 'large', array( 'alt' => '' ) ); ?>
    </a>
  </div>
  <div class="content">
    <?php if ( $cat ) : ?><span class="tag"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
    <span class="date"><?php echo esc_html( get_the_date() ); ?></span>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
    <div class="author"><span class="avatar" aria-hidden="true"></span><?php the_author(); ?></div>
  </div>
</article>
