<h1>Academic Sessions</h1>
<form method="post" action="<?= site_url('admin/sessions') ?>" class="card">
  <?= csrf_field() ?>
  <h3>Add Session</h3>
  <div class="form-grid">
    <div><label>Session Name (e.g. 2026-28)</label><input name="session_name" required></div>
    <div><label>Start Year</label><input type="number" name="start_year" value="<?= date('Y') ?>" required></div>
    <div><label>End Year</label><input type="number" name="end_year" value="<?= date('Y') + 2 ?>" required></div>
    <div><label><input type="checkbox" name="is_active" value="1" checked> Active</label></div>
  </div>
  <button class="btn btn-primary" style="margin-top:1rem">Add Session</button>
</form>
<div class="table-wrap">
<table>
<thead><tr><th>Session</th><th>Years</th><th>Active</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $s): ?>
<tr>
  <td><?= e($s['session_name']) ?></td>
  <td><?= e($s['start_year']) ?>-<?= e($s['end_year']) ?></td>
  <td><?= $s['is_active'] ? 'Yes' : 'No' ?></td>
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
