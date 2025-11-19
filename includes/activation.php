<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function sb_activate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'service_bookings';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
      id BIGINT(20) NOT NULL AUTO_INCREMENT,
      name VARCHAR(191) NOT NULL,
      email VARCHAR(191) NOT NULL,
      phone VARCHAR(50) NOT NULL,
      contact_method VARCHAR(50) NOT NULL,
      service_type VARCHAR(100) NOT NULL,
      service_description TEXT,
      attachment VARCHAR(255) DEFAULT NULL,
      service_date DATE DEFAULT NULL,
      country VARCHAR(100),
      city VARCHAR(100),
      address TEXT,
      notes TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
