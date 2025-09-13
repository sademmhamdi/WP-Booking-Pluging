<?php
class Simple_Booking_Deactivator {

    public static function deactivate() {
        // Flush rewrite rules if needed
        flush_rewrite_rules();
    }
}
?>
