<?php
$summary = $summary ?? [];
$income = $income ?? [];
$expenses = $expenses ?? [];
$feeBreakdown = $feeBreakdown ?? [];
$salaryBreakdown = $salaryBreakdown ?? [];
$tradeWise = $tradeWise ?? [];
$header = $header ?? [];
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') $logoText = 'Maner Private ITI';
?>
<style>
  @media print {
    body { font-size: 11px; }
    .no-print { display: none !important; }
    table { page-break-inside: auto; }
    tr { page-break-inside: avoid; }
  }
  .report-header { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #000; padding-bottom: 1rem; }
  .report-header h1 { font-size: 22px; margin: 0; }
  .report-header h2 { font-size: 16px; font-weight: 400; margin: 0.25rem 0 0; color: #555; }
  .report-header p { margin: 0.5rem 0 0; font-size: 12px; color: #777; }
  .report-meta { display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 12px; color: #555; }
  .summary-box { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
  .summary-item { border: 1px solid #ddd; padding: 0.75rem; text-align: center; }
  .summary-item span { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #777; }
  .summary-item strong { display: block; font-size: 18px; margin-top: 0.25rem; }
  .section-title { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid #ccc; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: 11px; }
  th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
  th { background: #f5f5f5; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.03em; }
  .text-right { text-align: right; }
  .text-green { color: #16a34a; }
  .text-red { color: #dc2626; }
  .text-orange { color: #f59e0b; }
  .report-footer { margin-top: 2rem; border-top: 1px solid #ccc; padding-top: 1rem; display: flex; justify-content: space-between; font-size: 10px; color: #999; }
</style>

<div class="no-print" style="padding:1rem;text-align:center;background:#f0f0f0;margin-bottom:1rem">
  <button onclick="window.print()" style="padding:0.5rem 2rem;font-size:14px;font-weight:bold;cursor:pointer">🖨 Print This Report</button>
  <a href="<?= site_url('admin/finance?' . http_build_query(['from' => $fromDate, 'to' => $toDate, 'type' => $reportType])) ?>" style="margin-left:1rem">← Back to Reports</a>
</div>

<div class="report-header">
  <h1><?= e($logoText) ?></h1>
  <h2>Financial Report</h2>
  <p>Period: <?= e(date('d M Y', strtotime($fromDate))) ?> to <?= e(date('d M Y', strtotime($toDate))) ?>
  <?php if ($reportType !== 'all'): ?> | Type: <?= e(ucfirst($reportType)) ?><?php endif; ?></p>
</div>

<div class="report-meta">
  <span>Generated: <?= date('d M Y, h:i A') ?></span>
  <span>Report ID: FIN-<?= date('Ymd-His') ?></span>
</div>

<!-- Summary -->
<div class="summary-box">
  <div class="summary-item">
    <span>Total Income</span>
    <strong class="text-green"><?= format_inr($summary['total_income'] ?? 0) ?></strong>
  </div>
  <div class="summary-item">
    <span>Total Expenses</span>
    <strong class="text-red"><?= format_inr($summary['total_expenses'] ?? 0) ?></strong>
  </div>
  <div class="summary-item">
    <span>Net Balance</span>
    <strong class="<?= ($summary['net_balance'] ?? 0) >= 0 ? 'text-green' : 'text-red' ?>"><?= format_inr($summary['net_balance'] ?? 0) ?></strong>
  </div>
  <div class="summary-item">
    <span>Outstanding Dues</span>
    <strong class="text-orange"><?= format_inr($summary['outstanding'] ?? 0) ?></strong>
  </div>
</div>

<!-- Fee Type Breakdown -->
<?php if ($reportType !== 'expense' && $feeBreakdown): ?>
<div class="section-title">Fee Collection Summary (By Type)</div>
<table>
  <thead><tr><th>Fee Type</th><th class="text-right">Transactions</th><th class="text-right">Billed</th><th class="text-right">Collected</th><th class="text-right">Due</th></tr></thead>
  <tbody>
  <?php $tb = 0; $tc = 0; $td = 0; foreach ($feeBreakdown as $fb): $tb += (float)($fb['total_billed']??0); $tc += (float)($fb['total_collected']??0); $td += (float)($fb['total_due']??0); ?>
  <tr>
    <td><?= e($fb['fee_type'] ?: 'Other') ?></td>
    <td class="text-right"><?= (int) $fb['count'] ?></td>
    <td class="text-right"><?= format_inr($fb['total_billed'] ?? 0) ?></td>
    <td class="text-right text-green"><?= format_inr($fb['total_collected'] ?? 0) ?></td>
    <td class="text-right text-orange"><?= format_inr($fb['total_due'] ?? 0) ?></td>
  </tr>
  <?php endforeach; ?>
  <tr style="font-weight:bold;background:#f9f9f9"><td>TOTAL</td><td></td><td class="text-right"><?= format_inr($tb) ?></td><td class="text-right text-green"><?= format_inr($tc) ?></td><td class="text-right text-orange"><?= format_inr($td) ?></td></tr>
  </tbody>
</table>
<?php endif; ?>

<!-- Trade-wise -->
<?php if ($reportType !== 'expense' && $tradeWise): ?>
<div class="section-title">Trade-wise Fee Collection</div>
<table>
  <thead><tr><th>Trade</th><th class="text-right">Students</th><th class="text-right">Collected</th><th class="text-right">Due</th></tr></thead>
  <tbody>
  <?php foreach ($tradeWise as $tw): ?>
  <tr>
    <td><?= e($tw['trade'] ?: 'Other') ?></td>
    <td class="text-right"><?= (int) $tw['count'] ?></td>
    <td class="text-right text-green"><?= format_inr($tw['total_collected'] ?? 0) ?></td>
    <td class="text-right text-orange"><?= format_inr($tw['total_due'] ?? 0) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<!-- Salary Breakdown -->
<?php if ($reportType !== 'income' && $salaryBreakdown): ?>
<div class="section-title">Salary Disbursement Summary (By Designation)</div>
<table>
  <thead><tr><th>Designation</th><th class="text-right">Slips</th><th class="text-right">Gross Pay</th><th class="text-right">Deductions</th><th class="text-right">Net Paid</th></tr></thead>
  <tbody>
  <?php $tg = 0; $tded = 0; $tn = 0; foreach ($salaryBreakdown as $sb): $tg += (float)($sb['total_gross']??0); $tded += (float)($sb['total_deductions']??0); $tn += (float)($sb['total_net']??0); ?>
  <tr>
    <td><?= e($sb['designation'] ?: 'Other') ?></td>
    <td class="text-right"><?= (int) $sb['slip_count'] ?></td>
    <td class="text-right"><?= format_inr($sb['total_gross'] ?? 0) ?></td>
    <td class="text-right text-orange"><?= format_inr($sb['total_deductions'] ?? 0) ?></td>
    <td class="text-right text-red"><?= format_inr($sb['total_net'] ?? 0) ?></td>
  </tr>
  <?php endforeach; ?>
  <tr style="font-weight:bold;background:#f9f9f9"><td>TOTAL</td><td></td><td class="text-right"><?= format_inr($tg) ?></td><td class="text-right text-orange"><?= format_inr($tded) ?></td><td class="text-right text-red"><?= format_inr($tn) ?></td></tr>
  </tbody>
</table>
<?php endif; ?>

<!-- Detailed Fee List -->
<?php if ($reportType !== 'expense' && $income): ?>
<div class="section-title">Fee Collection Details (<?= count($income) ?> Records)</div>
<table>
  <thead><tr><th>Date</th><th>Student</th><th>Trade</th><th>Type</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th>Method</th><th>Receipt</th></tr></thead>
  <tbody>
  <?php foreach ($income as $row): ?>
  <tr>
    <td><?= e(date('d/m/Y', strtotime($row['payment_date'] ?? $row['created_at'] ?? ''))) ?></td>
    <td><?= e($row['student_name'] ?? '') ?></td>
    <td><?= e($row['trade'] ?? '') ?></td>
    <td><?= e($row['fee_type'] ?? '') ?></td>
    <td class="text-right"><?= format_inr($row['amount'] ?? 0) ?></td>
    <td class="text-right text-green"><?= format_inr($row['paid_amount'] ?? 0) ?></td>
    <td><?= e($row['payment_method'] ?? '—') ?></td>
    <td><?= e($row['receipt_number'] ?? '—') ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<!-- Salary Detail -->
<?php if ($reportType !== 'income' && $expenses): ?>
<div class="section-title">Salary Disbursement Details (<?= count($expenses) ?> Records)</div>
<table>
  <thead><tr><th>Period</th><th>Employee</th><th>Code</th><th>Designation</th><th class="text-right">Gross</th><th class="text-right">Deductions</th><th class="text-right">Net Pay</th><th>Slip #</th></tr></thead>
  <tbody>
  <?php foreach ($expenses as $row): ?>
  <tr>
    <td><?= e(month_name($row['slip_month'] ?? 0)) ?> <?= (int) ($row['slip_year'] ?? 0) ?></td>
    <td><?= e($row['staff_name'] ?? '') ?></td>
    <td><?= e($row['employee_code'] ?? '') ?></td>
    <td><?= e($row['designation'] ?? '') ?></td>
    <td class="text-right"><?= format_inr($row['gross_pay'] ?? 0) ?></td>
    <td class="text-right text-orange"><?= format_inr($row['total_deductions'] ?? 0) ?></td>
    <td class="text-right text-red"><?= format_inr($row['net_pay'] ?? 0) ?></td>
    <td><?= e($row['slip_number'] ?? '—') ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<div class="report-footer">
  <span><?= e($logoText) ?> — Financial Report</span>
  <span>This is a computer-generated document. No signature required.</span>
  <span>Page 1</span>
</div>
