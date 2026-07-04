<div class="page-header"><div class="container"><h1>Notice Board</h1></div></div>
<section class="section"><div class="container">
  <?php foreach ($notices as $n): ?>
  <div class="card" style="margin-bottom:1rem">
    <h3><?= e($n['title']) ?></h3>
    <p><?= nl2br(e($n['description'])) ?></p>
    <small><?= format_date($n['created_at']) ?></small>
    <?php if ($n['pdf']): ?><p><a href="<?= upload_url($n['pdf']) ?>" target="_blank">Download PDF</a></p><?php endif; ?>
  </div>
  <?php endforeach; ?>
</div></section>
