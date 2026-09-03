<?php get_header(); ?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Blog</div>
    <h1>Tin tức &amp; Insights</h1>
    <p>Cập nhật xu hướng Marketing, Công nghệ &amp; Media mới nhất cùng những góc nhìn chuyên sâu từ đội ngũ Novalink.</p>
  </div>
</section>

<section class="section blog-body">
  <div class="container blog-layout">

    <div>
      <?php
      $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
      $blog_query = new WP_Query( array( 'paged' => $paged, 'posts_per_page' => 5 ) );

      if ( $blog_query->have_posts() ) :
          $post_index = 0;
          while ( $blog_query->have_posts() ) : $blog_query->the_post();
          $post_index++;

          // Bài đầu tiên hiển thị dạng Featured (to)
          if ( $post_index === 1 && $paged === 1 ) :
      ?>
        <article class="featured-post">
          <div class="thumb">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail(); else : ?>
              <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=700&q=80" alt="">
            <?php endif; ?>
          </div>
          <div class="content">
            <?php $cats = get_the_category(); if ( ! empty( $cats ) ) : ?>
              <span class="tag"><?php echo esc_html( $cats[0]->name ); ?></span>
            <?php endif; ?>
            <span class="date"><?php echo get_the_date(); ?></span>
            <h2><?php the_title(); ?></h2>
            <p><?php echo wp_trim_words( get_the_excerpt(), 24 ); ?></p>
            <div class="author"><span class="avatar"></span> <?php the_author(); ?></div>
          </div>
        </article>
        <h3 class="blog-section-title">Bài viết mới nhất</h3>
        <div class="posts-grid-2">
      <?php
          else :
      ?>
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
            <a href="<?php the_permalink(); ?>" class="link">Đọc thêm →</a>
          </div>
        </article>
      <?php
          endif;
          endwhile;
      ?>
        </div>

        <div class="pagination">
          <?php
          echo paginate_links( array(
              'total'   => $blog_query->max_num_pages,
              'current' => $paged,
              'prev_text' => '‹',
              'next_text' => '›',
          ) );
          ?>
        </div>
      <?php
          wp_reset_postdata();
      else :
      ?>
        <p style="color:var(--ink-soft);">Chưa có bài viết nào. Vào <strong>Bài viết → Thêm mới</strong> để đăng bài đầu tiên.</p>
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
