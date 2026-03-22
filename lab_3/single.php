<?php
/**
 * single.php – Șablon pentru postare individuală
 */

get_header();
?>

    <main id="primary" class="site-main">
        <div class="container">
            <div class="content-area">

                <div id="main-content">
                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <header class="single-post-header">
                                <div class="post-meta"><?php usm_post_meta(); ?></div>
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                            </header>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-featured-image">
                                    <?php the_post_thumbnail( 'usm-hero' ); ?>
                                </div>
                            <?php endif; ?>

                            <div class="post-content entry-content">
                                <?php
                                the_content( sprintf(
                                    esc_html__( 'Continuă să citești %s', 'usm-theme' ),
                                    '<span class="screen-reader-text">' . get_the_title() . '</span>'
                                ) );

                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pagini:', 'usm-theme' ),
                                    'after'  => '</div>',
                                ) );
                                ?>
                            </div><!-- .post-content -->

                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) : ?>
                                <div class="post-tags">
                                    <?php foreach ( $tags as $tag ) : ?>
                                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-item">
                                            # <?php echo esc_html( $tag->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </article><!-- #post -->

                        <!-- Navigare postări anterioare / următoare -->
                        <nav class="post-navigation" aria-label="<?php esc_attr_e( 'Navigare postare', 'usm-theme' ); ?>">
                            <div class="nav-previous">
                                <?php $prev = get_previous_post(); if ( $prev ) : ?>
                                    <span class="nav-label">&larr; <?php esc_html_e( 'Postare anterioară', 'usm-theme' ); ?></span>
                                    <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><?php echo esc_html( get_the_title( $prev ) ); ?></a>
                                <?php endif; ?>
                            </div>
                            <div class="nav-next">
                                <?php $next = get_next_post(); if ( $next ) : ?>
                                    <span class="nav-label"><?php esc_html_e( 'Postare următoare', 'usm-theme' ); ?> &rarr;</span>
                                    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?></a>
                                <?php endif; ?>
                            </div>
                        </nav>

                        <?php get_comments_template(); ?>

                    <?php endwhile; ?>
                </div><!-- #main-content -->

                <?php get_sidebar(); ?>

            </div><!-- .content-area -->
        </div><!-- .container -->
    </main><!-- #primary -->

<?php get_footer(); ?>
