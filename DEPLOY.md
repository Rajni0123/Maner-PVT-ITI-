# PRODUCTION DEPLOY — Fix 404 Errors

## ⚠️ Why 404 happens on cPanel

Usually one of these:

1. **Old React `index.html` + `.htaccess`** still on server (routes to SPA, not PHP)
2. **`install.php` not run** — site redirects but install missing
3. **Wrong folder** — files uploaded to subfolder but domain points elsewhere
4. **Old `.htaccess`** has `RewriteRule ^ index.html` — MUST replace with PHP `.htaccess`

---

## ✅ Step-by-step (manerpvtiti.space)

### 1. Backup old site
Download current `public_html` folder backup from cPanel.

### 2. Clean root folder
In `public_html/` (or `manerpvtiti.space/`):

**DELETE or rename these old React/Node files:**
- Old `index.html` (React version) — **replace** with PHP package `index.html` (redirect stub)
- Old `assets/` folder (React JS bundles) — optional delete
- Old `server/` folder — optional (Node no longer needed)

**KEEP / UPLOAD from `maner-iti-php/`:**
```
index.php          ← MAIN entry (required)
index.html         ← redirect stub (included)
.htaccess          ← REPLACE old .htaccess completely
install.php
config.php
bootstrap.php
router.php
app/
views/
assets/
database/
uploads/           ← chmod 775
storage/           ← chmod 775
```

### 3. Replace `.htaccess`
**Important:** Delete old `.htaccess` content. Upload new `.htaccess` from PHP package.
Old SPA rule `RewriteRule ^ index.html` causes 404 on all PHP routes.

### 4. Disable Node.js app (optional)
cPanel → Setup Node.js App → Stop/Remove old Node app.
PHP site does not need Node.js.

### 5. Create MySQL database
cPanel → MySQL Databases → create DB + user → ALL PRIVILEGES

### 6. Run installer
Open: `https://manerpvtiti.space/install.php`

Fill:
- DB Host: `localhost`
- DB Name: your cPanel database name
- DB User: your cPanel database user
- DB Password: your password
- Admin email + password

Click **Install Now**

### 7. After install
- **Delete** `install.php` from server
- Test: `https://manerpvtiti.space/health` → should show `{"ok":true,...}`
- Test: `https://manerpvtiti.space/` → homepage
- Test: `https://manerpvtiti.space/admin/login` → admin login

### 8. Permissions
```
uploads/  → 755 or 775 (writable)
storage/  → 755 or 775 (writable)
```

---

## Subfolder install (if not at domain root)

If uploaded to `public_html/iti/`:

1. Open `config.php` and set:
   ```php
   'base_path' => 'iti',
   ```
2. Access site at: `https://yourdomain.com/iti/`
3. Run: `https://yourdomain.com/iti/install.php`

---

## Quick test URLs

| URL | Expected |
|-----|----------|
| `/health` | JSON `ok: true` |
| `/` | Home page |
| `/about` | About page |
| `/apply-admission` | Admission form |
| `/admin/login` | Admin login |

If `/health` works but `/about` is 404 → `.htaccess` problem.
If everything 404 → `index.php` not in correct folder or PHP not enabled.
If blank/white → run `install.php` first.

---

## Still 404?

1. cPanel → **MultiPHP Manager** → ensure PHP 8.0+ enabled for domain
2. Check **Error Log** in cPanel
3. Temporarily set in `config.php`: `'debug' => true` to see errors
4. Confirm `index.php` exists in same folder as `.htaccess`
