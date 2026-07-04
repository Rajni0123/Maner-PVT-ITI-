<?php
/**
 * ONE-TIME admin password reset.
 * Open in browser → set new password → DELETE this file immediately.
 */
require __DIR__ . '/bootstrap.php';

use App\Core\Database;

$done = false;
$error = '';
$email = trim($_POST['email'] ?? 'admin@iticollege.edu');
$password = $_POST['password'] ?? 'admin123';
$confirm = $_POST['confirm'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (strlen($password) < 6) {
            throw new RuntimeException('Password must be at least 6 characters.');
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
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$userCount = 0;
try {
    $userCount = (int) (Database::fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0);
} catch (Throwable $e) {
    $error = $error ?: 'Database error: ' . $e->getMessage() . ' — check config.php';
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
      Users in database: <strong><?= $userCount ?></strong>
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
        <label>Admin Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($email) ?>" required style="margin-bottom:1rem;width:100%">
        <label>New Password</label>
        <input name="password" type="password" value="admin123" required style="margin-bottom:1rem;width:100%">
        <label>Confirm Password</label>
        <input name="confirm" type="password" value="admin123" required style="margin-bottom:1.5rem;width:100%">
        <button type="submit" class="btn btn-primary" style="width:100%">Reset / Create Admin</button>
      </form>
      <p style="margin-top:1rem;font-size:.85rem;color:#64748b">
        Default after install: <code>admin@iticollege.edu</code> / <code>admin123</code><br>
        Use the email &amp; password you entered in <code>install.php</code>.
      </p>
    <?php endif; ?>
  </div>
</body>
</html>
