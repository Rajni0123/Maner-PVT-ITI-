<div class="admin-page-header">
  <div>
    <h1>Fee Due Email Notifications</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Select students with pending fees (who have email) and send due reminder in one click.
    </p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees') ?>" class="btn btn-outline btn-sm">Back to Fees</a>
  </div>
</div>

<?php if (!$students): ?>
<div class="card">
  <p style="margin:0">No fee-due students found with a valid email address.</p>
  <p style="margin:0.75rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
    Email admission/student record mein hona chahiye, aur fee record mein pending due hona chahiye.
  </p>
</div>
<?php else: ?>
<form method="post" action="<?= site_url('admin/fees/due-notify') ?>" id="dueNotifyForm">
  <?= csrf_field() ?>

  <div class="card" style="margin-bottom:1rem;display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;justify-content:space-between">
    <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap">
      <label style="display:flex;align-items:center;gap:0.4rem;font-weight:600;cursor:pointer">
        <input type="checkbox" id="selectAllDue"> Select all due students
      </label>
      <span style="color:var(--admin-on-surface-variant);font-size:0.9rem">
        <?= count($students) ?> student(s) with email &amp; pending dues
      </span>
    </div>
    <button type="submit" class="btn btn-primary" id="sendDueBtn">
      Send Fee Due Email
    </button>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:40px"></th>
          <th>Student</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>Trade</th>
          <th>Pending Items</th>
          <th>Total Due</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($students as $s): ?>
      <tr>
        <td>
          <input type="checkbox" name="students[]" value="<?= e($s['key']) ?>" class="due-student-check">
        </td>
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
</form>

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
        alert('Select at least one student.');
        return false;
      }
      if (!confirm('Send fee due email to ' + selected.length + ' student(s)?')) {
        e.preventDefault();
        return false;
      }
      btn.disabled = true;
      btn.textContent = 'Sending...';
    });
  }
})();
</script>
<?php endif; ?>
