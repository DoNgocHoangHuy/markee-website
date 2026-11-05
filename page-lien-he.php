<?php
/*
 * Template Name: Trang Liên Hệ
 */

// Xử lý form TRƯỚC khi xuất HTML (để redirect được sau khi gửi thành công)
$cf = saybencode_contact_process();

// Thông tin văn phòng — sửa tại đây, form + map + liên kết tự cập nhật
$office = array(
    'address_full'  => saybencode_info( 'address_full' ),
    'address_short' => saybencode_info( 'address_short' ),
    'hotline_1'     => array( saybencode_info( 'hotline_1' ), saybencode_tel( saybencode_info( 'hotline_1' ) ) ),
    'hotline_2'     => saybencode_info( 'hotline_2' ) ? array( saybencode_info( 'hotline_2' ), saybencode_tel( saybencode_info( 'hotline_2' ) ) ) : null,
    'email'         => saybencode_info( 'email' ),
    'hours'         => saybencode_info( 'hours' ),
);
$maps_query = rawurlencode( $office['address_full'] );
$maps_open  = 'https://www.google.com/maps/search/?api=1&query=' . $maps_query;
$maps_embed = 'https://www.google.com/maps?q=' . $maps_query . '&hl=vi&z=16&output=embed';

$errors  = $cf['errors'];
$values  = $cf['values'];
$has_err = ( 'error' === $cf['status'] );

get_header();
?>

<section class="hero-dark">
  <div class="container page-hero">
    <div class="breadcrumb">Trang chủ <span>›</span> Liên hệ</div>
    <h1>Liên hệ với chúng tôi</h1>
    <p>Chia sẻ với Saybencode về dự án của bạn, đội ngũ chúng tôi sẽ phản hồi trong vòng 24 giờ làm việc.</p>
  </div>
</section>

<section class="section">
  <div class="container contact-grid">

    <!-- ============ FORM ============ -->
    <div class="contact-card" id="lien-he-form">

      <?php if ( 'success' === $cf['status'] ) : ?>

        <div class="cf-success" role="status">
          <span class="cf-success-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
          </span>
          <h2>Đã nhận yêu cầu của bạn</h2>
          <p>Cảm ơn bạn đã liên hệ. Chuyên viên Saybencode sẽ gọi hoặc email lại trong vòng 24 giờ làm việc.</p>
          <p class="cf-success-note">Cần gấp? Gọi <a href="tel:<?php echo esc_attr( $office['hotline_1'][1] ); ?>"><?php echo esc_html( $office['hotline_1'][0] ); ?></a>.</p>
          <a class="btn btn-outline-dark" href="<?php echo esc_url( get_permalink() ); ?>#lien-he-form">Gửi yêu cầu khác</a>
        </div>

      <?php else : ?>

        <h2>Gửi yêu cầu tư vấn</h2>
        <p class="sub">Điền thông tin bên dưới, chúng tôi sẽ liên hệ lại sớm nhất.</p>

        <?php if ( $has_err ) : ?>
          <div class="cf-alert" role="alert" tabindex="-1" id="cf-alert">
            <?php if ( $cf['notice'] ) : ?>
              <?php echo esc_html( $cf['notice'] ); ?>
            <?php else : ?>
              Vui lòng kiểm tra lại <?php echo count( $errors ); ?> ô được đánh dấu bên dưới.
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <form id="cf-form" class="cf-form" action="<?php echo esc_url( get_permalink() ); ?>#lien-he-form" method="post" novalidate>
          <?php wp_nonce_field( 'contact_form_submit', 'contact_nonce' ); ?>
          <input type="hidden" name="saybencode_contact" value="1">

          <!-- Honeypot chống bot: người thật không thấy ô này -->
          <div class="cf-hp" aria-hidden="true">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="cf-row">
            <div class="cf-field<?php echo isset( $errors['ho_ten'] ) ? ' has-error' : ''; ?>">
              <label for="ho_ten">Họ và tên <abbr title="bắt buộc">*</abbr></label>
              <input id="ho_ten" name="ho_ten" type="text" placeholder="Nguyễn Văn A" autocomplete="name" required
                     value="<?php echo esc_attr( $values['ho_ten'] ); ?>"<?php echo saybencode_cf_aria( 'ho_ten', $errors ); ?>>
              <p class="cf-error" id="err-ho_ten"><?php echo isset( $errors['ho_ten'] ) ? esc_html( $errors['ho_ten'] ) : ''; ?></p>
            </div>

            <div class="cf-field<?php echo isset( $errors['sdt'] ) ? ' has-error' : ''; ?>">
              <label for="sdt">Số điện thoại <abbr title="bắt buộc">*</abbr></label>
              <input id="sdt" name="sdt" type="tel" placeholder="0906 123 456" autocomplete="tel" inputmode="tel" maxlength="16" required
                     value="<?php echo esc_attr( $values['sdt'] ); ?>"<?php echo saybencode_cf_aria( 'sdt', $errors ); ?>>
              <p class="cf-error" id="err-sdt"><?php echo isset( $errors['sdt'] ) ? esc_html( $errors['sdt'] ) : ''; ?></p>
            </div>
          </div>

          <div class="cf-field<?php echo isset( $errors['email'] ) ? ' has-error' : ''; ?>">
            <label for="email">Email <abbr title="bắt buộc">*</abbr></label>
            <input id="email" name="email" type="email" placeholder="ban@congty.com" autocomplete="email" required
                   value="<?php echo esc_attr( $values['email'] ); ?>"<?php echo saybencode_cf_aria( 'email', $errors ); ?>>
            <p class="cf-error" id="err-email"><?php echo isset( $errors['email'] ) ? esc_html( $errors['email'] ) : ''; ?></p>
          </div>

          <fieldset class="cf-choices">
            <legend>Bạn quan tâm dịch vụ nào? <span class="cf-opt">(không bắt buộc)</span></legend>
            <div class="cf-chips">
              <?php foreach ( saybencode_contact_services() as $key => $label ) : ?>
                <label class="cf-chip">
                  <input type="radio" name="dich_vu" value="<?php echo esc_attr( $key ); ?>" <?php checked( $values['dich_vu'], $key ); ?>>
                  <span><?php echo esc_html( $label ); ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <div class="cf-field<?php echo isset( $errors['noi_dung'] ) ? ' has-error' : ''; ?>">
            <label for="noi_dung">Nội dung yêu cầu <abbr title="bắt buộc">*</abbr></label>
            <textarea id="noi_dung" name="noi_dung" rows="5" placeholder="Mô tả ngắn gọn về dự án, mục tiêu và thời gian dự kiến..." required<?php echo saybencode_cf_aria( 'noi_dung', $errors ); ?>><?php echo esc_textarea( $values['noi_dung'] ); ?></textarea>
            <p class="cf-error" id="err-noi_dung"><?php echo isset( $errors['noi_dung'] ) ? esc_html( $errors['noi_dung'] ) : ''; ?></p>
          </div>

          <button class="btn btn-primary btn-block cf-submit" type="submit">
            <span class="cf-submit-label">Gửi yêu cầu tư vấn</span>
          </button>
          <p class="cf-privacy">Thông tin của bạn chỉ dùng để tư vấn dự án, không chia sẻ cho bên thứ ba.</p>
        </form>

      <?php endif; ?>
    </div>

    <!-- ============ THÔNG TIN VĂN PHÒNG ============ -->
    <aside class="info-card" aria-label="Thông tin liên hệ">
      <h2>Trụ sở văn phòng chính</h2>

      <div class="info-item">
        <span class="ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
        </span>
        <div>
          <div class="lbl">Địa chỉ</div>
          <div class="val"><a href="<?php echo esc_url( $maps_open ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $office['address_short'] ); ?></a></div>
        </div>
      </div>

      <div class="info-item">
        <span class="ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
        </span>
        <div>
          <div class="lbl">Hotline</div>
          <div class="val">
            <a href="tel:<?php echo esc_attr( $office['hotline_1'][1] ); ?>"><?php echo esc_html( $office['hotline_1'][0] ); ?></a>
            <?php if ( $office['hotline_2'] ) : ?>
              <span class="sep" aria-hidden="true">/</span>
              <a href="tel:<?php echo esc_attr( $office['hotline_2'][1] ); ?>"><?php echo esc_html( $office['hotline_2'][0] ); ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="info-item">
        <span class="ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/></svg>
        </span>
        <div>
          <div class="lbl">Email</div>
          <div class="val"><a href="mailto:<?php echo esc_attr( $office['email'] ); ?>"><?php echo esc_html( $office['email'] ); ?></a></div>
        </div>
      </div>

      <div class="info-item">
        <span class="ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        </span>
        <div>
          <div class="lbl">Giờ làm việc</div>
          <div class="val"><?php echo esc_html( $office['hours'] ); ?></div>
        </div>
      </div>

      <p class="info-note"><strong>Phản hồi trong 24 giờ làm việc.</strong> Cần gấp, hãy gọi hotline để được hỗ trợ ngay.</p>

      <?php $socials = saybencode_socials(); if ( $socials ) : ?>
      <div class="info-social">
        <?php foreach ( $socials as $so ) : ?>
          <a href="<?php echo esc_url( $so[2] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $so[0] ); ?>"><?php echo esc_html( $so[1] ); ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </aside>

    <!-- ============ BẢN ĐỒ ============ -->
    <div class="map-block">
      <div class="map-wrap">
        <!-- Lớp dự phòng: hiện khi bản đồ không tải được (bị chặn, mạng chậm, lỗi Google) -->
        <div class="map-fallback">
          <strong>Văn phòng Saybencode</strong>
          <span><?php echo esc_html( $office['address_full'] ); ?></span>
          <a class="btn btn-primary btn-sm" href="<?php echo esc_url( $maps_open ); ?>" target="_blank" rel="noopener noreferrer">Mở trong Google Maps</a>
        </div>
        <iframe
          src="<?php echo esc_url( $maps_embed ); ?>"
          loading="lazy"
          allowfullscreen
          referrerpolicy="no-referrer-when-downgrade"
          title="Bản đồ vị trí văn phòng Saybencode"></iframe>
      </div>
    </div>

  </div>
</section>

<?php get_footer(); ?>
