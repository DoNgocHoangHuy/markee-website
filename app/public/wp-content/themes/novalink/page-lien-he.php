<?php
/*
 * Template Name: Trang Liên Hệ
 */

get_header();


// ==========================================================
// CONTACT FORM PROCESSING
// ==========================================================

$form_success = false;
$form_error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['contact_form_submit'])) {

    // ------------------------------------------------------
    // SECURITY - VERIFY NONCE
    // ------------------------------------------------------

    if (
        !isset($_POST['contact_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['contact_nonce'])),
            'contact_form_submit'
        )
    ) {
        $form_error = 'Yêu cầu không hợp lệ. Vui lòng tải lại trang và thử lại.';
    } else {

        // --------------------------------------------------
        // GET FORM DATA
        // --------------------------------------------------

        $ho_ten = isset($_POST['ho_ten'])
            ? sanitize_text_field(wp_unslash($_POST['ho_ten']))
            : '';

        $sdt = isset($_POST['sdt'])
            ? sanitize_text_field(wp_unslash($_POST['sdt']))
            : '';

        $email = isset($_POST['email'])
            ? sanitize_email(wp_unslash($_POST['email']))
            : '';

        $noi_dung = isset($_POST['noi_dung'])
            ? sanitize_textarea_field(wp_unslash($_POST['noi_dung']))
            : '';


        // --------------------------------------------------
        // VALIDATION
        // --------------------------------------------------

        if ($ho_ten === '') {

            $form_error = 'Vui lòng nhập họ và tên.';

        } elseif ($sdt === '') {

            $form_error = 'Vui lòng nhập số điện thoại.';

        } elseif (!preg_match('/^(0|\+84)[0-9\s\-.]{8,14}$/', $sdt)) {

            $form_error = 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam thật.';

        } elseif ($email === '') {

            $form_error = 'Vui lòng nhập email.';

        } elseif (!is_email($email)) {

            $form_error = 'Địa chỉ email không hợp lệ.';

        } elseif ($noi_dung === '') {

            $form_error = 'Vui lòng nhập nội dung yêu cầu.';

        }


        // --------------------------------------------------
        // SEND EMAIL
        // --------------------------------------------------

        if ($form_error === '') {

            /*
             * Email nhận thông báo.
             *
             * Lấy từ:
             * WordPress Admin
             * → Settings
             * → General
             * → Administration Email Address
             */

            $recipient_email = get_option('admin_email');


            $subject = '🔔 Yêu cầu tư vấn mới từ website';


            $message = "
Có một yêu cầu tư vấn mới từ website.

----------------------------------------
THÔNG TIN KHÁCH HÀNG
----------------------------------------

Họ và tên:
{$ho_ten}

Số điện thoại:
{$sdt}

Email:
{$email}

----------------------------------------
NỘI DUNG YÊU CẦU
----------------------------------------

{$noi_dung}

----------------------------------------
THỜI GIAN
----------------------------------------

" . current_time('d/m/Y H:i:s') . "

----------------------------------------
Website:
" . home_url('/') . "
";


            /*
             * Reply-To để khi bạn bấm Reply trong Gmail,
             * email trả lời sẽ gửi trực tiếp cho khách.
             */

            $headers = array(
                'Content-Type: text/plain; charset=UTF-8',
                'Reply-To: ' . $ho_ten . ' <' . $email . '>',
            );


            $sent = wp_mail(
                $recipient_email,
                $subject,
                $message,
                $headers
            );


            if ($sent) {

                $form_success = true;

            } else {

                $form_error = 'Không thể gửi yêu cầu lúc này. Vui lòng thử lại sau hoặc liên hệ trực tiếp với chúng tôi.';

            }
        }
    }
}
?>



<!-- ==========================================================
     HERO
     ========================================================== -->

<section class="hero-dark">

  <div class="container page-hero">

    <div class="breadcrumb">
      Trang chủ <span>›</span> Liên hệ
    </div>

    <h1>
      Liên hệ với chúng tôi
    </h1>

    <p>
      Chia sẻ với Novalink về dự án của bạn, đội ngũ chúng tôi sẽ phản hồi
      trong vòng 24 giờ làm việc.
    </p>

  </div>

</section>



<!-- ==========================================================
     CONTACT SECTION
     ========================================================== -->

<section class="section">

  <div class="container contact-grid">


    <!-- ======================================================
         CONTACT FORM
         ====================================================== -->

    <div class="contact-card">

      <h3>
        Gửi yêu cầu tư vấn
      </h3>

      <p class="sub">
        Hãy bắt đầu dự án tiếp theo của bạn cùng chúng tôi.
      </p>


      <!-- SUCCESS -->

      <?php if ($form_success) : ?>

        <div
          style="
            padding:14px 16px;
            margin-bottom:20px;
            border-radius:10px;
            background:#eaf8ef;
            color:#167a3f;
            border:1px solid #b9e5c9;
          "
        >
          <strong>
            Gửi yêu cầu thành công!
          </strong>

          <br>

          Cảm ơn bạn. Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.
        </div>

      <?php endif; ?>


      <!-- ERROR -->

      <?php if ($form_error !== '') : ?>

        <div
          style="
            padding:14px 16px;
            margin-bottom:20px;
            border-radius:10px;
            background:#fff0f0;
            color:#c62828;
            border:1px solid #f2b8b8;
          "
        >
          <strong>
            Có lỗi:
          </strong>

          <?php echo esc_html($form_error); ?>

        </div>

      <?php endif; ?>



      <?php if (!$form_success) : ?>

        <form
          action="<?php echo esc_url(get_permalink()); ?>"
          method="post"
          novalidate
        >

          <?php
          /*
           * WordPress Nonce.
           * Bảo vệ form khỏi request giả mạo.
           */

          wp_nonce_field(
              'contact_form_submit',
              'contact_nonce'
          );
          ?>


          <input
            type="hidden"
            name="contact_form_submit"
            value="1"
          >


          <!-- ================================================
               NAME + PHONE
               ================================================ -->

          <div class="form-row">

            <div class="form-field">

              <label for="ho_ten">
                Họ và tên *
              </label>

              <input
                id="ho_ten"
                type="text"
                name="ho_ten"
                placeholder="Nguyễn Văn A"
                autocomplete="name"
                required
                value="<?php
                  echo isset($_POST['ho_ten'])
                    ? esc_attr(
                        sanitize_text_field(
                            wp_unslash($_POST['ho_ten'])
                        )
                      )
                    : '';
                ?>"
              >

            </div>


            <div class="form-field">

              <label for="sdt">
                Số điện thoại *
              </label>

              <input
                id="sdt"
                type="tel"
                name="sdt"
                placeholder="0901234567"
                autocomplete="tel"
                inputmode="tel"
                pattern="^(0|\+84)[0-9\s\-.]{8,14}$"
                required
                value="<?php
                  echo isset($_POST['sdt'])
                    ? esc_attr(
                        sanitize_text_field(
                            wp_unslash($_POST['sdt'])
                        )
                      )
                    : '';
                ?>"
              >

            </div>

          </div>



          <!-- ================================================
               EMAIL
               ================================================ -->

          <div class="form-row">

            <div class="form-field full">

              <label for="email">
                Email công việc *
              </label>

              <input
                id="email"
                type="email"
                name="email"
                placeholder="ban@gmail.com"
                autocomplete="email"
                required
                value="<?php
                  echo isset($_POST['email'])
                    ? esc_attr(
                        sanitize_email(
                            wp_unslash($_POST['email'])
                        )
                      )
                    : '';
                ?>"
              >

            </div>

          </div>



          <!-- ================================================
               MESSAGE
               ================================================ -->

          <div class="form-row">

            <div class="form-field full">

              <label for="noi_dung">
                Nội dung yêu cầu *
              </label>

              <textarea
                id="noi_dung"
                name="noi_dung"
                placeholder="Mô tả ngắn gọn về dự án của bạn..."
                required
              ><?php
                echo isset($_POST['noi_dung'])
                  ? esc_textarea(
                      sanitize_textarea_field(
                          wp_unslash($_POST['noi_dung'])
                      )
                    )
                  : '';
              ?></textarea>

            </div>

          </div>



          <!-- ================================================
               SUBMIT
               ================================================ -->

          <button
            class="btn btn-red btn-block"
            type="submit"
          >
            Gửi yêu cầu tư vấn
          </button>

        </form>

      <?php endif; ?>

    </div>



    <!-- ======================================================
         OFFICE INFORMATION
         ====================================================== -->

    <div class="info-card">

      <h3>
        Trụ sở văn phòng chính
      </h3>


      <!-- ADDRESS -->

      <div class="info-item">

        <span class="ic">
          📍
        </span>

        <div>

          <div class="lbl">
            Địa chỉ
          </div>

          <div class="val">

            <a
              href="https://www.google.com/maps/search/?api=1&query=Tầng%2012%2C%20Tòa%20nhà%20Innovation%2C%2015%20Nguyễn%20Huệ%2C%20Quận%201%2C%Thành%20phố%20Hồ%20Chí%20Minh"
              target="_blank"
              rel="noopener noreferrer"
              style="color:inherit;text-decoration:none;"
            >
              Tầng 12, Toà nhà Innovation,
              15 Nguyễn Huệ, Q.1, TP.HCM
            </a>

          </div>

        </div>

      </div>


      <!-- HOTLINE -->

      <div class="info-item">

        <span class="ic">
          📞
        </span>

        <div>

          <div class="lbl">
            Hotline
          </div>

          <div class="val">
            1900 1234 · 0906 123 4567
          </div>

        </div>

      </div>


      <!-- EMAIL -->

      <div class="info-item">

        <span class="ic">
          ✉️
        </span>

        <div>

          <div class="lbl">
            Email
          </div>

          <div class="val">
            contact@novalink.vn
          </div>

        </div>

      </div>


      <!-- WORKING HOURS -->

      <div class="info-item">

        <span class="ic">
          🕐
        </span>

        <div>

          <div class="lbl">
            Giờ làm việc
          </div>

          <div class="val">
            Thứ 2 - Thứ 7 · 8:30 - 18:00
          </div>

        </div>

      </div>


      <!-- SOCIAL -->

      <div class="info-social">

        <a href="#">
          f
        </a>

        <a href="#">
          in
        </a>

        <a href="#">
          ig
        </a>

        <a href="#">
          yt
        </a>

      </div>

    </div>



    <!-- ======================================================
         GOOGLE MAP
         ====================================================== -->

    <div style="grid-column:1/-1;">

      <div class="map-wrap">

        <iframe
          src="https://www.google.com/maps?q=Tầng%2012%2C%20Tòa%20nhà%20Innovation%2C%2015%20Nguyễn%20Huệ%2C%20Quận%201%2C%20Thành%20phố%20Hồ%20Chí%20Minh&output=embed"
          width="100%"
          height="400"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Bản đồ vị trí văn phòng"
        ></iframe>

      </div>

    </div>


  </div>

</section>



<?php get_footer(); ?>