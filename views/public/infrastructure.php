<div class="page-header"><div class="container"><h1>Infrastructure / Gallery</h1></div></div>
<section class="section"><div class="container gallery-grid">
  <?php foreach ($images as $img): ?>
    <div><img src="<?= upload_url($img['image']) ?>" alt="<?= e($img['category']) ?>"><p><?= e($img['category']) ?></p></div>
  <?php endforeach; ?>
  <?php if (empty($images)): ?><p>No images uploaded yet.</p><?php endif; ?>
</div></section>
