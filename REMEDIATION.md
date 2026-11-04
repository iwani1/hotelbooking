# Audit remediation status

The findings in `hotelbooking-audit-report.md` describe the earlier package and are retained as an audit snapshot. The theme and plugin are now editable under `theme/` and `plugin/`; `scripts/package.sh` rebuilds the nested distribution ZIPs from those directories.

## Changes made

| Audit finding | Resolution or mitigation |
| --- | --- |
| 1. Nonfunctional availability form | Removed the fake form. Hero style 3 renders an explicitly configured booking-provider shortcode instead. **A booking provider and end-to-end booking tests are still required.** |
| 2. Companion plugin inactive | Installer now activates and verifies it. |
| 3. License API contract | Uses public POST validate/activate/deactivate endpoints, checks configured store and product IDs, persists the instance, and reads expiry from the license object. Removes the unnecessary API-key UI. |
| 4. Insecure local defaults | Compose binds to loopback and requires database passwords; the installer requires a unique administrator password for new installations. The stack is explicitly development-only. |
| 5. Destructive SQL fallback | Removed direct database manipulation. Existing plugins are preserved and normal activation errors stop the installer. Existing theme/plugin files are preserved unless replacement is explicitly requested. |
| 6. Broken SVG sanitizer | Removed the plugin's SVG upload enablement and custom sanitizer. SVG requires a separate reviewed solution. |
| 7. Ignored revalidation | Stores validation results, rejects definitive invalid responses, checks expiry and applies a bounded network-failure grace period. |
| 8. Masked failures and floating dependencies | Install/activation errors stop the script; new plugins require explicitly selected versions; WP-CLI release and SHA-256 are pinned. Compose uses versioned image tags. **Compatibility and current security of the selected versions still require review; tags are not immutable digests.** |
| 9. Runtime data in Git | `.env` and `wp-content/` are ignored. |
| 10. Demo importer | Removed the unused stub, corrected the OCDI widget-file and theme-option keys, and guarded missing menus/pages. OCDI still needs live verification. |
| 11. Shared Elementor assets | Removed the plugin's global deregistration/dequeue of Elementor Swiper and Font Awesome handles. |
| 12. Branch-switching helper | `pull.sh` updates only the current branch's configured upstream and refuses a dirty working tree. |

Additionally, the bundled Codestar backup AJAX endpoints now require administrator capability, a valid nonce, and a registered option group with a backup field. This applies when this bundled framework supplies the endpoints; another plugin loading its own Codestar copy first may supply different handlers and must be assessed separately. The GPL-2.0-or-later text and license scope are documented in `LICENSE` and `README.md`; the original package's separate terms for non-code assets remain intact.

## Verification performed

- PHP 8.5 WebAssembly CLI: linted the changed theme/plugin PHP files and ran the license-manager and backup-endpoint tests with WordPress functions stubbed.
- The installer mock smoke test verified companion activation and the absence of the old direct SQL fallback, without running Docker or WordPress.
- Parsed all 234 PHP files (theme, plugin and tests) with `php-parser` 3.2.5; checked the changed JavaScript with `node --check`; checked shell scripts with `bash -n`.
- Rebuilt the distribution, checked ZIP integrity, and compared its extracted theme/plugin files with the source directories.

## Still required before deployment

No real Docker/Podman, WordPress, MySQL, third-party plugins, Lemon Squeezy account, or booking provider was available here. These changes are not a production-readiness certification. Test fresh installation and upgrade/rollback, actual compatible version combinations, demo imports and rendering, capability boundaries, real license lifecycle (including renewal and network failures), browser/accessibility behavior, backup/restore, and the complete booking/payment lifecycle including simultaneous reservations. Review dependency vulnerabilities and image digests regularly. Do not expose the sample stack or use real customer data in it.
