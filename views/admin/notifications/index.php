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
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">notifications_active</span> Student Notifications</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Students ko email aur SMS bhejein. Pehle setup save karein, phir message likh kar send karein.
    </p>
  </div>
</div>

<?php if ($msg = flash('error')): ?><div class="admin-alert admin-alert-error"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('success')): ?><div class="admin-alert admin-alert-success"><?= e($msg) ?></div><?php endif; ?>

<div class="stat-grid notify-stat-grid">
  <div class="stat-card"><span>Students</span><strong><?= (int) ($stats['total'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>With Email</span><strong style="color:#16a34a"><?= (int) ($stats['with_email'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>With Mobile</span><strong style="color:#7c3aed"><?= (int) ($stats['with_mobile'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>SMS Gateway</span><strong style="font-size:0.95rem;color:<?= !empty($smsConfigured) ? '#16a34a' : '#b45309' ?>"><?= e($smsStatus ?? 'Not configured') ?></strong></div>
</div>

<form method="post" action="<?= site_url('admin/notifications/setup') ?>" class="card notify-setup-card">
  <?= csrf_field() ?>
  <h3>1. Email &amp; SMS Setup</h3>
  <p class="notify-help">Ye settings save ho kar future notifications ke liye use hongi.</p>

  <h4 class="notify-section-label">Email Sender</h4>
  <div class="form-grid">
    <div><label>From Name</label><input name="mail_from_name" value="<?= e($mailFromName) ?>" required></div>
    <div><label>From Email</label><input type="email" name="mail_from" value="<?= e($mailFrom) ?>" placeholder="manerpvtiti@gmail.com"></div>
  </div>

  <h4 class="notify-section-label">SMS Gateway</h4>
  <div class="form-grid">
    <div>
      <label>Enable SMS</label>
      <select name="sms_enabled">
        <option value="0" <?= ($sms['sms_enabled'] ?? '0') !== '1' ? 'selected' : '' ?>>Disabled</option>
        <option value="1" <?= ($sms['sms_enabled'] ?? '0') === '1' ? 'selected' : '' ?>>Enabled</option>
      </select>
    </div>
    <div>
      <label>Provider</label>
      <select name="sms_provider" id="smsProvider">
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
    <div><label>Custom Method</label>
      <select name="sms_custom_method">
        <option value="GET" <?= strtoupper($sms['sms_custom_method'] ?? 'GET') === 'GET' ? 'selected' : '' ?>>GET</option>
        <option value="POST" <?= strtoupper($sms['sms_custom_method'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
      </select>
    </div>
    <div style="grid-column:1/-1"><label>Custom API URL</label><input name="sms_custom_url" value="<?= e($sms['sms_custom_url'] ?? '') ?>" placeholder="https://...?mobile={mobile91}&message={message_encoded}"></div>
  </div>

  <h4 class="notify-section-label">Default Templates (optional)</h4>
  <div class="form-grid">
    <div style="grid-column:1/-1"><label>Default Email Subject</label><input name="student_notify_subject" value="<?= e($notifySubject ?: $defaultSubject) ?>"></div>
    <div style="grid-column:1/-1"><label>Default Email Body</label><textarea name="student_notify_email_body" rows="3"><?= e($notifyEmailBody ?: $defaultEmailBody) ?></textarea></div>
    <div style="grid-column:1/-1"><label>Default SMS Template</label><textarea name="student_notify_sms_body" rows="2" maxlength="500"><?= e($defaultSmsBody) ?></textarea></div>
  </div>
  <p class="notify-vars">Variables: <code>{name}</code> <code>{father_name}</code> <code>{trade}</code> <code>{session}</code> <code>{enrollment}</code> <code>{mobile}</code> <code>{institute}</code> <code>{phone}</code> <code>{message}</code></p>
  <button type="submit" class="btn btn-outline">Save Setup</button>
</form>

<form method="get" class="card filter-bar notify-filter-bar">
  <div>
    <label>Session</label>
    <select name="session" onchange="this.form.submit()">
      <option value="">All Sessions</option>
      <?php foreach ($sessions ?? [] as $sn): ?>
      <option value="<?= e($sn) ?>" <?= ($filterSession ?? '') === $sn ? 'selected' : '' ?>><?= e(session_short_label($sn)) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</form>

<form method="post" action="<?= site_url('admin/notifications/send') ?>" id="notifySendForm" class="card">
  <?= csrf_field() ?>
  <h3>2. Send Notification</h3>

  <div class="form-grid">
    <div>
      <label>Send Via *</label>
      <select name="notify_channel" id="notifyChannel" required>
        <option value="sms" <?= !empty($smsConfigured) ? 'selected' : '' ?>>SMS only</option>
        <option value="email" <?= empty($smsConfigured) ? 'selected' : '' ?>>Email only</option>
        <option value="both">Email + SMS</option>
      </select>
    </div>
    <div id="emailSubjectWrap">
      <label>Email Subject *</label>
      <input name="notify_subject" id="notifySubject" value="<?= e($notifySubject ?: $defaultSubject) ?>">
    </div>
  </div>

  <div style="margin-top:1rem">
    <label>Message *</label>
    <textarea name="notify_message" id="notifyMessage" rows="4" required placeholder="Apna message yahan likhein..."></textarea>
  </div>

  <div id="emailBodyWrap" style="margin-top:1rem">
    <label>Email Body (optional — blank = message use hoga)</label>
    <textarea name="notify_email_body" rows="3"><?= e($notifyEmailBody ?: $defaultEmailBody) ?></textarea>
  </div>

  <div id="smsBodyWrap" style="margin-top:1rem">
    <label>SMS Template *</label>
    <textarea name="notify_sms_body" id="notifySmsBody" rows="2" maxlength="500"><?= e($defaultSmsBody) ?></textarea>
    <p class="notify-vars">Preview: <span id="smsPreview" class="notify-preview"></span></p>
  </div>

  <div class="notify-send-head">
    <div>
      <h4 style="margin:0 0 0.25rem">Select Students</h4>
      <p class="notify-help" style="margin:0">Mobile par SMS, email par mail jayega.</p>
    </div>
    <button type="submit" class="btn btn-primary" id="notifySendBtn">Send Notifications</button>
  </div>

  <?php if (empty($students)): ?>
  <p class="notify-help">Is filter ke liye koi active student nahi mila.</p>
  <?php else: ?>
  <label class="notify-select-all">
    <input type="checkbox" id="selectAllStudents"> Select all (<?= count($students) ?>)
  </label>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:40px"></th>
          <th>Student</th>
          <th>Mobile</th>
          <th>Email</th>
          <th>Trade</th>
          <th>Session</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($students as $s):
          $hasMobile = \App\Core\Sms::normalizeMobile((string) ($s['mobile'] ?? '')) !== '';
          $hasEmail = trim((string) ($s['email'] ?? '')) !== '' && filter_var($s['email'], FILTER_VALIDATE_EMAIL);
      ?>
      <tr>
        <td><input type="checkbox" name="students[]" value="<?= (int) $s['id'] ?>" class="notify-student-check"></td>
        <td><strong><?= e($s['student_name']) ?></strong></td>
        <td><?= $hasMobile ? e(format_mobile($s['mobile'] ?? '')) : '—' ?></td>
        <td><?= $hasEmail ? e($s['email']) : '—' ?></td>
        <td><?= e($s['trade'] ?? '—') ?></td>
        <td><?= e(session_short_label($s['session'] ?? '') ?: '—') ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</form>

<script src="<?= asset('js/student-notify.js') ?>?v=<?= (int) @filemtime(base_path('assets/js/student-notify.js')) ?>"></script>
<script>
window.NOTIFY_SMS_CONFIGURED = <?= !empty($smsConfigured) ? 'true' : 'false' ?>;
window.NOTIFY_INSTITUTE = <?= json_encode($mailFromName) ?>;
</script>
