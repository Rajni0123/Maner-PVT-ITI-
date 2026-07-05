<?php require base_path('views/partials/login-brand.php'); ?>

<?php if ($msg = flash('error')): ?>
<div class="admin-alert admin-alert-error"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/login') ?>" class="login-form">
  <?= csrf_field() ?>
  <div class="login-field">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="admin@iticollege.edu" required autocomplete="username">
  </div>
  <div class="login-field">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
  </div>
  <div class="login-form-actions">
    <a href="<?= site_url('admin/forgot-password') ?>" class="login-forgot-link">Forgot password?</a>
  </div>
  <button type="submit" class="btn btn-primary login-submit">Login</button>
</form>
