<?php
class Simple_Booking_Admin_Actions {

    public function __construct() {
        add_action('admin_post_simple_booking_approve', array($this, 'handle_approve'));
        add_action('admin_post_simple_booking_decline', array($this, 'handle_decline'));
        add_action('admin_post_simple_booking_delete', array($this, 'handle_delete'));
        add_action('admin_post_simple_booking_bulk_actions', array($this, 'handle_bulk_actions'));
    }

    public function handle_approve() {
        $this->check_permissions();
        $id = intval($_GET['id']);
        $this->update_status($id, 'approved');
        $this->send_emails($id, 'approved');
        wp_redirect(admin_url('admin.php?page=simple-booking-list&message=approved'));
        exit;
    }

    public function handle_decline() {
        $this->check_permissions();
        $id = intval($_GET['id']);
        $this->update_status($id, 'declined');
        $this->send_emails($id, 'declined');
        wp_redirect(admin_url('admin.php?page=simple-booking-list&message=declined'));
        exit;
    }

    public function handle_delete() {
        $this->check_permissions();
        $id = intval($_GET['id']);
        $this->delete_booking($id);
        wp_redirect(admin_url('admin.php?page=simple-booking-list&message=deleted'));
        exit;
    }

    public function handle_bulk_actions() {
        $this->check_permissions();
        $action = $_POST['action'] ?? $_POST['action2'];
        $ids = $_POST['booking'] ?? array();
        if (empty($ids)) {
            wp_redirect(admin_url('admin.php?page=simple-booking-list'));
            exit;
        }
        foreach ($ids as $id) {
            $id = intval($id);
            if ($action == 'approve') {
                $this->update_status($id, 'approved');
                $this->send_emails($id, 'approved');
            } elseif ($action == 'decline') {
                $this->update_status($id, 'declined');
                $this->send_emails($id, 'declined');
            } elseif ($action == 'delete') {
                $this->delete_booking($id);
            }
        }
        wp_redirect(admin_url('admin.php?page=simple-booking-list&message=bulk_' . $action));
        exit;
    }

    private function check_permissions() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? $_GET['_wpnonce'], 'simple_booking_action')) {
            wp_die(__('Security check failed.'));
        }
    }

    private function update_status($id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $wpdb->update($table_name, array('status' => $status, 'updated_at' => current_time('mysql')), array('id' => $id));
    }

    private function delete_booking($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $wpdb->delete($table_name, array('id' => $id));
    }

    private function send_emails($id, $action) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $booking = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
        if (!$booking) return;
        $options = get_option('simple_booking_options');
        $admin_email = $options['admin_email'] ?? get_option('admin_email');
        $subject = __('Booking ' . ucfirst($action), 'simple-date-booking');
        $message = sprintf(__('Your booking for %s has been %s.', 'simple-date-booking'), $booking['booking_date'], $action);
        wp_mail($booking['email'], $subject, $message);
        wp_mail($admin_email, $subject, $message);
    }
}
?>
