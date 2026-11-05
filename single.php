<?php
get_header();
while ( have_posts() ) : the_post();
    $cat = saybencode_primary_cat();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> <span>›</span>
      <a href="<?php echo esc_url( saybencode_blog_url() ); ?>">Blog</a>
      <?php if ( $cat ) : ?> <span>›</span> <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><?php endif; ?>
    </div>
    <h1 class="post-title"><?php the_title(); ?></h1>
    <p class="post-meta"><?php echo esc_html( get_the_author() ); ?> · <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time> · <?php echo esc_html( saybencode_reading_time() ); ?> phút đọc</p>
  </div>
</section>

<section class="section">
  <div class="container single-layout">

    <div class="single-main">
      <article <?php post_class( 'single-article' ); ?>>
        <div class="cover"><?php saybencode_thumb( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?></div>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>

      <?php get_template_part( 'template-parts/post-nav' ); ?>
    </div>

    <?php get_template_part( 'template-parts/blog-sidebar', null, array( 'mode' => 'related' ) ); ?>

  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
