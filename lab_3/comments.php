<?php
/**
 * comments.php – Șablon pentru comentarii
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                esc_html( _n( '%s comentariu', '%s comentarii', $comment_count, 'usm-theme' ) ),
                '<span>' . number_format_i18n( $comment_count ) . '</span>'
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'usm_comment_template',
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php the_comments_pagination( array(
            'prev_text' => esc_html__( '&larr; Comentarii anterioare', 'usm-theme' ),
            'next_text' => esc_html__( 'Comentarii următoare &rarr;', 'usm-theme' ),
        ) ); ?>

    <?php endif; // have_comments() ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comentariile sunt închise.', 'usm-theme' ); ?></p>
    <?php endif; ?>

    <?php
    comment_form( array(
        'title_reply'          => esc_html__( 'Lasă un comentariu', 'usm-theme' ),
        'title_reply_to'       => esc_html__( 'Răspunde la %s', 'usm-theme' ),
        'cancel_reply_link'    => esc_html__( 'Anulează răspuns', 'usm-theme' ),
        'label_submit'         => esc_html__( 'Trimite comentariul', 'usm-theme' ),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s submit-btn">%4$s</button>',
        'class_form'           => 'comment-form',
        'comment_notes_before' => '',
        'fields'               => array(
            'author' => '<div class="form-row"><p class="comment-form-author"><label for="author">' . esc_html__( 'Nume *', 'usm-theme' ) . '</label><input id="author" name="author" type="text" size="30" maxlength="245" required></p>',
            'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email *', 'usm-theme' ) . '</label><input id="email" name="email" type="email" size="30" maxlength="100" required></p></div>',
            'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'usm-theme' ) . '</label><input id="url" name="url" type="url" size="30" maxlength="200"></p>',
        ),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comentariu *', 'usm-theme' ) . '</label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required></textarea></p>',
    ) );
    ?>

</div><!-- #comments -->

<?php
/**
 * Template callback pentru afișarea unui comentariu
 */
function usm_comment_template( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article class="comment">
            <div class="comment-avatar">
                <?php echo get_avatar( $comment, 48 ); ?>
            </div>
            <div class="comment-content">
                <div class="comment-author"><?php comment_author_link(); ?></div>
                <div class="comment-date">
                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <?php
                        printf(
                            esc_html__( '%1$s la %2$s', 'usm-theme' ),
                            get_comment_date(),
                            get_comment_time()
                        );
                        ?>
                    </a>
                    <?php edit_comment_link( esc_html__( 'Editează', 'usm-theme' ), '&nbsp;&middot;&nbsp;<span class="edit-link">', '</span>' ); ?>
                </div>

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e( 'Comentariul tău așteaptă moderare.', 'usm-theme' ); ?></p>
                <?php endif; ?>

                <div class="comment-body">
                    <?php comment_text(); ?>
                </div>

                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="reply">',
                    'after'     => '</div>',
                ) ) );
                ?>
            </div>
        </article>
    <?php
}
