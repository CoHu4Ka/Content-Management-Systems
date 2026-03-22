<?php
/**
 * footer.php – Subsolul temei USM Theme
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="container">

            <div class="footer-widgets">
                <!-- Coloana 1: Despre site -->
                <div class="footer-widget">
                    <h3><?php bloginfo( 'name' ); ?></h3>
                    <p><?php bloginfo( 'description' ); ?></p>
                    <p><?php esc_html_e( 'Temă WordPress creată ca proiect de laborator la Universitatea de Stat din Moldova.', 'usm-theme' ); ?></p>
                </div>

                <!-- Coloana 2: Widget area 1 -->
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3><?php esc_html_e( 'Navigare', 'usm-theme' ); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Acasă', 'usm-theme' ); ?></a></li>
                            <?php wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) ); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Coloana 3: Widget area 2 -->
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3><?php esc_html_e( 'Arhive', 'usm-theme' ); ?></h3>
                        <ul>
                            <?php wp_get_archives( array( 'type' => 'monthly', 'limit' => 6 ) ); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div><!-- .footer-widgets -->

            <div class="footer-bottom">
                <span>
                    &copy; <?php echo date( 'Y' ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                    <?php esc_html_e( 'Toate drepturile rezervate.', 'usm-theme' ); ?>
                </span>
                <span>
                    <?php esc_html_e( 'Creat cu', 'usm-theme' ); ?>
                    <a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a>
                    &amp; <strong>USM Theme</strong>.
                </span>
            </div><!-- .footer-bottom -->

        </div><!-- .container -->
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
