<?php
$summary = $summary ?? [];
$income = $income ?? [];
$expenses = $expenses ?? [];
$feeBreakdown = $feeBreakdown ?? [];
$salaryBreakdown = $salaryBreakdown ?? [];
$monthlyTrend = $monthlyTrend ?? [];
$tradeWise = $tradeWise ?? [];
$trendMax = 1;
foreach ($monthlyTrend as $t) {
    $trendMax = max($trendMax, $t['income'], $t['expense']);
}
?>

<div class="admin-page-header">
  <div>
    <h1><span class="material-symbols-outlined" style="vertical-align:middle;font-size:28px">account_balance</span> Financial Reports</h1>
    <p style="margin:0.35rem 0 0;font-size:0.9rem;color:var(--admin-on-surface-variant)">Generate income, expense & profit/loss reports</p>
  </div>
  <div class="admin-page-actions" style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a href="<?= site_url('admin/finance/print?' . http_build_query(['from' => $fromDate, 'to' => $toDate, 'type' => $reportType])) ?>" target="_blank" class="btn btn-primary btn-sm">
      <span class="material-symbols-outlined" style="font-size:18px">print</span> Print Report
    </a>
    <a href="<?= site_url('admin/finance/export?' . http_build_query(['from' => $fromDate, 'to' => $toDate, 'type' => $reportType])) ?>" class="btn btn-outline btn-sm">
      <span class="material-symbols-outlined" style="font-size:18px">download</span> Export CSV
    </a>
  </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:1.5rem">
  <form method="get" action="<?= site_url('admin/finance') ?>" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
    <div style="flex:1;min-width:140px">
      <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.25rem">From Date</label>
      <input type="date" name="from" value="<?= e($fromDate) ?>" style="width:100%">
    </div>
    <div style="flex:1;min-width:140px">
      <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.25rem">To Date</label>
      <input type="date" name="to" value="<?= e($toDate) ?>" style="width:100%">
    </div>
    <div style="flex:1;min-width:140px">
      <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.25rem">Report Type</label>
      <select name="type" style="width:100%">
        <option value="all" <?= $reportType === 'all' ? 'selected' : '' ?>>All (Income + Expense)</option>
        <option value="income" <?= $reportType === 'income' ? 'selected' : '' ?>>Income Only (Fees)</option>
        <option value="expense" <?= $reportType === 'expense' ? 'selected' : '' ?>>Expense Only (Salaries)</option>
      </select>
    </div>
    <div>
      <button type="submit" class="btn btn-primary">Generate Report</button>
    </div>
  </form>
  <div style="margin-top:0.75rem;display:flex;gap:0.5rem;flex-wrap:wrap">
    <a href="<?= site_url('admin/finance?from=' . date('Y-m-01') . '&to=' . date('Y-m-d') . '&type=' . $reportType) ?>" class="btn btn-sm btn-outline">This Month</a>
    <a href="<?= site_url('admin/finance?from=' . date('Y-m-01', strtotime('-1 month')) . '&to=' . date('Y-m-t', strtotime('-1 month')) . '&type=' . $reportType) ?>" class="btn btn-sm btn-outline">Last Month</a>
    <a href="<?= site_url('admin/finance?from=' . date('Y-01-01') . '&to=' . date('Y-m-d') . '&type=' . $reportType) ?>" class="btn btn-sm btn-outline">This Year</a>
    <a href="<?= site_url('admin/finance?from=' . date('Y-04-01', strtotime(date('m') < 4 ? '-1 year' : 'now')) . '&to=' . date('Y-03-31', strtotime(date('m') < 4 ? 'now' : '+1 year')) . '&type=' . $reportType) ?>" class="btn btn-sm btn-outline">Financial Year</a>
  </div>
</div>

<!-- Summary Cards -->
<div class="stat-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:1.5rem">
  <div class="stat-card" style="border-left:4px solid #16a34a">
    <span>Total Income</span>
    <strong style="color:#16a34a"><?= format_inr($summary['total_income'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= (int) ($summary['fee_count'] ?? 0) ?> fee transactions</p>
  </div>
  <div class="stat-card" style="border-left:4px solid #dc2626">
    <span>Total Expenses</span>
    <strong style="color:#dc2626"><?= format_inr($summary['total_expenses'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= (int) ($summary['salary_count'] ?? 0) ?> salary slips</p>
  </div>
  <div class="stat-card" style="border-left:4px solid <?= ($summary['net_balance'] ?? 0) >= 0 ? '#16a34a' : '#dc2626' ?>">
    <span>Net Balance</span>
    <strong style="color:<?= ($summary['net_balance'] ?? 0) >= 0 ? '#16a34a' : '#dc2626' ?>"><?= format_inr($summary['net_balance'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= ($summary['net_balance'] ?? 0) >= 0 ? 'Profit' : 'Loss' ?></p>
  </div>
  <div class="stat-card" style="border-left:4px solid #f59e0b">
    <span>Outstanding Dues</span>
    <strong style="color:#f59e0b"><?= format_inr($summary['outstanding'] ?? 0) ?></strong>
    <p class="dashboard-stat-note">of <?= format_inr($summary['total_billed'] ?? 0) ?> billed</p>
  </div>
</div>

<!-- Monthly Trend Chart -->
<?php if (count($monthlyTrend) > 1): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="dashboard-card-head">
    <h3>Monthly Trend (Income vs Expense)</h3>
  </div>
  <div class="dashboard-chart" style="height:180px;align-items:flex-end;gap:4px">
    <?php foreach ($monthlyTrend as $t): ?>
    <div style="display:flex;flex-direction:column;align-items:center;flex:1;gap:2px;height:100%;justify-content:flex-end">
      <?php
      $ih = $trendMax > 0 ? max(4, round(($t['income'] / $trendMax) * 140)) : 4;
      $eh = $trendMax > 0 ? max(4, round(($t['expense'] / $trendMax) * 140)) : 4;
      ?>
      <div style="display:flex;gap:2px;align-items:flex-end;height:150px">
        <div style="width:14px;background:#16a34a;border-radius:3px 3px 0 0;height:<?= $ih ?>px" title="Income: <?= format_inr($t['income']) ?>"></div>
        <div style="width:14px;background:#dc2626;border-radius:3px 3px 0 0;height:<?= $eh ?>px" title="Expense: <?= format_inr($t['expense']) ?>"></div>
      </div>
      <span style="font-size:10px;color:var(--admin-on-surface-variant)"><?= e(substr($t['label'], 0, 3)) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
  <div style="display:flex;gap:1rem;justify-content:center;margin-top:0.75rem">
    <span style="display:flex;align-items:center;gap:4px;font-size:11px"><i style="width:12px;height:12px;background:#16a34a;border-radius:2px;display:inline-block"></i> Income</span>
    <span style="display:flex;align-items:center;gap:4px;font-size:11px"><i style="width:12px;height:12px;background:#dc2626;border-radius:2px;display:inline-block"></i> Expense</span>
  </div>
</div>
<?php endif; ?>

<!-- Breakdown Section -->
<div class="dashboard-main-grid" style="margin-bottom:1.5rem">
  <!-- Fee Type Breakdown -->
  <?php if ($reportType !== 'expense' && $feeBreakdown): ?>
  <div class="card">
    <h3 style="margin-bottom:1rem">Fee Type Breakdown</h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Fee Type</th><th>Transactions</th><th>Billed</th><th>Collected</th><th>Due</th></tr></thead>
        <tbody>
        <?php foreach ($feeBreakdown as $fb): ?>
        <tr>
          <td><strong><?= e($fb['fee_type'] ?: 'Other') ?></strong></td>
          <td><?= (int) $fb['count'] ?></td>
          <td><?= format_inr($fb['total_billed'] ?? 0) ?></td>
          <td style="color:#16a34a;font-weight:600"><?= format_inr($fb['total_collected'] ?? 0) ?></td>
          <td style="color:#f59e0b"><?= format_inr($fb['total_due'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Trade-wise Collection -->
  <?php if ($reportType !== 'expense' && $tradeWise): ?>
  <div class="card">
    <h3 style="margin-bottom:1rem">Trade-wise Collection</h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Trade</th><th>Students</th><th>Collected</th><th>Due</th></tr></thead>
        <tbody>
        <?php foreach ($tradeWise as $tw): ?>
        <tr>
          <td><strong><?= e($tw['trade'] ?: 'Other') ?></strong></td>
          <td><?= (int) $tw['count'] ?></td>
          <td style="color:#16a34a;font-weight:600"><?= format_inr($tw['total_collected'] ?? 0) ?></td>
          <td style="color:#f59e0b"><?= format_inr($tw['total_due'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Salary Designation Breakdown -->
  <?php if ($reportType !== 'income' && $salaryBreakdown): ?>
  <div class="card">
    <h3 style="margin-bottom:1rem">Salary by Designation</h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Designation</th><th>Slips</th><th>Gross</th><th>Deductions</th><th>Net Paid</th></tr></thead>
        <tbody>
        <?php foreach ($salaryBreakdown as $sb): ?>
        <tr>
          <td><strong><?= e($sb['designation'] ?: 'Other') ?></strong></td>
          <td><?= (int) $sb['slip_count'] ?></td>
          <td><?= format_inr($sb['total_gross'] ?? 0) ?></td>
          <td style="color:#f59e0b"><?= format_inr($sb['total_deductions'] ?? 0) ?></td>
          <td style="color:#dc2626;font-weight:600"><?= format_inr($sb['total_net'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Detailed Transactions -->
<?php if ($reportType !== 'expense' && $income): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="dashboard-card-head">
    <h3>Fee Collections (<?= count($income) ?> records)</h3>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Student</th>
          <th>Trade</th>
          <th>Fee Type</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Method</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach (array_slice($income, 0, 50) as $row): ?>
      <tr>
        <td><?= format_date($row['payment_date'] ?? $row['created_at'] ?? '') ?></td>
        <td><?= e($row['student_name'] ?? '') ?></td>
        <td><?= e($row['trade'] ?? '') ?></td>
        <td><?= e($row['fee_type'] ?? 'Tuition Fee') ?></td>
        <td><?= format_inr($row['amount'] ?? 0) ?></td>
        <td style="color:#16a34a;font-weight:600"><?= format_inr($row['paid_amount'] ?? 0) ?></td>
        <td><?= e($row['payment_method'] ?? '—') ?></td>
        <td><code><?= e($row['receipt_number'] ?? '—') ?></code></td>
      </tr>
      <?php endforeach; ?>
      <?php if (count($income) > 50): ?>
      <tr><td colspan="8" style="text-align:center;color:var(--admin-on-surface-variant)">Showing 50 of <?= count($income) ?> records. Export CSV for full data.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php if ($reportType !== 'income' && $expenses): ?>
<div class="card">
  <div class="dashboard-card-head">
    <h3>Salary Disbursements (<?= count($expenses) ?> records)</h3>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Period</th>
          <th>Employee</th>
          <th>Designation</th>
          <th>Gross</th>
          <th>Deductions</th>
          <th>Net Pay</th>
          <th>Slip #</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($expenses as $row): ?>
      <tr>
        <td><?= e(month_name($row['slip_month'] ?? 0)) ?> <?= (int) ($row['slip_year'] ?? 0) ?></td>
        <td><?= e($row['staff_name'] ?? '') ?></td>
        <td><?= e($row['designation'] ?? '') ?></td>
        <td><?= format_inr($row['gross_pay'] ?? 0) ?></td>
        <td style="color:#f59e0b"><?= format_inr($row['total_deductions'] ?? 0) ?></td>
        <td style="color:#dc2626;font-weight:600"><?= format_inr($row['net_pay'] ?? 0) ?></td>
        <td><code><?= e($row['slip_number'] ?? '—') ?></code></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php if (!$income && !$expenses): ?>
<div class="card">
  <p style="margin:0;text-align:center;padding:2rem 0;color:var(--admin-on-surface-variant)">
    <span class="material-symbols-outlined" style="font-size:48px;display:block;margin-bottom:0.5rem">search_off</span>
    No financial records found for the selected date range. Try changing the filters above.
  </p>
</div>
<?php endif; ?>
