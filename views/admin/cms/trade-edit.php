<h1><?= e($title) ?></h1>
<form method="post" action="<?= site_url('admin/trades') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int) ($trade['id'] ?? 0) ?>">
  <div class="form-grid">
    <div><label>Trade Name</label><input name="name" value="<?= e($trade['name'] ?? '') ?>" required></div>
    <div><label>Slug</label><input name="slug" value="<?= e($trade['slug'] ?? '') ?>" placeholder="auto-from-name"></div>
    <div><label>Category</label><input name="category" value="<?= e($trade['category'] ?? 'Engineering') ?>"></div>
    <div><label>Duration</label><input name="duration" value="<?= e($trade['duration'] ?? '2 Years') ?>"></div>
    <div><label>Eligibility</label><input name="eligibility" value="<?= e($trade['eligibility'] ?? '10th Pass') ?>"></div>
    <div><label>Seats</label><input name="seats" value="<?= e($trade['seats'] ?? '60') ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>Description</label><textarea name="description" rows="3"><?= e($trade['description'] ?? '') ?></textarea></div>
  <div class="form-grid" style="margin-top:1rem">
    <div>
      <label>Card Image</label>
      <?php if (!empty($trade['image'])): ?><p><img src="<?= e(upload_url($trade['image'])) ?>" style="max-height:80px"></p><?php endif; ?>
      <input type="file" name="image" accept="image/*">
    </div>
    <div>
      <label>Syllabus PDF</label>
      <?php if (!empty($trade['syllabus_pdf'])): ?><p><a href="<?= e(upload_url($trade['syllabus_pdf'])) ?>" target="_blank">Current PDF</a></p><?php endif; ?>
      <input type="file" name="syllabus_pdf" accept=".pdf">
    </div>
    <div>
      <label>Prospectus PDF</label>
      <?php if (!empty($trade['prospectus_pdf'])): ?><p><a href="<?= e(upload_url($trade['prospectus_pdf'])) ?>" target="_blank">Current PDF</a></p><?php endif; ?>
      <input type="file" name="prospectus_pdf" accept=".pdf">
    </div>
  </div>
  <div style="margin-top:1rem">
    <label>Syllabus Page JSON (optional — overrides default syllabus layout)</label>
    <textarea name="syllabus_json" rows="8" placeholder='{"ncvt_code":"...","semesters":[...]}'><?= e($trade['syllabus_json'] ?? '') ?></textarea>
  </div>
  <div style="margin-top:1rem">
    <label>Careers JSON (optional)</label>
    <textarea name="careers_json" rows="4"><?= e($trade['careers_json'] ?? '') ?></textarea>
  </div>
  <label style="margin-top:1rem;display:flex;align-items:center;gap:.5rem">
    <input type="checkbox" name="is_active" value="1" <?= !isset($trade['is_active']) || $trade['is_active'] ? 'checked' : '' ?>> Active on website
  </label>
  <div style="margin-top:1rem;display:flex;gap:1rem">
    <button class="btn btn-primary">Save Trade</button>
    <a href="<?= site_url('admin/trades') ?>" class="btn">Cancel</a>
  </div>
</form>
