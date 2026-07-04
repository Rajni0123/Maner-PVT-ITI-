<?php
$u = $user;
$sessionUser = \App\Core\Auth::user();
$initials = '';
foreach (preg_split('/\s+/', trim($u['name'] ?? '')) as $part) {
    if ($part !== '') {
        $initials .= strtoupper($part[0]);
    }
}
if ($initials === '') {
    $initials = 'AD';
}
$hasAvatar = !empty($u['avatar']) && upload_exists($u['avatar']);
?>
<div class="admin-page-header">
  <h1>My Profile</h1>
  <p style="margin:0;color:var(--admin-on-surface-variant);font-size:0.9rem">Update your admin account details</p>
</div>

<div class="grid-2">
  <form method="post" action="<?= site_url('admin/profile') ?>" enctype="multipart/form-data" class="card">
    <h3>Account Details</h3>
    <?= csrf_field() ?>

    <div class="form-grid">
      <div><label>Full Name *</label><input name="name" value="<?= e($u['name'] ?? '') ?>" required></div>
      <div><label>Email *</label><input type="email" name="email" value="<?= e($u['email'] ?? '') ?>" required></div>
      <div><label>Phone</label><input name="phone" value="<?= e($u['phone'] ?? '') ?>" maxlength="15"></div>
      <div><label>Role</label><input value="<?= e(ucfirst($u['role'] ?? 'admin')) ?>" disabled></div>
    </div>

    <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Change Password</h4>
    <p style="margin:0 0 0.75rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">Leave blank to keep current password.</p>
    <div class="form-grid">
      <div><label>New Password</label><input type="password" name="password" autocomplete="new-password" minlength="6"></div>
      <div><label>Confirm Password</label><input type="password" name="password_confirm" autocomplete="new-password" minlength="6"></div>
    </div>

    <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Profile Photo</h4>
    <input type="file" name="avatar" accept="image/*">

    <button type="submit" class="btn btn-primary" style="margin-top:1.5rem">Save Profile</button>
  </form>

  <div class="card admin-profile-preview">
    <h3>Preview</h3>
    <div class="admin-profile-preview-body">
      <?php if ($hasAvatar): ?>
      <img src="<?= e(upload_url($u['avatar'])) ?>" alt="Profile photo" class="admin-profile-avatar-img">
      <?php else: ?>
      <div class="admin-profile-avatar"><?= e($initials) ?></div>
      <?php endif; ?>
      <div>
        <p class="admin-profile-name"><?= e($sessionUser['name'] ?? $u['name']) ?></p>
        <p class="admin-profile-role"><?= e(strtoupper($sessionUser['role'] ?? $u['role'] ?? 'admin')) ?></p>
        <p class="admin-profile-email"><?= e($sessionUser['email'] ?? $u['email']) ?></p>
        <?php if (!empty($u['phone'])): ?>
        <p class="admin-profile-phone"><?= e(format_mobile($u['phone'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
