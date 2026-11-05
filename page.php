<?php
/** Trang tĩnh mặc định (Chính sách bảo mật, Điều khoản, ...). */
get_header();
while ( have_posts() ) : the_post();
?>
<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> <span>›</span> <?php the_title(); ?></div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <article <?php post_class( 'single-article page-article' ); ?>>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  </div>
</section>
<?php
endwhile;
get_footer();
