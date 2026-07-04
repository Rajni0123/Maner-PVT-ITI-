<h1>Notices</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/notices') ?>" enctype="multipart/form-data" class="card">
    <h3>Add Notice</h3>
    <?= csrf_field() ?>
    <label>Title</label><input name="title" required>
    <label>Description</label><textarea name="description" required></textarea>
    <label>PDF (optional)</label><input type="file" name="pdf" accept=".pdf,image/*">
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
    <thead><tr><th>Title</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $n): ?>
    <tr>
      <td><?= e($n['title']) ?></td>
      <td><?= format_date($n['created_at']) ?></td>
      <td>
        <form method="post" action="<?= site_url('admin/notices/delete/' . $n['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
          <button class="btn btn-sm btn-danger">Del</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</div>
