<?php
/**
 * Sidebar Blog: Tìm kiếm, Chuyên mục, Bài viết liên quan/nổi bật, Đăng ký nhận tin.
 *
 * Dùng: get_template_part( 'template-parts/blog-sidebar', null, array( 'mode' => 'related' ) );
 *   mode = 'related'  -> cùng chuyên mục với bài đang xem (trang bài viết)
 *   mode = 'featured' -> bài nổi bật (trang Blog)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$mode     = isset( $args['mode'] ) && 'related' === $args['mode'] ? 'related' : 'featured';
$current  = is_singular( 'post' ) ? get_the_ID() : 0;
$cur_cats = $current ? wp_get_post_categories( $current ) : array();
if ( is_category() ) $cur_cats = array( get_queried_object_id() );

// Bài viết cho khối thứ 3
$qargs = array(
    'posts_per_page'      => 4,
    'post__not_in'        => $current ? array( $current ) : array(),
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
);
if ( 'related' === $mode ) {
    if ( $cur_cats ) $qargs['category__in'] = $cur_cats;
    $block_title = 'Bài viết liên quan';
} else {
    $qargs['posts_per_page'] = 3;
    $qargs['orderby']        = 'date';
    $block_title = 'Bài viết nổi bật';
}
$side_posts = new WP_Query( $qargs );
?>
<aside class="blog-sidebar" aria-label="Thanh bên">

  <!-- CTA -->
  <div class="sidebar-cta">
    <a class="btn btn-primary btn-block" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>">Liên hệ ngay</a>
  </div>

  <!-- Tìm kiếm -->
  <div class="sidebar-card">
    <h5>Tìm kiếm</h5>
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label class="screen-reader-text" for="sidebar-search">Tìm bài viết</label>
      <input id="sidebar-search" class="sidebar-search" type="search" name="s" placeholder="Tìm bài viết..." value="<?php echo esc_attr( get_search_query() ); ?>">
    </form>
  </div>

  <!-- Chuyên mục -->
  <div class="sidebar-card">
    <h5>Chuyên mục</h5>
    <ul class="cat-list">
      <?php
      $uncat = get_category_by_slug( 'uncategorized' );
      $sb_cats = get_categories( array( 'hide_empty' => false, 'exclude' => $uncat ? array( (int) $uncat->term_id ) : array() ) );
      foreach ( $sb_cats as $cat ) : ?>
        <li>
          <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"<?php echo in_array( $cat->term_id, $cur_cats, true ) ? ' class="current" aria-current="true"' : ''; ?>>
            <?php echo esc_html( $cat->name ); ?>
            <span class="count"><?php echo esc_html( $cat->count ); ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <!-- Bài viết liên quan / nổi bật -->
  <?php if ( $side_posts->have_posts() ) : ?>
    <div class="sidebar-card">
      <h5><?php echo esc_html( $block_title ); ?></h5>
      <?php while ( $side_posts->have_posts() ) : $side_posts->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="popular-post">
          <?php saybencode_thumb( 'thumbnail', array( 'alt' => '' ) ); ?>
          <div>
            <h6><?php the_title(); ?></h6>
            <div class="date"><?php echo esc_html( get_the_date() ); ?></div>
          </div>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  <?php endif; ?>

  <?php get_template_part( 'template-parts/newsletter-card' ); ?>

</aside>
