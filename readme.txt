=== Service Bookings Plugin ===
Contributors: Md. Rakibul Islam
Tags: service booking, form, appointment, booking, custom form
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom WordPress plugin for a simple multi-step service booking system with a secure database, file upload, and admin dashboard.

== Description ==
This plugin provides a 3-step service booking form using a shortcode. All submitted data is securely stored in a custom database table. An admin interface allows WordPress administrators to view booking entries.

**Features**
- 3-step responsive booking form (Customer Info → Service Details → Location Info)
- Validation and secure input handling
- File upload using WordPress media handling
- Custom DB table created on activation (`wp_service_bookings`)
- Admin dashboard page to view all submissions
- Uses AJAX and nonce security

== Installation ==
1. Upload the ZIP file via:
   - WordPress Admin → Plugins → Add New → Upload Plugin
2. Activate the “Service Bookings” plugin
3. On activation, the plugin will automatically create the required database table

== Usage ==
Add the form (shortcode) to any page or post using this shortcode: [service_booking_form]

A new admin page will appear in WordPress Admin:

**Service Bookings** → View all submitted entries

== Security ==
- All data sanitized & validated
- Nonce check for AJAX requests
- File uploads handled via `wp_handle_upload`

== Support ==
For any support or development improvements, contact:
**Rakib**
Email: raaakib0@gmail.com

== Changelog ==
= 1.0 =
* Initial release
