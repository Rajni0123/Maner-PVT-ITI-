<h1>Academic Sessions</h1>
<p class="text-muted" style="margin:0 0 1.25rem">Session pe click karke us session ke students aur admissions manage karein.</p>

<?php if (!empty($items)): ?>
<div class="session-manage-grid">
  <?php foreach ($items as $s): ?>
  <?php
    $sn = trim((string) ($s['session_name'] ?? ''));
    $stat = $sessionStats[$sn] ?? ['students' => 0, 'admissions' => 0, 'pending' => 0];
  ?>
  <div class="session-manage-card">
    <div class="session-manage-card-head">
      <div>
        <h3><?= e(session_short_label($sn)) ?></h3>
        <p class="session-full-name"><?= e($sn) ?> · <?= e($s['start_year']) ?>–<?= e($s['end_year']) ?></p>
      </div>
      <?php if ($s['is_active']): ?>
      <span class="session-manage-badge">Active</span>
      <?php else: ?>
      <span class="session-manage-badge session-manage-badge-inactive">Inactive</span>
      <?php endif; ?>
    </div>
    <div class="session-manage-stats<?= (int) $stat['pending'] > 0 ? ' has-pending' : '' ?>">
      <div class="session-stat-box">
        <span class="session-stat-value"><?= (int) $stat['students'] ?></span>
        <span class="session-stat-label">Students</span>
      </div>
      <div class="session-stat-box">
        <span class="session-stat-value"><?= (int) $stat['admissions'] ?></span>
        <span class="session-stat-label">Admissions</span>
      </div>
      <?php if ((int) $stat['pending'] > 0): ?>
      <div class="session-stat-box session-stat-box-warn">
        <span class="session-stat-value"><?= (int) $stat['pending'] ?></span>
        <span class="session-stat-label">Pending</span>
      </div>
      <?php endif; ?>
    </div>
    <div class="session-manage-actions">
      <a href="<?= e(admin_session_query('admin/students', $sn)) ?>" class="btn btn-sm btn-primary">Students</a>
      <a href="<?= e(admin_session_query('admin/admissions', $sn)) ?>" class="btn btn-sm btn-secondary">Admissions</a>
      <a href="<?= e(admin_session_query('admin/fees/report', $sn)) ?>" class="btn btn-sm btn-outline">Fee Report</a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/sessions') ?>" class="card">
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
    <a href="<?= e(admin_session_query('admin/students', $sn)) ?>" class="btn btn-sm btn-primary">Students</a>
    <a href="<?= e(admin_session_query('admin/admissions', $sn)) ?>" class="btn btn-sm btn-secondary">Admissions</a>
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
