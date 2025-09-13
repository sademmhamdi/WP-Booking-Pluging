<?php
class Simple_Booking_Admin_Menu {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_get_booking_details', array($this, 'get_booking_details'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Bookings Dashboard', 'simple-date-booking'),
            __('Bookings', 'simple-date-booking'),
            'manage_options',
            'simple-booking',
            array($this, 'dashboard_page'),
            'dashicons-calendar-alt',
            30
        );
        add_submenu_page(
            'simple-booking',
            __('Dashboard', 'simple-date-booking'),
            __('Dashboard', 'simple-date-booking'),
            'manage_options',
            'simple-booking',
            array($this, 'dashboard_page')
        );
        add_submenu_page(
            'simple-booking',
            __('List View', 'simple-date-booking'),
            __('List View', 'simple-date-booking'),
            'manage_options',
            'simple-booking-list',
            array($this, 'bookings_page')
        );
        add_submenu_page(
            'simple-booking',
            __('Kanban View', 'simple-date-booking'),
            __('Kanban View', 'simple-date-booking'),
            'manage_options',
            'simple-booking-kanban',
            array($this, 'kanban_page')
        );
        add_submenu_page(
            'simple-booking',
            __('Settings', 'simple-date-booking'),
            __('Settings', 'simple-date-booking'),
            'manage_options',
            'simple-booking-settings',
            array($this, 'settings_page')
        );
    }

    public function dashboard_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $pending = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'");
        $approved = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'approved'");
        $declined = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'declined'");
        ?>
        <div class="wrap">
            <h1><?php _e('Bookings Dashboard', 'simple-date-booking'); ?></h1>
            <div class="booking-stats" style="display: flex; gap: 20px; margin-bottom: 30px;">
                <a href="<?php echo admin_url('admin.php?page=simple-booking-list'); ?>" class="stat-card" style="flex: 1; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; text-decoration: none; display: block; transition: transform 0.3s ease;">
                    <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $total; ?></h3>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">Total Bookings</p>
                </a>
                <a href="<?php echo admin_url('admin.php?page=simple-booking-list&status=pending'); ?>" class="stat-card" style="flex: 1; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; text-decoration: none; display: block; transition: transform 0.3s ease;">
                    <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $pending; ?></h3>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">Pending</p>
                </a>
                <a href="<?php echo admin_url('admin.php?page=simple-booking-list&status=approved'); ?>" class="stat-card" style="flex: 1; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; text-decoration: none; display: block; transition: transform 0.3s ease;">
                    <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $approved; ?></h3>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">Approved</p>
                </a>
                <a href="<?php echo admin_url('admin.php?page=simple-booking-list&status=declined'); ?>" class="stat-card" style="flex: 1; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); text-align: center; text-decoration: none; display: block; transition: transform 0.3s ease;">
                    <h3 style="margin: 0; font-size: 36px; font-weight: bold;"><?php echo $declined; ?></h3>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">Declined</p>
                </a>
            </div>
            <style>
            .stat-card:hover { transform: translateY(-5px) !important; box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important; }
            </style>
        </div>
        <?php
    }

    public function bookings_page() {
        if (isset($_POST['action']) && in_array($_POST['action'], array('approve', 'decline', 'delete'))) {
            $this->handle_bulk_action($_POST['action'], $_POST['booking'] ?? array());
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Bookings List', 'simple-date-booking'); ?></h1>
            <style>
            body .wp-list-table { border-collapse: separate !important; border-spacing: 0 !important; background: #fff !important; border-radius: 8px !important; overflow: hidden !important; box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important; }
            body .wp-list-table th { background: linear-gradient(135deg, #007cba, #005a87) !important; color: white !important; padding: 15px !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; border: none !important; }
            body .wp-list-table td { padding: 15px !important; border-bottom: 1px solid #e9ecef !important; transition: background 0.3s ease !important; }
            body .wp-list-table tr:hover td { background: #f8f9fa !important; }
            body .wp-list-table .column-actions a { margin-right: 5px !important; padding: 5px 10px !important; text-decoration: none !important; border-radius: 4px !important; font-size: 12px !important; transition: all 0.3s ease !important; }
            body .wp-list-table .column-actions .approve { background: #28a745 !important; color: white !important; }
            body .wp-list-table .column-actions .approve:hover { background: #218838 !important; transform: scale(1.05) !important; }
            body .wp-list-table .column-actions .decline { background: #ffc107 !important; color: #212529 !important; }
            body .wp-list-table .column-actions .decline:hover { background: #e0a800 !important; transform: scale(1.05) !important; }
            body .wp-list-table .column-actions .delete { background: #dc3545 !important; color: white !important; }
            body .wp-list-table .column-actions .delete:hover { background: #c82333 !important; transform: scale(1.05) !important; }
            body .tablenav { margin: 20px 0 !important; }
            body .tablenav .button { background: #007cba !important; color: white !important; border: none !important; padding: 8px 16px !important; border-radius: 4px !important; transition: background 0.3s ease !important; }
            body .tablenav .button:hover { background: #005a87 !important; }
            </style>
            <?php
            $list_table = new Simple_Booking_Admin_ListTable();
            $list_table->prepare_items();
            $list_table->display();
            ?>
        </div>
        <?php
    }

    public function handle_bulk_action($action, $ids) {
        if (empty($ids)) {
            return;
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

    public function kanban_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $bookings = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);
        if (empty($bookings)) {
            $bookings = array(
                array(
                    'id' => 1,
                    'booking_date' => '2025-09-15',
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'status' => 'pending',
                    'created_at' => '2025-09-12 10:00:00',
                    'notes' => 'Test booking'
                )
            );
        }
        $grouped = array('pending' => array(), 'approved' => array(), 'declined' => array());
        foreach ($bookings as $booking) {
            $grouped[$booking['status']][] = $booking;
        }
        ?>
        <style>
        body .kanban-board { display: flex !important; gap: 20px !important; margin-top: 20px !important; padding: 20px !important; background: #f8f9fa !important; border-radius: 12px !important; }
        body .kanban-column { flex: 1 !important; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important; padding: 20px !important; border-radius: 12px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important; border: 1px solid #e9ecef !important; min-height: 400px !important; }
        body .kanban-column h3 { margin-top: 0 !important; margin-bottom: 15px !important; color: #495057 !important; font-size: 18px !important; font-weight: 600 !important; text-align: center !important; padding: 10px !important; background: linear-gradient(135deg, #007cba, #005a87) !important; color: white !important; border-radius: 8px !important; }
        body .kanban-cards { display: flex !important; flex-direction: column !important; gap: 15px !important; }
        body .kanban-card { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important; padding: 15px !important; border: 1px solid #dee2e6 !important; border-radius: 8px !important; cursor: pointer !important; transition: all 0.3s ease !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; position: relative !important; overflow: hidden !important; }
        body .kanban-card::before { content: '' !important; position: absolute !important; top: 0 !important; left: 0 !important; width: 4px !important; height: 100% !important; background: #007cba !important; }
        body .kanban-card:hover { transform: translateY(-5px) !important; box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important; border-color: #007cba !important; }
        body .kanban-card p { margin: 8px 0 !important; font-size: 14px !important; color: #495057 !important; }
        body .kanban-card p:first-child { font-weight: 600 !important; color: #212529 !important; font-size: 16px !important; }
        body .kanban-card .button { margin-right: 5px !important; padding: 5px 10px !important; font-size: 12px !important; border-radius: 4px !important; text-decoration: none !important; display: inline-block !important; transition: all 0.3s ease !important; }
        body .kanban-card .button-primary { background: #007cba !important; color: white !important; }
        body .kanban-card .button-primary:hover { background: #005a87 !important; transform: scale(1.05) !important; }
        body .kanban-card .button-secondary { background: #dc3545 !important; color: white !important; }
        body .kanban-card .button-secondary:hover { background: #c82333 !important; transform: scale(1.05) !important; }
        body .modal { position: fixed !important; z-index: 10000 !important; left: 0 !important; top: 0 !important; width: 100% !important; height: 100% !important; background-color: rgba(0,0,0,0.6) !important; backdrop-filter: blur(5px) !important; }
        body .modal-content { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important; margin: 10% auto !important; padding: 30px !important; border: 1px solid #dee2e6 !important; border-radius: 12px !important; width: 90% !important; max-width: 500px !important; box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important; animation: modalSlideIn 0.3s ease-out !important; }
        @keyframes modalSlideIn { from { opacity: 0 !important; transform: scale(0.9) translateY(-20px) !important; } to { opacity: 1 !important; transform: scale(1) translateY(0) !important; } }
        body .close { color: #6c757d !important; float: right !important; font-size: 28px !important; font-weight: bold !important; cursor: pointer !important; transition: color 0.3s ease !important; }
        body .close:hover { color: #495057 !important; }
        </style>
        <div class="wrap">
            <h1><?php _e('Bookings Kanban', 'simple-date-booking'); ?></h1>
            <div class="kanban-board">
                <?php foreach ($grouped as $status => $items): ?>
                    <div class="kanban-column">
                        <h3><?php echo ucfirst($status); ?> (<?php echo count($items); ?>)</h3>
                        <div class="kanban-cards">
                            <?php foreach ($items as $item): ?>
                                <div class="kanban-card" onclick="showDetails(<?php echo $item['id']; ?>)">
                                    <p><strong><?php echo esc_html($item['name']); ?></strong></p>
                                    <p><?php echo esc_html($item['booking_date']); ?></p>
                                    <p><?php echo esc_html($item['email']); ?></p>
                                    <?php if ($status == 'pending'): ?>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=simple_booking_approve&id=' . $item['id']), 'simple_booking_action'); ?>" class="button button-primary">Approve</a>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=simple_booking_decline&id=' . $item['id']), 'simple_booking_action'); ?>" class="button">Decline</a>
                                    <?php endif; ?>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=simple_booking_delete&id=' . $item['id']), 'simple_booking_action'); ?>" class="button button-secondary">Delete</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="booking-modal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div id="modal-body"></div>
                </div>
            </div>
        </div>
        <script>
        function showDetails(id) {
            // Fetch details via AJAX or use data
            // For simplicity, assume we have data
            // Here, use a simple alert or load from server
            fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_booking_details&id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-body').innerHTML = '<h2>Booking Details</h2><p>Name: ' + data.name + '</p><p>Email: ' + data.email + '</p><p>Date: ' + data.booking_date + '</p><p>Notes: ' + data.notes + '</p><p>Status: ' + data.status + '</p>';
                document.getElementById('booking-modal').style.display = 'block';
            });
        }
        function closeModal() {
            document.getElementById('booking-modal').style.display = 'none';
        }
        </script>
        <?php
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Simple Booking Settings', 'simple-date-booking'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('simple_booking_options_group');
                do_settings_sections('simple-booking-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('simple_booking_options_group', 'simple_booking_options');
        add_settings_section(
            'simple_booking_main_section',
            __('Main Settings', 'simple-date-booking'),
            null,
            'simple-booking-settings'
        );
        add_settings_field(
            'admin_email',
            __('Admin Email', 'simple-date-booking'),
            array($this, 'admin_email_callback'),
            'simple-booking-settings',
            'simple_booking_main_section'
        );
        add_settings_field(
            'success_message',
            __('Success Message', 'simple-date-booking'),
            array($this, 'success_message_callback'),
            'simple-booking-settings',
            'simple_booking_main_section'
        );
    }

    public function admin_email_callback() {
        $options = get_option('simple_booking_options');
        echo '<input type="email" name="simple_booking_options[admin_email]" value="' . esc_attr($options['admin_email'] ?? get_option('admin_email')) . '" />';
    }

    public function success_message_callback() {
        $options = get_option('simple_booking_options');
        echo '<textarea name="simple_booking_options[success_message]" rows="3" cols="50">' . esc_textarea($options['success_message'] ?? __('Your booking request has been submitted successfully.', 'simple-date-booking')) . '</textarea>';
    }

    public function get_booking_details() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $id = intval($_GET['id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $booking = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
        if ($booking) {
            wp_send_json($booking);
        } else {
            wp_send_json(array('error' => 'Booking not found'));
        }
    }
}
?>
