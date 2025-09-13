<?php
class Simple_Booking_Uninstaller {

    public static function uninstall() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Also delete options if any
        delete_option('simple_booking_options');
    }
}
?>
