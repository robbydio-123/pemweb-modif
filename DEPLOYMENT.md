# Deployment and database setup

JS 8 and JS 9 use PostgreSQL. JS 9 accepts the Neon integration's
`DATABASE_URL` or these individual environment variables:

- `DB_HOST`
- `DB_PORT` (defaults to `5432`)
- `DB_NAME` (defaults to `rental_motor`)
- `DB_USER` (defaults to `postgres`)
- `DB_PASSWORD` (required if `DATABASE_URL` is not set)
- `DB_SSLMODE` (defaults to `prefer`; set to `require` for Neon)

The Vercel Neon integration provisions and connects a database for Production
and Preview. For another PostgreSQL host, create the database and set the
variables above. Run the schema in the matching version:
`JS 8/sql/rental_motor.sql` or `JS 9/sql/rental_motor.sql`. The schema scripts
can be pasted into the database SQL editor. Never commit real database
credentials.

The PHP runtime must have the `pdo_pgsql` extension enabled. On XAMPP, enable
`extension=pdo_pgsql` in `php.ini` and restart Apache.

Vercel serves JS 9 through the `vercel-php` runtime at `api/index.php`. The
dispatcher only routes requests to existing PHP files inside the app's
`anggota`, `buku`, and `penyewaan` directories. Static assets are served from
`JS 9/assets`. JS 9 routes are available at `/index.php`, `/buku/...`,
`/anggota/...`, and `/penyewaan/...`. The site root serves the JS 9 sign-in
page. The Vercel project Node.js version is set to 22.x as required by the PHP
runtime.
