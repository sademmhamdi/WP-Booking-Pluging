<?php
class Simple_Booking_Activator {

    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            booking_date DATE NOT NULL,
            name VARCHAR(190) NOT NULL,
            email VARCHAR(190) NOT NULL,
            notes TEXT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
?>
