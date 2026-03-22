<?php
/**
 * page.php – Șablon pentru pagini statice WordPress
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="container">
            <div class="content-area">

                <div id="main-content">
                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <header class="page-header">
                                <h1 class="page-title"><?php the_title(); ?></h1>
                            </header>

                            <div class="page-content entry-content">
                                <?php
                                the_content();
                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pagini:', 'usm-theme' ),
                                    'after'  => '</div>',
                                ) );
                                ?>
                            </div><!-- .page-content -->

                        </article><!-- #post -->

                        <?php if ( comments_open() || get_comments_number() ) : ?>
                            <?php get_comments_template(); ?>
                        <?php endif; ?>

                    <?php endwhile; ?>
                </div><!-- #main-content -->

                <?php get_sidebar(); ?>

            </div><!-- .content-area -->
        </div><!-- .container -->
    </main><!-- #primary -->

<?php get_footer(); ?>
