# Bookora standalone — early foundation

This separate TypeScript/React, Node.js and PostgreSQL application does not depend on WordPress. **It is an early prototype, not a full hotel management system or production-ready service. Do not enter real guest data or accept live payments.** It does not migrate data from the WordPress plugin.

Current scope: a demo-only guest availability search and test-reservation flow, plus a single-property staff dashboard; one administrator login; physical rooms with a GHS nightly rate; reservations with guest details, date/capacity validation and a fixed-rate snapshot; check-in, checkout and cancellation. Checkout dates are exclusive. Room-level database locks coordinate competing reservation writers that use this API. The guest search shows at most 100 rooms. The staff overview and list only use the most recent 200 reservations, so their totals and occupancy are not complete once there are more than 200. Prices exclude taxes and extras. There is no room-rate editing yet.

Not yet implemented: production guest bookings; staff accounts and roles; Paystack or other online payments; reception payment recording, reconciliation and refunds; taxes, housekeeping, invoices, reports, notifications, audit trails and channel sync. Reservations and rooms cannot yet be edited or deleted. More work, real PostgreSQL concurrency and end-to-end tests, a security review, backups and deployment hardening are required before any real use.

## Local Podman development

Requires Podman, a Compose provider, and internet access for image and npm packages. This starts a **separate** PostgreSQL database and web app on port 8082; it does not touch the WordPress containers or data.

```bash
cd standalone
# Create private local credentials (alphanumeric so the database URL stays valid).
umask 077
python3 -c 'import os, secrets; f = os.open(".env", os.O_WRONLY | os.O_CREAT | os.O_EXCL, 0o600); os.write(f, ("POSTGRES_PASSWORD=" + secrets.token_hex(24) + "\nBOOKORA_ADMIN_PASSWORD=" + secrets.token_hex(16) + "\nBOOKORA_STANDALONE_PORT=8082\n").encode()); os.close(f)'
podman compose up --build -d
```

Open http://localhost:8082 and sign in with username `admin` and the `BOOKORA_ADMIN_PASSWORD` in `standalone/.env`. Keep that file private. To use a different port, edit `BOOKORA_STANDALONE_PORT` before starting. Do **not** recreate `.env` on an existing install: PostgreSQL retains its original password in its volume. Stopping with `podman compose down` retains the database volume; do not add `-v` unless you intend to delete its data. The compose stack and images have not been tested with a real Podman runtime in this environment.

### Guest booking demo with Podman

After creating `.env` above, start an **isolated demo project** with its own sample database and a different port:

```bash
BOOKORA_DEMO=1 BOOKORA_STANDALONE_PORT=8083 podman compose -p bookora-demo up --build -d
```

Visit http://localhost:8083/book. Demo-mode guest reservations share this demo database with its reception dashboard at `/`; they are fictional and no payment or email is sent. The admin username is `admin` and the password is the value in `.env`, not the preview's default password. To stop it without deleting its database, run `podman compose -p bookora-demo down`. The separate port and project avoid touching the WordPress stack or the non-demo standalone database. If port 8083 is in use, choose another unused port. This Podman setup has not been tested with a real runtime here.

## Node development

Node.js 22+ and npm are required. From this directory run `npm ci`. Set `DATABASE_URL` to a PostgreSQL database URL and `BOOKORA_ADMIN_PASSWORD` to a secret of at least 12 characters; then run `npm run server` and `npm run dev` in separate terminals. Without `DATABASE_URL`, the server uses an embedded PostgreSQL-compatible PGlite database in `.data/` for local experimentation only. `BOOKORA_DEMO=1 npm run server` opts into sample data and a known demo password (`admin` / `demo-bookora-2026`); never use that mode for real data. A browser only calls relative `/api` URLs through Vite's development proxy. In demo mode visit `/book` to search and create fictional test reservations; they share inventory with the staff dashboard. The guest routes are disabled outside demo mode. **No payment is taken and no email is sent**; the preview must never be treated as a real booking service.

Run `npm test` and `npm run build` to test and type-check. The API tests use embedded PGlite, **not** a real PostgreSQL server or independent server processes. The development server, prototype authentication and local database configuration must not be exposed publicly. This project does not claim compliance with payment or privacy regulations.

## Product reference and roadmap

[QloApps](https://github.com/qloapps/qloapps) is a functional reference for its hotel website, booking engine and property-management workflows. This application is independently implemented; no QloApps code or assets are incorporated. QloApps core and its modules use their own licenses, which must be reviewed separately before any future code reuse. The guest hero image is AI-generated for this prototype.

The guest demo is only an early journey test. Before opening bookings to real guests, implement and test staff roles, policies and accessible flows, reliable real PostgreSQL concurrency, privacy and abuse controls, confirmation emails, proper pricing/taxes and payment states. Paystack initialization, server-side verification, verified webhooks, idempotency, reconciliation, refunds and reception payment recording remain unimplemented. A PMS also needs housekeeping, room management, reporting and other operational workflows.
