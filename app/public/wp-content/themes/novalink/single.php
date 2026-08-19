<?php get_header(); ?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Blog <span>›</span> <?php the_title(); ?></div>
    <h1 style="font-size:26px;"><?php the_title(); ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php while ( have_posts() ) : the_post(); ?>
      <div class="single-post-wrap">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="cover"><?php the_post_thumbnail(); ?></div>
        <?php endif; ?>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php get_footer(); ?>
