<?php
$header = \App\Models\SiteData::header();
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
?>
<div class="page report-page">
  <div class="brand-top-line"></div>
  <header class="form-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="form-title">Students List</div>
  </header>

  <div class="meta-bar">
    <div class="meta-cell">
      <span class="lbl">Session</span>
      <span class="val"><?= e(($filterSession ?? '') !== '' ? $filterSession : 'All Sessions') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Status</span>
      <span class="val"><?= e(($filterStatus ?? '') !== '' ? $filterStatus : 'All') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Total</span>
      <span class="val"><?= (int) count($students) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Printed</span>
      <span class="val"><?= date('d-m-Y') ?></span>
    </div>
  </div>

  <table class="print-table">
    <thead>
      <tr>
        <th style="width:4%">#</th>
        <th>Name</th>
        <th>Father</th>
        <th>Mobile</th>
        <th>Enrollment</th>
        <th>Trade</th>
        <th>Session</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($students)): ?>
      <tr><td colspan="8" style="text-align:center">No students found.</td></tr>
    <?php else: ?>
      <?php foreach ($students as $i => $s): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($s['student_name']) ?></td>
        <td><?= e($s['father_name'] ?? '—') ?></td>
        <td><?= e(format_mobile($s['mobile'] ?? '')) ?></td>
        <td><?= e($s['enrollment_number'] ?? '—') ?></td>
        <td><?= e($s['trade'] ?? '—') ?></td>
        <td><?= e($s['session'] ?? '—') ?></td>
        <td><?= e($s['status'] ?? '—') ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
</div>
