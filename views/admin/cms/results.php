<h1>Results</h1>
<form method="post" action="<?= site_url('admin/results') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <h3>Add Result</h3>
  <div class="form-grid">
    <div><label>Title</label><input name="title" required></div>
    <div><label>Trade</label><input name="trade" required></div>
    <div><label>Year</label><input name="year" required></div>
    <div><label>PDF</label><input type="file" name="pdf" accept=".pdf" required></div>
  </div>
  <button class="btn btn-primary" style="margin-top:1rem">Add Result</button>
</form>
<div class="table-wrap">
<table>
<thead><tr><th>Title</th><th>Trade</th><th>Year</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $r): ?>
<tr>
  <td><?= e($r['title']) ?></td>
  <td><?= e($r['trade']) ?></td>
  <td><?= e($r['year']) ?></td>
  <td>
    <form method="post" action="<?= site_url('admin/results/delete/' . $r['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
      <button class="btn btn-sm btn-danger">Del</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
