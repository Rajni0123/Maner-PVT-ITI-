<h1>Faculty</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/faculty') ?>" enctype="multipart/form-data" class="card">
    <h3>Add / Edit Faculty</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="facId" value="">
    <div class="form-grid">
      <div><label>Name</label><input name="name" id="facName" required></div>
      <div><label>Designation</label><input name="designation" id="facDesig" required></div>
      <div><label>Department</label><input name="department" id="facDept" required></div>
      <div><label>Qualification</label><input name="qualification" id="facQual"></div>
      <div><label>Experience</label><input name="experience" id="facExp"></div>
      <div><label>Email</label><input name="email" id="facEmail"></div>
      <div><label>Phone</label><input name="phone" id="facPhone"></div>
      <div><label>Display Order</label><input type="number" name="display_order" id="facOrder" value="0"></div>
    </div>
    <label style="margin-top:1rem">Bio</label><textarea name="bio" id="facBio" rows="3"></textarea>
    <label style="margin-top:1rem">Photo</label><input type="file" name="image" accept="image/*">
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_active" value="1" checked> Active
    </label>
    <label style="display:flex;align-items:center;gap:.5rem;margin-top:.5rem">
      <input type="checkbox" name="is_principal" value="1"> Principal
    </label>
    <button class="btn btn-primary" style="margin-top:1rem">Save</button>
  </form>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Name</th><th>Designation</th><th>Order</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($items as $f): ?>
      <tr>
        <td><?= e($f['name']) ?><?= !empty($f['is_principal']) ? ' ★' : '' ?></td>
        <td><?= e($f['designation']) ?></td>
        <td><?= (int) $f['display_order'] ?></td>
        <td class="flex gap-2">
          <button type="button" class="btn btn-sm" onclick="editFac(<?= e(json_encode($f)) ?>)">Edit</button>
          <form method="post" action="<?= site_url('admin/faculty/delete/' . $f['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
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
function editFac(row) {
  document.getElementById('facId').value = row.id;
  document.getElementById('facName').value = row.name || '';
  document.getElementById('facDesig').value = row.designation || '';
  document.getElementById('facDept').value = row.department || '';
  document.getElementById('facQual').value = row.qualification || '';
  document.getElementById('facExp').value = row.experience || '';
  document.getElementById('facEmail').value = row.email || '';
  document.getElementById('facPhone').value = row.phone || '';
  document.getElementById('facBio').value = row.bio || '';
  document.getElementById('facOrder').value = row.display_order || 0;
}
</script>
