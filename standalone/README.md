# Bookora standalone — early foundation

This separate TypeScript/React, Node.js and PostgreSQL application does not depend on WordPress. **It is an early prototype, not a full hotel management system or production-ready service. Do not enter real guest data or accept live payments.** It does not migrate data from the WordPress plugin.

Current scope: a single-property staff dashboard; one administrator login; physical rooms with a GHS nightly rate; reservations with guest details, date/capacity validation and a fixed-rate snapshot; check-in, checkout and cancellation. Checkout dates are exclusive. Room-level database locks coordinate competing reservation writers that use this API. The overview and list only use the most recent 200 reservations, so their totals and occupancy are not complete once there are more than 200. Prices exclude taxes and extras. There is no room-rate editing yet.

Not yet implemented: guest-facing website or booking flow; staff accounts and roles; Paystack or other online payments; reception payment recording, reconciliation and refunds; taxes, housekeeping, invoices, reports, notifications, audit trails and channel sync. Reservations and rooms cannot yet be edited or deleted. More work, real PostgreSQL concurrency and end-to-end tests, a security review, backups and deployment hardening are required before any real use.

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

## Node development

Node.js 22+ and npm are required. From this directory run `npm ci`. Set `DATABASE_URL` to a PostgreSQL database URL and `BOOKORA_ADMIN_PASSWORD` to a secret of at least 12 characters; then run `npm run server` and `npm run dev` in separate terminals. Without `DATABASE_URL`, the server uses an embedded PostgreSQL-compatible PGlite database in `.data/` for local experimentation only. `BOOKORA_DEMO=1 npm run server` opts into sample data and a known demo password (`admin` / `demo-bookora-2026`); never use that mode for real data. A browser only calls relative `/api` URLs through Vite's development proxy.

Run `npm test` and `npm run build` to test and type-check. The API tests use embedded PGlite, **not** a real PostgreSQL server or independent server processes. The development server, prototype authentication and local database configuration must not be exposed publicly. This project does not claim compliance with payment or privacy regulations.
