<?php
$defaultSubject = 'Notice from {institute}';
$defaultEmailBody = "This is an important update from {institute}.\n\n{message}\n\nTrade: {trade}\nSession: {session}";
$defaultSmsBody = $notifySmsBody ?: 'Dear {name}, {message} - {institute}. Call {phone}';
if ($mailFromName === 'Maner Pvt ITI') {
    $mailFromName = 'Maner Private ITI';
}
$sms = $smsSettings ?? [];
?>
<div class="admin-page-header">
  <div>
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">settings</span> Notification Configuration</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Email sender aur SMS gateway yahan setup karein. Send karne ke liye Send tab use karein.
    </p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/notifications') ?>" class="btn btn-primary btn-sm">Send Notification</a>
  </div>
</div>

<?php if ($msg = flash('error')): ?><div class="admin-alert admin-alert-error"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('success')): ?><div class="admin-alert admin-alert-success"><?= e($msg) ?></div><?php endif; ?>

<?php $notifyTab = 'setup'; require base_path('views/partials/admin-notify-tabs.php'); ?>

<div class="stat-grid notify-stat-grid">
  <div class="stat-card"><span>Email From</span><strong style="font-size:0.9rem"><?= e($mailFrom ?: 'Not set') ?></strong></div>
  <div class="stat-card"><span>SMS Gateway</span><strong style="font-size:0.95rem;color:<?= !empty($smsConfigured) ? '#16a34a' : '#b45309' ?>"><?= e($smsStatus ?? 'Not configured') ?></strong></div>
  <div class="stat-card"><span>Provider</span><strong style="font-size:0.9rem"><?= e($sms['sms_provider'] ?: '—') ?></strong></div>
  <div class="stat-card"><span>SMS Enabled</span><strong style="color:<?= ($sms['sms_enabled'] ?? '0') === '1' ? '#16a34a' : '#b45309' ?>"><?= ($sms['sms_enabled'] ?? '0') === '1' ? 'Yes' : 'No' ?></strong></div>
</div>

<form method="post" action="<?= site_url('admin/notifications/setup') ?>" class="card notify-setup-card">
  <?= csrf_field() ?>

  <h3>Email Sender</h3>
  <p class="notify-help">Student notifications ke liye sender details.</p>
  <div class="form-grid">
    <div><label>From Name *</label><input name="mail_from_name" value="<?= e($mailFromName) ?>" required></div>
    <div><label>From Email</label><input type="email" name="mail_from" value="<?= e($mailFrom) ?>" placeholder="manerpvtiti@gmail.com"></div>
  </div>

  <h3 class="notify-block-title">SMS Gateway</h3>
  <p class="notify-help">Fast2SMS, MSG91, TextLocal, ya Custom API configure karein.</p>
  <div class="form-grid">
    <div>
      <label>Enable SMS</label>
      <select name="sms_enabled" class="notify-select">
        <option value="0" <?= ($sms['sms_enabled'] ?? '0') !== '1' ? 'selected' : '' ?>>Disabled</option>
        <option value="1" <?= ($sms['sms_enabled'] ?? '0') === '1' ? 'selected' : '' ?>>Enabled</option>
      </select>
    </div>
    <div>
      <label>Provider</label>
      <select name="sms_provider" class="notify-select">
        <option value="">— Select —</option>
        <?php foreach (['fast2sms' => 'Fast2SMS', 'msg91' => 'MSG91', 'textlocal' => 'TextLocal', 'custom' => 'Custom API'] as $val => $label): ?>
        <option value="<?= e($val) ?>" <?= ($sms['sms_provider'] ?? '') === $val ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>API Key</label><input name="sms_api_key" value="<?= e($sms['sms_api_key'] ?? '') ?>" autocomplete="off"></div>
    <div><label>Sender ID</label><input name="sms_sender_id" value="<?= e($sms['sms_sender_id'] ?? '') ?>" placeholder="MANERI"></div>
    <div><label>Route</label><input name="sms_route" value="<?= e($sms['sms_route'] ?? 'q') ?>" placeholder="q / dlt / 4"></div>
    <div><label>DLT Template ID</label><input name="sms_dlt_template_id" value="<?= e($sms['sms_dlt_template_id'] ?? '') ?>"></div>
    <div><label>Country Code</label><input name="sms_country_code" value="<?= e($sms['sms_country_code'] ?? '91') ?>"></div>
    <div>
      <label>Custom Method</label>
      <select name="sms_custom_method" class="notify-select">
        <option value="GET" <?= strtoupper($sms['sms_custom_method'] ?? 'GET') === 'GET' ? 'selected' : '' ?>>GET</option>
        <option value="POST" <?= strtoupper($sms['sms_custom_method'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
      </select>
    </div>
    <div style="grid-column:1/-1"><label>Custom API URL</label><input name="sms_custom_url" value="<?= e($sms['sms_custom_url'] ?? '') ?>" placeholder="https://...?mobile={mobile91}&message={message_encoded}"></div>
  </div>

  <h3 class="notify-block-title">Default Templates</h3>
  <p class="notify-help">Send page par ye defaults auto-fill honge.</p>
  <div class="form-grid">
    <div style="grid-column:1/-1"><label>Default Email Subject</label><input name="student_notify_subject" value="<?= e($notifySubject ?: $defaultSubject) ?>"></div>
    <div style="grid-column:1/-1"><label>Default Email Body</label><textarea name="student_notify_email_body" rows="3"><?= e($notifyEmailBody ?: $defaultEmailBody) ?></textarea></div>
    <div style="grid-column:1/-1"><label>Default SMS Template</label><textarea name="student_notify_sms_body" rows="2" maxlength="500"><?= e($defaultSmsBody) ?></textarea></div>
  </div>
  <p class="notify-vars">Variables: <code>{name}</code> <code>{father_name}</code> <code>{trade}</code> <code>{session}</code> <code>{enrollment}</code> <code>{mobile}</code> <code>{institute}</code> <code>{phone}</code> <code>{message}</code></p>

  <div class="notify-setup-actions">
    <button type="submit" class="btn btn-primary">Save Configuration</button>
    <a href="<?= site_url('admin/notifications') ?>" class="btn btn-outline">Go to Send</a>
  </div>
</form>
