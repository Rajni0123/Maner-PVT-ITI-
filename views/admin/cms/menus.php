<h1>Navigation Menus</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/menus') ?>" class="card">
    <h3>Add / Edit Menu</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="menuId" value="">
    <label>Title</label><input name="title" id="menuTitle" required>
    <label>URL</label><input name="url" id="menuUrl" placeholder="trades or /contact" required>
    <label>Order</label><input type="number" name="order_index" id="menuOrder" value="0">
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_active" value="1" checked> Active
    </label>
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Title</th><th>URL</th><th>Order</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($items as $m): ?>
      <tr>
        <td><?= e($m['title']) ?></td>
        <td><?= e($m['url']) ?></td>
        <td><?= (int) $m['order_index'] ?></td>
        <td class="flex gap-2">
          <button type="button" class="btn btn-sm" onclick="editMenu(<?= e(json_encode($m)) ?>)">Edit</button>
          <form method="post" action="<?= site_url('admin/menus/delete/' . $m['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
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
function editMenu(row) {
  document.getElementById('menuId').value = row.id;
  document.getElementById('menuTitle').value = row.title || '';
  document.getElementById('menuUrl').value = row.url || '';
  document.getElementById('menuOrder').value = row.order_index || 0;
}
</script>
