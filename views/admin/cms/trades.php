<h1>Trade Courses</h1>
<p style="margin-bottom:1rem"><a href="<?= site_url('admin/trades/edit') ?>" class="btn btn-primary">Add Trade</a></p>
<div class="table-wrap">
  <table>
    <thead><tr><th>Name</th><th>Slug</th><th>Category</th><th>Seats</th><th>Active</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $t): ?>
    <tr>
      <td><?= e($t['name']) ?></td>
      <td><?= e($t['slug']) ?></td>
      <td><?= e($t['category']) ?></td>
      <td><?= e($t['seats']) ?></td>
      <td><?= !empty($t['is_active']) ? 'Yes' : 'No' ?></td>
      <td class="flex gap-2">
        <a href="<?= site_url('admin/trades/edit/' . $t['id']) ?>" class="btn btn-sm">Edit</a>
        <form method="post" action="<?= site_url('admin/trades/delete/' . $t['id']) ?>" data-confirm="Delete trade?"><?= csrf_field() ?>
          <button class="btn btn-sm btn-danger">Del</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
