<h1>Site Settings</h1>
<form method="post" action="<?= site_url('admin/settings') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>

  <?php
    $activeTemplate = $settings['public_template'] ?? 'modern';
    if (!in_array($activeTemplate, ['modern', 'patna'], true)) {
        $activeTemplate = 'modern';
    }
    $templates = \App\Models\SiteData::availableTemplates();
  ?>
  <h3>Website Template</h3>
  <p style="color:#64748b;margin:0 0 1rem;font-size:.95rem">Choose which public website design visitors see. Admin panel stays the same.</p>
  <div class="template-picker" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:1.5rem">
    <?php foreach ($templates as $tpl): ?>
    <label style="display:block;border:2px solid <?= $activeTemplate === $tpl['key'] ? '#0f172a' : '#e2e8f0' ?>;border-radius:10px;padding:1rem 1.1rem;cursor:pointer;background:<?= $activeTemplate === $tpl['key'] ? '#f8fafc' : '#fff' ?>;transition:border-color .15s">
      <div style="display:flex;align-items:flex-start;gap:.75rem">
        <input type="radio" name="settings[public_template]" value="<?= e($tpl['key']) ?>" <?= $activeTemplate === $tpl['key'] ? 'checked' : '' ?> style="margin-top:.25rem">
        <div>
          <strong style="display:block;font-size:1.05rem"><?= e($tpl['label']) ?></strong>
          <span style="display:block;color:#64748b;font-size:.9rem;margin-top:.35rem;line-height:1.45"><?= e($tpl['description']) ?></span>
          <?php if ($activeTemplate === $tpl['key']): ?>
          <span style="display:inline-block;margin-top:.6rem;font-size:.75rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;background:#0f172a;color:#fff;padding:.25rem .55rem;border-radius:4px">Active</span>
          <?php endif; ?>
        </div>
      </div>
    </label>
    <?php endforeach; ?>
  </div>
  <p style="margin:0 0 1.5rem">
    <a href="<?= site_url() ?>" target="_blank" rel="noopener">Preview public site ↗</a>
  </p>

  <h3>Header</h3>
  <div class="form-grid">
    <div><label>Phone</label><input name="phone" value="<?= e($header['phone'] ?? '') ?>"></div>
    <div><label>Email</label><input name="header_email" value="<?= e($header['email'] ?? '') ?>"></div>
    <div><label>Logo Text</label><input name="logo_text" value="<?= e($header['logo_text'] ?? '') ?>"></div>
    <div><label>Tagline</label><input name="tagline" value="<?= e($header['tagline'] ?? '') ?>"></div>
    <div><label>Student Portal Text</label><input name="student_portal_text" value="<?= e($header['student_portal_text'] ?? '') ?>"></div>
    <div><label>Student Portal Link</label><input name="student_portal_link" value="<?= e($header['student_portal_link'] ?? '') ?>"></div>
    <div><label>NCVT MIS Text</label><input name="ncvt_mis_text" value="<?= e($header['ncvt_mis_text'] ?? '') ?>"></div>
    <div><label>NCVT MIS Link</label><input name="ncvt_mis_link" value="<?= e($header['ncvt_mis_link'] ?? '') ?>"></div>
  </div>

  <h3 style="margin-top:1.5rem">Footer</h3>
  <div class="form-grid">
    <div><label>Footer Phone</label><input name="footer_phone" value="<?= e($footer['phone'] ?? '') ?>"></div>
    <div><label>Footer Email</label><input name="footer_email" value="<?= e($footer['email'] ?? '') ?>"></div>
    <div><label>Copyright Text</label><input name="copyright_text" value="<?= e($footer['copyright_text'] ?? '') ?>"></div>
    <div><label>Privacy Link</label><input name="privacy_link" value="<?= e($footer['privacy_link'] ?? '') ?>"></div>
    <div><label>Terms Link</label><input name="terms_link" value="<?= e($footer['terms_link'] ?? '') ?>"></div>
    <div><label>Facebook</label><input name="facebook_link" value="<?= e($footer['facebook_link'] ?? '') ?>"></div>
    <div><label>YouTube</label><input name="youtube_link" value="<?= e($footer['youtube_link'] ?? '') ?>"></div>
    <div><label>LinkedIn</label><input name="linkedin_link" value="<?= e($footer['linkedin_link'] ?? '') ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>About Text</label><textarea name="about_text" rows="3"><?= e($footer['about_text'] ?? '') ?></textarea></div>
  <div style="margin-top:1rem"><label>Address</label><textarea name="address" rows="2"><?= e($footer['address'] ?? '') ?></textarea></div>

  <h3 style="margin-top:1.5rem">SEO &amp; Content</h3>
  <div class="form-grid">
    <div><label>Site Title</label><input name="settings[seo_title]" value="<?= e($settings['seo_title'] ?? '') ?>"></div>
    <div><label>SEO Description</label><input name="settings[seo_description]" value="<?= e($settings['seo_description'] ?? '') ?>"></div>
    <div><label>Header Announcement</label><input name="settings[header_text]" value="<?= e($settings['header_text'] ?? '') ?>"></div>
    <div><label>Principal Name</label><input name="settings[principal_name]" value="<?= e($settings['principal_name'] ?? '') ?>"></div>
    <div><label>MIS Code</label><input name="settings[mis_code]" value="<?= e($settings['mis_code'] ?? 'PR10001156') ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>Principal Message</label><textarea name="settings[principal_message]"><?= e($settings['principal_message'] ?? '') ?></textarea></div>

  <h3 style="margin-top:1.5rem">Fee Structure</h3>
  <?php if (!empty($settings['fee_structure_pdf'])): ?>
  <p><a href="<?= e(upload_url($settings['fee_structure_pdf'])) ?>" target="_blank">Current Fee PDF</a></p>
  <?php endif; ?>
  <label>Fee Structure PDF</label><input type="file" name="fee_structure_pdf" accept=".pdf">
  <div style="margin-top:1rem">
    <label>Fee Structure JSON (per-trade fees shown on /fee-structure)</label>
    <textarea name="settings[fee_structure_json]" rows="8" placeholder='{"electrician":{"rows":[...],"total":"₹ 34,000"}}'><?= e($settings['fee_structure_json'] ?? '') ?></textarea>
  </div>

  <button class="btn btn-primary" style="margin-top:1rem">Save Settings</button>
</form>
