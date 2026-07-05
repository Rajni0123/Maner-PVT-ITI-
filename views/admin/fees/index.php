<div class="admin-page-header">
  <h1>Fee Management</h1>
  <div class="admin-page-actions">
    <a href="<?= e(admin_session_query('admin/fees/report', $filterSession ?? '')) ?>" class="btn btn-outline btn-sm">Session Fee Report</a>
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary btn-sm">+ Collect Fee</a>
  </div>
</div>

<form method="get" class="card filter-bar fee-page-toolbar">
  <div>
    <label>Session</label>
    <select name="session">
      <option value="">All Sessions</option>
      <?php foreach ($sessions ?? [] as $sn): ?>
      <option value="<?= e($sn) ?>" <?= ($filterSession ?? '') === $sn ? 'selected' : '' ?>><?= e(session_short_label($sn)) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button class="btn btn-sm btn-primary">Filter</button>
  <?php if (($filterSession ?? '') !== ''): ?>
  <a href="<?= e(admin_session_query('admin/fees', '')) ?>" class="btn btn-sm btn-outline">Clear</a>
  <?php endif; ?>
</form>

<div class="stat-grid fee-stat-grid">
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
  <div class="stat-card">
    <span>Fee Records</span>
    <strong><?= (int) ($summary['cnt'] ?? count($fees)) ?></strong>
  </div>
</div>

<div class="card fee-records-card">
  <div class="fee-records-header">
    <div>
      <h3>Recent Fee Records</h3>
      <p class="fee-records-meta">
        <?php if (($filterSession ?? '') !== ''): ?>
        Session <strong><?= e(session_short_label($filterSession)) ?></strong>
        · <strong><?= count($fees) ?></strong> record<?= count($fees) === 1 ? '' : 's' ?>
        <?php else: ?>
        Latest fee collections across all sessions
        <?php endif; ?>
      </p>
    </div>
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary btn-sm">+ Collect Installment</a>
  </div>

  <div class="table-wrap fee-table-wrap">
    <table class="fee-records-table">
      <thead>
        <tr>
          <th>Student</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Due</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($fees)): ?>
      <tr>
        <td colspan="7" class="fee-table-empty">
          <?php if (($filterSession ?? '') !== ''): ?>
          No fee records found for session <?= e(session_short_label($filterSession)) ?>.
          <?php else: ?>
          No fee records yet.
          <?php endif; ?>
        </td>
      </tr>
      <?php else: ?>
      <?php foreach ($fees as $f): ?>
      <?php
        $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']);
        $statusClass = strtolower((string) ($f['status'] ?? 'pending'));
      ?>
      <tr>
        <td><?= e($f['student_name']) ?></td>
        <td><?= e($f['fee_type']) ?></td>
        <td>₹<?= number_format((float) $f['amount'], 2) ?></td>
        <td>₹<?= number_format((float) $f['paid_amount'], 2) ?></td>
        <td>₹<?= number_format($due, 2) ?></td>
        <td><span class="badge badge-<?= e($statusClass) ?>"><?= e($f['status']) ?></span></td>
        <td class="fee-row-actions">
          <?php if ($f['paid_amount'] > 0): ?>
          <a href="<?= site_url('admin/fees/receipt/' . $f['id']) ?>" target="_blank" class="btn btn-sm btn-outline">Receipt</a>
          <?php endif; ?>
          <?php if ($due > 0): ?>
          <form method="post" action="<?= site_url('admin/fees/pay/' . $f['id']) ?>" class="fee-inline-pay">
            <?= csrf_field() ?>
            <input type="number" step="0.01" min="0.01" max="<?= e((string) $due) ?>" name="pay_amount" value="<?= e((string) $due) ?>" title="Amount to collect">
            <select name="payment_method">
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
