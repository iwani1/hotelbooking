<?php
/**
 * Plugin Name: Bookora Reception
 * Description: Staff-only room and reservation management for reception.
 * Version: 1.1.0
 * Requires PHP: 8.0
 * License: GPL-2.0-or-later
 * Text Domain: bookora-reception
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Bookora_Reception {
    const CAPABILITY = 'manage_bookora_reception';
    const ROLE = 'bookora_receptionist';
    const PAGE = 'bookora-reception';
    const SCHEMA_VERSION = '1.1.0';

    public static function init() {
        add_action('plugins_loaded', array(__CLASS__, 'maybe_upgrade'));
        add_action('admin_menu', array(__CLASS__, 'menus'));
        add_action('admin_post_bookora_reception_add_room', array(__CLASS__, 'handle_add_room'));
        add_action('admin_post_bookora_reception_rate', array(__CLASS__, 'handle_rate'));
        add_action('admin_post_bookora_reception_book', array(__CLASS__, 'handle_booking'));
        add_action('admin_post_bookora_reception_status', array(__CLASS__, 'handle_status'));
    }

    private static function rooms_table() {
        global $wpdb;
        return $wpdb->prefix . 'bookora_rooms';
    }

    private static function bookings_table() {
        global $wpdb;
        return $wpdb->prefix . 'bookora_reservations';
    }

    public static function maybe_upgrade() {
        if (get_option('bookora_reception_schema_version') !== self::SCHEMA_VERSION) {
            self::activate(false);
        }
    }

    public static function activate($network_wide = false) {
        if ($network_wide) {
            wp_die('Bookora Reception must be activated separately on each site, not network-wide.');
        }
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();
        $rooms = self::rooms_table();
        $bookings = self::bookings_table();
        dbDelta("CREATE TABLE $rooms (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            room_number varchar(100) NOT NULL,
            room_type varchar(100) NOT NULL,
            capacity int(10) unsigned NOT NULL DEFAULT 1,
            nightly_rate_pesewas bigint(20) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY  (id),
            UNIQUE KEY room_number (room_number)
        ) ENGINE=InnoDB $charset;");
        dbDelta("CREATE TABLE $bookings (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            room_id bigint(20) unsigned NOT NULL,
            guest_name varchar(150) NOT NULL,
            guest_email varchar(190) NOT NULL DEFAULT '',
            guest_phone varchar(50) NOT NULL DEFAULT '',
            guests int(10) unsigned NOT NULL DEFAULT 1,
            check_in date NOT NULL,
            check_out date NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'confirmed',
            nightly_rate_pesewas bigint(20) unsigned DEFAULT NULL,
            total_pesewas bigint(20) unsigned DEFAULT NULL,
            created_by bigint(20) unsigned NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY availability (room_id,check_in,check_out),
            KEY status (status)
        ) ENGINE=InnoDB $charset;");
        foreach (array($rooms, $bookings) as $table) {
            $found = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table)));
            if ($found !== $table) {
                wp_die('Bookora Reception could not create its database tables. Check database permissions and try again.');
            }
        }
        foreach (array($rooms => array('nightly_rate_pesewas'), $bookings => array('nightly_rate_pesewas', 'total_pesewas')) as $table => $columns) {
            foreach ($columns as $column) {
                $found = $wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM $table LIKE %s", $wpdb->esc_like($column)));
                if ($found !== $column) {
                    wp_die('Bookora Reception could not update its database tables. Check database permissions and try again.');
                }
            }
        }
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap(self::CAPABILITY);
        }
        add_role(self::ROLE, 'Receptionist', array('read' => true, self::CAPABILITY => true));
        $receptionist = get_role(self::ROLE);
        if ($receptionist) {
            $receptionist->add_cap(self::CAPABILITY);
        }
        update_option('bookora_reception_schema_version', self::SCHEMA_VERSION, false);
    }

    public static function menus() {
        add_menu_page('Reception', 'Reception', self::CAPABILITY, self::PAGE, array(__CLASS__, 'bookings_page'), 'dashicons-calendar-alt', 26);
        add_submenu_page(self::PAGE, 'Reservations', 'Reservations', self::CAPABILITY, self::PAGE, array(__CLASS__, 'bookings_page'));
        add_submenu_page(self::PAGE, 'Rooms', 'Rooms', self::CAPABILITY, self::PAGE . '-rooms', array(__CLASS__, 'rooms_page'));
    }

    private static function authorized() {
        if (!current_user_can(self::CAPABILITY)) {
            wp_die('You do not have permission to manage reception.', '', array('response' => 403));
        }
    }

    private static function text_input($key) {
        return isset($_POST[$key]) && is_string($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
    }

    private static function int_input($key) {
        $value = self::text_input($key);
        return preg_match('/^[1-9][0-9]{0,8}$/', $value) ? (int) $value : 0;
    }

    private static function redirect($page, $notice) {
        wp_safe_redirect(add_query_arg(array('page' => $page, 'reception_notice' => $notice), admin_url('admin.php')));
        exit;
    }

    private static function notice() {
        $messages = array(
            'room_added' => array('success', 'Room added.'),
            'room_invalid' => array('error', 'Enter a unique room number, a room type, a capacity from 1 to 100, and a valid nightly rate in GHS.'),
            'rate_updated' => array('success', 'Nightly rate updated. Existing reservations retain their original price.'),
            'rate_invalid' => array('error', 'Enter a valid nightly rate greater than zero and up to GHS 1,000,000.'),
            'booked' => array('success', 'Reservation created.'),
            'invalid' => array('error', 'Check the guest details, room, guest count and dates. Check-in cannot be in the past; stays must be between 1 and 365 nights.'),
            'unavailable' => array('error', 'That room is already reserved for some of those dates.'),
            'busy' => array('error', 'The room is being booked by another staff member. Please retry.'),
            'db_error' => array('error', 'The operation could not be saved. Please retry or contact the site administrator.'),
            'status_updated' => array('success', 'Reservation status updated.'),
            'bad_status' => array('error', 'That status change is not allowed, or the reservation was already changed.'),
        );
        $key = isset($_GET['reception_notice']) && is_string($_GET['reception_notice']) ? sanitize_key(wp_unslash($_GET['reception_notice'])) : '';
        if (isset($messages[$key])) {
            printf('<div class="notice notice-%s"><p>%s</p></div>', esc_attr($messages[$key][0]), esc_html($messages[$key][1]));
        }
    }

    /** Parse GHS decimal text into whole pesewas without floating-point arithmetic. */
    public static function parse_ghs($value) {
        if (!is_string($value) || !preg_match('/^(0|[1-9][0-9]{0,6})(?:\.([0-9]{1,2}))?$/', $value, $matches)) {
            return null;
        }
        $amount = (int) $matches[1] * 100 + (int) str_pad($matches[2] ?? '', 2, '0');
        return $amount > 0 && $amount <= 100000000 ? $amount : null;
    }

    private static function format_ghs($amount) {
        if ($amount === null) {
            return 'Not set';
        }
        $amount = (int) $amount;
        return sprintf('GHS %d.%02d', intdiv($amount, 100), $amount % 100);
    }

    private static function lock_name($room_id) {
        global $wpdb;
        return 'bookora_' . substr(hash('sha256', $wpdb->prefix . ':' . $room_id), 0, 40);
    }

    public static function handle_add_room() {
        self::authorized();
        if (!current_user_can('manage_options')) {
            wp_die('Only administrators can manage room inventory and rates.', '', array('response' => 403));
        }
        check_admin_referer('bookora_reception_add_room');
        global $wpdb;
        $number = self::text_input('room_number');
        $type = self::text_input('room_type');
        $capacity = self::int_input('capacity');
        $rate = self::parse_ghs(self::text_input('nightly_rate'));
        if ($number === '' || strlen($number) > 100 || $type === '' || strlen($type) > 100 || $capacity < 1 || $capacity > 100 || $rate === null) {
            self::redirect(self::PAGE . '-rooms', 'room_invalid');
        }
        $exists = $wpdb->get_var($wpdb->prepare('SELECT id FROM ' . self::rooms_table() . ' WHERE room_number = %s', $number));
        if ($exists) {
            self::redirect(self::PAGE . '-rooms', 'room_invalid');
        }
        $inserted = $wpdb->insert(self::rooms_table(), array('room_number' => $number, 'room_type' => $type, 'capacity' => $capacity, 'nightly_rate_pesewas' => $rate), array('%s', '%s', '%d', '%d'));
        self::redirect(self::PAGE . '-rooms', $inserted === false ? 'db_error' : 'room_added');
    }

    public static function set_room_rate($room_id, $rate) {
        global $wpdb;
        if (!is_int($room_id) || $room_id < 1 || !is_int($rate) || $rate < 1 || $rate > 100000000) {
            return 'rate_invalid';
        }
        $lock_name = self::lock_name($room_id);
        if ((string) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 5)', $lock_name)) !== '1') {
            return 'busy';
        }
        try {
            $room = $wpdb->get_row($wpdb->prepare('SELECT id FROM ' . self::rooms_table() . ' WHERE id = %d', $room_id), ARRAY_A);
            if (!$room) {
                return 'rate_invalid';
            }
            $result = $wpdb->update(self::rooms_table(), array('nightly_rate_pesewas' => $rate), array('id' => $room_id), array('%d'), array('%d'));
            return $result === false ? 'db_error' : 'rate_updated';
        } finally {
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock_name));
        }
    }

    public static function handle_rate() {
        self::authorized();
        if (!current_user_can('manage_options')) {
            wp_die('Only administrators can manage room inventory and rates.', '', array('response' => 403));
        }
        $room_id = self::int_input('room_id');
        check_admin_referer('bookora_reception_rate_' . $room_id);
        $status = self::set_room_rate($room_id, self::parse_ghs(self::text_input('nightly_rate')));
        self::redirect(self::PAGE . '-rooms', $status);
    }

    private static function valid_date($date) {
        if (!is_string($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    /** Create a room reservation. The database lock serializes competing writers for the same room. */
    public static function create_reservation($room_id, $name, $email, $phone, $guests, $check_in, $check_out, $user_id) {
        global $wpdb;
        if (!is_int($room_id) || $room_id < 1 || !is_int($guests) || $guests < 1 || $guests > 100 || !is_int($user_id) || $user_id < 1 ||
            !is_string($name) || trim($name) === '' || strlen($name) > 150 || !is_string($email) || strlen($email) > 190 || ($email !== '' && !is_email($email)) ||
            !is_string($phone) || strlen($phone) > 50 || !self::valid_date($check_in) || !self::valid_date($check_out) ||
            $check_in < current_time('Y-m-d') || $check_out <= $check_in) {
            return 'invalid';
        }
        $nights = (int) DateTimeImmutable::createFromFormat('!Y-m-d', $check_in)->diff(DateTimeImmutable::createFromFormat('!Y-m-d', $check_out))->days;
        if ($nights < 1 || $nights > 365) {
            return 'invalid';
        }
        $lock_name = self::lock_name($room_id);
        $lock_result = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 5)', $lock_name));
        if ((string) $lock_result !== '1') {
            return 'busy';
        }
        try {
            $room = $wpdb->get_row($wpdb->prepare('SELECT id, capacity, nightly_rate_pesewas FROM ' . self::rooms_table() . ' WHERE id = %d', $room_id), ARRAY_A);
            if (!$room || $guests > (int) $room['capacity']) {
                return 'invalid';
            }
            // Checkout is exclusive: a guest can arrive on another guest's checkout date.
            $overlap = $wpdb->get_var($wpdb->prepare(
                'SELECT COUNT(*) FROM ' . self::bookings_table() . " WHERE room_id = %d AND status <> 'cancelled' AND check_in < %s AND check_out > %s",
                $room_id, $check_out, $check_in
            ));
            if ($overlap === null) {
                return 'db_error';
            }
            if ((int) $overlap > 0) {
                return 'unavailable';
            }
            $nightly_rate = (int) $room['nightly_rate_pesewas'];
            // Legacy rooms may be unpriced; retain a NULL total until a rate is set.
            $rate_snapshot = $nightly_rate > 0 ? $nightly_rate : null;
            if ($rate_snapshot !== null && $rate_snapshot > intdiv(PHP_INT_MAX, $nights)) {
                return 'invalid';
            }
            $total = $rate_snapshot !== null ? $rate_snapshot * $nights : null;
            $inserted = $wpdb->insert(self::bookings_table(), array(
                'room_id' => $room_id,
                'guest_name' => $name,
                'guest_email' => $email,
                'guest_phone' => $phone,
                'guests' => $guests,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'status' => 'confirmed',
                'nightly_rate_pesewas' => $rate_snapshot,
                'total_pesewas' => $total,
                'created_by' => $user_id,
                'created_at' => current_time('mysql', true),
            ), array('%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s'));
            return $inserted === false ? 'db_error' : 'booked';
        } finally {
            $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock_name));
        }
    }

    public static function handle_booking() {
        self::authorized();
        check_admin_referer('bookora_reception_book');
        $status = self::create_reservation(
            self::int_input('room_id'), self::text_input('guest_name'), self::text_input('guest_email'),
            self::text_input('guest_phone'), self::int_input('guests'), self::text_input('check_in'),
            self::text_input('check_out'), (int) get_current_user_id()
        );
        self::redirect(self::PAGE, $status);
    }

    public static function update_status($id, $action) {
        global $wpdb;
        $transitions = array(
            'check_in' => array('confirmed', 'checked_in'),
            'check_out' => array('checked_in', 'checked_out'),
            'cancel' => array('confirmed', 'cancelled'),
        );
        if (!is_int($id) || $id < 1 || !isset($transitions[$action])) {
            return 'bad_status';
        }
        list($from, $to) = $transitions[$action];
        $result = $wpdb->update(self::bookings_table(), array('status' => $to), array('id' => $id, 'status' => $from), array('%s'), array('%d', '%s'));
        return $result === false ? 'db_error' : ($result === 1 ? 'status_updated' : 'bad_status');
    }

    public static function handle_status() {
        self::authorized();
        $id = self::int_input('booking_id');
        check_admin_referer('bookora_reception_status_' . $id);
        $action = self::text_input('transition');
        self::redirect(self::PAGE, self::update_status($id, $action));
    }

    public static function rooms_page() {
        self::authorized();
        global $wpdb;
        $rooms = $wpdb->get_results('SELECT id, room_number, room_type, capacity, nightly_rate_pesewas FROM ' . self::rooms_table() . ' ORDER BY room_number ASC', ARRAY_A);
        $can_manage_rooms = current_user_can('manage_options');
        ?>
        <div class="wrap"><h1>Reception: Rooms</h1>
            <?php self::notice(); ?>
            <p>Each entry is one physical room. Prices are per night in Ghanaian cedis (GHS).</p>
            <?php if ($can_manage_rooms): ?>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="bookora_reception_add_room">
                    <?php wp_nonce_field('bookora_reception_add_room'); ?>
                    <table class="form-table" role="presentation"><tbody>
                        <tr><th><label for="room-number">Room number</label></th><td><input id="room-number" name="room_number" type="text" maxlength="100" required></td></tr>
                        <tr><th><label for="room-type">Room type</label></th><td><input id="room-type" name="room_type" type="text" maxlength="100" required placeholder="e.g. Deluxe"></td></tr>
                        <tr><th><label for="room-capacity">Maximum guests</label></th><td><input id="room-capacity" name="capacity" type="number" min="1" max="100" value="2" required></td></tr>
                        <tr><th><label for="room-rate">Nightly rate (GHS)</label></th><td><input id="room-rate" name="nightly_rate" type="number" min="0.01" max="1000000" step="0.01" required></td></tr>
                    </tbody></table>
                    <?php submit_button('Add room'); ?>
                </form>
            <?php else: ?>
                <p>Only administrators can add rooms or change room rates.</p>
            <?php endif; ?>
            <h2>Rooms</h2>
            <table class="widefat striped"><thead><tr><th>Room</th><th>Type</th><th>Maximum guests</th><th>Nightly rate</th><?php if ($can_manage_rooms): ?><th>Update rate</th><?php endif; ?></tr></thead><tbody>
                <?php if (empty($rooms)): ?><tr><td colspan="<?php echo $can_manage_rooms ? '5' : '4'; ?>">No rooms have been added.</td></tr><?php else: ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr><td><?php echo esc_html($room['room_number']); ?></td><td><?php echo esc_html($room['room_type']); ?></td><td><?php echo esc_html($room['capacity']); ?></td>
                            <td><?php echo esc_html(self::format_ghs((int) $room['nightly_rate_pesewas'] > 0 ? $room['nightly_rate_pesewas'] : null)); ?></td>
                            <?php if ($can_manage_rooms): ?>
                                <td><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <input type="hidden" name="action" value="bookora_reception_rate">
                                    <input type="hidden" name="room_id" value="<?php echo esc_attr($room['id']); ?>">
                                    <?php wp_nonce_field('bookora_reception_rate_' . $room['id']); ?>
                                    <label class="screen-reader-text" for="rate-<?php echo esc_attr($room['id']); ?>">Nightly rate in GHS</label>
                                    <input id="rate-<?php echo esc_attr($room['id']); ?>" name="nightly_rate" type="number" min="0.01" max="1000000" step="0.01" required>
                                    <button type="submit" class="button">Save rate</button>
                                </form></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody></table>
        </div>
        <?php
    }

    public static function bookings_page() {
        self::authorized();
        global $wpdb;
        $rooms = $wpdb->get_results('SELECT id, room_number, room_type, capacity, nightly_rate_pesewas FROM ' . self::rooms_table() . ' ORDER BY room_number ASC', ARRAY_A);
        $reservations = $wpdb->get_results('SELECT b.*, r.room_number, r.room_type FROM ' . self::bookings_table() . ' b LEFT JOIN ' . self::rooms_table() . ' r ON r.id = b.room_id ORDER BY b.id DESC LIMIT 100', ARRAY_A);
        ?>
        <div class="wrap"><h1>Reception: Reservations</h1>
            <?php self::notice(); ?>
            <p>Reservations here are for reception staff only. Stays are limited to 365 nights. Prices exclude taxes and extras. Unpriced legacy rooms can be booked with no total; ask an administrator to set their rates. No payments or outside channel synchronization are provided.</p>
            <?php if (empty($rooms)): ?>
                <p>Add a room on the <a href="<?php echo esc_url(admin_url('admin.php?page=' . self::PAGE . '-rooms')); ?>">Rooms page</a> before making a reservation.</p>
            <?php else: ?>
                <h2>New reservation</h2>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="bookora_reception_book">
                    <?php wp_nonce_field('bookora_reception_book'); ?>
                    <table class="form-table" role="presentation"><tbody>
                        <tr><th><label for="booking-room">Room</label></th><td><select id="booking-room" name="room_id" required><option value="">Select a room</option><?php foreach ($rooms as $room): ?><option value="<?php echo esc_attr($room['id']); ?>"><?php echo esc_html($room['room_number'] . ' — ' . $room['room_type'] . ' (max ' . $room['capacity'] . ') — ' . self::format_ghs((int) $room['nightly_rate_pesewas'] > 0 ? $room['nightly_rate_pesewas'] : null) . '/night'); ?></option><?php endforeach; ?></select></td></tr>
                        <tr><th><label for="guest-name">Guest name</label></th><td><input id="guest-name" name="guest_name" type="text" maxlength="150" required></td></tr>
                        <tr><th><label for="guest-email">Email (optional)</label></th><td><input id="guest-email" name="guest_email" type="email" maxlength="190"></td></tr>
                        <tr><th><label for="guest-phone">Phone (optional)</label></th><td><input id="guest-phone" name="guest_phone" type="tel" maxlength="50"></td></tr>
                        <tr><th><label for="booking-guests">Guests</label></th><td><input id="booking-guests" name="guests" type="number" min="1" max="100" value="1" required></td></tr>
                        <tr><th><label for="booking-check-in">Check-in</label></th><td><input id="booking-check-in" name="check_in" type="date" min="<?php echo esc_attr(current_time('Y-m-d')); ?>" required></td></tr>
                        <tr><th><label for="booking-check-out">Check-out</label></th><td><input id="booking-check-out" name="check_out" type="date" min="<?php echo esc_attr(current_time('Y-m-d')); ?>" required></td></tr>
                    </tbody></table>
                    <?php submit_button('Create reservation'); ?>
                </form>
            <?php endif; ?>
            <h2>Recent reservations</h2><p>Showing the most recent 100 reservations. Check-out dates are not occupied nights.</p>
            <table class="widefat striped"><thead><tr><th>ID</th><th>Guest / contact</th><th>Room</th><th>Guests</th><th>Check-in</th><th>Check-out</th><th>Rate / night</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                <?php if (empty($reservations)): ?><tr><td colspan="10">No reservations yet.</td></tr><?php else: ?>
                    <?php foreach ($reservations as $booking): ?>
                        <tr><td><?php echo esc_html($booking['id']); ?></td>
                            <td><?php echo esc_html($booking['guest_name']); ?><br><?php echo esc_html($booking['guest_email']); ?><br><?php echo esc_html($booking['guest_phone']); ?></td>
                            <td><?php echo esc_html(($booking['room_number'] ?? '') . ' ' . ($booking['room_type'] ?? '')); ?></td>
                            <td><?php echo esc_html($booking['guests']); ?></td><td><?php echo esc_html($booking['check_in']); ?></td><td><?php echo esc_html($booking['check_out']); ?></td>
                            <td><?php echo esc_html(self::format_ghs($booking['nightly_rate_pesewas'])); ?></td>
                            <td><?php echo esc_html(self::format_ghs($booking['total_pesewas'])); ?></td>
                            <td><?php echo esc_html(ucwords(str_replace('_', ' ', $booking['status']))); ?></td>
                            <td>
                                <?php $actions = $booking['status'] === 'confirmed' ? array('check_in' => 'Check in', 'cancel' => 'Cancel') : ($booking['status'] === 'checked_in' ? array('check_out' => 'Check out') : array()); ?>
                                <?php foreach ($actions as $action => $label): ?>
                                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;margin:2px">
                                        <input type="hidden" name="action" value="bookora_reception_status">
                                        <input type="hidden" name="booking_id" value="<?php echo esc_attr($booking['id']); ?>">
                                        <input type="hidden" name="transition" value="<?php echo esc_attr($action); ?>">
                                        <?php wp_nonce_field('bookora_reception_status_' . $booking['id']); ?>
                                        <button type="submit" class="button"><?php echo esc_html($label); ?></button>
                                    </form>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody></table>
        </div>
        <?php
    }
}

register_activation_hook(__FILE__, array('Bookora_Reception', 'activate'));
Bookora_Reception::init();
