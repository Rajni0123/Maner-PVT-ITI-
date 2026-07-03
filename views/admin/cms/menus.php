<h1>Navigation Menus</h1>
<p style="margin:0 0 1rem;color:var(--admin-on-surface-variant);font-size:0.9rem">
  Parent menus appear in the top bar. Sub menus show as dropdown under the parent (e.g. Important Links).
</p>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/menus') ?>" class="card">
    <h3>Add / Edit Menu</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="menuId" value="">
    <label>Title</label><input name="title" id="menuTitle" required>
    <label>URL</label><input name="url" id="menuUrl" placeholder="trades or https://ncvtmis.gov.in" required>
    <label>Parent Menu (for submenu)</label>
    <select name="parent_id" id="menuParent">
      <option value="">— Top Level Menu —</option>
      <?php foreach ($items as $m): ?>
        <?php if (empty($m['parent_id'])): ?>
        <option value="<?= (int) $m['id'] ?>"><?= e($m['title']) ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <label>Order</label><input type="number" name="order_index" id="menuOrder" value="0">
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_active" id="menuActive" value="1" checked> Active
    </label>
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Title</th><th>URL</th><th>Parent</th><th>Order</th><th></th></tr></thead>
      <tbody>
      <?php
      $parents = [];
      foreach ($items as $m) {
          if (empty($m['parent_id'])) {
              $parents[(int) $m['id']] = $m['title'];
          }
      }
      ?>
      <?php foreach ($items as $m): ?>
      <tr>
        <td>
          <?php if (!empty($m['parent_id'])): ?>
          <span style="opacity:0.5;margin-right:0.35rem">↳</span>
          <?php endif; ?>
          <?= e($m['title']) ?>
        </td>
        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($m['url']) ?></td>
        <td><?= !empty($m['parent_id']) ? e($parents[(int) $m['parent_id']] ?? '—') : '—' ?></td>
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
  document.getElementById('menuParent').value = row.parent_id || '';
  document.getElementById('menuActive').checked = Number(row.is_active) === 1;
}
</script>
