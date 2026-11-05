<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><span class="logo-mark">S</span> <?php echo esc_html( saybencode_brand() ); ?></a>
        <p>Đối tác chiến lược Marketing, Công nghệ &amp; Media cho doanh nghiệp Việt.</p>
        <?php $socials = saybencode_socials(); if ( $socials ) : ?>
        <div class="footer-social">
          <?php foreach ( $socials as $so ) : ?>
            <a href="<?php echo esc_url( $so[2] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $so[0] ); ?>"><?php echo esc_html( $so[1] ); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="footer-col">
        <h5>Giới thiệu</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Về Saybencode</a></li>
          <li><a href="<?php echo esc_url( home_url( '/dich-vu/' ) ); ?>">Dịch vụ</a></li>
          <li><a href="<?php echo esc_url( saybencode_blog_url() ); ?>">Blog</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Thông tin liên hệ</h5>
        <ul>
          <li><?php echo esc_html( saybencode_info( 'address_short' ) ); ?></li>
          <li><a href="tel:<?php echo esc_attr( saybencode_tel( saybencode_info( 'hotline_1' ) ) ); ?>"><?php echo esc_html( saybencode_info( 'hotline_1' ) ); ?></a></li>
          <li><a href="mailto:<?php echo esc_attr( saybencode_info( 'email' ) ); ?>"><?php echo esc_html( saybencode_info( 'email' ) ); ?></a></li>
        </ul>
      </div>
      <?php if ( $socials ) : ?>
      <div class="footer-col">
        <h5>Mạng xã hội</h5>
        <ul>
          <?php foreach ( $socials as $so ) : ?>
            <li><a href="<?php echo esc_url( $so[2] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $so[0] ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
    <div class="footer-bottom">
      <span>© <?php echo date('Y'); ?> <?php echo esc_html( saybencode_brand() ); ?>. Bản quyền thuộc về Saybencode Agency.</span>
      <span class="footer-legal">
        <?php
        $pp = saybencode_page_link( 'chinh-sach-bao-mat' );
        $tt = saybencode_page_link( 'dieu-khoan-su-dung' );
        echo $pp ? '<a href="' . esc_url( $pp ) . '">Chính sách bảo mật</a>' : 'Chính sách bảo mật';
        echo ' · ';
        echo $tt ? '<a href="' . esc_url( $tt ) . '">Điều khoản sử dụng</a>' : 'Điều khoản sử dụng';
        ?>
      </span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
