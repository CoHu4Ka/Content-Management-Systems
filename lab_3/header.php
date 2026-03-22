<?php
/**
 * header.php – Antetul temei USM Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-wrapper">

    <header id="masthead" class="site-header">
        <div class="header-inner">

            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </p>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) : ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div><!-- .site-branding -->

            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                &#9776; Meniu
            </button>

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Navigare principală', 'usm-theme' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => 'usm_fallback_menu',
                ) );
                ?>
            </nav><!-- #site-navigation -->

        </div><!-- .header-inner -->
    </header><!-- #masthead -->

<?php
/**
 * Meniu implicit dacă nu există meniu configurat
 */
function usm_fallback_menu() {
    echo '<ul id="primary-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Acasă', 'usm-theme' ) . '</a></li>';
    wp_list_pages( array( 'title_li' => '', 'echo' => true ) );
    echo '</ul>';
}
