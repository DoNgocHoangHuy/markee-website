<?php
/* Template Name: Trang Liên Hệ */
get_header();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Liên hệ</div>
    <h1>Liên hệ với chúng tôi</h1>
    <p>Chia sẻ với Novalink về dự án của bạn, đội ngũ chúng tôi sẽ phản hồi trong vòng 24 giờ làm việc.</p>
  </div>
</section>

<section class="section">
  <div class="container contact-grid">

    <div class="contact-card">
      <h3>Gửi yêu cầu tư vấn</h3>
      <p class="sub">Hãy bắt đầu dự án tiếp theo của bạn cùng chúng tôi.</p>

      <?php
      // Nếu bạn cài plugin Contact Form 7 hoặc WPForms, thay thế <form> bên dưới bằng shortcode, ví dụ:
      // echo do_shortcode('[contact-form-7 id="123" title="Liên hệ"]');
      ?>
      <form action="" method="post">
        <div class="form-row">
          <div class="form-field"><label>Họ và tên *</label><input type="text" name="ho_ten" placeholder="Nguyễn Văn A" required></div>
          <div class="form-field"><label>Số điện thoại *</label><input type="tel" name="sdt" placeholder="09xx xxx xxx" required></div>
        </div>
        <div class="form-row">
          <div class="form-field full"><label>Email công việc *</label><input type="email" name="email" placeholder="ban@congty.com" required></div>
        </div>
        <div class="form-row">
          <div class="form-field full"><label>Nội dung yêu cầu *</label><textarea name="noi_dung" placeholder="Mô tả ngắn gọn về dự án của bạn..." required></textarea></div>
        </div>
        <button class="btn btn-red btn-block" type="submit">Gửi yêu cầu tư vấn</button>
      </form>
    </div>

    <div class="info-card">
      <h3>Trụ sở văn phòng chính</h3>
      <div class="info-item">
        <span class="ic">📍</span>
        <div><div class="lbl">Địa chỉ</div><div class="val">Tầng 12, Toà nhà Innovation, 15 Nguyễn Huệ, Q.1, TP.HCM</div></div>
      </div>
      <div class="info-item">
        <span class="ic">📞</span>
        <div><div class="lbl">Hotline</div><div class="val">1900 1234 · 0906 123 4567</div></div>
      </div>
      <div class="info-item">
        <span class="ic">✉️</span>
        <div><div class="lbl">Email</div><div class="val">contact@novalink.vn</div></div>
      </div>
      <div class="info-item">
        <span class="ic">🕐</span>
        <div><div class="lbl">Giờ làm việc</div><div class="val">Thứ 2 - Thứ 7 · 8:30 - 18:00</div></div>
      </div>
      <div class="info-social">
        <a href="#">f</a><a href="#">in</a><a href="#">ig</a><a href="#">yt</a>
      </div>
    </div>

    <div style="grid-column:1/-1;">
      <div class="map-wrap">
        <!-- Thay src bằng link nhúng Google Maps thật của bạn (Google Maps > Chia sẻ > Nhúng bản đồ) -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4!2d106.7!3d10.77!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1" loading="lazy"></iframe>
      </div>
    </div>

  </div>
</section>

<?php get_footer(); ?>
