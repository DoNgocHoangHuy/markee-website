<?php
/**
 * Tự gán ảnh đại diện cho bài viết — KHÔNG cần tải/up gì thêm.
 *
 * Ảnh có sẵn trong theme: assets/img/blog/{marketing|cong-nghe|tin-tuc|chung}-{1..n}.jpg
 * (chọn theo chuyên mục của bài; một số bài có ảnh riêng theo tên bài, xem saybencode_blog_pick). Bấm nút ở  Bài viết -> Tất cả bài viết:
 *   - "Gán ảnh cho bài chưa có ảnh": chỉ bài đang trống ảnh.
 *   - "Thay ảnh cho TẤT CẢ bài": ghi đè ảnh đại diện cũ (dùng khi ảnh cũ đang là ảnh giả).
 * Ảnh được copy vào Thư viện phương tiện của WordPress.
 * Nếu đã khai báo SAYBENCODE_IMG_BASE (GitHub) thì ưu tiên lấy ảnh từ đó: <BASE>/blog/<chủ đề>-<số>.jpg
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SAYBENCODE_BLOG_IMG_BATCH    = 6; // mỗi lần xử lý tối đa 6 bài để không quá thời gian

/** Danh sách ID bài cần xử lý. $all = true: mọi bài (trừ bài đã xử lý trong đợt này). */
function saybencode_blog_img_targets( $all = false, $limit = -1 ) {
    $exclude = (array) get_transient( 'saybencode_img_failed' );
    if ( $all ) $exclude = array_merge( $exclude, (array) get_transient( 'saybencode_img_done' ) );

    $args = array(
        'post_type'      => 'post',
        'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
        'posts_per_page' => $limit,
        'fields'         => 'ids',
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'no_found_rows'  => true,
        'post__not_in'   => array_map( 'intval', $exclude ),
    );
    if ( ! $all ) {
        $args['meta_query'] = array( array( 'key' => '_thumbnail_id', 'compare' => 'NOT EXISTS' ) );
    }
    return get_posts( $args );
}

/** Nút trên màn hình Bài viết + kết quả lần chạy trước. */
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'upload_files' ) ) return;
    $screen = get_current_screen();
    if ( ! $screen || 'edit-post' !== $screen->id ) return;

    if ( isset( $_GET['sb_imgs_done'] ) ) {
        $ok   = (int) $_GET['sb_imgs_done'];
        $fail = isset( $_GET['sb_imgs_fail'] ) ? (int) $_GET['sb_imgs_fail'] : 0;
        printf( '<div class="notice notice-%s is-dismissible"><p>Đã gán ảnh cho <strong>%d</strong> bài.%s</p></div>',
            $fail ? 'warning' : 'success', $ok,
            $fail ? ' Không gán được ' . $fail . ' bài (lỗi tải/ghi ảnh).' : '' );
    }

    $empty = count( saybencode_blog_img_targets( false ) );
    $url1  = wp_nonce_url( admin_url( 'admin-post.php?action=saybencode_assign_images' ), 'saybencode_assign_images' );
    $url2  = wp_nonce_url( admin_url( 'admin-post.php?action=saybencode_assign_images&mode=all' ), 'saybencode_assign_images' );

    echo '<div class="notice notice-info"><p>';
    if ( $empty ) {
        printf( 'Có <strong>%d</strong> bài chưa có ảnh đại diện. <a class="button button-primary" href="%s">Gán ảnh cho bài chưa có ảnh</a> ', $empty, esc_url( $url1 ) );
    } else {
        echo 'Tất cả bài viết đã có ảnh đại diện. ';
    }
    printf( '<a class="button" href="%s" onclick="return confirm(\'Thay ảnh đại diện của TẤT CẢ bài viết bằng ảnh có sẵn của theme?\');">Thay ảnh cho TẤT CẢ bài</a>', esc_url( $url2 ) );
    echo '</p></div>';
} );


/** Chọn tên file ảnh cho 1 bài: ưu tiên theo tên bài, không khớp thì xoay vòng trong nhóm chuyên mục. */
function saybencode_blog_pick( $post_id ) {
    $dir  = get_stylesheet_directory() . '/assets/img/blog/';
    $hay  = get_post_field( 'post_name', $post_id ) . ' ' . sanitize_title( get_the_title( $post_id ) );
    $rules = array(
        '/bo-nhan-dien|nhan-dien-thuong-hieu/'  => 'special-nhan-dien.jpg',
        '/(^|[\s-])seo([\s-]|$)/'             => 'marketing-1.jpg',
        '/ai-tao-sinh|(^|[\s-])ai([\s-]|$)/'  => 'cong-nghe-1.jpg',
        '/social/'                              => 'marketing-2.jpg',
        '/facebook|google-ads/'                 => 'marketing-5.jpg',
        '/email/'                               => 'marketing-3.jpg',
        '/content/'                             => 'marketing-4.jpg',
    );
    foreach ( $rules as $re => $file ) {
        if ( preg_match( $re, $hay ) && file_exists( $dir . $file ) ) return $file;
    }
    $key   = saybencode_topic_key( $post_id );
    $files = glob( $dir . $key . '-*.jpg' );
    $count = $files ? count( $files ) : 0;
    if ( ! $count ) return $key . '-1.jpg';
    return $key . '-' . ( ( (int) $post_id % $count ) + 1 ) . '.jpg';
}

/** Đưa 1 ảnh vào Thư viện và trả về attachment ID (hoặc 0 nếu lỗi). */
function saybencode_import_blog_image( $name, $post_id, array &$cache ) {

    // Nguồn 1: GitHub (nếu có khai báo)
    if ( defined( 'SAYBENCODE_IMG_BASE' ) && SAYBENCODE_IMG_BASE ) {
        $url = trailingslashit( SAYBENCODE_IMG_BASE ) . 'blog/' . $name;
        if ( isset( $cache[ $url ] ) && get_post( $cache[ $url ] ) ) return (int) $cache[ $url ];
        $res = media_sideload_image( $url, $post_id, get_the_title( $post_id ), 'id' );
        if ( ! is_wp_error( $res ) ) { $cache[ $url ] = (int) $res; return (int) $res; }
        // GitHub lỗi -> thử ảnh có sẵn trong theme
    }

    // Nguồn 2: ảnh có sẵn trong theme
    $local = get_stylesheet_directory() . '/assets/img/blog/' . $name;
    $ckey  = 'theme:' . $name;
    if ( isset( $cache[ $ckey ] ) && get_post( $cache[ $ckey ] ) ) return (int) $cache[ $ckey ];
    if ( ! file_exists( $local ) ) return 0;

    $tmp = wp_tempnam( $name );
    if ( ! $tmp || ! copy( $local, $tmp ) ) return 0;
    $att = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), $post_id, get_the_title( $post_id ) );
    if ( is_wp_error( $att ) ) { @unlink( $tmp ); return 0; }
    $cache[ $ckey ] = (int) $att;
    return (int) $att;
}

add_action( 'admin_post_saybencode_assign_images', function () {
    if ( ! current_user_can( 'upload_files' ) ) wp_die( 'Không đủ quyền.' );
    check_admin_referer( 'saybencode_assign_images' );

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $all    = isset( $_GET['mode'] ) && 'all' === $_GET['mode'];
    $cache  = get_option( 'saybencode_blog_img_ids', array() );
    $failed = (array) get_transient( 'saybencode_img_failed' );
    $done   = (array) get_transient( 'saybencode_img_done' );
    $ok = 0; $fail = 0;

    $targets = saybencode_blog_img_targets( $all, SAYBENCODE_BLOG_IMG_BATCH );
    foreach ( $targets as $post_id ) {
        $att = saybencode_import_blog_image( saybencode_blog_pick( $post_id ), $post_id, $cache );
        if ( $att ) {
            set_post_thumbnail( $post_id, $att );
            $ok++;
            $done[] = $post_id;
        } else {
            $fail++;
            $failed[] = $post_id;
        }
    }
    update_option( 'saybencode_blog_img_ids', $cache, false );
    set_transient( 'saybencode_img_failed', array_values( array_unique( array_map( 'intval', $failed ) ) ), HOUR_IN_SECONDS );

    if ( $all ) {
        // Còn bài chưa xử lý trong đợt "thay tất cả" -> giữ danh sách đã làm để lần bấm sau tiếp tục
        $more = count( $targets ) >= SAYBENCODE_BLOG_IMG_BATCH;
        if ( $more ) set_transient( 'saybencode_img_done', array_values( array_unique( array_map( 'intval', $done ) ) ), HOUR_IN_SECONDS );
        else delete_transient( 'saybencode_img_done' );
    }

    wp_safe_redirect( add_query_arg( array( 'sb_imgs_done' => $ok, 'sb_imgs_fail' => $fail ), admin_url( 'edit.php' ) ) );
    exit;
} );
