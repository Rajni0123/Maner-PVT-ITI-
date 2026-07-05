<h1>Academic Sessions</h1>

<form method="post" action="<?= site_url('admin/sessions') ?>" class="card" style="margin-top:1rem">
  <?= csrf_field() ?>
  <h3>Add Session</h3>
  <div class="form-grid">
    <div><label>Session Name (e.g. 2026-28)</label><input name="session_name" required placeholder="2026-28"></div>
    <div><label>Start Year</label><input type="number" name="start_year" value="<?= date('Y') ?>" required></div>
    <div><label>End Year</label><input type="number" name="end_year" value="<?= date('Y') + 2 ?>" required></div>
    <div><label><input type="checkbox" name="is_active" value="1" checked> Active</label></div>
  </div>
  <button class="btn btn-primary" style="margin-top:1rem">Add Session</button>
</form>

<div class="table-wrap" style="margin-top:1.5rem">
<table>
<thead><tr><th>Session</th><th>Display</th><th>Years</th><th>Students</th><th>Admissions</th><th>Active</th><th>Manage</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $s): ?>
<?php
  $sn = trim((string) ($s['session_name'] ?? ''));
  $stat = $sessionStats[$sn] ?? ['students' => 0, 'admissions' => 0, 'pending' => 0];
?>
<tr>
  <td><strong><?= e($sn) ?></strong></td>
  <td><?= e(session_short_label($sn)) ?></td>
  <td><?= e($s['start_year']) ?>-<?= e($s['end_year']) ?></td>
  <td><?= (int) $stat['students'] ?></td>
  <td><?= (int) $stat['admissions'] ?></td>
  <td><?= $s['is_active'] ? 'Yes' : 'No' ?></td>
  <td>
    <div class="action-btns">
      <a href="<?= e(admin_session_query('admin/students', $sn)) ?>" class="btn btn-sm btn-primary">Students</a>
      <a href="<?= e(admin_session_query('admin/admissions', $sn)) ?>" class="btn btn-sm btn-secondary">Admissions</a>
      <a href="<?= e(admin_session_query('admin/fees/report', $sn)) ?>" class="btn btn-sm btn-outline">Fee Report</a>
    </div>
  </td>
  <td>
    <form method="post" action="<?= site_url('admin/sessions/delete/' . $s['id']) ?>" data-confirm="Delete this session?"><?= csrf_field() ?>
      <button class="btn btn-sm btn-danger">Delete</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
