<?php
/**
 * Template chi tiết 1 bài viết Blog
 * - Layout 2 cột: nội dung + sidebar (sidebar cố định khi cuộn)
 * - Chân bài: nút điều hướng Bài trước / Bài kế tiếp
 */
get_header();
while ( have_posts() ) : the_post();

$cats = get_the_category();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;">Trang chủ</a> <span>›</span>
      <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>" style="color:inherit;">Blog</a>
      <?php if ( ! empty( $cats ) ) : ?>
        <span>›</span> <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" style="color:inherit;"><?php echo esc_html( $cats[0]->name ); ?></a>
      <?php endif; ?>
    </div>
    <h1 style="font-size:26px;"><?php the_title(); ?></h1>
    <p style="font-size:13.5px;margin-top:10px;">
      <?php echo get_the_date(); ?> · <?php the_author(); ?>
    </p>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">

    <div>
      <article class="single-post-wrap">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="cover"><?php the_post_thumbnail( 'large' ); ?></div>
        <?php endif; ?>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>

      <?php
      // ---- Điều hướng Bài trước / Bài kế tiếp ----
      // Ưu tiên hiện tên bài ngắn gọn (cắt còn 8 từ) để nút không bị dài.
      $prev_post = get_previous_post();
      $next_post = get_next_post();

      if ( $prev_post || $next_post ) :
      ?>
        <nav class="post-nav">
          <?php if ( $prev_post ) :
            $prev_thumb = get_the_post_thumbnail_url( $prev_post->ID, 'medium' );
            $prev_cats  = get_the_category( $prev_post->ID );
          ?>
            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="post-nav-item prev">
              <span class="nav-arrow">←</span>
              <span class="nav-thumb">
                <?php if ( $prev_thumb ) : ?>
                  <img src="<?php echo esc_url( $prev_thumb ); ?>" alt="">
                <?php endif; ?>
              </span>
              <span class="nav-text">
                <span class="nav-label">Bài viết trước</span>
                <span class="nav-title"><?php echo esc_html( wp_trim_words( get_the_title( $prev_post->ID ), 8, '…' ) ); ?></span>
                <?php if ( ! empty( $prev_cats ) ) : ?>
                  <span class="nav-cat"><?php echo esc_html( $prev_cats[0]->name ); ?></span>
                <?php endif; ?>
              </span>
            </a>
          <?php else : ?>
            <span class="post-nav-item is-empty"></span>
          <?php endif; ?>

          <?php if ( $next_post ) :
            $next_thumb = get_the_post_thumbnail_url( $next_post->ID, 'medium' );
            $next_cats  = get_the_category( $next_post->ID );
          ?>
            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="post-nav-item next">
              <span class="nav-text">
                <span class="nav-label">Bài viết kế tiếp</span>
                <span class="nav-title"><?php echo esc_html( wp_trim_words( get_the_title( $next_post->ID ), 8, '…' ) ); ?></span>
                <?php if ( ! empty( $next_cats ) ) : ?>
                  <span class="nav-cat"><?php echo esc_html( $next_cats[0]->name ); ?></span>
                <?php endif; ?>
              </span>
              <span class="nav-thumb">
                <?php if ( $next_thumb ) : ?>
                  <img src="<?php echo esc_url( $next_thumb ); ?>" alt="">
                <?php endif; ?>
              </span>
              <span class="nav-arrow">→</span>
            </a>
          <?php else : ?>
            <span class="post-nav-item is-empty"></span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </div>

    <aside class="blog-sidebar">
      <div class="sidebar-sticky">
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
            $current_cat_id = ! empty( $cats ) ? $cats[0]->term_id : 0;
            $categories = get_categories( array( 'hide_empty' => false ) );
            foreach ( $categories as $cat ) :
                $is_current = ( $cat->term_id === $current_cat_id ) ? 'style="color:var(--red);font-weight:800;"' : '';
            ?>
              <li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" <?php echo $is_current; ?>><?php echo esc_html( $cat->name ); ?> <span class="count"><?php echo $cat->count; ?></span></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="sidebar-card">
          <h5>Bài viết liên quan</h5>
          <?php
          $related_args = array(
              'posts_per_page'      => 4,
              'post__not_in'        => array( get_the_ID() ),
              'ignore_sticky_posts' => 1,
          );
          if ( ! empty( $cats ) ) {
              $related_args['cat'] = $cats[0]->term_id;
          }
          $related = new WP_Query( $related_args );

          // Nếu cùng chuyên mục không đủ bài, lấy bài mới nhất bù vào
          if ( ! $related->have_posts() ) {
              $related = new WP_Query( array(
                  'posts_per_page' => 4,
                  'post__not_in'   => array( get_the_ID() ),
              ) );
          }

          if ( $related->have_posts() ) :
              while ( $related->have_posts() ) : $related->the_post();
          ?>
            <a href="<?php the_permalink(); ?>" class="popular-post" style="color:inherit;">
              <?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=120&q=80" alt="">
              <?php endif; ?>
              <div><h6><?php echo esc_html( wp_trim_words( get_the_title(), 10, '…' ) ); ?></h6><div class="date"><?php echo get_the_date(); ?></div></div>
            </a>
          <?php
              endwhile; wp_reset_postdata();
          else :
          ?>
            <p style="font-size:12.5px;color:var(--ink-soft);">Chưa có bài viết liên quan.</p>
          <?php endif; ?>
        </div>

        <div class="sidebar-card newsletter-card">
          <h5>Đăng ký nhận tin</h5>
          <p>Nhận bài viết mới nhất từ Novalink mỗi tuần.</p>
          <input type="email" placeholder="Email của bạn">
          <button class="btn btn-red btn-block btn-sm">Đăng ký</button>
        </div>
      </div>
    </aside>

  </div>
</section>

<?php endwhile; get_footer(); ?>
