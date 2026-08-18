<?php
/**
 * Markee Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Theme support
function markee_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Menu chính', 'markee' ),
    ) );
}
add_action( 'after_setup_theme', 'markee_theme_setup' );

// Enqueue styles + Google Font
function markee_enqueue_assets() {
    wp_enqueue_style( 'markee-google-font', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'markee-style', get_stylesheet_uri(), array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'markee_enqueue_assets' );

// Excerpt length for blog cards
function markee_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'markee_excerpt_length' );

// Register Blog categories used on Home (Marketing / Cong nghe & Media / Tin tuc tong hop)
function markee_register_default_categories() {
    $cats = array( 'Marketing', 'Công nghệ & Media', 'Tin tức tổng hợp' );
    foreach ( $cats as $cat ) {
        if ( ! term_exists( $cat, 'category' ) ) {
            wp_insert_term( $cat, 'category' );
        }
    }
}
add_action( 'after_switch_theme', 'markee_register_default_categories' );
