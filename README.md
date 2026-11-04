# Bookora hotel theme package

This repository contains the Bookora WordPress theme and companion plugin. **It is not a complete hotel booking system.** Install and configure a separate booking provider, then thoroughly test availability, reservations, payments, cancellations, notifications, and concurrency before accepting real bookings.

## Contents

- `theme/bookora/` and `plugin/bookora-plugin/`: editable source of the packaged theme and companion plugin.
- `bookora_hotel_booking_wordpress_theme_2026_01_08_10_59_53_utc.zip`: distributable package, including the nested theme and plugin ZIPs and the original documentation and license notices. Run `scripts/package.sh` after editing the source to rebuild it. The local installer uses the source directories, not the ZIP.
- `docker-compose.yml` and `install.sh`: **local development only**, not a production deployment.
- `hotelbooking-audit-report.md`: the original audit, including limitations. See `REMEDIATION.md` for what changed and what remains to verify.

## Local setup

For a guided Podman setup, run `bash scripts/run-podman.sh`. It generates local database passwords, prompts for an administrator password, uses WooCommerce 10.6.2 for the bundled WordPress 6.8.3 baseline, and selects the latest available versions of the other plugins unless version variables are already set. Those versions still need compatibility and security review. On macOS or Windows, start the Podman machine first. If port 8080 is occupied, run `BOOKORA_PORT=8081 bash scripts/run-podman.sh` and open `http://localhost:8081`. A previously installed WordPress site retains its original site URL when the port changes; back it up and update its WordPress URLs before switching ports.

Requirements: Docker Compose or Podman Compose, Bash, curl, `sha256sum`, internet access to GitHub and wordpress.org. The example pins image tags and the installer pins and verifies WP-CLI; choose and export explicitly tested plugin versions as shown below. Version tags are not immutable image digests. Review and update all versions for security and compatibility before use; these particular combinations have not been integration-tested in this environment.

1. Copy `.env.example` to `.env` and set **different, long, random** `MYSQL_ROOT_PASSWORD` and `MYSQL_PASSWORD` values. `.env` and runtime `wp-content/` are Git-ignored. Use a fresh volume or the existing database's original credentials; changing `.env` does not update passwords in an already-initialized MySQL volume.
2. Start the stack with `docker compose up -d` (or `podman compose up -d`). The web port binds to `127.0.0.1:8080`, so it is accessible only from the same host by default.
3. Set a unique WordPress administrator password of at least 12 characters and choose compatible, reviewed versions of the five plugins. Substitute actual versions in this example, not the angle-bracket placeholders:

   ```bash
   export WP_ADMIN_PASSWORD='<unique-long-password>'
   export WP_ELEMENTOR_VERSION='<tested-version>'
   export WP_WOOCOMMERCE_VERSION='<tested-version>'
   export WP_CONTACT_FORM_7_VERSION='<tested-version>'
   export WP_OCDI_VERSION='<tested-version>'
   export WP_MAILCHIMP_VERSION='<tested-version>'
   bash install.sh  # For Podman when Docker is also installed: BOOKORA_RUNTIME=podman bash install.sh
   unset WP_ADMIN_PASSWORD
   ```

   Optional initial-install variables: `WP_URL` (defaults to `http://localhost:8080`), `WP_TITLE`, `WP_ADMIN_USER` (defaults to `bookora-admin`) and `WP_ADMIN_EMAIL`. The password is only required when creating a new WordPress installation. Do not use a real customer-facing email or site in this development stack.
4. Open `http://localhost:8080`. Configure and test the booking provider separately. Directorist, if desired, is an optional separate installation; it is not a substitute for verifying booking capability.

The installer checks the WP-CLI release SHA-256, installs missing dependencies at the supplied versions, activates the Bookora companion plugin and theme through WP-CLI, verifies activation, and stops on errors. Installed dependencies are preserved rather than silently upgraded or removed. It also preserves an existing `bookora` theme or `bookora-plugin` directory by default; to replace them with this checkout, **back up customizations first**, then run `BOOKORA_REPLACE=1 bash install.sh`. This explicitly replaces those directories, not the rest of `wp-content` or the active plugin list. The script does not roll back a partially completed installation; back up the database and files before modifying an existing site. If an activation fails, fix the reported error and rerun. Do not use the example stack for production, public access, or sensitive data.

## Availability and demo import

Hero style 3 no longer shows a nonfunctional availability form. Install a booking plugin or service, configure it, and enter its form shortcode in the Hero widget's **Booking provider shortcode** field; leave the field empty to hide the form. The shortcode and booking implementation are supplied by that provider, not by this theme. Hero style 1 is a separate Directorist directory search, not a hotel reservation form.

For sample content, use **Bookora → Import Demo Data** (One Click Demo Import). The old unfinished custom demo importer was removed. Import into a disposable site first and review all imported content. Booking functionality is not supplied by demo data.

## Lemon Squeezy license activation

The license feature uses Lemon Squeezy's public license API, not a store API key. Ask the license issuer for the intended **store ID** and **product ID**. For this Compose setup, set `BOOKORA_LICENSE_STORE_ID` and `BOOKORA_LICENSE_PRODUCT_ID` in `.env` and recreate the web container (`docker compose up -d --force-recreate web`). In other WordPress installations define those constants in `wp-config.php` before WordPress loads. Never commit credentials or real keys.

Under **Appearance → Theme License**, enter the license key. The code checks that the key belongs to the configured store/product, activates an instance, stores its ID, periodically validates it, and deactivates it with the provider. A temporary network failure has a bounded 14-day grace period measured from the last successful validation; a definitive invalid response disables local active status. Admin activity triggers revalidation. Remote deactivation failures retain the local instance to avoid losing track of an activation slot. The previous implementation's locally marked licenses without an instance must be activated again. Any old stored API key is removed when activating or deactivating; if migrating a site, revoke or remove any unused stored key as appropriate.

A valid license does not by itself install a booking system, guarantee support, or implement theme updates. For an existing manually edited `wp-config.php`, ensure the two constants are actually available to WordPress; Compose environment values alone cannot override definitions in a custom configuration.

## SVG uploads

The companion plugin no longer enables SVG uploads or uses the broken custom sanitizer. If SVG is required, install a maintained solution that restricts upload permissions and sanitizes active content; review its security and test it independently.

## Tests

With PHP 8+ and Node.js installed, run `php tests/license-manager-test.php`, `php tests/backup-ajax-test.php`, `bash tests/installer-smoke.sh`, and `node --check theme/bookora/assets/js/admin-license.js`. These tests use stubs and mocks; they do not replace a live WordPress integration test.

## License

The repository's installer, build helper, and newly authored tests and modifications are offered under **GPL-2.0-or-later**; the GPL v2 text is in `LICENSE`. The packaged theme identifies its code as GPL v2 or later. The original package also contains a split-license notice for non-code assets: CSS, images, design, and third-party assets may be subject to separate terms. The root license does **not** change or grant rights in those assets; review `bookora-wp-package/licensing/` and applicable third-party notices inside the distribution before reuse.
