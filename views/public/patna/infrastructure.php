<?php
$navActive = 'gallery';
$images = $images ?? [];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Campus Gallery</h1>
    <p>Workshops, labs, and campus life</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <?php if (empty($images)): ?>
    <div class="pti-card"><p>Gallery images will appear here once uploaded in admin.</p></div>
    <?php else: ?>
    <div class="pti-why">
      <?php foreach ($images as $img):
        $src = !empty($img['image']) ? upload_url($img['image']) : (!empty($img['path']) ? upload_url($img['path']) : '');
        if ($src === '') continue;
      ?>
      <div class="pti-why__item" style="padding:0;overflow:hidden">
        <img src="<?= e($src) ?>" alt="<?= e($img['title'] ?? 'Gallery') ?>" style="width:100%;height:180px;object-fit:cover">
        <?php if (!empty($img['title'])): ?>
        <p style="padding:.75rem;margin:0"><?= e($img['title']) ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
