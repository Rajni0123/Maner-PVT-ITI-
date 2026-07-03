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

  <h3 style="margin-top:1.5rem">Newsletter</h3>
  <div class="form-grid">
    <div style="display:flex;align-items:center;gap:0.5rem">
      <input type="checkbox" name="newsletter_enabled" id="newsletter_enabled" value="1" <?= ($settings['newsletter_enabled'] ?? '1') === '1' ? 'checked' : '' ?>>
      <label for="newsletter_enabled" style="margin:0">Show newsletter form in footer</label>
    </div>
    <div><label>Newsletter Title</label><input name="newsletter_title" value="<?= e($settings['newsletter_title'] ?? 'Join Our Newsletter') ?>"></div>
    <div><label>Email Placeholder</label><input name="newsletter_placeholder" value="<?= e($settings['newsletter_placeholder'] ?? 'Email Address') ?>"></div>
  </div>
  <p style="margin-top:0.5rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
    Manage subscribers in <a href="<?= site_url('admin/newsletter') ?>">Newsletter</a> section.
  </p>

  <h3 style="margin-top:1.5rem">SEO &amp; Content</h3>
  <div class="form-grid">
    <div><label>Site Title</label><input name="settings[seo_title]" value="<?= e($settings['seo_title'] ?? '') ?>"></div>
    <div><label>SEO Description</label><input name="settings[seo_description]" value="<?= e($settings['seo_description'] ?? '') ?>"></div>
    <div><label>Header Announcement</label><input name="settings[header_text]" value="<?= e($settings['header_text'] ?? '') ?>"></div>
    <div><label>Principal Name</label><input name="settings[principal_name]" value="<?= e($settings['principal_name'] ?? '') ?>"></div>
    <div><label>MIS Code</label><input name="settings[mis_code]" value="<?= e($settings['mis_code'] ?? 'PR10001156') ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>Principal Message</label><textarea name="settings[principal_message]"><?= e($settings['principal_message'] ?? '') ?></textarea></div>

  <h3 style="margin-top:1.5rem">Website Branding</h3>
  <p style="margin:0 0 0.75rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
    Favicon browser tab icon hai. App logo mobile web app / home screen icon ke liye use hota hai (PNG recommended, square).
  </p>
  <div class="form-grid">
    <div>
      <label>Website Favicon</label>
      <?php if (!empty($settings['site_favicon']) && upload_exists($settings['site_favicon'])): ?>
      <p style="margin:0 0 0.5rem">
        <img src="<?= e(upload_url($settings['site_favicon'])) ?>" alt="Favicon" style="width:32px;height:32px;object-fit:contain;border:1px solid #ddd;background:#fff;padding:2px">
        <a href="<?= e(upload_url($settings['site_favicon'])) ?>" target="_blank" style="margin-left:0.5rem">View current</a>
      </p>
      <?php endif; ?>
      <input type="file" name="site_favicon" accept="image/png,image/jpeg,image/webp,image/x-icon,.ico,.png,.jpg,.jpeg,.webp">
    </div>
    <div>
      <label>Web App Logo</label>
      <?php if (!empty($settings['app_logo']) && upload_exists($settings['app_logo'])): ?>
      <p style="margin:0 0 0.5rem">
        <img src="<?= e(upload_url($settings['app_logo'])) ?>" alt="App logo" style="width:64px;height:64px;object-fit:contain;border:1px solid #ddd;background:#fff;padding:2px">
        <a href="<?= e(upload_url($settings['app_logo'])) ?>" target="_blank" style="margin-left:0.5rem">View current</a>
      </p>
      <?php endif; ?>
      <input type="file" name="app_logo" accept="image/png,image/jpeg,image/webp,.png,.jpg,.jpeg,.webp">
      <small style="display:block;margin-top:0.35rem;color:var(--admin-on-surface-variant)">Square image best (512×512 PNG)</small>
    </div>
  </div>

  <h3 style="margin-top:1.5rem">Fee Structure</h3>
  <?php if (!empty($settings['fee_structure_pdf'])): ?>
  <p><a href="<?= e(upload_url($settings['fee_structure_pdf'])) ?>" target="_blank">Current Fee PDF</a></p>
  <?php endif; ?>
  <label>Fee Structure PDF</label><input type="file" name="fee_structure_pdf" accept=".pdf">
  <div class="form-grid" style="margin-top:1rem">
    <div><label>Bank Name</label><input name="settings[fee_bank_name]" value="<?= e($settings['fee_bank_name'] ?? '') ?>" placeholder="e.g. State Bank of India"></div>
    <div><label>Bank Address / Branch</label><input name="settings[fee_bank_address]" value="<?= e($settings['fee_bank_address'] ?? '') ?>" placeholder="e.g. Maner, Patna"></div>
    <div><label>Account Holder</label><input name="settings[fee_bank_holder]" value="<?= e($settings['fee_bank_holder'] ?? '') ?>" placeholder="Maner Private ITI"></div>
    <div><label>Account Number</label><input name="settings[fee_bank_account]" value="<?= e($settings['fee_bank_account'] ?? '') ?>"></div>
    <div><label>IFSC Code</label><input name="settings[fee_bank_ifsc]" value="<?= e($settings['fee_bank_ifsc'] ?? '') ?>"></div>
  </div>

  <button class="btn btn-primary" style="margin-top:1rem">Save Settings</button>
</form>
