<h1>Footer Links</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/footer-links') ?>" class="card">
    <h3>Add / Edit Link</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="flId" value="">
    <label>Title</label><input name="title" id="flTitle" required>
    <label>URL</label><input name="url" id="flUrl" required>
    <label>Category</label>
    <select name="category" id="flCategory">
      <option value="quick_links">Quick Links</option>
      <option value="legal">Legal</option>
      <option value="resources">Resources</option>
    </select>
    <label>Order</label><input type="number" name="order_index" id="flOrder" value="0">
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_active" value="1" checked> Active
    </label>
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Title</th><th>URL</th><th>Category</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($items as $l): ?>
      <tr>
        <td><?= e($l['title']) ?></td>
        <td><?= e($l['url']) ?></td>
        <td><?= e($l['category']) ?></td>
        <td class="flex gap-2">
          <button type="button" class="btn btn-sm" onclick="editFl(<?= e(json_encode($l)) ?>)">Edit</button>
          <form method="post" action="<?= site_url('admin/footer-links/delete/' . $l['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Del</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
function editFl(row) {
  document.getElementById('flId').value = row.id;
  document.getElementById('flTitle').value = row.title || '';
  document.getElementById('flUrl').value = row.url || '';
  document.getElementById('flCategory').value = row.category || 'quick_links';
  document.getElementById('flOrder').value = row.order_index || 0;
}
</script>
