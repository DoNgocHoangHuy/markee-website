<?php
/**
 * Trang Blog (trang "Bài viết" của WordPress).
 * Chỉ hiển thị trang 1 (1 bài nổi bật + 4 bài), không phân trang. /blog/page/2/... tự chuyển về /blog/.
 */
get_header(); ?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> <span>›</span> Blog</div>
    <h1>Tin tức &amp; Insights</h1>
    <p>Cập nhật xu hướng Marketing, Công nghệ &amp; Media mới nhất cùng những góc nhìn chuyên sâu từ đội ngũ <?php echo esc_html( saybencode_brand() ); ?>.</p>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">
    <div class="blog-main">

      <?php if ( have_posts() ) :
        $i = 0; $grid_open = false;
        while ( have_posts() ) : the_post(); $i++;

          // Bài đầu tiên của trang 1 hiển thị dạng nổi bật
          if ( 1 === $i && ! is_paged() ) {
              get_template_part( 'template-parts/post-featured' );
              continue;
          }
          // Mở lưới đúng 1 lần, ở bài đầu tiên cần vào lưới (trang 1 và cả trang 2+)
          if ( ! $grid_open ) {
              echo '<h3 class="blog-section-title">' . ( is_paged() ? 'Bài viết' : 'Bài viết mới nhất' ) . '</h3><div class="posts-grid-2">';
              $grid_open = true;
          }
          get_template_part( 'template-parts/post-card' );
        endwhile;
        echo $grid_open ? '</div>' : '';
        // Blog chỉ hiển thị trang 1 (không phân trang)
      else : ?>
        <p class="empty-note">Chưa có bài viết nào. Vui lòng quay lại sau.</p>
      <?php endif; ?>

    </div>
    <?php get_template_part( 'template-parts/blog-sidebar', null, array( 'mode' => 'featured' ) ); ?>
  </div>
</section>

<?php get_footer(); ?>
