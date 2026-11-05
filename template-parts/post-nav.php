<?php
/**
 * Điều hướng bài viết (hoặc dịch vụ) trước / kế tiếp (đặt dưới nội dung bài, cùng cột với bài viết).
 * Chỉ hiện khi có ít nhất 1 bài liền kề. Thiếu bài nào thì ô đó để trống, ô còn lại giữ đúng vị trí.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$is_service = 'dich_vu' === get_post_type();
if ( $is_service ) {
    list( $prev, $next ) = saybencode_service_neighbors( get_the_ID() );
} else {
    $prev = get_previous_post();
    $next = get_next_post();
}
if ( ! $prev && ! $next ) return;

/** In 1 thẻ điều hướng. $dir = 'prev' | 'next' */
$render = function ( $p, $dir ) use ( $is_service ) {
    $is_prev = 'prev' === $dir;
    if ( $is_service ) {
        $label = $is_prev ? 'Dịch vụ trước' : 'Dịch vụ kế tiếp';
        $cat   = (object) array( 'name' => 'Dịch vụ ' . saybencode_brand() );
        $thumb = get_the_post_thumbnail( $p->ID, 'thumbnail', array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) );
        if ( ! $thumb ) {
            $icon  = get_post_meta( $p->ID, '_sv_icon', true );
            $thumb = '<span class="svc-thumb" aria-hidden="true">' . saybencode_icon( $icon ? $icon : 'megaphone', 26 ) . '</span>';
        }
    } else {
        $label = $is_prev ? 'Bài viết trước' : 'Bài viết kế tiếp';
        $cat   = saybencode_primary_cat( $p->ID );
        $thumb = get_the_post_thumbnail( $p->ID, 'thumbnail', array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) );
        if ( ! $thumb ) {
            $thumb = sprintf( '<img class="thumb-fallback" src="%s" alt="" loading="lazy" decoding="async">', esc_url( saybencode_topic_img_url( $p->ID ) ) );
        }
    }
    $arrow = '<span class="post-nav-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">'
           . ( $is_prev ? '<path d="M19 12H5M11 6l-6 6 6 6"/>' : '<path d="M5 12h14M13 6l6 6-6 6"/>' ) . '</svg></span>';
    ?>
    <a class="post-nav-item is-<?php echo esc_attr( $dir ); ?>" href="<?php echo esc_url( get_permalink( $p ) ); ?>" rel="<?php echo esc_attr( $dir ); ?>">
      <?php if ( $is_prev ) echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput ?>
      <?php if ( $is_prev ) : ?>
      <span class="post-nav-thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
      <span class="post-nav-text">
        <span class="post-nav-label"><?php echo esc_html( $label ); ?></span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $p ) ); ?></span>
        <?php if ( $cat ) : ?><span class="post-nav-cat"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
      </span>
      <?php else : ?>
      <span class="post-nav-text">
        <span class="post-nav-label"><?php echo esc_html( $label ); ?></span>
        <span class="post-nav-title"><?php echo esc_html( get_the_title( $p ) ); ?></span>
        <?php if ( $cat ) : ?><span class="post-nav-cat"><?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
      </span>
      <span class="post-nav-thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
      <?php endif; ?>
      <?php if ( ! $is_prev ) echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput ?>
    </a>
    <?php
};
?>
<nav class="post-nav" aria-label="<?php echo $is_service ? 'Điều hướng dịch vụ' : 'Điều hướng bài viết'; ?>">
  <?php if ( $prev ) $render( $prev, 'prev' ); else echo '<span class="post-nav-empty" aria-hidden="true"></span>'; ?>
  <?php if ( $next ) $render( $next, 'next' ); else echo '<span class="post-nav-empty" aria-hidden="true"></span>'; ?>
</nav>
