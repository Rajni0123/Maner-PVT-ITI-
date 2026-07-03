<h1>Students</h1>
<form method="get" class="card filter-bar">
  <div style="flex:1;min-width:200px">
    <label>Search</label>
    <input name="q" value="<?= e($q ?? '') ?>" placeholder="Search name, mobile, enrollment...">
  </div>
  <button class="btn btn-sm btn-primary">Search</button>
</form>
<div class="table-wrap">
<table>
<thead><tr><th>Name</th><th>Enrollment</th><th>Trade</th><th>Session</th><th>Status</th><th></th></tr></thead>
<tbody>
<?php foreach ($students as $s): ?>
<tr>
  <td><?= e($s['student_name']) ?></td>
  <td><?= e($s['enrollment_number'] ?? '—') ?></td>
  <td><?= e($s['trade']) ?></td>
  <td><?= e($s['session'] ?? '—') ?></td>
  <td><?= e($s['status']) ?></td>
  <td>
    <a href="<?= site_url('admin/students/view/' . $s['id']) ?>" class="btn btn-sm btn-primary">View</a>
    <a href="<?= site_url('admin/fees/collect?student_id=' . $s['id']) ?>" class="btn btn-sm btn-secondary">Collect Fee</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
