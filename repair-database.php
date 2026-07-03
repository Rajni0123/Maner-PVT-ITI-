<?php
/**
 * ONE-TIME database repair — creates missing tables (site_settings, etc.)
 * Open in browser → Repair → DELETE this file immediately after success.
 */
require __DIR__ . '/bootstrap.php';

use App\Core\Database;
use App\Core\DatabaseRepair;

$done = false;
$error = '';
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $result = DatabaseRepair::runFullRepair();
        $messages = $result['messages'] ?? [];
        $done = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$dbName = config('db_name', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Repair Database | Maner ITI</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 560px; margin: 3rem auto; padding: 0 1rem; color: #111; }
    .card { border: 1px solid #cbd5e1; padding: 1.5rem; border-radius: 8px; background: #fff; }
    h1 { font-size: 1.35rem; margin: 0 0 0.75rem; }
    p, li { line-height: 1.5; color: #475569; }
    .err { background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
    .ok { background: #dcfce7; color: #166534; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; }
    ul { margin: 0.5rem 0 0; padding-left: 1.25rem; }
    button, .btn { display: inline-block; background: #131b2e; color: #fff; border: none; padding: 0.7rem 1.2rem; font-weight: 700; border-radius: 6px; cursor: pointer; text-decoration: none; }
    code { background: #f1f5f9; padding: 0.1rem 0.35rem; border-radius: 4px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Repair Database</h1>
    <p>Database: <code><?= htmlspecialchars($dbName) ?></code></p>

    <?php if ($error): ?>
    <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($done): ?>
    <div class="ok"><strong>Repair completed.</strong> Missing tables (including <code>site_settings</code>) are created.</div>
    <ul>
      <?php foreach ($messages as $msg): ?>
      <li><?= htmlspecialchars($msg) ?></li>
      <?php endforeach; ?>
    </ul>
    <p style="margin-top:1.25rem"><a class="btn" href="<?= htmlspecialchars(site_url()) ?>">Open Website</a>
    &nbsp; <a class="btn" href="<?= htmlspecialchars(site_url('admin/login')) ?>" style="background:#855300">Admin Login</a></p>
    <p><strong>Delete <code>repair-database.php</code> from server now.</strong></p>
    <?php else: ?>
    <p>Use this if you see errors like <code>Table site_settings doesn't exist</code>. Safe to run — existing data is kept.</p>
    <form method="post" style="margin-top:1.25rem">
      <button type="submit">Run Database Repair</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
