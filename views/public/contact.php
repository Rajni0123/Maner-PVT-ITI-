<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$trades = $trades ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$address = $footer['address'] ?? "Station Road, Near Petrol Pump,\nManer, Patna - 801108, Bihar";
$phone = $footer['phone'] ?? $header['phone'] ?? '+91-9155401839';
$email = $footer['email'] ?? $header['email'] ?? 'manerpvtiti@gmail.com';
$footerAbout = $footer['about_text'] ?? 'Affiliated to NCVT, Government of India. Providing technical excellence since establishment.';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. All Rights Reserved. Affiliated to NCVT, Government of India.';

$mapImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuC8AFqSslifzhAQPd-yDl_326wIBHcbmTa-GpKNdDTf1lbfzpuVx7rZ9bb9H5YOrQVH__MtnYJUOpyeMkJ6vYuQA5VaG-CZ6kGBIt7n0If2_W2gwAimX6i9iYaqugx1XfPZaokHEsckkE6tNryPQ9UrJYLGkDScAx6JynjcSlBuFuHYspKQMfYjeLo9JiyXr08SeHqNNi8GwjJlHNL6j1l_hzdRREnnK8ntnMod_QWLZiYZzG-C1fwEougsDrx7y6DzCL8-6oeka9g';
$mapsQuery = urlencode(str_replace("\n", ', ', $address));
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;

$pageTitle = $title ?? 'Contact Us - Maner Private ITI';
$pageDescription = 'Contact Maner Private ITI for admission inquiries, campus visits, and BSCC support in Patna, Bihar.';
$extraCss = ['contact.css'];
$navActive = 'contact';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-background font-body-md">

<?php require base_path('views/partials/design-nav-contact.php'); ?>

<main>
  <section class="technical-gradient py-24 px-gutter relative overflow-hidden">
    <div class="max-w-container-max mx-auto relative z-10 text-center md:text-left">
      <h1 class="font-display text-display text-surface mb-6">Get in Touch</h1>
      <p class="font-body-lg text-primary-fixed-dim max-w-2xl mb-8">
        Your journey toward technical excellence starts here. Whether you're an aspiring student or a curious parent, our team is here to support your career aspirations in industrial trades.
      </p>
      <div class="flex flex-wrap gap-4 justify-center md:justify-start items-center">
        <a href="<?= site_url('fee-structure') ?>" class="bg-secondary-container text-on-secondary-container px-8 py-3 rounded-lg font-bold flex items-center gap-2 hover:brightness-110 transition-all">
          <span class="material-symbols-outlined">download</span>
          Download Prospectus
        </a>
        <div class="flex items-center gap-4 text-surface border-l border-outline-variant pl-4 ml-2">
          <a class="hover:text-secondary-fixed-dim transition-colors" href="<?= e($email ? 'mailto:' . $email : '#') ?>">
            <span class="material-symbols-outlined">alternate_email</span>
          </a>
          <a class="hover:text-secondary-fixed-dim transition-colors" href="<?= e($phone ? 'tel:' . preg_replace('/\s+/', '', $phone) : '#') ?>">
            <span class="material-symbols-outlined">call</span>
          </a>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>
  </section>

  <section class="max-w-container-max mx-auto -mt-12 px-gutter relative z-20 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-surface border border-outline-variant p-8 shadow-sm hover:shadow-md transition-shadow group">
      <div class="w-12 h-12 bg-primary-container text-surface flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined">location_on</span>
      </div>
      <h3 class="font-headline-md text-primary mb-2">Visit Us</h3>
      <p class="text-on-surface-variant"><?= nl2br(e($address)) ?></p>
    </div>
    <div class="bg-surface border border-outline-variant p-8 shadow-sm hover:shadow-md transition-shadow group">
      <div class="w-12 h-12 bg-secondary-container text-on-secondary-container flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined">call</span>
      </div>
      <h3 class="font-headline-md text-primary mb-2">Call Us</h3>
      <p class="text-on-surface-variant font-label-sm"><?= e($phone) ?></p>
      <p class="text-on-surface-variant text-sm mt-1">Available 09:00 AM - 05:00 PM</p>
    </div>
    <div class="bg-surface border border-outline-variant p-8 shadow-sm hover:shadow-md transition-shadow group">
      <div class="w-12 h-12 bg-primary-container text-surface flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined">mail</span>
      </div>
      <h3 class="font-headline-md text-primary mb-2">Email Us</h3>
      <p class="text-on-surface-variant font-label-sm"><?= e($email) ?></p>
      <p class="text-on-surface-variant text-sm mt-1">Response within 24 hours</p>
    </div>
  </section>

  <section class="max-w-container-max mx-auto py-section-gap px-gutter grid grid-cols-1 lg:grid-cols-12 gap-12">
    <div class="lg:col-span-8">
      <div class="bg-surface-container-lowest p-8 border border-outline-variant shadow-sm">
        <h2 class="font-headline-lg text-primary mb-2">Admission Inquiry</h2>
        <p class="text-on-surface-variant mb-8">Fill out the form below and our technical counselors will contact you shortly.</p>

        <?php if ($msg = flash('success')): ?>
        <div class="mb-6 p-4 bg-green-50 text-green-800 border border-green-200 text-sm"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
        <div class="mb-6 p-4 bg-error-container text-on-error-container border border-error text-sm"><?= e($msg) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('contact') ?>" class="space-y-6" id="contactForm">
          <?= csrf_field() ?>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block font-bold text-primary mb-2 font-body-md" for="name">Full Name *</label>
              <input class="w-full border border-outline-variant p-3 focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-all" id="name" name="name" value="<?= e(old('name')) ?>" placeholder="Enter your full name" type="text" required/>
            </div>
            <div>
              <label class="block font-bold text-primary mb-2 font-body-md" for="phone">Mobile Number *</label>
              <input class="w-full border border-outline-variant p-3 focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-all" id="phone" name="phone" value="<?= e(old('phone')) ?>" placeholder="+91 00000 00000" type="tel" required/>
            </div>
          </div>
          <div>
            <label class="block font-bold text-primary mb-2 font-body-md" for="email">Email Address *</label>
            <input class="w-full border border-outline-variant p-3 focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-all" id="email" name="email" value="<?= e(old('email')) ?>" placeholder="example@email.com" type="email" required/>
          </div>
          <div>
            <label class="block font-bold text-primary mb-2 font-body-md" for="trade_interest">Interested Trade</label>
            <select class="w-full border border-outline-variant p-3 focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-all" id="trade_interest" name="trade_interest">
              <option value="">Select a trade (optional)</option>
              <?php foreach ($trades as $t): ?>
              <option value="<?= e($t['name']) ?>" <?= old('trade_interest') === $t['name'] ? 'selected' : '' ?>><?= e($t['name']) ?> (<?= e($t['duration'] ?? '2 Years') ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-bold text-primary mb-2 font-body-md" for="message">Message *</label>
            <textarea class="w-full border border-outline-variant p-3 focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-all" id="message" name="message" placeholder="How can we help you?" rows="4" required><?= e(old('message')) ?></textarea>
          </div>
          <button class="w-full bg-primary-container text-surface py-4 font-bold text-lg hover:bg-opacity-90 active:scale-[0.99] transition-all flex items-center justify-center gap-2" type="submit" id="contactSubmitBtn">
            Send Inquiry
            <span class="material-symbols-outlined">send</span>
          </button>
        </form>
      </div>
    </div>

    <div class="lg:col-span-4 space-y-8">
      <div class="bg-on-tertiary-container text-surface p-8 bscc-accent-border shadow-md">
        <div class="flex items-center gap-3 mb-4">
          <span class="material-symbols-outlined text-tertiary-fixed-dim">account_balance</span>
          <h3 class="font-headline-md font-bold">BSCC Support</h3>
        </div>
        <p class="font-body-md mb-6 opacity-90">
          Interested in the Bihar Student Credit Card (BSCC) scheme? Our dedicated counselors provide free documentation assistance for eligible students.
        </p>
        <ul class="space-y-3 mb-8">
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined text-secondary-fixed-dim text-sm mt-1">check_circle</span>
            <span class="text-sm">Step-by-step guidance</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined text-secondary-fixed-dim text-sm mt-1">check_circle</span>
            <span class="text-sm">Document verification support</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="material-symbols-outlined text-secondary-fixed-dim text-sm mt-1">check_circle</span>
            <span class="text-sm">Direct liaison with government portals</span>
          </li>
        </ul>
        <a class="inline-flex items-center gap-2 font-bold text-tertiary-fixed-dim hover:underline transition-all" href="<?= site_url('bscc-info') ?>">
          Learn More about BSCC
          <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
      </div>
      <div class="bg-surface-container-high p-8 border border-outline-variant">
        <h3 class="font-headline-md text-primary mb-4">Working Hours</h3>
        <div class="space-y-3 text-on-surface-variant font-label-sm">
          <div class="flex justify-between border-b border-outline-variant pb-2">
            <span>Monday - Friday</span>
            <span class="text-primary font-bold">09:00 - 17:00</span>
          </div>
          <div class="flex justify-between border-b border-outline-variant pb-2">
            <span>Saturday</span>
            <span class="text-primary font-bold">09:00 - 14:00</span>
          </div>
          <div class="flex justify-between text-error font-bold">
            <span>Sunday</span>
            <span>Closed</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="w-full relative h-[500px] overflow-hidden group">
    <div class="absolute inset-0 grayscale contrast-125 opacity-40 mix-blend-multiply bg-primary-container z-0 pointer-events-none"></div>
    <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?= e($mapImage) ?>')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-primary-container/80 to-transparent flex items-center justify-center px-gutter">
      <div class="bg-surface p-8 max-w-md border border-outline shadow-2xl transform translate-y-0 group-hover:-translate-y-2 transition-transform duration-500">
        <h3 class="font-headline-md text-primary mb-2">Campus Location</h3>
        <p class="text-on-surface-variant mb-6">Our campus is strategically located near the main transport artery for easy student access.</p>
        <a href="<?= e($mapsUrl) ?>" target="_blank" rel="noopener" class="bg-primary-container text-surface px-6 py-3 font-bold inline-flex items-center gap-2 hover:bg-tertiary transition-all">
          <span class="material-symbols-outlined">directions</span>
          Get Directions
        </a>
      </div>
    </div>
  </section>
</main>

<?php
$hideEnquiryPopup = true;
require base_path('views/partials/design-footer.php');
?>

<script>
document.getElementById('contactForm')?.addEventListener('submit', function (e) {
  const trade = document.getElementById('trade_interest');
  const message = document.getElementById('message');
  if (trade && trade.value && message) {
    const prefix = 'Interested Trade: ' + trade.value + '\n\n';
    if (!message.value.startsWith('Interested Trade:')) {
      message.value = prefix + message.value;
    }
  }
  const btn = document.getElementById('contactSubmitBtn');
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Sending...';
  }
});
</script>
</body>
</html>
