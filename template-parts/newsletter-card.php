<?php
/** Khối "Đăng ký nhận tin" (dùng chung cho sidebar Blog và sidebar Dịch vụ). */
if ( ! defined( 'ABSPATH' ) ) exit;
$sub = isset( $_GET['dang-ky'] ) ? sanitize_key( wp_unslash( $_GET['dang-ky'] ) ) : '';
?>
  <div class="sidebar-card newsletter-card" id="dang-ky-nhan-tin">
    <h5>Đăng ký nhận tin</h5>
    <p>Nhận bài viết mới nhất từ <?php echo esc_html( saybencode_brand() ); ?> mỗi tuần.</p>

    <?php if ( 'ok' === $sub ) : ?>
      <p class="newsletter-msg is-ok" role="status">Đã đăng ký. Cảm ơn bạn!</p>
    <?php else : ?>
      <?php if ( 'loi' === $sub ) : ?>
        <p class="newsletter-msg is-err" role="alert">Email chưa đúng hoặc phiên đã hết hạn. Vui lòng thử lại.</p>
      <?php endif; ?>
      <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="saybencode_subscribe">
        <input type="hidden" name="redirect_to" value="<?php echo esc_url( is_singular() ? get_permalink() : saybencode_blog_url() ); ?>">
        <?php wp_nonce_field( 'saybencode_subscribe', 'sub_nonce' ); ?>
        <div class="cf-hp" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
        <label class="screen-reader-text" for="sub-email">Email của bạn</label>
        <input id="sub-email" type="email" name="email" placeholder="Email của bạn" autocomplete="email" required>
        <button class="btn btn-primary btn-block btn-sm" type="submit">Đăng ký</button>
      </form>
    <?php endif; ?>
  </div>
