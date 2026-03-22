<?php
/**
 * sidebar.php – Bara laterală a temei USM Theme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'usm-theme' ); ?>">

    <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
        <!-- Widget implicit dacă nu există widget-uri configurate -->

        <section class="widget widget_search">
            <h2 class="widget-title"><?php esc_html_e( 'Căutare', 'usm-theme' ); ?></h2>
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="s"><?php esc_html_e( 'Caută:', 'usm-theme' ); ?></label>
                <input type="search" id="s" name="s" class="search-field"
                       placeholder="<?php esc_attr_e( 'Caută...', 'usm-theme' ); ?>"
                       value="<?php echo esc_attr( get_search_query() ); ?>">
                <button type="submit"><?php esc_html_e( '🔍', 'usm-theme' ); ?></button>
            </form>
        </section>

        <section class="widget widget_recent_posts">
            <h2 class="widget-title"><?php esc_html_e( 'Postări recente', 'usm-theme' ); ?></h2>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts( array(
                    'numberposts' => 5,
                    'post_status' => 'publish',
                ) );
                foreach ( $recent_posts as $post ) :
                ?>
                    <li>
                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                            <?php echo esc_html( $post['post_title'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="widget widget_categories">
            <h2 class="widget-title"><?php esc_html_e( 'Categorii', 'usm-theme' ); ?></h2>
            <ul>
                <?php wp_list_categories( array(
                    'show_count' => true,
                    'title_li'   => '',
                ) ); ?>
            </ul>
        </section>

        <section class="widget widget_archive">
            <h2 class="widget-title"><?php esc_html_e( 'Arhive', 'usm-theme' ); ?></h2>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </section>

    <?php endif; ?>

</aside><!-- #secondary -->
