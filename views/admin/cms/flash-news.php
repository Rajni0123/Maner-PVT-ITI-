<h1>News Ticker</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/flash-news') ?>" class="card">
    <h3>Add / Edit Item</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="flashId" value="">
    <label>Title</label><input name="title" id="flashTitle" required>
    <label>Content</label><textarea name="content" id="flashContent"></textarea>
    <label>Link (optional)</label><input name="link" id="flashLink" placeholder="apply-admission">
    <label>Order</label><input type="number" name="order_index" id="flashOrder" value="0">
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_active" value="1" checked> Active
    </label>
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Title</th><th>Order</th><th>Active</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($items as $n): ?>
      <tr>
        <td><?= e($n['title']) ?></td>
        <td><?= (int) $n['order_index'] ?></td>
        <td><?= !empty($n['is_active']) ? 'Yes' : 'No' ?></td>
        <td class="flex gap-2">
          <button type="button" class="btn btn-sm" onclick="editFlash(<?= e(json_encode($n)) ?>)">Edit</button>
          <form method="post" action="<?= site_url('admin/flash-news/delete/' . $n['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
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
function editFlash(row) {
  document.getElementById('flashId').value = row.id;
  document.getElementById('flashTitle').value = row.title || '';
  document.getElementById('flashContent').value = row.content || '';
  document.getElementById('flashLink').value = row.link || '';
  document.getElementById('flashOrder').value = row.order_index || 0;
}
</script>
