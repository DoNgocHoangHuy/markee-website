<?php get_header(); ?>
<section class="hero-dark">
  <div class="container page-hero">
    <h1>Không tìm thấy trang</h1>
    <p>Đường dẫn bạn truy cập không tồn tại hoặc đã được chuyển đi.</p>
  </div>
</section>
<section class="section">
  <div class="container" style="text-align:center">
    <p class="empty-note">Bạn có thể quay về trang chủ hoặc xem các dịch vụ của chúng tôi.</p>
    <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ</a>
    <a class="btn btn-outline-dark" href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>">Xem dịch vụ</a>
  </div>
</section>
<?php get_footer(); ?>
