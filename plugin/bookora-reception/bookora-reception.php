<?php
/**
 * Plugin Name: Bookora Reception
 * Description: Staff-only room and reservation management for reception.
 * Version: 1.0.0
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

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'menus'));
        add_action('admin_post_bookora_reception_add_room', array(__CLASS__, 'handle_add_room'));
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
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap(self::CAPABILITY);
        }
        add_role(self::ROLE, 'Receptionist', array('read' => true, self::CAPABILITY => true));
        $receptionist = get_role(self::ROLE);
        if ($receptionist) {
            $receptionist->add_cap(self::CAPABILITY);
        }
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
            'room_invalid' => array('error', 'Enter a unique room number, a room type, and a capacity from 1 to 100.'),
            'booked' => array('success', 'Reservation created.'),
            'invalid' => array('error', 'Check the guest details, room, guest count and dates. Check-in cannot be in the past; check-out must be after check-in.'),
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

    public static function handle_add_room() {
        self::authorized();
        check_admin_referer('bookora_reception_add_room');
        global $wpdb;
        $number = self::text_input('room_number');
        $type = self::text_input('room_type');
        $capacity = self::int_input('capacity');
        if ($number === '' || strlen($number) > 100 || $type === '' || strlen($type) > 100 || $capacity < 1 || $capacity > 100) {
            self::redirect(self::PAGE . '-rooms', 'room_invalid');
        }
        $exists = $wpdb->get_var($wpdb->prepare('SELECT id FROM ' . self::rooms_table() . ' WHERE room_number = %s', $number));
        if ($exists) {
            self::redirect(self::PAGE . '-rooms', 'room_invalid');
        }
        $inserted = $wpdb->insert(self::rooms_table(), array('room_number' => $number, 'room_type' => $type, 'capacity' => $capacity), array('%s', '%s', '%d'));
        self::redirect(self::PAGE . '-rooms', $inserted === false ? 'db_error' : 'room_added');
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
        $lock_name = 'bookora_' . substr(hash('sha256', $wpdb->prefix . ':' . $room_id), 0, 40);
        $lock_result = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 5)', $lock_name));
        if ((string) $lock_result !== '1') {
            return 'busy';
        }
        try {
            $room = $wpdb->get_row($wpdb->prepare('SELECT id, capacity FROM ' . self::rooms_table() . ' WHERE id = %d', $room_id), ARRAY_A);
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
            $inserted = $wpdb->insert(self::bookings_table(), array(
                'room_id' => $room_id,
                'guest_name' => $name,
                'guest_email' => $email,
                'guest_phone' => $phone,
                'guests' => $guests,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'status' => 'confirmed',
                'created_by' => $user_id,
                'created_at' => current_time('mysql', true),
            ), array('%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%s'));
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
        $rooms = $wpdb->get_results('SELECT id, room_number, room_type, capacity FROM ' . self::rooms_table() . ' ORDER BY room_number ASC', ARRAY_A);
        ?>
        <div class="wrap"><h1>Reception: Rooms</h1>
            <?php self::notice(); ?>
            <p>Add one entry for each physical room before creating reservations.</p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="bookora_reception_add_room">
                <?php wp_nonce_field('bookora_reception_add_room'); ?>
                <table class="form-table" role="presentation"><tbody>
                    <tr><th><label for="room-number">Room number</label></th><td><input id="room-number" name="room_number" type="text" maxlength="100" required></td></tr>
                    <tr><th><label for="room-type">Room type</label></th><td><input id="room-type" name="room_type" type="text" maxlength="100" required placeholder="e.g. Deluxe"></td></tr>
                    <tr><th><label for="room-capacity">Maximum guests</label></th><td><input id="room-capacity" name="capacity" type="number" min="1" max="100" value="2" required></td></tr>
                </tbody></table>
                <?php submit_button('Add room'); ?>
            </form>
            <h2>Rooms</h2>
            <table class="widefat striped"><thead><tr><th>Room</th><th>Type</th><th>Maximum guests</th></tr></thead><tbody>
                <?php if (empty($rooms)): ?><tr><td colspan="3">No rooms have been added.</td></tr><?php else: ?>
                    <?php foreach ($rooms as $room): ?><tr><td><?php echo esc_html($room['room_number']); ?></td><td><?php echo esc_html($room['room_type']); ?></td><td><?php echo esc_html($room['capacity']); ?></td></tr><?php endforeach; ?>
                <?php endif; ?>
            </tbody></table>
        </div>
        <?php
    }

    public static function bookings_page() {
        self::authorized();
        global $wpdb;
        $rooms = $wpdb->get_results('SELECT id, room_number, room_type, capacity FROM ' . self::rooms_table() . ' ORDER BY room_number ASC', ARRAY_A);
        $reservations = $wpdb->get_results('SELECT b.*, r.room_number, r.room_type FROM ' . self::bookings_table() . ' b LEFT JOIN ' . self::rooms_table() . ' r ON r.id = b.room_id ORDER BY b.id DESC LIMIT 100', ARRAY_A);
        ?>
        <div class="wrap"><h1>Reception: Reservations</h1>
            <?php self::notice(); ?>
            <p>Reservations here are for reception staff only. This dashboard does not process payments or synchronize with outside booking channels.</p>
            <?php if (empty($rooms)): ?>
                <p>Add a room on the <a href="<?php echo esc_url(admin_url('admin.php?page=' . self::PAGE . '-rooms')); ?>">Rooms page</a> before making a reservation.</p>
            <?php else: ?>
                <h2>New reservation</h2>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="bookora_reception_book">
                    <?php wp_nonce_field('bookora_reception_book'); ?>
                    <table class="form-table" role="presentation"><tbody>
                        <tr><th><label for="booking-room">Room</label></th><td><select id="booking-room" name="room_id" required><option value="">Select a room</option><?php foreach ($rooms as $room): ?><option value="<?php echo esc_attr($room['id']); ?>"><?php echo esc_html($room['room_number'] . ' — ' . $room['room_type'] . ' (max ' . $room['capacity'] . ')'); ?></option><?php endforeach; ?></select></td></tr>
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
            <table class="widefat striped"><thead><tr><th>ID</th><th>Guest / contact</th><th>Room</th><th>Guests</th><th>Check-in</th><th>Check-out</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                <?php if (empty($reservations)): ?><tr><td colspan="8">No reservations yet.</td></tr><?php else: ?>
                    <?php foreach ($reservations as $booking): ?>
                        <tr><td><?php echo esc_html($booking['id']); ?></td>
                            <td><?php echo esc_html($booking['guest_name']); ?><br><?php echo esc_html($booking['guest_email']); ?><br><?php echo esc_html($booking['guest_phone']); ?></td>
                            <td><?php echo esc_html(($booking['room_number'] ?? '') . ' ' . ($booking['room_type'] ?? '')); ?></td>
                            <td><?php echo esc_html($booking['guests']); ?></td><td><?php echo esc_html($booking['check_in']); ?></td><td><?php echo esc_html($booking['check_out']); ?></td>
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
