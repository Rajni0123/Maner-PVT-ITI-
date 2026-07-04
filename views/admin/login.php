<h2>Maner Private ITI</h2>
<p class="login-sub">Admin Portal</p>
<?php if ($msg = flash('error')): ?><div class="admin-alert admin-alert-error"><?= e($msg) ?></div><?php endif; ?>
<form method="post" action="<?= site_url('admin/login') ?>">
  <?= csrf_field() ?>
  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="admin@iticollege.edu" required class="mb-4">
  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Your install password" required>
  <p class="login-hint">
    Use email &amp; password from <strong>install.php</strong> step.<br>
    Default: <code>admin@iticollege.edu</code> / <code>admin123</code><br>
    Not working? Open <a href="<?= site_url('reset-admin.php') ?>" class="text-on-tertiary-container hover:underline">reset-admin.php</a>
  </p>
  <button type="submit" class="btn btn-secondary w-full">Login</button>
</form>
