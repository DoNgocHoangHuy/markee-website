<?php
/**
 * Template hiển thị các trang Archive khác (Tag, theo ngày, tác giả...)
 * Fallback chung, dùng cùng style với Blog để không bị vỡ giao diện.
 */
get_header();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;">Trang chủ</a> <span>›</span> Lưu trữ
    </div>
    <h1><?php the_archive_title(); ?></h1>
    <?php the_archive_description( '<p>', '</p>' ); ?>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">

    <div>
      <?php if ( have_posts() ) : ?>
        <div class="posts-grid-2">
          <?php while ( have_posts() ) : the_post(); ?>
            <article class="post-card">
              <div class="thumb">
                <?php $cats = get_the_category(); if ( ! empty( $cats ) ) : ?>
                  <span class="tag"><?php echo esc_html( $cats[0]->name ); ?></span>
                <?php endif; ?>
                <?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
                  <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=500&q=80" alt="">
                <?php endif; ?>
              </div>
              <div class="body">
                <h4><?php the_title(); ?></h4>
                <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 16 ); ?></p>
                <a href="<?php the_permalink(); ?>" class="link">Đọc thêm →</a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="pagination">
          <?php echo paginate_links( array( 'prev_text' => '‹', 'next_text' => '›' ) ); ?>
        </div>
      <?php else : ?>
        <p style="color:var(--ink-soft);">Không tìm thấy bài viết nào.</p>
      <?php endif; ?>
    </div>

    <aside>
      <div class="sidebar-card">
        <h5>Tìm kiếm</h5>
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
          <input type="text" name="s" placeholder="Tìm bài viết..." style="width:100%;padding:11px 13px;border-radius:10px;border:1.5px solid var(--line);font-size:13.5px;" value="<?php echo get_search_query(); ?>">
        </form>
      </div>

      <div class="sidebar-card">
        <h5>Chuyên mục</h5>
        <ul class="cat-list">
          <?php
          $categories = get_categories( array( 'hide_empty' => false ) );
          foreach ( $categories as $cat ) :
          ?>
            <li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?> <span class="count"><?php echo $cat->count; ?></span></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>

  </div>
</section>

<?php get_footer(); ?>
