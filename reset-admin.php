<?php
/**
 * ONE-TIME admin password reset.
 * Open in browser → set new password → DELETE this file immediately.
 * Only accessible from localhost for security.
 */
require __DIR__ . '/bootstrap.php';

use App\Core\Database;

$allowedIps = ['127.0.0.1', '::1'];
$clientIp = \App\Core\Security::clientIp();
if (!in_array($clientIp, $allowedIps, true)) {
    http_response_code(403);
    die('Access denied. This tool is only available from the server itself (localhost).');
}

$done = false;
$error = '';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $token = $_POST['_csrf'] ?? '';
        if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            throw new RuntimeException('Invalid security token. Refresh and try again.');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Enter a valid admin email.');
        }
        $pwdError = \App\Core\Security::validatePasswordStrength($password);
        if ($pwdError !== null) {
            throw new RuntimeException($pwdError);
        }
        if ($password !== $confirm) {
            throw new RuntimeException('Passwords do not match.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $existing = Database::fetch('SELECT id FROM users WHERE LOWER(email) = LOWER(?)', [$email]);

        if ($existing) {
            Database::update('users', [
                'password' => $hash,
                'is_active' => 1,
                'role' => 'admin',
            ], 'id = ?', [$existing['id']]);
        } else {
            Database::insert('users', [
                'email' => $email,
                'password' => $hash,
                'name' => 'Administrator',
                'role' => 'admin',
                'is_active' => 1,
            ]);
        }

        $done = true;
        unset($_SESSION['_csrf']);
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$userCount = 0;
try {
    $userCount = (int) (Database::fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0);
} catch (Throwable $e) {
    $error = $error ?: 'Database error. Check config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Admin Password</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="install-page">
  <div class="card" style="max-width:480px;margin:3rem auto;padding:2rem;">
    <h1>Reset Admin Password</h1>
    <p style="color:#64748b;margin-bottom:1rem;font-size:.9rem">
      Users in database: <strong><?= (int) $userCount ?></strong>
      <?php if ($userCount === 0): ?> — no admin found, this will create one.<?php endif; ?>
    </p>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <?php if ($done): ?>
      <div class="alert alert-success">
        <strong>Password updated!</strong><br>
        Email: <code><?= htmlspecialchars($email) ?></code><br>
        You can now <a href="<?= htmlspecialchars(site_url('admin/login')) ?>">login here</a>.
      </div>
      <p style="color:#dc2626;font-weight:700;margin-top:1rem">⚠ DELETE this file (reset-admin.php) from server NOW for security.</p>
    <?php else: ?>
      <form method="post">
        <?= csrf_field() ?>
        <label>Admin Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($email) ?>" placeholder="admin@example.com" required style="margin-bottom:1rem;width:100%" autocomplete="username">
        <label>New Password (min 8 chars, letters + numbers)</label>
        <input name="password" type="password" placeholder="Enter strong password" required minlength="8" style="margin-bottom:1rem;width:100%" autocomplete="new-password">
        <label>Confirm Password</label>
        <input name="confirm" type="password" placeholder="Confirm password" required minlength="8" style="margin-bottom:1.5rem;width:100%" autocomplete="new-password">
        <button type="submit" class="btn btn-primary" style="width:100%">Reset / Create Admin</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
