<div class="admin-page-header">
  <h1>Fee Management</h1>
  <div class="admin-page-actions">
    <a href="<?= e(admin_session_query('admin/fees/report', $filterSession ?? '')) ?>" class="btn btn-outline btn-sm">Session Fee Report</a>
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary btn-sm">+ Collect Fee</a>
  </div>
</div>

<?php
$baseUrl = 'admin/fees';
require base_path('views/partials/admin-session-tabs.php');
?>

<div class="stat-grid">
  <div class="stat-card">
    <span>Total Fees<?php if (($filterSession ?? '') !== ''): ?> <small>(<?= e(session_short_label($filterSession)) ?>)</small><?php endif; ?></span>
    <strong>₹<?= number_format((float) ($summary['total'] ?? 0), 2) ?></strong>
  </div>
  <div class="stat-card">
    <span>Collected</span>
    <strong>₹<?= number_format((float) ($summary['paid'] ?? 0), 2) ?></strong>
  </div>
  <div class="stat-card">
    <span>Pending</span>
    <strong>₹<?= number_format(max(0, (float) ($summary['total'] ?? 0) - (float) ($summary['paid'] ?? 0)), 2) ?></strong>
  </div>
  <?php if (($filterSession ?? '') !== ''): ?>
  <div class="stat-card">
    <span>Fee Records</span>
    <strong><?= (int) ($summary['cnt'] ?? count($fees)) ?></strong>
  </div>
  <?php endif; ?>
</div>

<?php if (($filterSession ?? '') !== ''): ?>
<p class="text-muted" style="margin:0 0 1rem">
  Showing fees for session <strong><?= e(session_short_label($filterSession)) ?></strong>
  · <strong><?= count($fees) ?></strong> record<?= count($fees) === 1 ? '' : 's' ?>
</p>
<?php endif; ?>

<div class="grid-2">
  <div class="card">
    <h3>Quick Collect</h3>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">Student se installment collect karne ke liye Collect Fee page use karein.</p>
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary">+ Collect Installment</a>
  </div>

  <div class="table-wrap">
    <table>
    <thead><tr><th>Student</th><th>Type</th><th>Amount</th><th>Paid</th><th>Due</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php if (empty($fees)): ?>
    <tr><td colspan="7" style="text-align:center;padding:1.5rem;color:#64748b">
      <?php if (($filterSession ?? '') !== ''): ?>
      No fee records found for session <?= e(session_short_label($filterSession)) ?>.
      <?php else: ?>
      No fee records yet.
      <?php endif; ?>
    </td></tr>
    <?php else: ?>
    <?php foreach ($fees as $f): ?>
    <?php $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']); ?>
    <tr>
      <td><?= e($f['student_name']) ?></td>
      <td><?= e($f['fee_type']) ?></td>
      <td>₹<?= number_format((float) $f['amount'], 2) ?></td>
      <td>₹<?= number_format((float) $f['paid_amount'], 2) ?></td>
      <td>₹<?= number_format($due, 2) ?></td>
      <td><?= e($f['status']) ?></td>
      <td style="min-width:220px">
        <?php if ($f['paid_amount'] > 0): ?>
        <a href="<?= site_url('admin/fees/receipt/' . $f['id']) ?>" target="_blank" class="btn btn-sm btn-outline">Receipt</a>
        <?php endif; ?>
        <?php if ($due > 0): ?>
        <form method="post" action="<?= site_url('admin/fees/pay/' . $f['id']) ?>" style="display:inline-flex;gap:.25rem;align-items:center;flex-wrap:wrap">
          <?= csrf_field() ?>
          <input type="number" step="0.01" min="0.01" max="<?= e((string) $due) ?>" name="pay_amount" value="<?= e((string) $due) ?>" style="width:80px" title="Amount to collect">
          <select name="payment_method" style="width:90px">
            <option>Cash</option>
            <option>UPI</option>
            <option>Bank Transfer</option>
          </select>
          <button class="btn btn-sm btn-secondary">Collect</button>
        </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
    </table>
  </div>
</div>
