<?php
/**
 * Tự tạo 2 trang pháp lý mẫu (Chính sách bảo mật, Điều khoản sử dụng) một lần duy nhất.
 * Chỉ tạo khi CHƯA có trang cùng slug, nên không ghi đè trang bạn đã viết.
 * LƯU Ý: đây là nội dung mẫu, bạn nên nhờ người có chuyên môn pháp lý rà soát trước khi chạy thật.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function saybencode_legal_pages() {
    $brand = saybencode_brand();
    $mail  = saybencode_info( 'email' );
    $tel   = saybencode_info( 'hotline_1' );
    $addr  = saybencode_info( 'address_full' );

    $privacy = "<p>Chính sách này giải thích cách {$brand} thu thập, sử dụng và bảo vệ thông tin cá nhân của bạn khi truy cập website và gửi yêu cầu tư vấn.</p>
<h2>1. Thông tin chúng tôi thu thập</h2>
<p>Khi bạn điền biểu mẫu liên hệ hoặc đăng ký nhận tin, chúng tôi thu thập: họ và tên, số điện thoại, email, dịch vụ quan tâm và nội dung bạn gửi. Website cũng có thể ghi nhận dữ liệu kỹ thuật cơ bản như loại trình duyệt và địa chỉ IP phục vụ bảo mật.</p>
<h2>2. Mục đích sử dụng</h2>
<ul><li>Liên hệ lại, tư vấn và báo giá theo yêu cầu của bạn.</li><li>Gửi bản tin, bài viết mới nếu bạn đã đăng ký nhận tin.</li><li>Cải thiện nội dung và trải nghiệm website.</li></ul>
<h2>3. Chia sẻ thông tin</h2>
<p>Chúng tôi không bán hay cho thuê thông tin cá nhân của bạn. Thông tin chỉ được chia sẻ cho đơn vị cung cấp dịch vụ kỹ thuật (hosting, gửi email) khi cần thiết để vận hành website, hoặc khi pháp luật yêu cầu.</p>
<h2>4. Thời gian lưu trữ và bảo mật</h2>
<p>Thông tin được lưu trong thời gian cần thiết cho mục đích nêu trên. Chúng tôi áp dụng các biện pháp kỹ thuật hợp lý để bảo vệ dữ liệu, tuy nhiên không có hệ thống nào an toàn tuyệt đối.</p>
<h2>5. Quyền của bạn</h2>
<p>Bạn có quyền yêu cầu truy cập, chỉnh sửa, xoá thông tin cá nhân hoặc rút lại sự đồng ý nhận tin bất kỳ lúc nào, theo quy định về bảo vệ dữ liệu cá nhân tại Việt Nam (Nghị định 13/2023/NĐ-CP).</p>
<h2>6. Liên hệ</h2>
<p>Mọi yêu cầu liên quan đến dữ liệu cá nhân, vui lòng liên hệ: Email <a href=\"mailto:{$mail}\">{$mail}</a> · Hotline {$tel} · Địa chỉ: {$addr}.</p>";

    $terms = "<p>Khi truy cập và sử dụng website của {$brand}, bạn đồng ý với các điều khoản dưới đây. Nếu không đồng ý, vui lòng ngừng sử dụng website.</p>
<h2>1. Nội dung website</h2>
<p>Nội dung trên website (bài viết, hình ảnh, thông tin dịch vụ) mang tính tham khảo chung và có thể được cập nhật mà không cần báo trước. Chúng tôi nỗ lực đảm bảo thông tin chính xác nhưng không cam kết tuyệt đối về tính đầy đủ hay phù hợp với từng trường hợp cụ thể.</p>
<h2>2. Quyền sở hữu trí tuệ</h2>
<p>Toàn bộ nội dung, logo, thiết kế thuộc quyền sở hữu của {$brand} hoặc bên cấp phép. Bạn không được sao chép, phân phối hoặc sử dụng cho mục đích thương mại khi chưa có sự đồng ý bằng văn bản.</p>
<h2>3. Sử dụng hợp lệ</h2>
<p>Bạn cam kết không sử dụng website vào mục đích vi phạm pháp luật, gửi thông tin sai sự thật, phát tán mã độc hoặc làm gián đoạn hoạt động của website.</p>
<h2>4. Yêu cầu tư vấn và báo giá</h2>
<p>Việc gửi biểu mẫu liên hệ không cấu thành hợp đồng. Phạm vi, chi phí và điều kiện hợp tác chỉ có hiệu lực khi hai bên thống nhất bằng văn bản hoặc hợp đồng.</p>
<h2>5. Giới hạn trách nhiệm</h2>
<p>{$brand} không chịu trách nhiệm cho các thiệt hại phát sinh từ việc sử dụng hoặc không thể sử dụng website, hoặc từ nội dung của các website bên thứ ba được liên kết, trong phạm vi pháp luật cho phép.</p>
<h2>6. Thay đổi điều khoản</h2>
<p>Chúng tôi có thể cập nhật điều khoản này theo thời gian. Phiên bản mới có hiệu lực khi được đăng trên website.</p>
<h2>7. Liên hệ</h2>
<p>Email <a href=\"mailto:{$mail}\">{$mail}</a> · Hotline {$tel}.</p>";

    return array(
        'chinh-sach-bao-mat' => array( 'Chính sách bảo mật', $privacy ),
        'dieu-khoan-su-dung' => array( 'Điều khoản sử dụng', $terms ),
    );
}

function saybencode_create_legal_pages() {
    if ( get_option( 'saybencode_legal_pages_v1' ) ) return;
    foreach ( saybencode_legal_pages() as $slug => $page ) {
        if ( get_page_by_path( $slug ) ) continue; // đã có -> giữ nguyên
        wp_insert_post( array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $page[0],
            'post_name'    => $slug,
            'post_content' => $page[1],
        ) );
    }
    update_option( 'saybencode_legal_pages_v1', 1, false );
}
add_action( 'after_switch_theme', 'saybencode_create_legal_pages' );
add_action( 'admin_init', function () {
    if ( current_user_can( 'publish_pages' ) ) saybencode_create_legal_pages();
} );
