# Maner Pvt ITI — Pure PHP Website

**100% PHP** — no Node.js, no React, no build step. Runs on any **cPanel shared hosting**.

## Features Included

### Public Website
- Home, About, Trades, Trade Detail
- Admission Process, **Online Admission Form** (all fields)
- Fee Structure, Faculty, Gallery, Notices, Results, Contact
- UIDAI duplicate check, file uploads, **A4 print form**

### Admin Panel (`/admin/login`)
- Dashboard with stats
- **Admissions** — view all fields, approve/reject, auto-create student, print form, CSV export
- **Students** — list, edit, enrollment number
- **Fees** — create, collect payment, print receipt
- **Notices, Results, Gallery** — CRUD with file upload
- **Sessions** — manage academic sessions
- **Settings** — header, SEO, principal message
- **Contact messages** inbox

---

## cPanel Deployment (Step by Step)

### 1. Create MySQL Database
1. cPanel → **MySQL Databases**
2. Create database: e.g. `ecowells_maneriti`
3. Create user + password, add user to database with **ALL PRIVILEGES**

### 2. Upload Files
Upload entire `maner-iti-php` folder contents to:
- `public_html/` (main domain), OR
- `public_html/subfolder/`

**Important folders:**
```
index.php
install.php
config.php
.htaccess
app/
views/
assets/
uploads/   ← chmod 755 or 775 (writable)
storage/   ← chmod 755 or 775 (writable)
database/
```

### 3. Set Folder Permissions
- `uploads/` → **755** or **775** (must be writable for photos/documents)
- `storage/` → **755** or **775**

### 4. Run Installer
Open in browser:
```
https://yourdomain.com/install.php
```
Enter MySQL details + admin email/password → **Install Now**

### 5. After Install
- **Delete** `install.php` (security)
- Login: `https://yourdomain.com/admin/login`
- Default: `admin@iticollege.edu` / `admin123` (change after login)

### 6. Replace Old Node Site (optional)
If replacing `manerpvtiti.space`:
1. Backup old files
2. Upload PHP site to `public_html/`
3. Remove/disable Node.js app in cPanel (no longer needed)
4. Point domain to PHP files only

---

## Local Testing (XAMPP/WAMP)

1. Copy folder to `htdocs/maner-iti-php`
2. Create MySQL database `maner_iti`
3. Open `http://localhost/maner-iti-php/install.php`
4. Configure and install

---

## File Structure

```
maner-iti-php/
├── index.php          # Front controller (all routes)
├── install.php        # One-time setup
├── config.php         # DB credentials
├── .htaccess          # URL rewriting
├── app/
│   ├── Core/          # Database, Auth, Router, Upload
│   ├── Controllers/   # All page logic
│   └── Models/        # SiteData helper
├── views/             # HTML templates
├── assets/css/        # Styles
├── uploads/           # Uploaded files
└── database/schema.sql
```

---

## Admin URLs

| URL | Purpose |
|-----|---------|
| `/admin/login` | Login |
| `/admin` | Dashboard |
| `/admin/admissions` | Manage applications |
| `/admin/students` | Student records |
| `/admin/fees` | Fee management |
| `/admin/notices` | Notice board |
| `/admin/gallery` | Photo gallery |
| `/admin/settings` | Site settings |

---

## Notes

- **No npm, no build** — upload and run
- All admission fields saved and shown in admin (no N/A bug)
- Print form opens as PHP page (no blank window issue)
- Uses PHP sessions for admin auth (no JWT complexity)
- MySQL required (standard on all cPanel hosting)

---

## Future Enhancements (can be added)
- Library module
- Staff permissions
- Trade CMS editor
- Faculty CMS
- Fee installments (EMI)

Built for **Maner Pvt ITI** — Patna, Bihar.
