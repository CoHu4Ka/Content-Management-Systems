<?php
/**
 * Plugin Name: USM Notes
 * Plugin URI:  https://github.com/your-username/usm-notes
 * Description: Плагин для управления заметками с приоритетами и датой напоминания.
 * Version:     1.0.0
 * Author:      Student USM
 * License:     GPL-2.0+
 * Text Domain: usm-notes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'USM_NOTES_VERSION', '1.0.0' );
define( 'USM_NOTES_DIR', plugin_dir_path( __FILE__ ) );
define( 'USM_NOTES_URL', plugin_dir_url( __FILE__ ) );

// ─────────────────────────────────────────────
// 1. CUSTOM POST TYPE
// ─────────────────────────────────────────────
function usm_notes_register_cpt() {
    $labels = [
        'name'               => __( 'Notes', 'usm-notes' ),
        'singular_name'      => __( 'Note', 'usm-notes' ),
        'menu_name'          => __( 'USM Notes', 'usm-notes' ),
        'add_new'            => __( 'Add Note', 'usm-notes' ),
        'add_new_item'       => __( 'Add New Note', 'usm-notes' ),
        'edit_item'          => __( 'Edit Note', 'usm-notes' ),
        'view_item'          => __( 'View Note', 'usm-notes' ),
        'all_items'          => __( 'All Notes', 'usm-notes' ),
        'search_items'       => __( 'Search Notes', 'usm-notes' ),
        'not_found'          => __( 'No notes found.', 'usm-notes' ),
        'not_found_in_trash' => __( 'No notes found in trash.', 'usm-notes' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'usm-notes' ],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-sticky',
        'supports'           => [ 'title', 'editor', 'author', 'thumbnail' ],
    ];

    register_post_type( 'usm_note', $args );
}
add_action( 'init', 'usm_notes_register_cpt' );

// ─────────────────────────────────────────────
// 2. CUSTOM TAXONOMY — Priority
// ─────────────────────────────────────────────
function usm_notes_register_taxonomy() {
    $labels = [
        'name'              => __( 'Priorities', 'usm-notes' ),
        'singular_name'     => __( 'Priority', 'usm-notes' ),
        'search_items'      => __( 'Search Priorities', 'usm-notes' ),
        'all_items'         => __( 'All Priorities', 'usm-notes' ),
        'parent_item'       => __( 'Parent Priority', 'usm-notes' ),
        'parent_item_colon' => __( 'Parent Priority:', 'usm-notes' ),
        'edit_item'         => __( 'Edit Priority', 'usm-notes' ),
        'update_item'       => __( 'Update Priority', 'usm-notes' ),
        'add_new_item'      => __( 'Add New Priority', 'usm-notes' ),
        'new_item_name'     => __( 'New Priority Name', 'usm-notes' ),
        'menu_name'         => __( 'Priority', 'usm-notes' ),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'public'            => true,
        'rewrite'           => [ 'slug' => 'priority' ],
    ];

    register_taxonomy( 'usm_priority', [ 'usm_note' ], $args );
}
add_action( 'init', 'usm_notes_register_taxonomy' );

// ─────────────────────────────────────────────
// 3. METABOX — Due Date
// ─────────────────────────────────────────────
function usm_notes_add_metabox() {
    add_meta_box(
        'usm_notes_due_date',
        __( 'Due Date (Reminder)', 'usm-notes' ),
        'usm_notes_metabox_callback',
        'usm_note',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'usm_notes_add_metabox' );

function usm_notes_metabox_callback( $post ) {
    wp_nonce_field( 'usm_notes_save_due_date', 'usm_notes_nonce' );

    $due_date = get_post_meta( $post->ID, '_usm_due_date', true );
    $error    = get_transient( 'usm_notes_error_' . $post->ID );

    if ( $error ) {
        echo '<p style="color:red;font-weight:bold;">' . esc_html( $error ) . '</p>';
        delete_transient( 'usm_notes_error_' . $post->ID );
    }
    ?>
    <p>
        <label for="usm_due_date"><strong><?php esc_html_e( 'Due Date *', 'usm-notes' ); ?></strong></label><br>
        <input
            type="date"
            id="usm_due_date"
            name="usm_due_date"
            value="<?php echo esc_attr( $due_date ); ?>"
            required
            style="width:100%;margin-top:4px;"
        >
        <span style="font-size:11px;color:#888;"><?php esc_html_e( 'Date cannot be in the past.', 'usm-notes' ); ?></span>
    </p>
    <?php
}

function usm_notes_save_meta( $post_id ) {
    // Autosave / revision guard
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) )                 return;
    if ( get_post_type( $post_id ) !== 'usm_note' )        return;

    // Nonce verification
    if ( ! isset( $_POST['usm_notes_nonce'] ) ||
         ! wp_verify_nonce( $_POST['usm_notes_nonce'], 'usm_notes_save_due_date' ) ) {
        return;
    }

    // Permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Required field
    if ( empty( $_POST['usm_due_date'] ) ) {
        set_transient( 'usm_notes_error_' . $post_id, __( 'Error: Due Date is required.', 'usm-notes' ), 45 );
        return;
    }

    $date = sanitize_text_field( $_POST['usm_due_date'] );

    // Validate format
    $dt = DateTime::createFromFormat( 'Y-m-d', $date );
    if ( ! $dt ) {
        set_transient( 'usm_notes_error_' . $post_id, __( 'Error: Invalid date format.', 'usm-notes' ), 45 );
        return;
    }

    // Validate not in the past
    $today = new DateTime( 'today' );
    if ( $dt < $today ) {
        set_transient( 'usm_notes_error_' . $post_id, __( 'Error: Due Date cannot be in the past.', 'usm-notes' ), 45 );
        return;
    }

    update_post_meta( $post_id, '_usm_due_date', $date );
}
add_action( 'save_post', 'usm_notes_save_meta' );

// ─────────────────────────────────────────────
// 4. ADMIN COLUMN — Due Date in list
// ─────────────────────────────────────────────
function usm_notes_add_columns( $columns ) {
    $columns['usm_due_date'] = __( 'Due Date', 'usm-notes' );
    return $columns;
}
add_filter( 'manage_usm_note_posts_columns', 'usm_notes_add_columns' );

function usm_notes_render_column( $column, $post_id ) {
    if ( $column === 'usm_due_date' ) {
        $date = get_post_meta( $post_id, '_usm_due_date', true );
        echo $date ? esc_html( $date ) : '—';
    }
}
add_action( 'manage_usm_note_posts_custom_column', 'usm_notes_render_column', 10, 2 );

function usm_notes_sortable_columns( $columns ) {
    $columns['usm_due_date'] = 'usm_due_date';
    return $columns;
}
add_filter( 'manage_edit-usm_note_sortable_columns', 'usm_notes_sortable_columns' );

// ─────────────────────────────────────────────
// 5. SHORTCODE  [usm_notes priority="" before_date=""]
// ─────────────────────────────────────────────
function usm_notes_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'priority'    => '',
        'before_date' => '',
    ], $atts, 'usm_notes' );

    $args = [
        'post_type'      => 'usm_note',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value',
        'meta_key'       => '_usm_due_date',
        'order'          => 'ASC',
    ];

    // Filter by priority (taxonomy)
    if ( ! empty( $atts['priority'] ) ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'usm_priority',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $atts['priority'] ),
            ],
        ];
    }

    // Filter by before_date (meta)
    if ( ! empty( $atts['before_date'] ) ) {
        $args['meta_query'] = [
            [
                'key'     => '_usm_due_date',
                'value'   => sanitize_text_field( $atts['before_date'] ),
                'compare' => '<=',
                'type'    => 'DATE',
            ],
        ];
    }

    $query = new WP_Query( $args );

    ob_start();

    // Enqueue styles inline
    wp_enqueue_style( 'usm-notes-style' );

    if ( ! $query->have_posts() ) {
        echo '<p class="usm-notes-empty">' . esc_html__( 'Nu există notițe cu parametrii specificați', 'usm-notes' ) . '</p>';
    } else {
        echo '<div class="usm-notes-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $due_date   = get_post_meta( get_the_ID(), '_usm_due_date', true );
            $priorities = get_the_terms( get_the_ID(), 'usm_priority' );
            $priority   = ( $priorities && ! is_wp_error( $priorities ) ) ? $priorities[0]->name : '';
            $slug       = ( $priorities && ! is_wp_error( $priorities ) ) ? strtolower( $priorities[0]->slug ) : '';

            echo '<div class="usm-note usm-priority-' . esc_attr( $slug ) . '">';
            echo '<h3 class="usm-note-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';

            if ( $priority ) {
                echo '<span class="usm-note-badge usm-badge-' . esc_attr( $slug ) . '">' . esc_html( $priority ) . '</span>';
            }

            if ( $due_date ) {
                echo '<p class="usm-note-date">📅 ' . esc_html( $due_date ) . '</p>';
            }

            $excerpt = get_the_excerpt();
            if ( $excerpt ) {
                echo '<p class="usm-note-excerpt">' . esc_html( $excerpt ) . '</p>';
            }

            echo '</div>';
        }
        echo '</div>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'usm_notes', 'usm_notes_shortcode' );

// ─────────────────────────────────────────────
// 6. STYLES
// ─────────────────────────────────────────────
function usm_notes_enqueue_styles() {
    wp_register_style(
        'usm-notes-style',
        USM_NOTES_URL . 'assets/css/usm-notes.css',
        [],
        USM_NOTES_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'usm_notes_enqueue_styles' );

// ─────────────────────────────────────────────
// 7. ACTIVATION — flush rewrite rules
// ─────────────────────────────────────────────
function usm_notes_activate() {
    usm_notes_register_cpt();
    usm_notes_register_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'usm_notes_activate' );

function usm_notes_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'usm_notes_deactivate' );
