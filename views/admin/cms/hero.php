<h1>Homepage Hero</h1>
<form method="post" action="<?= site_url('admin/hero') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>Title</label><input name="title" value="<?= e($hero['title'] ?? 'Master Your Trade, Shape Your Future') ?>" required></div>
    <div><label>Subtitle (eyebrow)</label><input name="subtitle" value="<?= e($hero['subtitle'] ?? '') ?>"></div>
    <div><label>CTA 1 Text</label><input name="cta_text" value="<?= e($hero['cta_text'] ?? 'Fast-Track Your Career') ?>"></div>
    <div><label>CTA 1 Link</label><input name="cta_link" value="<?= e($hero['cta_link'] ?? 'apply-admission') ?>" placeholder="apply-admission"></div>
    <div><label>CTA 2 Text</label><input name="cta2_text" value="<?= e($hero['cta2_text'] ?? 'Download Prospectus') ?>"></div>
    <div><label>CTA 2 Link</label><input name="cta2_link" value="<?= e($hero['cta2_link'] ?? 'fee-structure') ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>Description</label><textarea name="description" rows="4"><?= e($hero['description'] ?? '') ?></textarea></div>
  <div style="margin-top:1rem">
    <label>Background Image</label>
    <?php if (!empty($hero['background_image'])): ?>
    <p class="text-sm" style="margin:.5rem 0"><img src="<?= e(upload_url($hero['background_image'])) ?>" alt="" style="max-height:120px;border-radius:4px"></p>
    <?php endif; ?>
    <input type="file" name="background_image" accept="image/*">
  </div>
  <label style="margin-top:1rem;display:flex;align-items:center;gap:.5rem">
    <input type="checkbox" name="is_active" value="1" <?= !isset($hero['is_active']) || $hero['is_active'] ? 'checked' : '' ?>> Active
  </label>
  <button class="btn btn-primary" style="margin-top:1rem">Save Hero</button>
</form>
