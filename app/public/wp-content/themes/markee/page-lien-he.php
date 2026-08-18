<?php
/*
 * Template Name: Trang Liên Hệ
 */

// ==========================================================
// FORM STATE
// ==========================================================

$form_success = false;
$form_error   = '';


// ==========================================================
// HANDLE CONTACT FORM
// ==========================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['contact_form_submit'])
) {

    // ======================================================
    // VERIFY NONCE
    // ======================================================

    if (
        !isset($_POST['contact_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['contact_nonce'])
            ),
            'contact_form_submit'
        )
    ) {

        $form_error =
            'Phiên gửi biểu mẫu đã hết hạn. Vui lòng tải lại trang và thử lại.';

    } else {

        // ==================================================
        // GET FORM DATA
        // ==================================================

        $ho_ten = isset($_POST['ho_ten'])
            ? sanitize_text_field(
                wp_unslash($_POST['ho_ten'])
            )
            : '';

        $sdt = isset($_POST['sdt'])
            ? sanitize_text_field(
                wp_unslash($_POST['sdt'])
            )
            : '';

        $email = isset($_POST['email'])
            ? sanitize_email(
                wp_unslash($_POST['email'])
            )
            : '';

        $noi_dung = isset($_POST['noi_dung'])
            ? sanitize_textarea_field(
                wp_unslash($_POST['noi_dung'])
            )
            : '';


        // ==================================================
        // VALIDATION
        // ==================================================

        if ($ho_ten === '') {

            $form_error = 'Vui lòng nhập họ và tên.';

        } elseif ($sdt === '') {

            $form_error = 'Vui lòng nhập số điện thoại.';

        } elseif ($email === '') {

            $form_error = 'Vui lòng nhập email.';

        } elseif (!is_email($email)) {

            $form_error =
                'Email không hợp lệ. Vui lòng kiểm tra lại địa chỉ email.';

        } elseif ($noi_dung === '') {

            $form_error =
                'Vui lòng nhập nội dung yêu cầu.';

        } else {

            // ==================================================
            // NORMALIZE PHONE
            // ==================================================

            $normalized_phone = preg_replace(
                '/[\s\-.]+/',
                '',
                $sdt
            );


            // ==================================================
            // VALIDATE VIETNAM PHONE
            // ==================================================

            if (
                !preg_match(
                    '/^(0[0-9]{9}|\+84[0-9]{9})$/',
                    $normalized_phone
                )
            ) {

                $form_error =
                    'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam.';
            }
        }


        // ==================================================
        // SEND EMAIL
        // ==================================================

        if ($form_error === '') {

            // ==================================================
            // GET ADMIN EMAIL
            // ==================================================

            $recipient_email = get_option('admin_email');


            if (
                empty($recipient_email) ||
                !is_email($recipient_email)
            ) {

                $form_error =
                    'Email nhận thông báo của website chưa được cấu hình đúng.';

            } else {

                // ==================================================
                // SUBJECT
                // ==================================================

                $subject =
                    'Yêu cầu tư vấn mới từ website';


                // ==================================================
                // EMAIL MESSAGE
                // ==================================================

                $message = '';

                $message .= "CÓ YÊU CẦU TƯ VẤN MỚI\n\n";

                $message .= "====================================\n";
                $message .= "THÔNG TIN KHÁCH HÀNG\n";
                $message .= "====================================\n\n";

                $message .= "Họ và tên: ";
                $message .= $ho_ten;
                $message .= "\n";

                $message .= "Số điện thoại: ";
                $message .= $normalized_phone;
                $message .= "\n";

                $message .= "Email: ";
                $message .= $email;
                $message .= "\n\n";

                $message .= "====================================\n";
                $message .= "NỘI DUNG YÊU CẦU\n";
                $message .= "====================================\n\n";

                $message .= $noi_dung;
                $message .= "\n\n";

                $message .= "====================================\n";
                $message .= "THỜI GIAN\n";
                $message .= "====================================\n\n";

                $message .= current_time('d/m/Y H:i:s');
                $message .= "\n\n";

                $message .= "====================================\n";
                $message .= "WEBSITE\n";
                $message .= "====================================\n\n";

                $message .= home_url('/');
                $message .= "\n";


                // ==================================================
                // HEADERS
                // ==================================================

                $headers = array(
                    'Content-Type: text/plain; charset=UTF-8',
                    'Reply-To: ' . $ho_ten . ' <' . $email . '>',
                );


                // ==================================================
                // CAPTURE REAL WP MAIL ERROR
                // ==================================================

                $mail_error_message = '';


                $mail_failed_handler = function ($wp_error) use (
                    &$mail_error_message
                ) {

                    if ($wp_error instanceof WP_Error) {

                        $mail_error_message =
                            $wp_error->get_error_message();
                    }
                };


                add_action(
                    'wp_mail_failed',
                    $mail_failed_handler
                );


                // ==================================================
                // SEND MAIL
                // ==================================================

                $sent = wp_mail(
                    $recipient_email,
                    $subject,
                    $message,
                    $headers
                );


                // ==================================================
                // REMOVE ERROR HANDLER
                // ==================================================

                remove_action(
                    'wp_mail_failed',
                    $mail_failed_handler
                );


                // ==================================================
                // RESULT
                // ==================================================

                if ($sent) {

                    $form_success = true;

                    // Xóa dữ liệu POST sau khi gửi thành công.
                    $_POST = array();

                } else {

                    if ($mail_error_message !== '') {

                        $form_error =
                            'Gửi email thất bại: ' .
                            $mail_error_message;

                    } else {

                        $form_error =
                            'WordPress không thể gửi email. ' .
                            'Vui lòng kiểm tra cấu hình WP Mail SMTP.';
                    }
                }
            }
        }
    }
}


// ==========================================================
// HEADER
// ==========================================================

get_header();

?>



<!-- ==========================================================
     HERO
     ========================================================== -->

<section class="hero-dark">

  <div class="container page-hero">

    <div class="breadcrumb">

      Trang chủ

      <span>›</span>

      Liên hệ

    </div>


    <h1>
      Liên hệ với chúng tôi
    </h1>


    <p>
      Chia sẻ với Markee về dự án của bạn,
      đội ngũ chúng tôi sẽ phản hồi trong vòng
      24 giờ làm việc.
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



      <!-- ====================================================
           SUCCESS MESSAGE
           ==================================================== -->

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

          Cảm ơn bạn.

          Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.

        </div>

      <?php endif; ?>



      <!-- ====================================================
           ERROR MESSAGE
           ==================================================== -->

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



      <!-- ====================================================
           FORM
           ==================================================== -->

      <?php if (!$form_success) : ?>

        <form
          action="<?php echo esc_url(get_permalink()); ?>"
          method="post"
        >

          <?php
          /*
           * WordPress Nonce
           */
          wp_nonce_field(
              'contact_form_submit',
              'contact_nonce'
          );
          ?>


          <!-- Form marker -->

          <input
            type="hidden"
            name="contact_form_submit"
            value="1"
          >



          <!-- ================================================
               NAME + PHONE
               ================================================ -->

          <div class="form-row">


            <!-- NAME -->

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



            <!-- PHONE -->

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
                pattern="(0[0-9]{9}|\+84[0-9]{9})"
                maxlength="15"
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
              href="https://www.google.com/maps/search/?api=1&amp;query=T%E1%BA%A7ng%2012%2C%20T%C3%B2a%20nh%C3%A0%20Innovation%2C%2015%20Nguy%E1%BB%85n%20Hu%E1%BB%87%2C%20Qu%E1%BA%ADn%201%2C%20Th%C3%A0nh%20ph%E1%BB%91%20H%E1%BB%93%20Ch%C3%AD%20Minh"
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

            <a
              href="tel:19001234"
              style="color:inherit;text-decoration:none;"
            >
              1900 1234
            </a>

            ·

            <a
              href="tel:09061234567"
              style="color:inherit;text-decoration:none;"
            >
              0906 123 4567
            </a>

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

            <a
              href="mailto:contact@markee.vn"
              style="color:inherit;text-decoration:none;"
            >
              contact@markee.vn
            </a>

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

        <a href="#">f</a>

        <a href="#">in</a>

        <a href="#">ig</a>

        <a href="#">yt</a>

      </div>

    </div>



    <!-- ======================================================
         GOOGLE MAP
         ====================================================== -->

    <div style="grid-column:1/-1;">

      <div class="map-wrap">

        <iframe
          src="https://www.google.com/maps?q=T%E1%BA%A7ng%2012%2C%20T%C3%B2a%20nh%C3%A0%20Innovation%2C%2015%20Nguy%E1%BB%85n%20Hu%E1%BB%87%2C%20Qu%E1%BA%ADn%201%2C%20Th%C3%A0nh%20ph%E1%BB%91%20H%E1%BB%93%20Ch%C3%AD%20Minh&amp;output=embed"
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