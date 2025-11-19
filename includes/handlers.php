<?php
if (! defined('ABSPATH')) exit;

add_action('wp_ajax_sb_submit_booking', 'sb_submit_booking');
add_action('wp_ajax_nopriv_sb_submit_booking', 'sb_submit_booking');

function sb_submit_booking()
{
    if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'sb_submit_nonce')) {
        wp_send_json_error('Security check failed', 403);
    }

    // Basic validation/sanitization
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $contact_method = isset($_POST['contact_method']) ? sanitize_text_field($_POST['contact_method']) : '';
    $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';
    $service_description = isset($_POST['service_description']) ? sanitize_textarea_field($_POST['service_description']) : '';
    $service_date = isset($_POST['service_date']) ? sanitize_text_field($_POST['service_date']) : null;
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
    $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || ! is_email($email)) {
        wp_send_json_error('Please provide a valid name and email.');
    }

    // Handle file upload if exists
    $attachment_url = null;
    if (! empty($_FILES['attachment']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $file = $_FILES['attachment'];
        // Allowed types
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (! in_array(strtolower($ext), $allowed, true)) {
            wp_send_json_error('Invalid file type. Allowed: jpg, jpeg, png, pdf.');
        }

        $upload = wp_handle_upload($file, array('test_form' => false));
        if (isset($upload['error'])) {
            wp_send_json_error('File upload error: ' . esc_html($upload['error']));
        }
        $attachment_url = esc_url_raw($upload['url']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'service_bookings';

    $data = array(
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'contact_method' => $contact_method,
        'service_type' => $service_type,
        'service_description' => $service_description,
        'attachment' => $attachment_url,
        'service_date' => $service_date,
        'country' => $country,
        'city' => $city,
        'address' => $address,
        'notes' => $notes,
        'created_at' => current_time('mysql')
    );

    $format = array_fill(0, count($data), '%s');

    $inserted = $wpdb->insert($table, $data, $format);

    if ($inserted) {
        wp_send_json_success('Booking submitted successfully.');
    } else {
        wp_send_json_error('Database error. Please try again.');
    }
}
