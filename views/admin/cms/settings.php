<h1>Site Settings</h1>
<form method="post" action="<?= site_url('admin/settings') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>

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
