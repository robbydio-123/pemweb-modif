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

JS 11's optional Google Sign-In uses Google Identity Services. Create a Web
OAuth client in Google Cloud Console, add the site's origin to its authorized
JavaScript origins, and set `GOOGLE_CLIENT_ID` in the PHP environment. For the
local PHP server, set `$env:GOOGLE_CLIENT_ID="...apps.googleusercontent.com"`
in the PowerShell window before starting PHP. On an existing database, run
`JS 11/sql/03_google_login.sql` once; new databases include the Google subject
column in `JS 11/sql/02_users.sql`. Google sign-in creates customer accounts
only.

Vercel serves JS 10 through the `vercel-php` runtime at `api/index.php`. The
dispatcher only routes requests to existing PHP files inside the app's
`auth`, `anggota`, `buku`, and `penyewaan` directories. Static assets are
served from `JS 10/assets`. JS 10 routes are available at `/index.php`,
`/auth/...`, `/buku/...`, `/anggota/...`, and `/penyewaan/...`. The site root
redirects to the public home page at `/index.php`. Set the Vercel project's
`DATABASE_URL` to a reachable PostgreSQL database and run
`JS 10/sql/rental_motor.sql` and `JS 10/sql/02_users.sql` against it before
using the site. The Vercel project Node.js version is set to 22.x as required
by the PHP runtime.
