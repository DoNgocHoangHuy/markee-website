<?php
/**
 * Sidebar trang Dịch vụ: Liên hệ ngay, Tìm kiếm, Dịch vụ gần đây, Đăng ký nhận tin.
 * "Dịch vụ gần đây" có cùng kiểu hiển thị với "Bài viết liên quan" ở trang bài viết.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$current  = (int) ( get_the_ID() ? get_the_ID() : get_queried_object_id() );
$services = get_posts( array(
    'post_type'        => 'dich_vu',
    'post_status'      => 'publish',
    'numberposts'      => 4,
    'post__not_in'     => $current ? array( $current ) : array(),
    'orderby'          => array( 'date' => 'DESC', 'menu_order' => 'ASC' ),
    'suppress_filters' => true,
) );
?>
<aside class="blog-sidebar service-sidebar" aria-label="Thanh bên">

  <div class="sidebar-cta">
    <a class="btn btn-primary btn-block" href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>">Liên hệ ngay</a>
  </div>

  <div class="sidebar-card">
    <h5>Tìm kiếm</h5>
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label class="screen-reader-text" for="sidebar-search">Tìm bài viết</label>
      <input id="sidebar-search" class="sidebar-search" type="search" name="s" placeholder="Tìm bài viết..." value="<?php echo esc_attr( get_search_query() ); ?>">
    </form>
  </div>

  <?php if ( $services ) : ?>
    <div class="sidebar-card">
      <h5>Dịch vụ gần đây</h5>
      <?php foreach ( $services as $sv ) :
        $icon = get_post_meta( $sv->ID, '_sv_icon', true );
        $card = get_post_meta( $sv->ID, '_sv_card', true );
        if ( ! $card ) $card = get_the_excerpt( $sv );
      ?>
        <a href="<?php echo esc_url( get_permalink( $sv ) ); ?>" class="popular-post">
          <?php if ( has_post_thumbnail( $sv ) ) : ?>
            <?php echo get_the_post_thumbnail( $sv, 'thumbnail', array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
          <?php else : ?>
            <span class="svc-thumb" aria-hidden="true"><?php echo saybencode_icon( $icon ? $icon : 'megaphone', 24 ); ?></span>
          <?php endif; ?>
          <div>
            <h6><?php echo esc_html( get_the_title( $sv ) ); ?></h6>
            <div class="date date-clamp"><?php echo esc_html( wp_trim_words( $card, 9, '…' ) ); ?></div>
          </div>
        </a>
      <?php endforeach; ?>
      <a class="sidebar-more" href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>">Xem tất cả dịch vụ →</a>
    </div>
  <?php endif; ?>

  <?php get_template_part( 'template-parts/newsletter-card' ); ?>

</aside>
