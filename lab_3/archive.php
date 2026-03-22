<?php
/**
 * archive.php – Șablon pentru arhive (categorii, etichete, autori, date)
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <header class="archive-header">
                    <?php
                    the_archive_title( '<h1 class="archive-title">', '</h1>' );
                    the_archive_description( '<div class="archive-description">', '</div>' );
                    ?>
                </header><!-- .archive-header -->

                <div class="content-area">
                    <div id="main-content">
                        <div class="posts-list">
                        <?php while ( have_posts() ) : the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>

                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'usm-card' ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="post-card-body">
                                    <div class="post-meta"><?php usm_post_meta(); ?></div>

                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>

                                    <div class="post-excerpt"><?php the_excerpt(); ?></div>

                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php esc_html_e( 'Citește mai mult', 'usm-theme' ); ?> &rarr;
                                    </a>
                                </div>

                            </article>

                        <?php endwhile; ?>
                        </div><!-- .posts-list -->

                        <?php the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'class'     => 'pagination',
                        ) ); ?>
                    </div><!-- #main-content -->

                    <?php get_sidebar(); ?>
                </div><!-- .content-area -->

            <?php else : ?>

                <div class="no-results">
                    <div class="no-results-icon">🗂️</div>
                    <h2><?php esc_html_e( 'Nu s-au găsit postări în această arhivă.', 'usm-theme' ); ?></h2>
                </div>

            <?php endif; ?>

        </div><!-- .container -->
    </main><!-- #primary -->

<?php get_footer(); ?>
