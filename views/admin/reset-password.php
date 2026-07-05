<div class="login-brand">
  <div class="login-brand-icon">MP</div>
  <h2>New Password</h2>
  <p class="login-sub">Create a secure password</p>
</div>

<?php if ($msg = flash('error')): ?>
<div class="admin-alert admin-alert-error"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/reset-password') ?>" class="login-form">
  <?= csrf_field() ?>
  <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
  <div class="login-field">
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" placeholder="Minimum 6 characters" required minlength="6" autocomplete="new-password">
  </div>
  <div class="login-field">
    <label for="password_confirm">Confirm Password</label>
    <input type="password" id="password_confirm" name="password_confirm" placeholder="Re-enter password" required minlength="6" autocomplete="new-password">
  </div>
  <button type="submit" class="btn btn-primary login-submit">Update Password</button>
</form>

<p class="login-back-link"><a href="<?= site_url('admin/login') ?>">← Back to Login</a></p>
