# Service Bookings Plugin

A custom WordPress plugin that provides a secure, responsive 3-step service booking form with database storage and an admin dashboard for managing entries.

---

## Features

- Multi-step (3 steps) booking form:
  - **Step 1:** Customer Information
  - **Step 2:** Service Details (with optional file upload)
  - **Step 3:** Location Information
- Fully AJAX-enabled submission
- Form validation (client + server-side)
- Secure file uploads using WordPress APIs
- Custom database table (`wp_service_bookings`) created on plugin activation
- Admin dashboard page to view all bookings
- Responsive UI & shortcode support: [service_booking_form]

## Plugin Structure

service-bookings/
├─ service-bookings.php # Main plugin file (shortcode + scripts)
├─ readme.txt # WordPress plugin how to install the plugin
├─ README.md # Documentation for developers
├─ includes/
│ ├─ activation.php # Database table creation on activation
│ ├─ handlers.php # AJAX request handlers & validations
│ └─ admin-page.php # Admin listing page for bookings
└─ assets/
├─ css/style.css # Form styles
└─ js/frontend.js # Multi-step form + AJAX logic

---

## Installation

1. Download the `service-bookings` ziped folder
2. Go to: **WordPress Admin → Plugins → Add New → Upload Plugin**
3. Upload the ZIP file and **Activate Plugin**
4. Database table will be auto-created

---

## Usage

Add the form (shortcode) to any page/post:

shortcode: [service_booking_form]

Admin can view submitted bookings from:

**Dashboard → Service Bookings**  
(Only visible to users with Admin capability)

---

## Security

✔ Sanitized + validated data  
✔ Nonce verification for AJAX  
✔ Secure database insert using `$wpdb`  
✔ Secure file uploads with `wp_handle_upload()`

---

## Notes

- Upload files stored inside WordPress uploads directory

---

## Developer

**Rakib**  
Email: raaakib0@gmail.com

---

If you like this project, feel free to improve or fork it!
