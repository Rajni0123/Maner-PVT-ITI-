<div class="admin-page-header">
  <h1>Fee Management</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees/collect') ?>" class="btn btn-primary btn-sm">+ Collect Fee</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card"><span>Total Fees</span><strong>₹<?= number_format($summary['total'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Collected</span><strong>₹<?= number_format($summary['paid'] ?? 0) ?></strong></div>
  <div class="stat-card"><span>Pending</span><strong>₹<?= number_format(max(0, ($summary['total'] ?? 0) - ($summary['paid'] ?? 0))) ?></strong></div>
</div>

<div class="grid-2">
  <form method="post" action="<?= site_url('admin/fees') ?>" class="card">
    <h3>Create Fee Record</h3>
    <?= csrf_field() ?>
    <div class="form-grid">
      <div><label>Student Name *</label><input name="student_name" required></div>
      <div><label>Father Name</label><input name="father_name"></div>
      <div><label>Mobile</label><input name="mobile"></div>
      <div><label>Trade *</label><input name="trade" required></div>
      <div><label>Fee Type</label><select name="fee_type"><option>Tuition Fee</option><option>Admission Fee</option><option>Examination Fee</option></select></div>
      <div><label>Amount *</label><input type="number" step="0.01" name="amount" required></div>
      <div><label>Paid Now</label><input type="number" step="0.01" name="paid_amount" value="0"></div>
      <div><label>Due Date</label><input type="date" name="due_date"></div>
      <div><label>Payment Method</label><select name="payment_method"><option>Cash</option><option>UPI</option><option>Bank Transfer</option></select></div>
    </div>
    <button class="btn btn-primary" style="margin-top:1rem">Create Fee</button>
  </form>

  <div class="table-wrap">
    <table>
    <thead><tr><th>Student</th><th>Type</th><th>Amount</th><th>Paid</th><th>Due</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($fees as $f): ?>
    <?php $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']); ?>
    <tr>
      <td><?= e($f['student_name']) ?></td>
      <td><?= e($f['fee_type']) ?></td>
      <td>₹<?= number_format($f['amount'], 2) ?></td>
      <td>₹<?= number_format($f['paid_amount'], 2) ?></td>
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
    </tbody>
    </table>
  </div>
</div>
