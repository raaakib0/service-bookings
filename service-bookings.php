<?php

/**
 * Plugin Name: Service Bookings
 * Description: Simple multi-step service booking plugin (custom table + admin list + frontend form).
 * Version: 1.0.0
 * Author: Rakib
 * Text Domain: service-bookings
 */

if (! defined('ABSPATH')) {
    exit;
}

define('SB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SB_VERSION', '1.0.0');

// Includes
require_once SB_PLUGIN_DIR . 'includes/activation.php';
require_once SB_PLUGIN_DIR . 'includes/handlers.php';
require_once SB_PLUGIN_DIR . 'admin/admin-page.php';

// Activation hook (creates table)
register_activation_hook(__FILE__, 'sb_activate_plugin');

// Enqueue scripts & styles (frontend)
add_action('wp_enqueue_scripts', 'sb_enqueue_frontend_assets');
function sb_enqueue_frontend_assets()
{
    wp_enqueue_style('sb-frontend-css', SB_PLUGIN_URL . 'assets/css/frontend.css', array(), SB_VERSION);
    wp_enqueue_script('sb-frontend-js', SB_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), SB_VERSION, true);

    wp_localize_script('sb-frontend-js', 'sb_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('sb_submit_nonce'),
    ));
}

// Shortcode to display form
add_shortcode('service_booking_form', 'sb_render_booking_form');
function sb_render_booking_form()
{
    ob_start();
?>
    <div id="sb-booking-wrap">
        <form id="sb-booking-form" enctype="multipart/form-data">
            <div id="sb-message" style="display:none;"></div>
            <!-- Step 1 -->
            <div class="sb-step" data-step="1">
                <h3>Customer Information</h3>
                <label>Full Name<input type="text" name="name" required></label>
                <label>Email<input type="email" name="email" required></label>
                <label>Phone<input type="text" name="phone" required></label>
                <label>Preferred Contact Method
                    <select name="contact_method" required>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                    </select>
                </label>
            </div>

            <!-- Step 2 -->
            <div class="sb-step" data-step="2" style="display:none;">
                <h3>Service Details</h3>
                <label>Service Type
                    <select name="service_type" required>
                        <option value="display">Display Problem</option>
                        <option value="camera">Camera Problem</option>
                        <option value="network">Network Problem</option>
                    </select>
                </label>
                <label>Service Description
                    <textarea name="service_description" required></textarea>
                </label>
                <label class="custom-file-upload">
                    File Upload (Optional)
                    <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf">
                    <span id="file-name">No file chosen</span>
                </label>
                <label>Preferred Service Date
                    <input type="date" name="service_date" required>
                </label>
            </div>

            <!-- Step 3 -->
            <div class="sb-step" data-step="3" style="display:none;">
                <h3>Location Information</h3>
                <label>Country<input type="text" name="country" required></label>
                <label>City<input type="text" name="city" required></label>
                <label>Full Address<textarea name="address" required></textarea></label>
                <label>Additional Notes (optional)<textarea name="notes"></textarea></label>
            </div>

            <div class="sb-navigation">
                <button type="button" id="sb-prev" style="display:none;">Previous</button>
                <button type="button" id="sb-next">Next</button>
                <button type="submit" id="sb-submit" style="display:none;">Submit</button>
            </div>

        </form>
    </div>
<?php
    return ob_get_clean();
}
