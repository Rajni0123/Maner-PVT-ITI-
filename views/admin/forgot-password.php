<?php if ($msg = flash('error')): ?>
<div class="admin-alert admin-alert-error"><?= e($msg) ?></div>
<?php endif; ?>
<?php if ($msg = flash('success')): ?>
<div class="admin-alert admin-alert-success"><?= e($msg) ?></div>
<?php endif; ?>

<p class="login-lead">Enter your admin email to receive a reset link.</p>

<form method="post" action="<?= site_url('admin/forgot-password') ?>" class="login-form">
  <?= csrf_field() ?>
  <div class="login-field">
    <label for="email">Admin Email</label>
    <input type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="admin@iticollege.edu" required autocomplete="email">
  </div>
  <button type="submit" class="btn btn-secondary login-submit">Send Reset Link</button>
</form>

<p class="login-back-link"><a href="<?= site_url('admin/login') ?>">← Back to Login</a></p>
