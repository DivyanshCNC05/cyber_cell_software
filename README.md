# Dewas Cyber Department - Complaint Management System

A lightweight PHP + MySQL app for managing cyber complaints, CEIR forms and reports. This repository now includes a `cybercell/` scaffold with a focused layout to make deployment and maintenance easier.

## Quick summary ✅
- **DB schema:** `cybercell/schema.sql` (copy of your updated schema)
- **App root:** `cybercell/public/` (open `cybercell/public/index.php` in your browser)
- **Config:** `cybercell/.env` (optional) or environment variables
- **Key includes:** `cybercell/includes/` (config, db, auth, helpers, reports)
- **Uploads:** `cybercell/uploads/` (contains `ceir_forms/` and `cyber_attachments/`)

---

## Setup (local / XAMPP) ⚙️
1. Place this project in your web server root (e.g., `htdocs`).
2. Import the database schema:
   - `mysql -u root -p < cybercell/schema.sql` or import via phpMyAdmin.
3. Configure database credentials:
   - Edit `cybercell/.env` or set environment variables `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
4. Seed an admin user:
   - Using helper script (recommended): `php scripts/create_admin.php admin "YourSecurePassword!"`
   - Or manually: generate a password hash and INSERT into the `users` table (see the earlier example in this README).
5. Ensure `cybercell/uploads/` is writable by the webserver (for local uploads).
6. Visit `http://localhost/cybercell/public/index.php` and login.

> Tip: For production, host `cybercell/public/` as your site root and secure `.env` (never commit secrets).

---

## Roles (as seeded by the schema) 🔐
Be aware the role names are **case-sensitive** and match the schema values:
- `ADMIN` — full access (admin dashboard + reports)
- `CYBER_USER` — manage cyber (3-user) complaints
- `CEIR_USER` — manage CEIR physical forms

---

## Where to find important files 📁
- `cybercell/schema.sql` — DB schema
- `cybercell/.env` — example environment config
- `cybercell/includes/config.php` — reads `.env`, sets constants
- `cybercell/includes/db.php` — PDO connection
- `cybercell/includes/auth.php` — login/session helpers
- `cybercell/includes/helpers.php` — flash, CSRF, sanitize, logging
- `cybercell/includes/reports.php` — report helpers
- `cybercell/public/` — login, dashboard, forms, reports, logout
- `cybercell/public/assets/` — `css/` and `js/` (basic styling)
- `cybercell/uploads/` — `ceir_forms/`, `cyber_attachments/`

---

## UI & Development notes 🎨
- The UI is functional and minimal: Bootstrap CDN is used for login/dashboard; other pages are basic HTML forms.
- Recommended next steps: add a shared layout (header/nav/footer), apply consistent styling, and port legacy pages into `cybercell/public/`.
- For file storage you can use local uploads (already scaffolded) or integrate S3 and store S3 keys in `.env`.

---

## Security & production checklist ⚠️
- Use HTTPS and set secure cookie flags.
- Move secrets out of the repository and into secure environment variables.
- Restrict file uploads by type/size and store outside of webroot or protect with `.htaccess` (already added to `uploads/`).
- Review permissions for `uploads/` and any temporary directories.

---

If you want, I can:
1. Move and adapt the original `public/complaints/` and `public/users/` pages into `cybercell/public/` and apply a shared layout. ✅
2. Improve the UI styling and add a shared header/footer. 🎨
3. Implement local upload validation or S3 integration. ☁️

Pick an item and I'll proceed with a short plan and the changes.

