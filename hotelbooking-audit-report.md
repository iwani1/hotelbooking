# Hotelbooking application audit

**Historical snapshot:** These findings describe the package before remediation. See `REMEDIATION.md` for changes and outstanding verification.

**Date:** 2026-11-04  
**Scope:** repository checkout, local installation scripts and Compose configuration, plus the theme and companion plugin in the nested distribution ZIPs.  
**Overall assessment:** **Not ready for public booking or production deployment.** The supplied availability form is not connected to a booking system, the installer does not activate the companion plugin, and license validation does not follow the provider's documented API contract. The Compose stack is explicitly for local development, but its defaults are unsafe if exposed.

## Scope and limits

The theme and plugin are shipped inside `bookora_hotel_booking_wordpress_theme_2026_01_08_10_59_53_utc.zip`, respectively as `bookora-wp-package/bookora.zip` and `bookora-wp-package/plugin/bookora-plugin.zip`. Paths below beginning `bookora/` or `bookora-plugin/` refer to files inside those nested archives, not independent tracked source files. Extract them to inspect or modify them, and rebuild the appropriate archives when shipping fixes.

This is a static, risk-focused audit, not a penetration test or a certification. PHP, Docker/Podman, WordPress, and a database were not available in the audit environment. No live installation, actual bookings/payments, email delivery, browser/accessibility tests, authenticated exploit tests, or real Lemon Squeezy requests were performed. Third-party plugins downloaded during installation, their current vulnerabilities, and any separately installed booking software were outside the available source scope. Severity assumes the indicated exposure and prerequisites; issues applicable only after manual activation are identified as such.

## Findings

### 1. High — Availability form does not perform availability search

**Evidence:** `bookora-plugin/elementor/widgets/Hero/hero-template/hero-3.php:15-53`. The form posts a GET to `#`; its date inputs and guest/room selects have no `name` attributes. There is no target booking endpoint in the form and no submitted search values. The room widgets contain presentation data; no complete booking engine was identified in the supplied theme/plugin. The installer does not install a hotel booking engine, and the optional Directorist integration is not evidence of a working reservations flow.

**Impact:** Guests using this form cannot submit these selections to check availability or reserve a room. Do not represent this as a working booking flow based on the supplied package alone.

**Remediation:** Select and integrate a real booking/inventory provider, wire validated date, occupancy and room fields to it, handle time zones and availability changes, and test reservation, payment, cancellation, confirmation and concurrency/overbooking end to end. Hide or relabel the placeholder until then.

### 2. High — Installer never activates the companion plugin

**Evidence:** `install.sh:94-97` copies `bookora-plugin`, but the activation commands at `install.sh:181-199` do not include it in either the WP-CLI path or the fallback plugin list. Its Elementor widgets and theme options are registered by `bookora-plugin/bookora-plugin.php:50-73` and `bookora-plugin/elementor/elementor-init.php:18-23,53-60`.

**Impact:** A fresh install can claim success while the required Bookora widgets, options, and other companion features remain unavailable. Demo content relying on them may not render correctly.

**Remediation:** Activate the companion plugin via WordPress's normal activation API, verify it is active, and fail clearly on errors. Test a fresh install and an existing installation.

### 3. High — License activation uses the wrong API contract

**Evidence:** `bookora/includes/class-license-manager.php:178-217` calls `wp_remote_get` with a request body against `/v1/licenses/validate`, although the [Lemon Squeezy validation API](https://docs.lemonsqueezy.com/api/license-api/validate-license-key) specifies POST with a `license_key`. The code checks `meta.expires_at`, while the documented response puts expiry under `license_key.expires_at`. It never calls the separate activation endpoint or stores an instance ID.

**Impact:** The documented license activation workflow is unlikely to succeed against the provider, and even a corrected validation call alone would not create a tracked activation. The displayed expiry can be wrong. Live provider behavior was not tested.

**Remediation:** Implement the documented activate/validate/deactivate lifecycle with correctly encoded POST requests, response/status checking, instance storage, and expected store/product/variant validation. Add mocked contract tests and a provider sandbox integration test. Do not require customers to supply a store-wide API key when the public license API does not require it; avoid storing and echoing such a secret (`class-license-manager.php:50-54,99-114`).

### 4. High — Predictable administrator credentials on a network-exposed local stack

**Evidence:** `docker-compose.yml:16-20,27-31,37-38` hardcodes database credentials and publishes `8080:80` without a host-IP restriction; `install.sh:149-156` defaults the WordPress administrator to `admin` / `admin`. README describes these defaults. Port publishing normally binds all host interfaces unless the container runtime is configured otherwise.

**Impact:** If used on a reachable host, the development site can be taken over with the documented credentials. Unencrypted HTTP also exposes credentials over the network. This is conditional on network exposure; the stack is labeled local development, not a production deployment.

**Remediation:** Bind the development port to `127.0.0.1` by default, require or generate a unique administrator password, use environment-provided database secrets, and clearly prohibit public deployment of this stack. For production, use HTTPS, managed secrets and a hardened deployment.

### 5. High — Installer can overwrite existing plugin state and bypass activation hooks

**Evidence:** `install.sh:187-210` falls back to an SQL `UPDATE` of `wp_options.active_plugins` with a hardcoded five-plugin list when Elementor activation fails, and similarly changes theme options directly. It assumes the `wp_` prefix and a database named `bookora`.

**Impact:** On an existing installation the fallback can deactivate unrelated plugins by replacing the entire active list, includes plugins whose installation may have failed, and skips activation hooks; direct theme changes skip normal switch hooks. It may report success without touching the correct table on nonstandard prefixes. These problems occur only on the relevant fallback path.

**Remediation:** Remove direct database activation. Preserve existing state and use supported WP-CLI/WordPress activation APIs, diagnose underlying incompatibility, verify each result, and stop with actionable errors instead of silently continuing. Back up before any migration.

### 6. Medium — SVG upload handler fails instead of sanitizing

**Evidence:** When the companion plugin is active, `bookora-plugin/inc/helper.php:481-524` enables SVG uploads and hooks a sanitizer. At line 507 it passes a closure to `preg_replace` rather than `preg_replace_callback`; PHP 8 expects a string or array replacement. At lines 529-536 it calls `getParent()` on a SimpleXML element, which is not a SimpleXMLElement method, when a matching dangerous node is found.

**Impact:** Ordinary SVG uploads reaching the handler can fail with a PHP error; dangerous-element input can fail even earlier. The advertised hardening is therefore not a working sanitizer. No runtime PHP test was possible. Do not assume switching to `preg_replace_callback` alone would make the custom sanitizer safe.

**Remediation:** Disable SVG uploads until an actively maintained, well-tested SVG sanitizer is used. Restrict upload capability, reject malformed input safely, and test malicious namespaces, event handlers, URLs, CSS, entities, and normal files on supported PHP versions.

### 7. Medium — Periodic license revalidation does not change license state

**Evidence:** `bookora/includes/class-license-manager.php:222-233` discards the verification result and sets a weekly transient regardless of whether validation succeeded. `is_license_active` only checks locally stored data.

**Impact:** After a successful initial activation, a revoked or expired license can remain marked active indefinitely; a transient network error is also treated the same as a successful check for scheduling. This is separate from the currently broken initial API call.

**Remediation:** Persist verified status and expiry, handle definitive invalid/revoked responses separately from temporary network failures with an explicit grace period, and test both cases.

### 8. Medium — Dependency installation and activation failures are masked

**Evidence:** `install.sh:164-173,189-193` suppresses errors and uses `|| true` for several required plugins. The script prints `Done` at line 230 without checking that they are installed and active. It also fetches an unpinned WP-CLI binary at lines 103-116 and floating WordPress/MySQL images in `docker-compose.yml:14,25`.

**Impact:** Installations can silently be incomplete; versions and compatibility can change between runs, making builds difficult to reproduce and security updates hard to track. Downloading an executable without independent integrity verification increases supply-chain exposure.

**Remediation:** Pin and periodically update tested image/plugin/WP-CLI versions or digests, verify downloaded binary integrity against a trusted release, report individual failures, and validate final plugin/theme state. Add a repeatable update and vulnerability-review process.

### 9. Medium — Local WordPress data is not ignored by Git

**Evidence:** `docker-compose.yml:34-36` binds `./wp-content`, which includes uploads and installed plugins/themes. `.gitignore` excludes extraction and WP-CLI temporary files but not `/wp-content/`.

**Impact:** Normal use of the documented installer creates a large untracked tree that can accidentally be committed, including media uploads or other site data. The MySQL volume is separate, but uploads can still contain private information.

**Remediation:** Ignore runtime `wp-content` (or separate source-controlled development code from runtime uploads) and establish explicit rules for secrets, backups and customer data. Review files before adding them to Git.

### 10. Medium — Custom demo importer is unreachable and would report false success

**Evidence:** `bookora/inc/activation.php:5-13` includes `class-demo-importer.php` but initializes only `Theme_License_Manager`; there are no calls to `Theme_Demo_Importer::get_instance()` in the theme. Even if initialized, `bookora/includes/class-demo-importer.php:87-110` calls empty methods and returns success. The repository README acknowledges stubs and directs users to One Click Demo Import (OCDI).

**Impact:** The custom importer page/AJAX hooks are not registered; if merely wired up later, its success message would be misleading. OCDI is a distinct path and includes local demo files; it was not tested live. Its widget file is configured as `local_import_widgets` (`bookora/inc/admin/demo-import/functions.php:18`), rather than the documented `local_import_widget_file`, so the widgets file is likely skipped by OCDI ([configuration examples](https://github.com/mybooking-es/mybooking-templates-importer)).

**Remediation:** Remove the unfinished feature or implement and test it fully. Keep a single supported import workflow, and validate expected pages and menus before assigning them (`bookora/inc/admin/demo-import/functions.php:26-45` dereferences results without checking for missing content).

### 11. Medium — Global removal of Elementor/Swiper assets can break unrelated widgets

**Evidence:** `bookora-plugin/bookora-plugin.php:81-88,110-128` globally dequeues/deregisters the `swiper` handle and Elementor Font Awesome handles. The theme separately loads its own Swiper under different handles (`bookora/inc/enqueue-scripts.php:52,69`).

**Impact:** Other Elementor or plugin components that depend on the registered handles may lose scripts/styles or receive an incompatible library. Exact affected widgets depend on installed versions and require browser verification.

**Remediation:** Do not deregister shared third-party handles globally; declare proper dependencies and isolate or version the theme's own assets. Test Elementor carousels and icons across supported versions.

### 12. Low — Pull helper hardcodes an older branch

**Evidence:** `pull.sh:17-35` checks out and pulls `arena/b83f1ab3-hotelbooking` regardless of the caller's current branch.

**Impact:** Running the helper may switch a developer away from their intended work or fail when local changes conflict. It does not update the current session branch.

**Remediation:** Remove the branch-specific helper or make it operate explicitly on the current branch after checking for uncommitted changes; document the intended workflow.

## Verification performed

- `bash -n install.sh pull.sh`: passed syntax check.
- `unzip -tq` on the outer package and both nested theme/plugin archives: passed integrity checks.
- Parsed all **233 PHP files** in the extracted theme and plugin with `php-parser` 3.2.5: no parse errors. This is not PHP's own lint or a runtime/compatibility test.
- `node --check` on **30 JavaScript files** under the theme and plugin asset JS directories: no syntax errors. This does not execute browser code or cover every bundled/vendor JS file.
- Reviewed the installer and Compose configuration, the booking form, license and demo paths, upload handler, plugin registration and selected AJAX/security-sensitive code. No automated application test suite was found in the tracked repository.

## Recommended sequence

1. Keep the development stack private; remove predictable admin defaults and protect local runtime data from Git.
2. Make installation deterministic and non-destructive, activate and verify the companion plugin, and remove the direct SQL fallback.
3. Replace the placeholder availability form with a supported booking integration and test the full transactional lifecycle before launch.
4. Correct the license API lifecycle; remove the unnecessary store API-key requirement and test invalidation and network failure.
5. Disable SVG uploads pending replacement of the sanitizer, and regression-test demo import and shared frontend assets.
6. Run the application on supported PHP/WordPress versions with real dependencies: fresh install, upgrades, role/nonce checks, accessibility, responsive rendering, backups/restores, booking concurrency, payment callbacks, and dependency vulnerability scanning. Reassess findings after runtime validation.
