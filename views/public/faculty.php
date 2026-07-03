<div class="page-header"><div class="container"><h1>Faculty</h1></div></div>
<section class="section"><div class="container grid-3">
  <?php foreach ($faculty as $f): ?>
  <div class="card">
    <h3><?= e($f['name']) ?><?= $f['is_principal'] ? ' ⭐' : '' ?></h3>
    <p><strong><?= e($f['designation']) ?></strong></p>
    <p><?= e($f['department']) ?></p>
    <p><?= e($f['qualification']) ?></p>
  </div>
  <?php endforeach; ?>
</div></section>
