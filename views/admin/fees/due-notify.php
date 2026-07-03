<?php
$students = $students ?? [];
$stats = $stats ?? ['total_students' => 0, 'with_email' => 0, 'with_mobile' => 0, 'total_due' => 0];
$mailFrom = $mailFrom ?? '';
$mailFromName = $mailFromName ?? 'Maner Private ITI';
$mailSubject = $mailSubject ?? '';
$mailMessage = $mailMessage ?? '';
$smsMessage = $smsMessage ?? 'Dear {name}, fee due {due} for {trade} at {institute}. Please pay soon. Call {phone}';
$smsConfigured = $smsConfigured ?? false;
$smsStatus = $smsStatus ?? 'Not configured';
if ($mailFromName === 'Maner Pvt ITI') {
    $mailFromName = 'Maner Private ITI';
}
$defaultSubject = 'Fee Payment Reminder / शुल्क भुगतान अनुस्मारक — ' . $mailFromName;
$defaultMessage = "कृपया अपना बकाया शुल्क यथाशीघ्र जमा करें।\nPlease pay your pending fees at the earliest to continue your training without interruption.";
?>

<div class="admin-page-header">
  <div>
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">notifications_active</span> Fee Reminder Panel</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Due students select karke email aur/ya SMS (mobile number) par fee reminder bhejein.
    </p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/settings') ?>" class="btn btn-outline btn-sm">SMS Settings</a>
    <a href="<?= site_url('admin/fees') ?>" class="btn btn-outline btn-sm">Fee Tracker</a>
  </div>
</div>

<div class="stat-grid" style="margin-bottom:1.25rem">
  <div class="stat-card" style="border-left:4px solid #2563eb">
    <span>Due Students</span>
    <strong style="color:#2563eb"><?= (int) ($stats['total_students'] ?? 0) ?></strong>
    <p class="dashboard-stat-note">All pending fee records</p>
  </div>
  <div class="stat-card" style="border-left:4px solid #16a34a">
    <span>With Email</span>
    <strong style="color:#16a34a"><?= (int) ($stats['with_email'] ?? 0) ?></strong>
    <p class="dashboard-stat-note">Can receive email</p>
  </div>
  <div class="stat-card" style="border-left:4px solid #7c3aed">
    <span>With Mobile</span>
    <strong style="color:#7c3aed"><?= (int) ($stats['with_mobile'] ?? 0) ?></strong>
    <p class="dashboard-stat-note">Can receive SMS</p>
  </div>
  <div class="stat-card" style="border-left:4px solid #dc2626">
    <span>Total Outstanding</span>
    <strong style="color:#dc2626"><?= format_inr($stats['total_due'] ?? 0) ?></strong>
    <p class="dashboard-stat-note">All pending fee dues</p>
  </div>
</div>

<form method="post" action="<?= site_url('admin/fee-reminders') ?>" id="dueNotifyForm">
  <?= csrf_field() ?>

  <div class="card" style="margin-bottom:1.25rem">
    <h3 style="margin:0 0 0.35rem">1. Notification Channel</h3>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
      Student ke mobile number par SMS bhejne ke liye SMS gateway Settings mein configure hona chahiye.
    </p>
    <div class="form-grid">
      <div>
        <label>Send Via</label>
        <select name="notify_channel" id="notifyChannel" required>
          <option value="sms" <?= $smsConfigured ? 'selected' : '' ?>>SMS only (student mobile)</option>
          <option value="email" <?= !$smsConfigured ? 'selected' : '' ?>>Email only</option>
          <option value="both">Both Email + SMS</option>
        </select>
      </div>
      <div>
        <label>SMS Gateway Status</label>
        <div class="admin-alert <?= $smsConfigured ? 'admin-alert-success' : '' ?>" style="margin:0;padding:0.65rem 0.85rem">
          <strong><?= e($smsStatus) ?></strong>
          <?php if (!$smsConfigured): ?>
          — <a href="<?= site_url('admin/settings') ?>">Configure SMS</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1.25rem" id="emailSetupCard">
    <h3 style="margin:0 0 0.35rem">2. Email Setup</h3>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
      Sender details save ho jayenge. Server par PHP <code>mail()</code> enable hona chahiye.
    </p>
    <div class="form-grid">
      <div>
        <label>From Name</label>
        <input name="mail_from_name" value="<?= e($mailFromName) ?>" placeholder="Maner Private ITI">
      </div>
      <div>
        <label>From Email</label>
        <input type="email" name="mail_from" value="<?= e($mailFrom) ?>" placeholder="manerpvtiti@gmail.com">
      </div>
      <div style="grid-column:1/-1">
        <label>Email Subject</label>
        <input name="mail_subject" value="<?= e($mailSubject ?: $defaultSubject) ?>">
      </div>
      <div style="grid-column:1/-1">
        <label>Extra Message (optional — Hindi / English)</label>
        <textarea name="mail_message" rows="3" placeholder="<?= e($defaultMessage) ?>"><?= e($mailMessage) ?></textarea>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1.25rem" id="smsSetupCard">
    <h3 style="margin:0 0 0.35rem">3. SMS Message (Mobile Notification)</h3>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
      Har selected student ke mobile number par yeh message jayega. Variables auto-replace honge.
    </p>
    <div>
      <label>SMS Template</label>
      <textarea name="sms_message" id="smsMessage" rows="3" maxlength="500"><?= e($smsMessage) ?></textarea>
      <small style="display:block;margin-top:0.35rem;color:var(--admin-on-surface-variant)">
        Variables: <code>{name}</code>, <code>{due}</code>, <code>{trade}</code>, <code>{institute}</code>, <code>{phone}</code>, <code>{mobile}</code>
      </small>
    </div>
    <div style="margin-top:0.85rem;padding:12px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#334155">
      <strong>Preview:</strong>
      <div id="smsPreview" style="margin-top:6px;line-height:1.5"></div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1rem">
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:1rem">
      <div>
        <h3 style="margin:0 0 0.25rem">4. Select Due Students</h3>
        <p style="margin:0;font-size:0.85rem;color:var(--admin-on-surface-variant)">
          Mobile number wale students ko SMS milega; email wale ko email.
        </p>
      </div>
      <button type="submit" class="btn btn-primary" id="sendDueBtn">
        Send Notifications
      </button>
    </div>

    <?php if (!$students): ?>
    <p style="margin:0;color:var(--admin-on-surface-variant)">
      Koi due student nahi mila.
    </p>
    <?php else: ?>
    <label style="display:flex;align-items:center;gap:0.45rem;font-weight:600;cursor:pointer;margin-bottom:0.75rem">
      <input type="checkbox" id="selectAllDue"> Select all (<?= count($students) ?>)
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
            <th>Items</th>
            <th>Total Due</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $s):
            $hasMobile = \App\Core\Sms::normalizeMobile((string) ($s['mobile'] ?? '')) !== '';
            $hasEmail = ($s['email'] ?? '') !== '';
        ?>
        <tr>
          <td><input type="checkbox" name="students[]" value="<?= e($s['key']) ?>" class="due-student-check" data-has-mobile="<?= $hasMobile ? '1' : '0' ?>" data-has-email="<?= $hasEmail ? '1' : '0' ?>"></td>
          <td><strong><?= e($s['student_name']) ?></strong></td>
          <td>
            <?php if ($hasMobile): ?>
              <span style="color:#7c3aed;font-weight:600"><?= e(format_mobile($s['mobile'] ?? '')) ?></span>
            <?php else: ?>
              <span style="color:#94a3b8">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($hasEmail): ?>
              <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a>
            <?php else: ?>
              <span style="color:#94a3b8">—</span>
            <?php endif; ?>
          </td>
          <td><?= e($s['trade'] ?: '—') ?></td>
          <td><?= (int) $s['fee_count'] ?></td>
          <td style="color:#ba1a1a;font-weight:700"><?= format_inr($s['total_due']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</form>

<script>
(function () {
  var all = document.getElementById('selectAllDue');
  var checks = document.querySelectorAll('.due-student-check');
  var form = document.getElementById('dueNotifyForm');
  var btn = document.getElementById('sendDueBtn');
  var channel = document.getElementById('notifyChannel');
  var emailCard = document.getElementById('emailSetupCard');
  var smsCard = document.getElementById('smsSetupCard');
  var smsInput = document.getElementById('smsMessage');
  var smsPreview = document.getElementById('smsPreview');
  var smsConfigured = <?= $smsConfigured ? 'true' : 'false' ?>;

  function updateChannelUi() {
    var mode = channel ? channel.value : 'email';
    if (emailCard) emailCard.style.display = (mode === 'email' || mode === 'both') ? '' : 'none';
    if (smsCard) smsCard.style.display = (mode === 'sms' || mode === 'both') ? '' : 'none';
    if (btn) {
      if (mode === 'sms') btn.textContent = 'Send SMS Notifications';
      else if (mode === 'both') btn.textContent = 'Send Email + SMS';
      else btn.textContent = 'Send Fee Reminder Email';
    }
  }

  function updatePreview() {
    if (!smsInput || !smsPreview) return;
    var text = smsInput.value || '';
    var sample = {
      '{name}': 'Rahul Kumar',
      '{due}': '₹7,000.00',
      '{trade}': 'Electrician',
      '{institute}': <?= json_encode($mailFromName) ?>,
      '{phone}': '9155401839',
      '{mobile}': '9876543210'
    };
    Object.keys(sample).forEach(function (k) {
      text = text.split(k).join(sample[k]);
    });
    smsPreview.textContent = text;
  }

  if (all) {
    all.addEventListener('change', function () {
      checks.forEach(function (c) { c.checked = all.checked; });
    });
  }

  if (channel) {
    channel.addEventListener('change', updateChannelUi);
    updateChannelUi();
  }
  if (smsInput) {
    smsInput.addEventListener('input', updatePreview);
    updatePreview();
  }

  if (form && btn) {
    form.addEventListener('submit', function (e) {
      var selected = document.querySelectorAll('.due-student-check:checked');
      if (!selected.length) {
        e.preventDefault();
        alert('Kam se kam ek student select karein.');
        return false;
      }
      var mode = channel ? channel.value : 'email';
      if ((mode === 'sms' || mode === 'both') && !smsConfigured) {
        e.preventDefault();
        alert('SMS gateway configured nahi hai. Pehle Settings → SMS Notification setup karein.');
        return false;
      }
      var label = mode === 'sms' ? 'SMS' : (mode === 'both' ? 'Email + SMS' : 'email');
      if (!confirm('Send fee reminder ' + label + ' to ' + selected.length + ' student(s)?')) {
        e.preventDefault();
        return false;
      }
      btn.disabled = true;
      btn.textContent = 'Sending...';
    });
  }
})();
</script>
