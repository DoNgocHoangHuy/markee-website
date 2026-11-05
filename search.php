<?php get_header(); global $wp_query; $q = get_search_query(); ?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> <span>›</span> Tìm kiếm</div>
    <h1>Kết quả tìm kiếm</h1>
    <p><?php echo $q ? 'Có ' . (int) $wp_query->found_posts . ' kết quả cho “' . esc_html( $q ) . '”.' : 'Nhập từ khoá để tìm bài viết.'; ?></p>
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
        <p class="empty-note">Không tìm thấy bài viết nào phù hợp. Hãy thử từ khoá khác hoặc <a href="<?php echo esc_url( saybencode_blog_url() ); ?>">xem tất cả bài viết →</a></p>
      <?php endif; ?>
    </div>
    <?php get_template_part( 'template-parts/blog-sidebar', null, array( 'mode' => 'featured' ) ); ?>
  </div>
</section>

<?php get_footer(); ?>
