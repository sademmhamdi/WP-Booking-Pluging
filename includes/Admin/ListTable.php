<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Simple_Booking_Admin_ListTable extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => __('Booking', 'simple-date-booking'),
            'plural' => __('Bookings', 'simple-date-booking'),
            'ajax' => false
        ));
        $this->screen = get_current_screen();
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'booking_date' => __('Date', 'simple-date-booking'),
            'name' => __('Name', 'simple-date-booking'),
            'email' => __('Email', 'simple-date-booking'),
            'status' => __('Status', 'simple-date-booking'),
            'actions' => __('Actions', 'simple-date-booking'),
            'created_at' => __('Created', 'simple-date-booking'),
        );
    }

    public function get_sortable_columns() {
        return array(
            'booking_date' => array('booking_date', false),
            'created_at' => array('created_at', false),
        );
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'booking_date':
            case 'name':
            case 'email':
            case 'created_at':
                return esc_html($item[$column_name]);
            case 'status':
                return esc_html($item[$column_name]);
            case 'actions':
                return $this->column_actions($item);
            default:
                return print_r($item, true);
        }
    }

    public function column_actions($item) {
        $actions = array();
        if ($item['status'] == 'pending') {
            $actions['approve'] = sprintf('<a href="%s">%s</a>', wp_nonce_url(admin_url('admin-post.php?action=simple_booking_approve&id=' . $item['id']), 'simple_booking_action'), __('Approve', 'simple-date-booking'));
            $actions['decline'] = sprintf('<a href="%s">%s</a>', wp_nonce_url(admin_url('admin-post.php?action=simple_booking_decline&id=' . $item['id']), 'simple_booking_action'), __('Decline', 'simple-date-booking'));
        }
        $actions['delete'] = sprintf('<a href="%s">%s</a>', wp_nonce_url(admin_url('admin-post.php?action=simple_booking_delete&id=' . $item['id']), 'simple_booking_action'), __('Delete', 'simple-date-booking'));
        return $this->row_actions($actions);
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="booking[]" value="%s" />', $item['id']);
    }

    public function get_bulk_actions() {
        return array(
            'approve' => __('Approve', 'simple-date-booking'),
            'decline' => __('Decline', 'simple-date-booking'),
            'delete' => __('Delete', 'simple-date-booking'),
        );
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'simple_bookings';
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $orderby = !empty($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'created_at';
        $order = !empty($_GET['order']) && in_array($_GET['order'], array('asc', 'desc')) ? $_GET['order'] : 'desc';
        $search = !empty($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $status_filter = !empty($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

        $where = '';
        if ($search) {
            $where .= $wpdb->prepare(" AND (name LIKE %s OR email LIKE %s)", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%');
        }
        if ($status_filter) {
            $where .= $wpdb->prepare(" AND status = %s", $status_filter);
        }

        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE 1=1 $where");
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
        ));

        $offset = ($current_page - 1) * $per_page;
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE 1=1 $where ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);
        if (empty($this->items)) {
            $this->items = array(
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
    }

    public function extra_tablenav($which) {
        if ($which == 'top') {
            $status = isset($_GET['status']) ? $_GET['status'] : '';
            ?>
            <div class="alignleft actions">
                <select name="status">
                    <option value=""><?php _e('All statuses', 'simple-date-booking'); ?></option>
                    <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending', 'simple-date-booking'); ?></option>
                    <option value="approved" <?php selected($status, 'approved'); ?>><?php _e('Approved', 'simple-date-booking'); ?></option>
                    <option value="declined" <?php selected($status, 'declined'); ?>><?php _e('Declined', 'simple-date-booking'); ?></option>
                </select>
                <?php submit_button(__('Filter', 'simple-date-booking'), '', 'filter_action', false); ?>
            </div>
            <?php
        }
    }
}
?>
