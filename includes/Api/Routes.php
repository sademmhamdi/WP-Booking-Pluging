<?php
class Simple_Booking_Api_Routes {

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('simple-booking/v1', '/submit', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_submit'),
            'permission_callback' => '__return_true',
        ));
    }

    public function handle_submit($request) {
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new WP_Error('rest_forbidden', __('Security check failed.', 'simple-date-booking'), array('status' => 403));
        }
        $params = $request->get_json_params();
        $date = sanitize_text_field($params['booking_date'] ?? '');
        $name = sanitize_text_field($params['name'] ?? '');
        $email = sanitize_email($params['email'] ?? '');
        $notes = sanitize_textarea_field($params['notes'] ?? '');
        if (empty($date) || empty($name) || empty($email)) {
            return new WP_Error('rest_invalid_param', __('All fields are required.', 'simple-date-booking'), array('status' => 400));
        }
        if (!is_email($email)) {
            return new WP_Error('rest_invalid_param', __('Invalid email address.', 'simple-date-booking'), array('status' => 400));
        }
        if (strtotime($date) < strtotime('today')) {
            return new WP_Error('rest_invalid_param', __('Date must be in the future.', 'simple-date-booking'), array('status' => 400));
        }
        // Check for double booking
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $existing = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE booking_date = %s AND status = 'approved'", $date));
        if ($existing > 0) {
            return new WP_Error('rest_invalid_param', __('This date is already booked.', 'simple-date-booking'), array('status' => 400));
        }
        $inserted = $wpdb->insert($table_name, array(
            'booking_date' => $date,
            'name' => $name,
            'email' => $email,
            'notes' => $notes,
            'status' => 'pending',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ));
        if ($inserted) {
            $options = get_option('simple_booking_options');
            $message = $options['success_message'] ?? __('Your booking request has been submitted successfully.', 'simple-date-booking');
            // Send confirmation email
            $subject = __('Booking Request Received', 'simple-date-booking');
            $email_message = sprintf(__('Dear %s, your booking request for %s has been received and is pending approval.', 'simple-date-booking'), $name, $date);
            wp_mail($email, $subject, $email_message);
            return new WP_REST_Response(array('success' => true, 'message' => $message), 200);
        } else {
            return new WP_Error('rest_internal_error', __('Error submitting booking: ', 'simple-date-booking') . $wpdb->last_error, array('status' => 500));
        }
    }
}
?>
