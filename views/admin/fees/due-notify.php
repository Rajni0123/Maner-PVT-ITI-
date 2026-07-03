<?php
$students = $students ?? [];
$studentsNoEmail = $studentsNoEmail ?? [];
$stats = $stats ?? ['with_email' => 0, 'without_email' => 0, 'total_due' => 0];
$mailFrom = $mailFrom ?? '';
$mailFromName = $mailFromName ?? 'Maner Private ITI';
$mailSubject = $mailSubject ?? '';
$mailMessage = $mailMessage ?? '';
if ($mailFromName === 'Maner Pvt ITI') {
    $mailFromName = 'Maner Private ITI';
}
$defaultSubject = 'Fee Payment Reminder / शुल्क भुगतान अनुस्मारक — ' . $mailFromName;
$defaultMessage = "कृपया अपना बकाया शुल्क यथाशीघ्र जमा करें।\nPlease pay your pending fees at the earliest to continue your training without interruption.";
?>

<div class="admin-page-header">
  <div>
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">mark_email_unread</span> Fee Reminder Panel</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Due students select karke professional fee reminder email ek saath bhejein.
    </p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees') ?>" class="btn btn-outline btn-sm">Fee Tracker</a>
  </div>
</div>

<div class="stat-grid" style="margin-bottom:1.25rem">
  <div class="stat-card" style="border-left:4px solid #16a34a">
    <span>Ready to Email</span>
    <strong style="color:#16a34a"><?= (int) $stats['with_email'] ?></strong>
    <p class="dashboard-stat-note">Students with email + due</p>
  </div>
  <div class="stat-card" style="border-left:4px solid #f59e0b">
    <span>No Email on Record</span>
    <strong style="color:#f59e0b"><?= (int) $stats['without_email'] ?></strong>
    <p class="dashboard-stat-note">Cannot receive email reminder</p>
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
    <h3 style="margin:0 0 0.35rem">1. Email Setup</h3>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
      Sender details save ho jayenge. Server par PHP <code>mail()</code> enable hona chahiye.
    </p>
    <div class="form-grid">
      <div>
        <label>From Name</label>
        <input name="mail_from_name" value="<?= e($mailFromName) ?>" placeholder="Maner Private ITI" required>
      </div>
      <div>
        <label>From Email</label>
        <input type="email" name="mail_from" value="<?= e($mailFrom) ?>" placeholder="manerpvtiti@gmail.com" required>
      </div>
      <div style="grid-column:1/-1">
        <label>Email Subject</label>
        <input name="mail_subject" value="<?= e($mailSubject ?: $defaultSubject) ?>" required>
      </div>
      <div style="grid-column:1/-1">
        <label>Extra Message (optional — Hindi / English)</label>
        <textarea name="mail_message" rows="3" placeholder="<?= e($defaultMessage) ?>"><?= e($mailMessage) ?></textarea>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1.25rem">
    <h3 style="margin:0 0 0.75rem">2. Email Preview</h3>
    <div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;max-width:640px">
      <div style="background:#131b2e;color:#fff;padding:16px 18px">
        <div style="display:inline-block;background:#fea619;color:#131b2e;font-size:10px;font-weight:800;padding:3px 8px;border-radius:3px;margin-bottom:8px">FEE REMINDER</div>
        <div style="font-size:18px;font-weight:800"><?= e($mailFromName) ?></div>
        <div style="font-size:12px;color:#94a3b8;margin-top:4px">Official Fee Payment Reminder</div>
      </div>
      <div style="padding:16px 18px;font-size:13px;line-height:1.55;color:#334155">
        <p>Dear <strong>Student Name</strong>,</p>
        <p>This is a gentle reminder regarding your pending course fee. Kindly clear the outstanding amount at the earliest.</p>
        <p>यह आपके बकाया शुल्क के संबंध में आधिकारिक अनुस्मारक है। कृपया राशि यथाशीघ्र जमा करें।</p>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:6px;padding:12px;text-align:center;margin:12px 0">
          <div style="font-size:11px;color:#991b1b;font-weight:700">TOTAL OUTSTANDING / कुल बकाया</div>
          <div style="font-size:22px;font-weight:800;color:#b91c1c">₹ X,XXX.XX</div>
        </div>
        <p style="margin:0;color:#64748b;font-size:12px">Fee type table + institute contact details email mein automatically add honge.</p>
      </div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1rem">
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:1rem">
      <div>
        <h3 style="margin:0 0 0.25rem">3. Select Due Students</h3>
        <p style="margin:0;font-size:0.85rem;color:var(--admin-on-surface-variant)">
          Sirf un students ki list jinke paas email hai aur fee due hai.
        </p>
      </div>
      <button type="submit" class="btn btn-primary" id="sendDueBtn">
        Send Fee Reminder Email
      </button>
    </div>

    <?php if (!$students): ?>
    <p style="margin:0;color:var(--admin-on-surface-variant)">
      Koi due student email ke saath nahi mila. Admission/Student record mein email add karein.
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
            <th>Email</th>
            <th>Mobile</th>
            <th>Trade</th>
            <th>Items</th>
            <th>Total Due</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $s): ?>
        <tr>
          <td><input type="checkbox" name="students[]" value="<?= e($s['key']) ?>" class="due-student-check"></td>
          <td><strong><?= e($s['student_name']) ?></strong></td>
          <td><a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></td>
          <td><?= e(format_mobile($s['mobile'] ?? '')) ?></td>
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

<?php if ($studentsNoEmail): ?>
<div class="card">
  <h3 style="margin:0 0 0.5rem">Due Students Without Email</h3>
  <p style="margin:0 0 0.75rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
    Inhe email nahi ja sakta. Student/Admission profile mein email update karein.
  </p>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Student</th>
          <th>Mobile</th>
          <th>Trade</th>
          <th>Total Due</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($studentsNoEmail as $s): ?>
      <tr>
        <td><?= e($s['student_name']) ?></td>
        <td><?= e(format_mobile($s['mobile'] ?? '')) ?></td>
        <td><?= e($s['trade'] ?: '—') ?></td>
        <td style="color:#ba1a1a;font-weight:700"><?= format_inr($s['total_due']) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<script>
(function () {
  var all = document.getElementById('selectAllDue');
  var checks = document.querySelectorAll('.due-student-check');
  var form = document.getElementById('dueNotifyForm');
  var btn = document.getElementById('sendDueBtn');

  if (all) {
    all.addEventListener('change', function () {
      checks.forEach(function (c) { c.checked = all.checked; });
    });
  }

  if (form && btn) {
    form.addEventListener('submit', function (e) {
      var selected = document.querySelectorAll('.due-student-check:checked');
      if (!selected.length) {
        e.preventDefault();
        alert('Kam se kam ek student select karein.');
        return false;
      }
      if (!confirm('Send fee reminder email to ' + selected.length + ' student(s)?')) {
        e.preventDefault();
        return false;
      }
      btn.disabled = true;
      btn.textContent = 'Sending emails...';
    });
  }
})();
</script>
