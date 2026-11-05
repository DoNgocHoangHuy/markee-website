<?php
/**
 * Trang chuyên mục / thẻ / tác giả / ngày: dùng chung 1 layout.
 */
get_header();

if ( is_category() ) {
    $kicker = 'Chuyên mục';
    $title  = single_cat_title( '', false );
} elseif ( is_tag() ) {
    $kicker = 'Thẻ';
    $title  = single_tag_title( '', false );
} elseif ( is_author() ) {
    $kicker = 'Tác giả';
    $title  = get_the_author();
} else {
    $kicker = 'Lưu trữ';
    $title  = wp_strip_all_tags( preg_replace( '/^[^:]+:\s*/u', '', get_the_archive_title() ) );
}
$kicker_lc = array( 'Chuyên mục' => 'chuyên mục', 'Thẻ' => 'thẻ', 'Tác giả' => 'tác giả', 'Lưu trữ' => 'mục lưu trữ' )[ $kicker ];
$desc = term_description();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> <span>›</span>
      <a href="<?php echo esc_url( saybencode_blog_url() ); ?>">Blog</a> <span>›</span> <?php echo esc_html( $title ); ?>
    </div>
    <span class="archive-kicker"><?php echo esc_html( $kicker ); ?></span>
    <h1><?php echo esc_html( $title ); ?></h1>
    <p><?php echo $desc ? wp_kses_post( wp_strip_all_tags( $desc ) ) : 'Tổng hợp các bài viết thuộc ' . esc_html( $kicker_lc ) . ' này từ đội ngũ ' . esc_html( saybencode_brand() ) . '.'; ?></p>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">
    <div class="blog-main">
      <?php if ( have_posts() ) : ?>
        <div class="posts-grid-2">
          <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/post-card' ); endwhile; ?>
        </div>
        <?php saybencode_pagination(); ?>
      <?php else : ?>
        <p class="empty-note">Chưa có bài viết nào trong mục này. <a href="<?php echo esc_url( saybencode_blog_url() ); ?>">Xem tất cả bài viết →</a></p>
      <?php endif; ?>
    </div>
    <?php get_template_part( 'template-parts/blog-sidebar', null, array( 'mode' => 'featured' ) ); ?>
  </div>
</section>

<?php get_footer(); ?>
