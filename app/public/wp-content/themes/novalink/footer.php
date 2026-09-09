<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><span class="logo-mark">N</span> <?php bloginfo( 'name' ); ?></a>
        <p>Đối tác chiến lược Marketing, Công nghệ &amp; Media cho doanh nghiệp Việt.</p>
        <div class="footer-social">
          <a href="#">f</a><a href="#">in</a><a href="#">ig</a><a href="#">yt</a>
        </div>
      </div>
      <div class="footer-col">
        <h5>Giới thiệu</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Về Novalink</a></li>
          <li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>">Dịch vụ</a></li>
          <li><a href="<?php echo esc_url( get_post_type_archive_link( 'san_pham' ) ); ?>">Sản phẩm</a></li>
          <li><a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>">Blog</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Thông tin liên hệ</h5>
        <ul>
          <li>123 Nguyễn Huệ, Q.1, TP.HCM</li>
          <li>0906 123 4567</li>
          <li>contact@novalink.vn</li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Mạng xã hội</h5>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">LinkedIn</a></li>
          <li><a href="#">Instagram</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. Bản quyền thuộc về Novalink Agency.</span>
      <span>Chính sách bảo mật · Điều khoản sử dụng</span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
