<?php
/**
 * USM Theme - functions.php
 * Funcțiile principale ale temei
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* -----------------------------------------------
   1. SETUP TEMĂ
----------------------------------------------- */
function usm_theme_setup() {
    // Suport pentru traduceri
    load_theme_textdomain( 'usm-theme', get_template_directory() . '/languages' );

    // Suport pentru titlul documentului gestionat de WP
    add_theme_support( 'title-tag' );

    // Suport pentru imagini recomandate (featured images)
    add_theme_support( 'post-thumbnails' );

    // Dimensiuni imagini personalizate
    add_image_size( 'usm-card',   800, 400, true );
    add_image_size( 'usm-hero',  1200, 600, true );

    // Suport pentru HTML5 în diverse elemente
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ) );

    // Feed-uri automate
    add_theme_support( 'automatic-feed-links' );

    // Suport logo personalizat
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Înregistrare meniu de navigare
    register_nav_menus( array(
        'primary' => esc_html__( 'Meniu Principal', 'usm-theme' ),
        'footer'  => esc_html__( 'Meniu Footer',    'usm-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'usm_theme_setup' );


/* -----------------------------------------------
   2. ÎNCĂRCARE STILURI ȘI SCRIPTURI
----------------------------------------------- */
function usm_theme_scripts() {
    // Stilul principal al temei
    wp_enqueue_style(
        'usm-theme-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );

    // Font Google (opțional)
    wp_enqueue_style(
        'usm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Source+Sans+3:wght@400;600&display=swap',
        array(),
        null
    );

    // Script navigare mobilă
    wp_enqueue_script(
        'usm-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        '1.0.0',
        true
    );

    // Suport comentarii threaded
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'usm_theme_scripts' );


/* -----------------------------------------------
   3. WIDGET AREAS (SIDEBAR)
----------------------------------------------- */
function usm_theme_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar Principal', 'usm-theme' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Adaugă widget-uri în sidebar.', 'usm-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'usm-theme' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'usm-theme' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'usm_theme_widgets_init' );


/* -----------------------------------------------
   4. LUNGIMEA EXCERPTULUI
----------------------------------------------- */
function usm_custom_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'usm_custom_excerpt_length', 999 );

function usm_custom_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'usm_custom_excerpt_more' );


/* -----------------------------------------------
   5. CLASA BODY PENTRU PAGINI
----------------------------------------------- */
function usm_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'singular';
    }
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }
    return $classes;
}
add_filter( 'body_class', 'usm_body_classes' );


/* -----------------------------------------------
   6. HELPER: META POSTARE
----------------------------------------------- */
function usm_post_meta() {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() )
    );

    echo '<span class="posted-on">' . $time_string . '</span>';
    echo '<span class="byline"> · ' . esc_html__( 'de', 'usm-theme' ) . ' ';
    echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">';
    echo esc_html( get_the_author() ) . '</a></span>';

    $categories = get_the_category();
    if ( $categories ) {
        echo '<span class="cat-links"> · ';
        echo get_the_category_list( ', ' );
        echo '</span>';
    }
}
