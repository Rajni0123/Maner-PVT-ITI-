<?php
$defaultSubject = 'Notice from {institute}';
$defaultEmailBody = "This is an important update from {institute}.\n\n{message}\n\nTrade: {trade}\nSession: {session}";
$defaultSmsBody = $notifySmsBody ?: 'Dear {name}, {message} - {institute}. Call {phone}';
if ($mailFromName === 'Maner Pvt ITI') {
    $mailFromName = 'Maner Private ITI';
}
?>
<div class="admin-page-header">
  <div>
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">send</span> Send Notification</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Students select karke email ya SMS bhejein. Setup ke liye Configuration tab use karein.
    </p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/notifications?section=setup') ?>" class="btn btn-outline btn-sm">Configuration</a>
  </div>
</div>

<?php if ($msg = flash('error')): ?><div class="admin-alert admin-alert-error"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('success')): ?><div class="admin-alert admin-alert-success"><?= e($msg) ?></div><?php endif; ?>

<?php $notifyTab = 'send'; require base_path('views/partials/admin-notify-tabs.php'); ?>

<?php if (empty($smsConfigured)): ?>
<div class="admin-alert" style="margin-bottom:1rem">
  SMS abhi configured nahi hai. <a href="<?= site_url('admin/notifications?section=setup') ?>">Configuration</a> se SMS gateway setup karein.
</div>
<?php endif; ?>

<div class="stat-grid notify-stat-grid">
  <div class="stat-card"><span>Students</span><strong><?= (int) ($stats['total'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>With Email</span><strong style="color:#16a34a"><?= (int) ($stats['with_email'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>With Mobile</span><strong style="color:#7c3aed"><?= (int) ($stats['with_mobile'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>SMS Status</span><strong style="font-size:0.95rem;color:<?= !empty($smsConfigured) ? '#16a34a' : '#b45309' ?>"><?= e($smsStatus ?? 'Not configured') ?></strong></div>
</div>

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

  <div class="form-grid notify-channel-grid">
    <div>
      <label for="notifyChannel">Send Via *</label>
      <select name="notify_channel" id="notifyChannel" required class="notify-select">
        <option value="sms" <?= !empty($smsConfigured) ? 'selected' : '' ?>>SMS only</option>
        <option value="email" <?= empty($smsConfigured) ? 'selected' : '' ?>>Email only</option>
        <option value="both">Email + SMS</option>
      </select>
    </div>
    <div id="emailSubjectWrap">
      <label for="notifySubject">Email Subject *</label>
      <input name="notify_subject" id="notifySubject" value="<?= e($notifySubject ?: $defaultSubject) ?>">
    </div>
  </div>

  <div style="margin-top:1rem">
    <label for="notifyMessage">Message *</label>
    <textarea name="notify_message" id="notifyMessage" rows="4" required placeholder="Apna message yahan likhein..."></textarea>
  </div>

  <div id="emailBodyWrap" style="margin-top:1rem">
    <label>Email Body (optional)</label>
    <textarea name="notify_email_body" rows="3"><?= e($notifyEmailBody ?: $defaultEmailBody) ?></textarea>
  </div>

  <div id="smsBodyWrap" style="margin-top:1rem">
    <label for="notifySmsBody">SMS Template *</label>
    <textarea name="notify_sms_body" id="notifySmsBody" rows="2" maxlength="500"><?= e($defaultSmsBody) ?></textarea>
    <p class="notify-vars">Preview: <span id="smsPreview" class="notify-preview"></span></p>
  </div>

  <div class="notify-send-head">
    <div>
      <h3 style="margin:0 0 0.25rem">Select Students</h3>
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
