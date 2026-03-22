<?php
/**
 * index.php – Șablonul principal (homepage / blog)
 */

get_header();
?>

    <?php if ( is_home() && is_front_page() ) : ?>
        <div class="hero-strip">
            <div class="container">
                <h1><?php bloginfo( 'name' ); ?></h1>
                <p><?php bloginfo( 'description' ); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <main id="primary" class="site-main">
        <div class="container">
            <div class="content-area">

                <div id="main-content">
                    <?php if ( have_posts() ) : ?>

                        <div class="posts-list">
                        <?php
                        // Bucla WordPress – afișează ultimele 5 postări
                        $args = array(
                            'posts_per_page' => 5,
                            'post_status'    => 'publish',
                        );
                        $query = new WP_Query( $args );

                        while ( $query->have_posts() ) :
                            $query->the_post();
                        ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>

                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'usm-card' ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="post-card-body">
                                    <div class="post-meta">
                                        <?php usm_post_meta(); ?>
                                    </div>

                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>

                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php esc_html_e( 'Citește mai mult', 'usm-theme' ); ?> &rarr;
                                    </a>
                                </div>

                            </article>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                        </div><!-- .posts-list -->

                        <?php the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class'     => 'pagination',
                        ) ); ?>

                    <?php else : ?>

                        <div class="no-results">
                            <div class="no-results-icon">📝</div>
                            <h2><?php esc_html_e( 'Nu există postări momentan.', 'usm-theme' ); ?></h2>
                            <p><?php esc_html_e( 'Reveniți curând sau adăugați prima postare din panoul de administrare.', 'usm-theme' ); ?></p>
                        </div>

                    <?php endif; ?>
                </div><!-- #main-content -->

                <?php get_sidebar(); ?>

            </div><!-- .content-area -->
        </div><!-- .container -->
    </main><!-- #primary -->

<?php get_footer(); ?>
