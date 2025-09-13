<?php
class Simple_Booking_Frontend_Shortcode {

    public function __construct() {
        add_shortcode('simple_booking', array($this, 'render_form'));
        add_action('wp_ajax_nopriv_simple_booking_submit', array($this, 'handle_ajax_submit'));
        add_action('wp_ajax_simple_booking_submit', array($this, 'handle_ajax_submit'));
        add_action('admin_post_nopriv_simple_booking_submit', array($this, 'handle_post_submit'));
        add_action('admin_post_simple_booking_submit', array($this, 'handle_post_submit'));
    }

    public function render_form() {
        ob_start();
        ?>
        <div id="simple-booking-form-container">
            <h2><?php _e('Book Your Date', 'simple-date-booking'); ?></h2>
            <form id="simple-booking-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('simple_booking_submit', 'simple_booking_nonce'); ?>
                <input type="hidden" name="action" value="simple_booking_submit">
                <p>
                    <label for="booking_date"><?php _e('Select Date:', 'simple-date-booking'); ?></label>
                    <input type="date" id="booking_date" name="booking_date" required>
                </p>
                <p>
                    <label for="name"><?php _e('Name:', 'simple-date-booking'); ?></label>
                    <input type="text" id="name" name="name" required>
                </p>
                <p>
                    <label for="email"><?php _e('Email:', 'simple-date-booking'); ?></label>
                    <input type="email" id="email" name="email" required>
                </p>
                <p>
                    <label for="notes"><?php _e('Notes:', 'simple-date-booking'); ?></label>
                    <textarea id="notes" name="notes"></textarea>
                </p>
                <p>
                    <button type="submit"><?php _e('Submit Booking', 'simple-date-booking'); ?></button>
                </p>
            </form>
            <div id="simple-booking-message"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_ajax_submit() {
        $this->process_submission(true);
    }

    public function handle_post_submit() {
        $this->process_submission(false);
    }

    private function process_submission($is_ajax) {
        if (!wp_verify_nonce($_POST['simple_booking_nonce'], 'simple_booking_submit')) {
            $this->send_response(__('Security check failed.', 'simple-date-booking'), false, $is_ajax);
            return;
        }
        $date = sanitize_text_field($_POST['booking_date']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $notes = sanitize_textarea_field($_POST['notes']);
        if (empty($date) || empty($name) || empty($email)) {
            $this->send_response(__('All fields are required.', 'simple-date-booking'), false, $is_ajax);
            return;
        }
        if (!is_email($email)) {
            $this->send_response(__('Invalid email address.', 'simple-date-booking'), false, $is_ajax);
            return;
        }
        if (strtotime($date) < strtotime('today')) {
            $this->send_response(__('Date must be in the future.', 'simple-date-booking'), false, $is_ajax);
            return;
        }
        // Check for double booking
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $existing = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE booking_date = %s AND status = 'approved'", $date));
        if ($existing > 0) {
            $this->send_response(__('This date is already booked.', 'simple-date-booking'), false, $is_ajax);
            return;
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
            $this->send_response($message, true, $is_ajax);
        } else {
            $this->send_response(__('Error submitting booking: ', 'simple-date-booking') . $wpdb->last_error, false, $is_ajax);
        }
    }

    private function send_response($message, $success, $is_ajax) {
        if ($is_ajax) {
            wp_send_json(array('success' => $success, 'message' => $message));
        } else {
            wp_redirect(add_query_arg('message', urlencode($message), wp_get_referer()));
            exit;
        }
    }
}
?>
