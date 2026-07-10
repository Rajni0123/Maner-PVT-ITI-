<?php
$navActive = 'contact';
$header = $header ?? [];
$footer = $footer ?? [];
$trades = $trades ?? [];
$phone = $footer['phone'] ?? $header['phone'] ?? '';
$email = $footer['email'] ?? $header['email'] ?? '';
$address = $footer['address'] ?? 'Maner, Patna, Bihar';
$old = old();
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Contact Us</h1>
    <p>Reach our campus for admission counseling and student support</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container pti-grid-2">
    <div class="pti-card">
      <h2 style="margin-top:0;color:var(--pti-navy)">Send a Message</h2>
      <?php if ($msg = flash('success')): ?><div class="pti-flash pti-flash--success"><?= e($msg) ?></div><?php endif; ?>
      <?php if ($msg = flash('error')): ?><div class="pti-flash pti-flash--error"><?= e($msg) ?></div><?php endif; ?>
      <form class="pti-form" method="post" action="<?= site_url('contact') ?>">
        <?= csrf_field() ?>
        <label>Full Name *</label>
        <input name="name" required value="<?= e($old['name'] ?? '') ?>">
        <label>Email *</label>
        <input type="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
        <label>Mobile *</label>
        <input name="phone" required value="<?= e($old['phone'] ?? '') ?>">
        <label>Interested Trade</label>
        <select name="trade_interest">
          <option value="">-- Select --</option>
          <?php foreach ($trades as $t): ?>
          <option value="<?= e($t['name']) ?>" <?= (($old['trade_interest'] ?? '') === $t['name']) ? 'selected' : '' ?>><?= e($t['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <label>Message *</label>
        <textarea name="message" rows="5" required><?= e($old['message'] ?? '') ?></textarea>
        <button class="pti-btn pti-btn--primary" type="submit">Submit Enquiry</button>
      </form>
    </div>
    <div class="pti-card">
      <h2 style="margin-top:0;color:var(--pti-navy)">Campus Details</h2>
      <p><strong>Address</strong><br><?= nl2br(e($address)) ?></p>
      <?php if ($phone): ?><p><strong>Phone</strong><br><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a></p><?php endif; ?>
      <?php if ($email): ?><p><strong>Email</strong><br><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p><?php endif; ?>
      <p style="margin-top:1.5rem">
        <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Admission Form</a>
        <a class="pti-btn pti-btn--outline" href="<?= site_url('fee-structure') ?>">Fee List</a>
      </p>
    </div>
  </div>
</section>
