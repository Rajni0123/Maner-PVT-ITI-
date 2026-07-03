<div class="admin-page-header">
  <div>
    <h1>Site Settings</h1>
    <p style="margin:0.35rem 0 0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Header, SMS notification, storage, branding aur fee bank details yahan manage karein.
    </p>
  </div>
</div>

<form method="post" action="<?= site_url('admin/settings') ?>" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>

  <?php
  $smsOn = class_exists(\App\Core\Sms::class) && \App\Core\Sms::isConfigured();
  $smsProvider = $settings['sms_provider'] ?? 'fast2sms';
  $smsStatus = class_exists(\App\Core\Sms::class) ? \App\Core\Sms::statusLabel() : 'Not configured';
  ?>

  <div id="sms-settings" class="admin-sms-panel">
    <h3 class="admin-sms-panel-title">
      <span class="material-symbols-outlined">sms</span>
      SMS Notification (Student Mobile)
    </h3>
    <p class="admin-sms-panel-desc">
      Har student ke mobile number par fee reminder / notification bhejne ke liye SMS gateway configure karein.
      India mein DLT-approved sender ID aur template use karna zaroori ho sakta hai.
    </p>
    <div class="admin-alert admin-sms-status <?= $smsOn ? 'admin-alert-success' : 'admin-alert-muted' ?>">
      SMS status: <strong><?= e($smsStatus) ?></strong>
      <?php if ($smsOn): ?>
      — Fee Reminders panel se student numbers par SMS bhej sakte hain.
      <?php else: ?>
      — Enable karke API key save karein.
      <?php endif; ?>
    </div>
    <div class="form-grid">
      <div>
        <label>Enable SMS</label>
        <select name="settings[sms_enabled]">
          <option value="0" <?= ($settings['sms_enabled'] ?? '0') !== '1' ? 'selected' : '' ?>>Disabled</option>
          <option value="1" <?= ($settings['sms_enabled'] ?? '') === '1' ? 'selected' : '' ?>>Enabled</option>
        </select>
      </div>
      <div>
        <label>SMS Provider</label>
        <select name="settings[sms_provider]" id="smsProvider">
          <option value="fast2sms" <?= $smsProvider === 'fast2sms' ? 'selected' : '' ?>>Fast2SMS</option>
          <option value="msg91" <?= $smsProvider === 'msg91' ? 'selected' : '' ?>>MSG91</option>
          <option value="textlocal" <?= $smsProvider === 'textlocal' ? 'selected' : '' ?>>TextLocal</option>
          <option value="custom" <?= $smsProvider === 'custom' ? 'selected' : '' ?>>Custom HTTP API</option>
        </select>
      </div>
      <div>
        <label>API Key / Auth Key</label>
        <input name="settings[sms_api_key]" type="password" value="" autocomplete="new-password" placeholder="<?= !empty($settings['sms_api_key']) ? '•••••••• (saved — leave blank to keep)' : 'Provider API key' ?>">
      </div>
      <div><label>Sender ID</label><input name="settings[sms_sender_id]" value="<?= e($settings['sms_sender_id'] ?? '') ?>" placeholder="e.g. MANERI (DLT approved)" maxlength="12"></div>
      <div>
        <label>Route</label>
        <select name="settings[sms_route]">
          <option value="q" <?= ($settings['sms_route'] ?? 'q') === 'q' ? 'selected' : '' ?>>Quick / Transactional (Fast2SMS q)</option>
          <option value="dlt" <?= ($settings['sms_route'] ?? '') === 'dlt' ? 'selected' : '' ?>>DLT Template (Fast2SMS dlt)</option>
          <option value="4" <?= ($settings['sms_route'] ?? '') === '4' ? 'selected' : '' ?>>MSG91 Transactional (4)</option>
        </select>
      </div>
      <div><label>DLT Template ID</label><input name="settings[sms_dlt_template_id]" value="<?= e($settings['sms_dlt_template_id'] ?? '') ?>" placeholder="Only for Fast2SMS DLT route"></div>
      <div><label>Country Code</label><input name="settings[sms_country_code]" value="<?= e($settings['sms_country_code'] ?? '91') ?>" placeholder="91" maxlength="4"></div>
      <div style="grid-column:1/-1">
        <label>Custom API URL (only if provider = Custom)</label>
        <input name="settings[sms_custom_url]" value="<?= e($settings['sms_custom_url'] ?? '') ?>" placeholder="https://api.example.com/send?to={mobile91}&amp;text={message_encoded}&amp;key={api_key}">
        <small class="admin-sms-help">
          Placeholders: <code>{mobile}</code>, <code>{mobile91}</code>, <code>{message}</code>, <code>{message_encoded}</code>, <code>{api_key}</code>, <code>{sender_id}</code>
        </small>
      </div>
      <div>
        <label>Custom API Method</label>
        <select name="settings[sms_custom_method]">
          <option value="GET" <?= ($settings['sms_custom_method'] ?? 'GET') === 'GET' ? 'selected' : '' ?>>GET</option>
          <option value="POST" <?= ($settings['sms_custom_method'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
        </select>
      </div>
    </div>
    <div class="admin-sms-template">
      <label>Default Fee Reminder SMS Template</label>
      <textarea name="settings[fee_reminder_sms_message]" rows="3" placeholder="Dear {name}, fee due {due} for {trade} at {institute}. Please pay soon. Call {phone}"><?= e($settings['fee_reminder_sms_message'] ?? 'Dear {name}, fee due {due} for {trade} at {institute}. Please pay soon. Call {phone}') ?></textarea>
      <small class="admin-sms-help">
        Variables: <code>{name}</code>, <code>{due}</code>, <code>{trade}</code>, <code>{institute}</code>, <code>{phone}</code>, <code>{mobile}</code>.
        Fast2SMS DLT mode mein variables auto: <code>name|due|trade|institute|phone</code>
      </small>
    </div>
    <p class="admin-sms-footer-note">
      SMS bhejne ke liye <a href="<?= site_url('admin/fee-reminders') ?>">Fee Reminders</a> panel use karein — channel SMS / Email / Both select karke students ke number par notification jayegi.
    </p>
  </div>

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

  <h3 style="margin-top:1.5rem">Cloud Document Storage (Cloudflare R2)</h3>
  <p style="margin:0 0 0.75rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">
    Documents (photo, signature, certificates, PDFs) ko main server ki jagah Cloudflare R2 par save karein.
    Credentials Cloudflare Dashboard → R2 → Manage R2 API Tokens se milte hain.
  </p>
  <?php $r2On = storage_uses_r2(); ?>
  <div class="admin-alert <?= $r2On ? 'admin-alert-success' : '' ?>" style="margin-bottom:1rem">
    Storage status:
    <strong><?= $r2On ? 'Cloudflare R2 (active)' : 'Local server (uploads/)' ?></strong>
  </div>
  <div class="form-grid">
    <div>
      <label>Storage Driver</label>
      <select name="settings[storage_driver]">
        <option value="local" <?= ($settings['storage_driver'] ?? 'local') === 'local' ? 'selected' : '' ?>>Local server</option>
        <option value="r2" <?= ($settings['storage_driver'] ?? '') === 'r2' ? 'selected' : '' ?>>Cloudflare R2</option>
      </select>
    </div>
    <div><label>R2 Account ID</label><input name="settings[r2_account_id]" value="<?= e($settings['r2_account_id'] ?? '') ?>" placeholder="Cloudflare Account ID"></div>
    <div><label>R2 Access Key ID</label><input name="settings[r2_access_key]" value="<?= e($settings['r2_access_key'] ?? '') ?>" autocomplete="off"></div>
    <div><label>R2 Secret Access Key</label><input name="settings[r2_secret_key]" type="password" value="<?= e($settings['r2_secret_key'] ?? '') ?>" autocomplete="new-password"></div>
    <div><label>R2 Bucket Name</label><input name="settings[r2_bucket]" value="<?= e($settings['r2_bucket'] ?? '') ?>" placeholder="maner-iti-docs"></div>
    <div><label>R2 Public URL</label><input name="settings[r2_public_url]" value="<?= e($settings['r2_public_url'] ?? '') ?>" placeholder="https://pub-xxxxx.r2.dev"></div>
    <div><label>R2 Folder Prefix</label><input name="settings[r2_prefix]" value="<?= e($settings['r2_prefix'] ?? 'uploads') ?>" placeholder="uploads"></div>
    <div>
      <label>Delete from server after R2 upload</label>
      <select name="settings[r2_delete_local]">
        <option value="1" <?= ($settings['r2_delete_local'] ?? '1') === '1' ? 'selected' : '' ?>>Yes (recommended)</option>
        <option value="0" <?= ($settings['r2_delete_local'] ?? '') === '0' ? 'selected' : '' ?>>No (keep local copy)</option>
      </select>
    </div>
  </div>

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
