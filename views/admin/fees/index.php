<div class="admin-page-header">
  <div>
    <h1>Fee Management</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Fee collect karne ke liye <strong>Collect Fee</strong> use karein — student search se select hoga.
    </p>
  </div>
  <div class="admin-page-actions" style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a href="<?= site_url('admin/fee-reminders') ?>" class="btn btn-outline btn-sm">Fee Reminders</a>
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary btn-sm">+ Collect Fee</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card"><span>Total Fees</span><strong>₹<?= number_format($summary['total'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Collected</span><strong>₹<?= number_format($summary['paid'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Pending</span><strong>₹<?= number_format(max(0, ($summary['total'] ?? 0) - ($summary['paid'] ?? 0))) ?></strong></div>
</div>

<div class="card">
  <div class="dashboard-card-head" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem">
    <h3 style="margin:0">Fee Records</h3>
  </div>
  <?php if (!$fees): ?>
  <p style="margin:0;color:var(--admin-on-surface-variant)">No fee records yet. Click <strong>Collect Fee</strong> to add payment.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Student</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Due</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($fees as $f): ?>
      <?php $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']); ?>
      <tr>
        <td>
          <strong><?= e($f['student_name']) ?></strong>
          <?php if (!empty($f['mobile'])): ?>
          <div style="font-size:0.8rem;color:var(--admin-on-surface-variant)"><?= e(format_mobile($f['mobile'])) ?></div>
          <?php endif; ?>
        </td>
        <td><?= e($f['fee_type']) ?></td>
        <td>₹<?= number_format((float) $f['amount'], 2) ?></td>
        <td>₹<?= number_format((float) $f['paid_amount'], 2) ?></td>
        <td style="<?= $due > 0 ? 'color:#ba1a1a;font-weight:700' : '' ?>">₹<?= number_format($due, 2) ?></td>
        <td><?= e($f['status']) ?></td>
        <td style="min-width:220px">
          <?php if ((float) $f['paid_amount'] > 0): ?>
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
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
