<div class="admin-page-header">
  <h1>Admissions</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/admissions/add') ?>" class="btn btn-primary btn-sm">+ Add Admission</a>
    <a href="<?= site_url('admin/admissions/export') ?>" class="btn btn-outline btn-sm">Export CSV</a>
  </div>
</div>
<form method="get" class="card filter-bar">
  <div>
    <label>Status</label>
    <select name="status">
      <option value="">All Status</option>
      <?php foreach (['pending', 'approved', 'rejected'] as $s): ?>
      <option value="<?= $s ?>" <?= ($filterStatus ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label>Trade</label>
    <input name="trade" placeholder="Trade filter" value="<?= e($filterTrade ?? '') ?>">
  </div>
  <button class="btn btn-primary btn-sm">Filter</button>
</form>
<div class="table-wrap">
<table>
<thead><tr><th>ID</th><th>Name</th><th>Mobile</th><th>Trade</th><th>Session</th><th>BSCC</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php foreach ($admissions as $a): ?>
<tr>
  <td><?= e(app_id($a['id'], $a['created_at'] ?? null, $a['session'] ?? null)) ?></td>
  <td><?= e($a['name']) ?></td>
  <td><?= e(format_mobile($a['mobile'] ?? '')) ?></td>
  <td><?= e($a['trade']) ?></td>
  <td><?= e($a['session'] ?? '—') ?></td>
  <td><?= e($a['student_credit_card'] ?? 'No') ?></td>
  <td><span class="badge badge-<?= strtolower($a['status']) ?>"><?= e($a['status']) ?></span></td>
  <td>
    <div class="action-btns">
      <a href="<?= site_url('admin/admissions/view/' . $a['id']) ?>" class="btn btn-sm btn-primary">View</a>
      <?php if (strtolower((string) $a['status']) === 'pending'): ?>
      <form method="post" action="<?= site_url('admin/admissions/status/' . $a['id']) ?>" style="display:inline">
        <?= csrf_field() ?>
        <input type="hidden" name="status" value="Approved">
        <input type="hidden" name="return" value="list">
        <button type="submit" class="btn btn-sm btn-success">Approve</button>
      </form>
      <form method="post" action="<?= site_url('admin/admissions/status/' . $a['id']) ?>" style="display:inline" data-confirm="Reject this application?">
        <?= csrf_field() ?>
        <input type="hidden" name="status" value="Rejected">
        <input type="hidden" name="return" value="list">
        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
      </form>
      <?php elseif (strtolower((string) $a['status']) === 'approved'): ?>
      <a href="<?= site_url('admin/fees/collect?admission_id=' . $a['id']) ?>" class="btn btn-sm btn-secondary">Collect Fee</a>
      <?php endif; ?>
    </div>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
