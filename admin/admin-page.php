<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Add admin menu
add_action( 'admin_menu', 'sb_register_admin_page' );
function sb_register_admin_page() {
    add_menu_page(
        'Service Bookings',
        'Service Bookings',
        'manage_options',
        'sb-service-bookings',
        'sb_render_admin_page',
        'dashicons-calendar-alt',
        26
    );
    add_action( 'admin_enqueue_scripts', 'sb_enqueue_admin_assets' );
}

function sb_enqueue_admin_assets($hook) {
    if ( $hook !== 'toplevel_page_sb-service-bookings' ) return;
    wp_enqueue_style( 'sb-admin-css', SB_PLUGIN_URL . 'admin/admin.css', array(), SB_VERSION );
}

function sb_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Access denied' );
    }

    global $wpdb;
    $table = $wpdb->prefix . 'service_bookings';

    $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;

    $total = $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
    $entries = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset ) );

    echo '<div class="wrap"><h1>Service Bookings</h1>';
    echo '<table class="wp-list-table widefat fixed striped"><thead><tr>';
    echo '<th>ID</th><th>Customer</th><th>Email</th><th>Phone</th><th>Service</th><th>City</th><th>Service Date</th><th>Submitted</th>';
    echo '</tr></thead><tbody>';
    if ( $entries ) {
        foreach ( $entries as $row ) {
            printf(
                '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                esc_html($row->id),
                esc_html($row->name),
                esc_html($row->email),
                esc_html($row->phone),
                esc_html($row->service_type),
                esc_html($row->city),
                esc_html($row->service_date),
                esc_html($row->created_at)
            );
        }
    } else {
        echo '<tr><td colspan="8">No bookings found.</td></tr>';
    }
    echo '</tbody></table>';

    // Pagination
    $total_pages = ceil( $total / $per_page );
    if ( $total_pages > 1 ) {
        $base = add_query_arg( 'paged', '%#%' );
        echo '<div class="tablenav"><div class="tablenav-pages">';
        echo paginate_links( array(
            'base' => $base,
            'format' => '',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'total' => $total_pages,
            'current' => $page
        ) );
        echo '</div></div>';
    }

    echo '</div>';
}
