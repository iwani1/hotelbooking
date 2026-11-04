=== Bookora Reception ===
Requires at least: 6.8
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPL-2.0-or-later

Staff-only physical-room reservations in WordPress admin. Administrators can add rooms and set fixed nightly rates in GHS; receptionists can view rooms and manage reservations. Reservations snapshot the nightly rate and total, without taxes or extras. Stays are limited to 365 nights. Rate changes do not affect existing bookings.

Rooms from version 1.0 have no rate until an administrator sets one. Past bookings and new bookings for an unpriced room show Not set; setting a rate later does not update them. Data remains in the WordPress database on deactivation. Back up files and the database before updating. Schema changes run on the next WordPress load. Network-wide multisite activation is not supported.

Availability covers only this plugin. No public bookings, Paystack or other payments, payment reconciliation, taxes, refunds, emails, or external channel synchronization are provided. This prototype has not been tested with a real WordPress/MySQL setup or concurrent requests. Do not use it for real bookings, customer data, or live payments until it has been completed and thoroughly tested.
