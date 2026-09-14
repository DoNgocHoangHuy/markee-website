<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
      <span class="logo-mark">N</span> <?php bloginfo( 'name' ); ?>
    </a>
    <nav class="main-nav">
      <?php
      wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'items_wrap'     => '%3$s',
          'fallback_cb'    => function() {
              $home_class    = is_front_page() ? 'active' : '';
              $blog_class    = is_home() ? 'active' : '';
              $is_service    = is_page('dich-vu') || get_page_template_slug() === 'page-chi-tiet-dich-vu.php';
              echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="' . $home_class . '">Trang chủ</a>';
              echo '<a href="' . esc_url( home_url( '/dich-vu/' ) ) . '" class="' . ( $is_service ? 'active' : '' ) . '">Dịch vụ</a>';
              echo '<a href="' . esc_url( home_url( '/lien-he/' ) ) . '" class="' . ( is_page('lien-he') ? 'active' : '' ) . '">Liên hệ</a>';
              echo '<a href="' . esc_url( get_permalink( get_option('page_for_posts') ) ) . '" class="' . $blog_class . '">Blog</a>';
          },
      ) );
      ?>
    </nav>
    <div class="header-actions">
      <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-red btn-sm">Liên hệ ngay</a>
    </div>
  </div>
</header>
