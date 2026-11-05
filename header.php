<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
      <span class="logo-mark">S</span> <?php echo esc_html( saybencode_brand() ); ?>
    </a>

    <?php
    // Menu cố định (khớp site thật). Không phụ thuộc menu đã lưu trong WP Admin,
    // nên menu cũ (vd: "Combo ưu đãi", "Hệ thống cơ sở") sẽ không còn chen vào.
    $nav = array(
        array( 'Trang chủ', home_url( '/' ),            is_front_page() ),
        array( 'Dịch vụ',   home_url( '/dich-vu/' ),    is_page( 'dich-vu' ) || is_singular( 'dich_vu' ) ),
        array( 'Liên hệ',   home_url( '/lien-he/' ),    is_page( 'lien-he' ) ),
        array( 'Blog',      saybencode_blog_url(),      ( is_home() && ! is_front_page() ) || is_singular( 'post' ) || is_category() ),
    );
    ?>
    <nav class="main-nav" id="main-nav" aria-label="Menu chính">
      <?php foreach ( $nav as $item ) : ?>
        <a href="<?php echo esc_url( $item[1] ); ?>"<?php echo $item[2] ? ' class="active" aria-current="page"' : ''; ?>><?php echo esc_html( $item[0] ); ?></a>
      <?php endforeach; ?>
      <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-primary btn-sm nav-cta">Liên hệ ngay</a>
    </nav>

    <div class="header-actions">
      <a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>" class="btn btn-primary btn-sm header-cta">Liên hệ ngay</a>
      <button class="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Mở menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
