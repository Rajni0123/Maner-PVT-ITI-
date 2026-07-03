<h1>Gallery</h1>
<div class="grid-2">
  <form method="post" action="<?= site_url('admin/gallery') ?>" enctype="multipart/form-data" class="card">
    <h3>Upload Image</h3>
    <?= csrf_field() ?>
    <label>Category</label><input name="category" value="General">
    <label>Image</label><input type="file" name="image" accept="image/*" required>
    <button class="btn btn-primary" style="margin-top:1rem">Upload</button>
  </form>
  <div class="gallery-grid">
    <?php foreach ($items as $img): ?>
    <div class="card" style="padding:.5rem">
      <img src="<?= upload_url($img['image']) ?>" alt="">
      <form method="post" action="<?= site_url('admin/gallery/delete/' . $img['id']) ?>" data-confirm="Delete?"><?= csrf_field() ?>
        <button class="btn btn-sm btn-danger" style="margin-top:.5rem;width:100%">Delete</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</div>
