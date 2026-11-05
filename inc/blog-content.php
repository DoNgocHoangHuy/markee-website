<?php
/**
 * Soạn sẵn nội dung chuẩn cho bài viết (dạng khối của trình soạn thảo WordPress).
 *
 * Nội dung được GHI THẲNG vào bài viết (post_content) -> mở bài ra là sửa được như bình thường,
 * không có gì bị "để cứng" trong theme. Mỗi lần ghi, WordPress tự lưu bản cũ vào "Bản sửa đổi"
 * nên có thể khôi phục.
 *
 * Dùng:  Bài viết -> Tất cả bài viết -> nút "Soạn nội dung chuẩn cho bài còn sơ sài".
 * Chỉ động vào bài còn ngắn / chưa có tiêu đề phụ. Bài đã soạn đẹp hoặc đã được nút này xử lý thì bỏ qua.
 * Đây là NỘI DUNG MẪU để trình bày — hãy chỉnh lại số liệu, câu chữ thật trước khi đăng chính thức.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---------- Dựng khối ---------- */
function saybencode_blk_p( $t )  { return "<!-- wp:paragraph -->\n<p>" . $t . "</p>\n<!-- /wp:paragraph -->\n\n"; }
function saybencode_blk_h( $t )  { return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $t . "</h2>\n<!-- /wp:heading -->\n\n"; }
function saybencode_blk_ul( $a ) {
    $li = '';
    foreach ( $a as $i ) $li .= "<!-- wp:list-item -->\n<li>" . $i . "</li>\n<!-- /wp:list-item -->\n";
    return "<!-- wp:list -->\n<ul class=\"wp-block-list\">\n" . $li . "</ul>\n<!-- /wp:list -->\n\n";
}
function saybencode_blk_q( $t )  { return "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>" . $t . "</p></blockquote>\n<!-- /wp:quote -->\n\n"; }

/* ---------- Mẫu nội dung theo chủ đề ---------- */
function saybencode_content_templates() {
    return array(
        'seo' => array(
            'lead' => 'Tìm kiếm vẫn là cửa ngõ đưa khách hàng đến với doanh nghiệp. Bài viết tóm lược những hướng đi đáng chú ý để xây dựng thứ hạng bền vững thay vì chạy theo mẹo ngắn hạn.',
            's' => array(
                array( 'Ưu tiên nội dung giải quyết đúng nhu cầu', 'Công cụ tìm kiếm ngày càng hiểu ý định của người dùng. Nội dung có chiều sâu, trả lời trọn vẹn câu hỏi và được cập nhật thường xuyên sẽ luôn có lợi thế hơn những bài viết nhồi từ khoá.' ),
                array( 'Nền tảng kỹ thuật vững chắc', 'Tốc độ tải trang, hiển thị tốt trên điện thoại và cấu trúc website rõ ràng là điều kiện cần để nội dung hay được nhìn thấy.', array( 'Tối ưu tốc độ và trải nghiệm trên thiết bị di động', 'Cấu trúc tiêu đề, liên kết nội bộ và dữ liệu có cấu trúc rõ ràng', 'Theo dõi định kỳ để phát hiện lỗi kỹ thuật sớm' ) ),
                array( 'Đo lường để tối ưu liên tục', 'SEO là hành trình dài hạn. Đặt chỉ số theo từng giai đoạn, xem lại hằng tháng và điều chỉnh dựa trên dữ liệu thực tế của website.' ),
            ),
            'q' => 'SEO hiệu quả là kết quả của sự kiên trì làm đúng, không phải một thủ thuật đơn lẻ.',
        ),
        'ai' => array(
            'lead' => 'Trí tuệ nhân tạo đang thay đổi cách doanh nghiệp làm nội dung, chăm sóc khách hàng và ra quyết định. Điều quan trọng là áp dụng đúng chỗ, đúng mức.',
            's' => array(
                array( 'AI hỗ trợ con người, không thay thế', 'AI giúp rút ngắn các công đoạn lặp lại như lên ý tưởng, phác thảo, tổng hợp dữ liệu. Phần định hướng, kiểm duyệt và giữ giọng điệu thương hiệu vẫn cần con người đảm nhận.' ),
                array( 'Bắt đầu từ những việc nhỏ, rõ lợi ích', 'Nên thử nghiệm ở quy mô nhỏ trước khi mở rộng.', array( 'Soạn nháp nội dung và kịch bản truyền thông', 'Phân tích phản hồi, đánh giá của khách hàng', 'Tự động hoá các báo cáo định kỳ' ) ),
                array( 'Quy trình và trách nhiệm rõ ràng', 'Cần quy định ai kiểm tra kết quả, dữ liệu nào được phép sử dụng và cách ghi nhận nguồn để đảm bảo chất lượng cũng như bảo mật.' ),
            ),
            'q' => 'Công nghệ chỉ tạo ra giá trị khi gắn với một bài toán kinh doanh cụ thể.',
        ),
        'social' => array(
            'lead' => 'Mạng xã hội là nơi thương hiệu trò chuyện trực tiếp với khách hàng. Một kênh hiệu quả cần giọng điệu nhất quán, nội dung phù hợp từng nền tảng và nhịp đăng đều đặn.',
            's' => array(
                array( 'Hiểu khán giả trên từng nền tảng', 'Mỗi kênh có thói quen xem và tương tác khác nhau. Điều chỉnh định dạng, độ dài và cách mở đầu để nội dung tự nhiên với từng nơi đăng.' ),
                array( 'Xây dựng trụ cột nội dung', 'Chia nội dung thành vài nhóm chính giúp đội ngũ dễ lên kế hoạch và khán giả dễ nhận ra thương hiệu.', array( 'Kiến thức và chia sẻ chuyên môn', 'Câu chuyện đằng sau sản phẩm, dịch vụ', 'Tương tác, hỏi đáp cùng cộng đồng' ) ),
                array( 'Lắng nghe và phản hồi', 'Trả lời bình luận, tin nhắn kịp thời và ghi nhận ý kiến để cải thiện nội dung ở những lần sau.' ),
            ),
            'q' => 'Thương hiệu được nhớ đến nhờ cách họ trò chuyện, không chỉ nhờ những gì họ đăng.',
        ),
        'ads' => array(
            'lead' => 'Quảng cáo trả phí giúp tiếp cận đúng người vào đúng thời điểm, nhưng ngân sách chỉ hiệu quả khi chiến dịch được thiết kế và theo dõi cẩn thận.',
            's' => array(
                array( 'Mục tiêu rõ ràng ngay từ đầu', 'Xác định chiến dịch nhằm tăng nhận biết, thu thập khách hàng tiềm năng hay thúc đẩy đơn hàng để chọn định dạng và chỉ số đo lường phù hợp.' ),
                array( 'Thông điệp và hình ảnh', 'Thử nghiệm nhiều phiên bản và giữ lại phiên bản được khán giả đón nhận.', array( 'Tiêu đề ngắn, nêu rõ lợi ích', 'Hình ảnh, video bám sát thông điệp', 'Lời kêu gọi hành động cụ thể' ) ),
                array( 'Theo dõi và tối ưu hằng tuần', 'Xem lại chi phí, tỷ lệ chuyển đổi và chất lượng khách hàng đem về, từ đó điều chỉnh đối tượng và ngân sách.' ),
            ),
            'q' => 'Quảng cáo tốt là quảng cáo được đo lường và cải thiện không ngừng.',
        ),
        'email' => array(
            'lead' => 'Email vẫn là kênh chủ động, chi phí hợp lý để giữ liên hệ với khách hàng. Chìa khoá nằm ở việc gửi đúng nội dung cho đúng nhóm người nhận.',
            's' => array(
                array( 'Phân nhóm danh sách', 'Chia khách hàng theo nhu cầu và hành vi để mỗi email mang lại cảm giác được cá nhân hoá thay vì thư hàng loạt.' ),
                array( 'Viết ngắn gọn, giá trị rõ ràng', 'Người đọc quyết định mở thư trong vài giây.', array( 'Tiêu đề cụ thể, trung thực', 'Một thông điệp chính cho mỗi email', 'Một nút kêu gọi hành động nổi bật' ) ),
                array( 'Đo lường và cải thiện', 'Theo dõi tỷ lệ mở, nhấp chuột và huỷ đăng ký để biết nội dung nào hiệu quả và điều chỉnh tần suất gửi.' ),
            ),
            'q' => 'Một email hay là email người nhận thấy hữu ích khi đọc nó.',
        ),
        'content' => array(
            'lead' => 'Nội dung chất lượng giúp thương hiệu tạo niềm tin dài hạn. Thay vì đăng nhiều, hãy đăng đúng và có hệ thống.',
            's' => array(
                array( 'Bắt đầu từ chân dung khách hàng', 'Hiểu họ đang băn khoăn điều gì, tìm kiếm ở đâu và cần thông tin gì để quyết định. Từ đó chọn chủ đề thực sự hữu ích.' ),
                array( 'Lập kế hoạch nội dung', 'Một lịch nội dung đơn giản giúp duy trì nhịp độ và đảm bảo các chủ đề cân đối.', array( 'Chủ đề chính theo từng tháng', 'Định dạng phù hợp: bài viết, video, hình ảnh', 'Người phụ trách và thời hạn rõ ràng' ) ),
                array( 'Tái sử dụng thông minh', 'Một bài viết chuyên sâu có thể chia thành nhiều bài ngắn, hình ảnh minh hoạ hoặc email, giúp tiết kiệm thời gian mà vẫn nhất quán.' ),
            ),
            'q' => 'Nội dung tốt giúp khách hàng hiểu mình trước khi họ cần đến mình.',
        ),
        'brand' => array(
            'lead' => 'Bộ nhận diện thương hiệu là cách doanh nghiệp xuất hiện nhất quán trước khách hàng, đối tác và đội ngũ của mình.',
            's' => array(
                array( 'Định hướng thương hiệu mới', 'Bộ nhận diện được xây dựng trên tinh thần kết nối giá trị thực, thể hiện qua hệ thống màu sắc, logo và ngôn ngữ hình ảnh hiện đại, nhất quán trên mọi điểm chạm.' ),
                array( 'Những thành phần chính', 'Hệ thống được chuẩn hoá để đội ngũ dễ áp dụng.', array( 'Logo và các phiên bản sử dụng', 'Bảng màu và phông chữ', 'Mẫu ấn phẩm, tài liệu và nội dung số' ) ),
                array( 'Áp dụng đồng bộ', 'Bộ nhận diện sẽ lần lượt được triển khai trên website, hồ sơ năng lực, ấn phẩm truyền thông và môi trường làm việc để mang lại trải nghiệm thống nhất cho khách hàng.' ),
            ),
            'q' => 'Nhất quán là cách đơn giản nhất để thương hiệu được tin tưởng.',
        ),
        'partner' => array(
            'lead' => 'Hợp tác tốt bắt đầu từ mục tiêu chung và sự bổ trợ về năng lực giữa hai bên. Thông tin dưới đây tóm tắt định hướng của chương trình hợp tác.',
            's' => array(
                array( 'Mục tiêu hợp tác', 'Hai bên cùng hướng đến việc nâng cao chất lượng dịch vụ và mang lại giá trị rõ ràng hơn cho khách hàng trong lĩnh vực marketing, công nghệ và truyền thông.' ),
                array( 'Các lĩnh vực phối hợp', 'Phạm vi hợp tác được xác định theo từng giai đoạn để đảm bảo hiệu quả.', array( 'Chia sẻ chuyên môn và kinh nghiệm triển khai', 'Phối hợp xây dựng giải pháp cho khách hàng', 'Tổ chức các hoạt động đào tạo, giao lưu' ) ),
                array( 'Kế hoạch tiếp theo', 'Các hoạt động cụ thể sẽ được thông báo trên website và các kênh chính thức của Saybencode trong thời gian tới.' ),
            ),
            'q' => 'Hợp tác bền vững được xây dựng bằng kết quả thực tế của từng dự án.',
        ),
        'recap' => array(
            'lead' => 'Nhìn lại một giai đoạn hoạt động giúp đội ngũ ghi nhận những gì đã làm được và xác định rõ ưu tiên cho thời gian tới.',
            's' => array(
                array( 'Những điểm nổi bật', 'Giai đoạn vừa qua ghi nhận nhiều hoạt động đáng chú ý trong triển khai dự án, phát triển đội ngũ và hoàn thiện quy trình làm việc.' ),
                array( 'Bài học rút ra', 'Mỗi dự án đều để lại kinh nghiệm cho các lần sau.', array( 'Lắng nghe khách hàng sớm và thường xuyên', 'Chuẩn hoá quy trình để làm việc hiệu quả hơn', 'Đầu tư vào năng lực và sự gắn kết của đội ngũ' ) ),
                array( 'Định hướng tiếp theo', 'Saybencode tiếp tục tập trung nâng cao chất lượng dịch vụ và mở rộng các giải pháp phù hợp với nhu cầu của khách hàng.' ),
            ),
            'q' => 'Mỗi giai đoạn là một bước chuẩn bị cho giai đoạn kế tiếp.',
        ),
        'event' => array(
            'lead' => 'Tham dự sự kiện ngành là cơ hội để cập nhật xu hướng, trao đổi cùng chuyên gia và mang những ý tưởng mới về áp dụng trong công việc.',
            's' => array(
                array( 'Nội dung đáng chú ý', 'Sự kiện xoay quanh các chủ đề về marketing số, dữ liệu và trải nghiệm khách hàng, với nhiều chia sẻ thực tiễn từ các đơn vị trong ngành.' ),
                array( 'Điều đội ngũ mang về', 'Những ý tưởng có thể áp dụng ngay vào hoạt động hằng ngày.', array( 'Cập nhật xu hướng và công cụ mới', 'Kết nối với cộng đồng làm nghề', 'Gợi ý cải tiến cho các dự án đang triển khai' ) ),
                array( 'Chia sẻ trong nội bộ', 'Sau sự kiện, đội ngũ sẽ tổng hợp và chia sẻ lại để cả công ty cùng tiếp cận kiến thức mới.' ),
            ),
            'q' => 'Học hỏi liên tục là cách nhanh nhất để giữ vững chất lượng dịch vụ.',
        ),
        'digital' => array(
            'lead' => 'Chuyển đổi số không chỉ là mua công nghệ mà là thay đổi cách doanh nghiệp vận hành để phục vụ khách hàng tốt hơn.',
            's' => array(
                array( 'Bắt đầu từ vấn đề thực tế', 'Xác định công đoạn nào đang tốn nhiều thời gian hoặc dễ sai sót nhất để ưu tiên số hoá trước, thay vì làm dàn trải.' ),
                array( 'Các bước triển khai gợi ý', 'Lộ trình rõ ràng giúp doanh nghiệp tiến từng bước chắc chắn.', array( 'Đánh giá hiện trạng quy trình và dữ liệu', 'Chọn giải pháp phù hợp quy mô và ngân sách', 'Đào tạo đội ngũ và đo lường kết quả' ) ),
                array( 'Đồng hành lâu dài', 'Saybencode cùng doanh nghiệp xây dựng giải pháp, theo dõi vận hành và điều chỉnh trong suốt quá trình áp dụng.' ),
            ),
            'q' => 'Chuyển đổi số thành công khi con người và công nghệ cùng tiến bước.',
        ),
        'marketing' => array(
            'lead' => 'Một chiến lược marketing hiệu quả bắt đầu từ việc hiểu khách hàng và đặt mục tiêu có thể đo lường được.',
            's' => array(
                array( 'Xác định mục tiêu và đối tượng', 'Làm rõ doanh nghiệp muốn đạt điều gì và ai là người cần được tiếp cận để chọn kênh, thông điệp phù hợp.' ),
                array( 'Chọn kênh và thông điệp', 'Tập trung vào vài kênh hiệu quả thay vì dàn trải.', array( 'Thông điệp nhất quán giữa các kênh', 'Nội dung có giá trị cho khách hàng', 'Lộ trình triển khai rõ ràng' ) ),
                array( 'Đo lường và điều chỉnh', 'Theo dõi số liệu định kỳ để biết điều gì đang hiệu quả và cải thiện ở chu kỳ tiếp theo.' ),
            ),
            'q' => 'Marketing tốt là hiểu khách hàng đủ sâu để nói điều họ cần nghe.',
        ),
        'tech' => array(
            'lead' => 'Công nghệ và truyền thông số mở ra nhiều cách mới để doanh nghiệp tiếp cận và phục vụ khách hàng. Quan trọng là chọn giải pháp phù hợp với nhu cầu thực tế.',
            's' => array(
                array( 'Xu hướng đáng chú ý', 'Các công cụ mới giúp rút ngắn thời gian triển khai, nhưng hiệu quả vẫn phụ thuộc vào cách tổ chức và sử dụng.' ),
                array( 'Áp dụng thực tế', 'Nên thử nghiệm nhỏ để đánh giá trước khi mở rộng.', array( 'Xác định bài toán cần giải quyết', 'Chọn công cụ phù hợp ngân sách', 'Đo lường kết quả sau thử nghiệm' ) ),
                array( 'Chuẩn bị cho giai đoạn tiếp theo', 'Đầu tư vào kỹ năng của đội ngũ và dữ liệu sạch là nền tảng để tận dụng công nghệ lâu dài.' ),
            ),
            'q' => 'Công nghệ tốt là công nghệ mà người dùng gần như không cần để ý đến.',
        ),
        'news' => array(
            'lead' => 'Cập nhật những thông tin mới từ Saybencode: hoạt động, dự án và các mốc đáng chú ý của công ty.',
            's' => array(
                array( 'Thông tin chính', 'Saybencode tiếp tục triển khai các hoạt động nhằm nâng cao chất lượng dịch vụ và đồng hành cùng khách hàng trong lĩnh vực marketing, công nghệ và truyền thông.' ),
                array( 'Điểm nhấn', 'Một số nội dung đáng chú ý trong thời gian gần đây.', array( 'Triển khai dự án mới cùng khách hàng', 'Phát triển năng lực và đội ngũ', 'Hoàn thiện quy trình làm việc' ) ),
                array( 'Theo dõi thêm', 'Thông tin chi tiết sẽ tiếp tục được cập nhật trên website và các kênh chính thức của Saybencode.' ),
            ),
            'q' => 'Mỗi bước tiến nhỏ đều đến từ sự cố gắng chung của cả đội ngũ.',
        ),
    );
}

/** Chọn mẫu theo tên bài (ưu tiên) rồi đến chuyên mục. */
function saybencode_content_pick( $post_id ) {
    $hay = get_post_field( 'post_name', $post_id ) . ' ' . sanitize_title( get_the_title( $post_id ) );
    $rules = array(
        '/nhan-dien/'                          => 'brand',
        '/hop-tac|ky-ket|doi-tac/'             => 'partner',
        '/tong-ket|quy-\d|nhin-lai/'           => 'recap',
        '/hoi-thao|summit|su-kien/'            => 'event',
        '/chuyen-doi-so/'                      => 'digital',
        '/(^|[\s-])seo([\s-]|$)/'              => 'seo',
        '/ai-tao-sinh|(^|[\s-])ai([\s-]|$)/'   => 'ai',
        '/social|mang-xa-hoi/'                 => 'social',
        '/facebook|google-ads|quang-cao/'      => 'ads',
        '/email/'                              => 'email',
        '/content|noi-dung/'                   => 'content',
    );
    foreach ( $rules as $re => $key ) {
        if ( preg_match( $re, $hay ) ) return $key;
    }
    $topic = saybencode_topic_key( $post_id );
    return array( 'marketing' => 'marketing', 'cong-nghe' => 'tech', 'tin-tuc' => 'news' )[ $topic ] ?? 'news';
}

/** Dựng nội dung đầy đủ cho 1 bài. */
function saybencode_build_content( $post_id ) {
    $tpls = saybencode_content_templates();
    $t    = $tpls[ saybencode_content_pick( $post_id ) ];
    $out  = saybencode_blk_p( $t['lead'] );
    foreach ( $t['s'] as $s ) {
        $out .= saybencode_blk_h( $s[0] ) . saybencode_blk_p( $s[1] );
        if ( ! empty( $s[2] ) ) $out .= saybencode_blk_ul( $s[2] );
    }
    $out .= saybencode_blk_q( $t['q'] );
    $out .= saybencode_blk_h( 'Liên hệ với Saybencode' );
    $out .= saybencode_blk_p( 'Nếu bạn muốn trao đổi thêm về nội dung này hoặc cần tư vấn giải pháp phù hợp cho doanh nghiệp, hãy liên hệ đội ngũ Saybencode qua trang Liên hệ.' );
    return $out;
}

/** Bài cần soạn: còn ngắn / chưa có tiêu đề phụ, và chưa được xử lý. */
function saybencode_content_targets() {
    $ids = get_posts( array(
        'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'future', 'private' ),
        'posts_per_page' => -1, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC', 'no_found_rows' => true,
        'meta_query' => array( array( 'key' => '_sb_std_content', 'compare' => 'NOT EXISTS' ) ),
    ) );
    $out = array();
    foreach ( $ids as $id ) {
        $c    = (string) get_post_field( 'post_content', $id );
        $text = wp_strip_all_tags( $c );
        $has_heads = preg_match( '/<h[23][\s>]/i', $c ) || false !== strpos( $c, 'wp:heading' );
        if ( $has_heads && mb_strlen( $text ) > 500 ) continue; // đã soạn đẹp
        $out[] = $id;
    }
    return $out;
}

add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'edit_others_posts' ) ) return;
    $screen = get_current_screen();
    if ( ! $screen || 'edit-post' !== $screen->id ) return;

    if ( isset( $_GET['sb_content_done'] ) ) {
        printf( '<div class="notice notice-success is-dismissible"><p>Đã soạn nội dung chuẩn cho <strong>%d</strong> bài. Mở từng bài để chỉnh lại theo ý bạn.</p></div>', (int) $_GET['sb_content_done'] );
    }
    $n = count( saybencode_content_targets() );
    if ( ! $n ) return;
    $url = wp_nonce_url( admin_url( 'admin-post.php?action=saybencode_fill_content' ), 'saybencode_fill_content' );
    printf( '<div class="notice notice-info"><p>Có <strong>%d</strong> bài nội dung còn sơ sài. <a class="button button-primary" href="%s" onclick="return confirm(\'Ghi nội dung mẫu vào %d bài này? Nội dung cũ của các bài đó sẽ được lưu trong Bản sửa đổi.\');">Soạn nội dung chuẩn cho bài còn sơ sài</a> <span class="description">Nội dung ghi vào bài thật, sửa được bình thường.</span></p></div>', $n, esc_url( $url ), $n );
} );

add_action( 'admin_post_saybencode_fill_content', function () {
    if ( ! current_user_can( 'edit_others_posts' ) ) wp_die( 'Không đủ quyền.' );
    check_admin_referer( 'saybencode_fill_content' );
    $ok = 0;
    foreach ( saybencode_content_targets() as $id ) {
        $r = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => saybencode_build_content( $id ) ) ), true );
        if ( ! is_wp_error( $r ) && $r ) { update_post_meta( $id, '_sb_std_content', 1 ); $ok++; }
    }
    wp_safe_redirect( add_query_arg( 'sb_content_done', $ok, admin_url( 'edit.php' ) ) );
    exit;
} );
