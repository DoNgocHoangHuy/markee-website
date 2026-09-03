<?php
/**
 * Template hiển thị trang Chuyên mục Blog (Category Archive)
 * VD: /category/marketing/, /category/cong-nghe-media/...
 * Dùng chung style với trang Blog (home.php) để đồng bộ giao diện.
 */
get_header();
$current_cat = get_queried_object();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;">Trang chủ</a> <span>›</span>
      <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>" style="color:inherit;">Blog</a> <span>›</span>
      <?php echo esc_html( $current_cat->name ); ?>
    </div>
    <h1>Chuyên mục: <?php echo esc_html( $current_cat->name ); ?></h1>
    <?php if ( ! empty( $current_cat->description ) ) : ?>
      <p><?php echo esc_html( $current_cat->description ); ?></p>
    <?php else : ?>
      <p>Tổng hợp bài viết mới nhất thuộc chuyên mục <?php echo esc_html( $current_cat->name ); ?> từ Novalink.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">

    <div>
      <h3 class="blog-section-title"><?php echo esc_html( $current_cat->name ); ?> — <?php echo $current_cat->count; ?> bài viết</h3>

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
                <div class="date"><?php echo get_the_date(); ?></div>
                <h4><?php the_title(); ?></h4>
                <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 16 ); ?></p>
                <a href="<?php the_permalink(); ?>" class="link">Đọc thêm →</a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div class="pagination">
          <?php
          echo paginate_links( array(
              'prev_text' => '‹',
              'next_text' => '›',
          ) );
          ?>
        </div>
      <?php else : ?>
        <p style="color:var(--ink-soft);">Chưa có bài viết nào trong chuyên mục này.</p>
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
              $is_current = ( $cat->term_id === $current_cat->term_id ) ? 'style="color:var(--red);font-weight:800;"' : '';
          ?>
            <li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" <?php echo $is_current; ?>><?php echo esc_html( $cat->name ); ?> <span class="count"><?php echo $cat->count; ?></span></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="sidebar-card">
        <h5>Bài viết nổi bật</h5>
        <?php
        $popular = new WP_Query( array( 'posts_per_page' => 3, 'orderby' => 'comment_count' ) );
        if ( $popular->have_posts() ) :
            while ( $popular->have_posts() ) : $popular->the_post();
        ?>
          <a href="<?php the_permalink(); ?>" class="popular-post" style="color:inherit;">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
              <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=120&q=80" alt="">
            <?php endif; ?>
            <div><h6><?php the_title(); ?></h6><div class="date"><?php echo get_the_date(); ?></div></div>
          </a>
        <?php
            endwhile; wp_reset_postdata();
        endif;
        ?>
      </div>

      <div class="sidebar-card newsletter-card">
        <h5>Đăng ký nhận tin</h5>
        <p>Nhận bài viết mới nhất từ Novalink mỗi tuần.</p>
        <input type="email" placeholder="Email của bạn">
        <button class="btn btn-red btn-block btn-sm">Đăng ký</button>
      </div>
    </aside>

  </div>
</section>

<?php get_footer(); ?>