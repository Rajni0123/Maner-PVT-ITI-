<?php
$qs = http_build_query(array_filter([
    'session' => $filterSession ?? '',
    'status' => $filterStatus ?? '',
    'q' => $q ?? '',
], static fn($v) => $v !== '' && $v !== null));
$exportQs = $qs !== '' ? '?' . $qs : '';
$extraQuery = array_filter([
    'status' => $filterStatus ?? '',
    'q' => $q ?? '',
], static fn($v) => $v !== '' && $v !== null);
?>
<div class="admin-page-header">
  <h1>Students</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/students/export' . $exportQs) ?>" class="btn btn-outline btn-sm">Export Excel</a>
    <a href="<?= site_url('admin/students/print' . $exportQs) ?>" target="_blank" class="btn btn-outline btn-sm">Export PDF</a>
  </div>
</div>

<?php require base_path('views/partials/admin-session-tabs.php'); ?>

<form method="get" class="card filter-bar">
  <input type="hidden" name="session" value="<?= e($filterSession ?? '') ?>">
  <div>
    <label>Status</label>
    <select name="status">
      <option value="">All Status</option>
      <?php foreach (['Active', 'Inactive', 'Completed'] as $st): ?>
      <option value="<?= $st ?>" <?= ($filterStatus ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div style="flex:1;min-width:200px">
    <label>Search</label>
    <input name="q" value="<?= e($q ?? '') ?>" placeholder="Name, mobile, enrollment, father...">
  </div>
  <button class="btn btn-sm btn-primary">Filter</button>
  <?php if (($filterSession ?? '') !== '' || ($filterStatus ?? '') !== '' || ($q ?? '') !== ''): ?>
  <a href="<?= site_url('admin/students') ?>" class="btn btn-sm btn-outline">Clear All</a>
  <?php endif; ?>
</form>

<p class="text-muted" style="margin:0.5rem 0 1rem">
  Showing <strong><?= (int) count($students) ?></strong>
  of <strong><?= (int) ($totalCount ?? count($students)) ?></strong> students
  <?php if (($filterSession ?? '') !== ''): ?>
  · Session <strong><?= e(session_short_label($filterSession)) ?></strong>
  <?php endif; ?>
</p>

<div class="table-wrap">
<table>
<thead>
<tr>
  <th>#</th>
  <th>Name</th>
  <th>Father</th>
  <th>Mobile</th>
  <th>Enrollment</th>
  <th>Trade</th>
  <th>Session</th>
  <th>Status</th>
  <th></th>
</tr>
</thead>
<tbody>
<?php if (empty($students)): ?>
<tr><td colspan="9" style="text-align:center;padding:1.5rem;color:#64748b">
  <?php if (($filterSession ?? '') !== ''): ?>
  No students found for session <?= e(session_short_label($filterSession)) ?>.
  <?php else: ?>
  No students found for this filter.
  <?php endif; ?>
</td></tr>
<?php else: ?>
<?php foreach ($students as $i => $s): ?>
<tr>
  <td><?= $i + 1 ?></td>
  <td><?= e($s['student_name']) ?></td>
  <td><?= e($s['father_name'] ?? '—') ?></td>
  <td><?= e(format_mobile($s['mobile'] ?? '')) ?></td>
  <td><?= e($s['enrollment_number'] ?? '—') ?></td>
  <td><?= e($s['trade']) ?></td>
  <td><?= e(session_short_label($s['session'] ?? '') ?: '—') ?></td>
  <td><?= e($s['status']) ?></td>
  <td>
    <a href="<?= site_url('admin/students/view/' . $s['id']) ?>" class="btn btn-sm btn-primary">View</a>
    <a href="<?= site_url('admin/fees/collect?student_id=' . $s['id']) ?>" class="btn btn-sm btn-secondary">Collect Fee</a>
  </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
