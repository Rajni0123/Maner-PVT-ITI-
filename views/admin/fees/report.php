<?php
$sessionQs = ($filterSession ?? '') !== '' ? '?session=' . urlencode($filterSession) : '';
$pending = max(0, (float) ($summary['total'] ?? 0) - (float) ($summary['paid'] ?? 0));
?>
<div class="admin-page-header">
  <h1>Session Fee Report</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees') ?>" class="btn btn-outline btn-sm">Fee Tracker</a>
    <?php if (($filterSession ?? '') !== ''): ?>
    <a href="<?= site_url('admin/fees/report/export' . $sessionQs) ?>" class="btn btn-outline btn-sm">Export Excel</a>
    <a href="<?= site_url('admin/fees/report/print' . $sessionQs) ?>" target="_blank" class="btn btn-outline btn-sm">Export PDF</a>
    <?php endif; ?>
  </div>
</div>

<form method="get" class="card filter-bar">
  <div>
    <label>Session *</label>
    <select name="session" required>
      <option value="">Select Session</option>
      <?php foreach ($sessions as $sn): ?>
      <option value="<?= e($sn) ?>" <?= ($filterSession ?? '') === $sn ? 'selected' : '' ?>><?= e($sn) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button class="btn btn-sm btn-primary">View Report</button>
</form>

<?php if (($filterSession ?? '') === ''): ?>
<div class="card" style="margin-top:1rem;color:#64748b">
  Select a session to view fee collection report for all students in that session.
</div>
<?php else: ?>

<div class="stat-grid" style="margin-top:1rem">
  <div class="stat-card"><span>Students</span><strong><?= (int) ($summary['students'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Fee Records</span><strong><?= (int) ($summary['cnt'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Total Fees</span><strong>₹<?= number_format((float) ($summary['total'] ?? 0), 2) ?></strong></div>
  <div class="stat-card"><span>Collected</span><strong>₹<?= number_format((float) ($summary['paid'] ?? 0), 2) ?></strong></div>
  <div class="stat-card"><span>Pending</span><strong>₹<?= number_format($pending, 2) ?></strong></div>
</div>

<?php if (!empty($byTrade)): ?>
<div class="card" style="margin:1rem 0">
  <h3 style="margin:0 0 0.75rem">Trade-wise Summary — Session <?= e($filterSession) ?></h3>
  <div class="table-wrap">
  <table>
  <thead><tr><th>Trade</th><th>Records</th><th>Total</th><th>Collected</th><th>Pending</th></tr></thead>
  <tbody>
  <?php foreach ($byTrade as $t): ?>
  <?php $tdue = max(0, (float) $t['total'] - (float) $t['paid']); ?>
  <tr>
    <td><?= e($t['trade']) ?></td>
    <td><?= (int) $t['cnt'] ?></td>
    <td>₹<?= number_format((float) $t['total'], 2) ?></td>
    <td>₹<?= number_format((float) $t['paid'], 2) ?></td>
    <td>₹<?= number_format($tdue, 2) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
  </div>
</div>
<?php endif; ?>

<div class="table-wrap">
<table>
<thead>
<tr>
  <th>#</th>
  <th>Student</th>
  <th>Mobile</th>
  <th>Trade</th>
  <th>Fee Type</th>
  <th>Amount</th>
  <th>Paid</th>
  <th>Due</th>
  <th>Status</th>
  <th>Receipt</th>
  <th>Date</th>
</tr>
</thead>
<tbody>
<?php if (empty($fees)): ?>
<tr><td colspan="11" style="text-align:center;padding:1.5rem;color:#64748b">No fee records found for session <?= e($filterSession) ?>.</td></tr>
<?php else: ?>
<?php foreach ($fees as $i => $f): ?>
<?php $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']); ?>
<tr>
  <td><?= $i + 1 ?></td>
  <td><?= e($f['student_name']) ?></td>
  <td><?= e(format_mobile($f['mobile'] ?? '')) ?></td>
  <td><?= e($f['trade'] ?? '—') ?></td>
  <td><?= e($f['fee_type'] ?? '—') ?></td>
  <td>₹<?= number_format((float) $f['amount'], 2) ?></td>
  <td>₹<?= number_format((float) $f['paid_amount'], 2) ?></td>
  <td>₹<?= number_format($due, 2) ?></td>
  <td><?= e($f['status'] ?? '—') ?></td>
  <td>
    <?php if (!empty($f['receipt_number'])): ?>
    <a href="<?= site_url('admin/fees/receipt/' . $f['id']) ?>" target="_blank"><?= e($f['receipt_number']) ?></a>
    <?php else: ?>
    —
    <?php endif; ?>
  </td>
  <td><?= e($f['payment_date'] ?? '—') ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
<?php endif; ?>
