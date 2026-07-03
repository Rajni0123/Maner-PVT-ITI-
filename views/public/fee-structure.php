<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$trades = $trades ?? [];
$pdf = $pdf ?? '';

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$footerAbout = $footer['about_text'] ?? 'Empowering the youth of Bihar with technical expertise and industrial precision since 2014.';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$heroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjzBDc2c-IpEqyJKrP5lROR9MSAK2Z4p4n_0BwCD4A5vDl11SJHNtz0aN32PcizFL18o18rs9EosR6NAVinAAZqIdQ5dJkOyuI47tazCkUsw1Xk04JJO-UHjVHeKqRvT9LiOeDK4pQ4GJC1Mnz4eoJZlPG8VKma7Z6fsl7eUoLEbWUJ0e-5SKshA98oFgG9LTVx-hVMoRtnK2P7-Rbm-g85l1q7v5gNGsUPtePgArTzdJFp6fNG2ALFvqnA-0j3yRkmLKTJ4z2CWA';
$bsccImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBl2wRY6acZAQtHdQ1tWdwjZqW88WD9YiE_35KWgxiAdd94FZ_4sXiBG_1Y06FbrG6VGgXNULOY25wOGe5w3VUqAvVmhqZ5fyv1hk2bwCU25G87_-H5AUTJTLrG3K_7txK-7suFuWd-RtRE84PAh5pRJ9UHCUD9RLQaXKRlR9eNFMMhairkz2lm8qObxEHALNvPh2C-DrL9xR54W2jwa0K_jfhbCurQgKV5fgA7zBp-7CZEDdN5hv_zJATQf_ALPhtTRgpA1lRfJ58';

$pdfUrl = $pdf ? upload_url($pdf) : '';

$pageTitle = $title ?? 'Fee Structure | Maner Private ITI';
$pageDescription = 'Transparent fee structure for Electrician and Fitter trades at Maner Private ITI. Explore Bihar Student Credit Card (BSCC) benefits.';
$extraCss = ['fee-structure.css'];
$navActive = 'admission';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container selection:text-on-secondary-container">

<?php require base_path('views/partials/design-nav-courses.php'); ?>

<main>
  <section class="relative bg-primary-container text-white py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
      <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?= e($heroImage) ?>')"></div>
    </div>
    <div class="relative z-10 max-w-container-max mx-auto px-gutter">
      <div class="max-w-3xl">
        <span class="font-label-sm text-label-sm text-secondary-container uppercase tracking-widest mb-4 block">Session Aug 2026 – 2028</span>
        <h1 class="font-display text-display mb-6">Investment in Your <span class="text-secondary-container">Professional Future</span></h1>
        <p class="font-body-lg text-body-lg text-on-primary-container max-w-2xl">
          सरकार द्वारा निर्धारित कोर्स शुल्क / Government Prescribed Course Fee for Electrician &amp; Fitter trades. Transparent fee structure with installment plans and government-backed financial support.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="<?= site_url('admission-process') ?>" class="border border-white/30 bg-white/10 hover:bg-white/20 px-6 py-3 font-bold transition-all inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">school</span>
            Admission Requirements
          </a>
          <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-3 font-bold hover:opacity-90 transition-all inline-flex items-center gap-2">
            Apply Online
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="max-w-container-max mx-auto px-gutter -mt-12 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-surface p-8 border border-outline-variant shadow-sm flex items-start gap-4">
        <span class="material-symbols-outlined text-secondary text-4xl">verified</span>
        <div>
          <p class="font-headline-md text-headline-md text-primary">NCVT Approved</p>
          <p class="font-body-md text-on-surface-variant">Globally recognized certification for all trades.</p>
        </div>
      </div>
      <div class="bg-surface p-8 border border-outline-variant shadow-sm flex items-start gap-4">
        <span class="material-symbols-outlined text-secondary text-4xl">account_balance</span>
        <div>
          <p class="font-headline-md text-headline-md text-primary">BSCC Enabled</p>
          <p class="font-body-md text-on-surface-variant">Zero-interest loans via Bihar Govt scheme.</p>
        </div>
      </div>
      <div class="bg-surface p-8 border border-outline-variant shadow-sm flex items-start gap-4">
        <span class="material-symbols-outlined text-secondary text-4xl">payments</span>
        <div>
          <p class="font-headline-md text-headline-md text-primary">Installment Plans</p>
          <p class="font-body-md text-on-surface-variant">Easy quarterly payment options available.</p>
        </div>
      </div>
    </div>
  </section>

  <?php
  $settings = $settings ?? [];
  $feeTableRows = [
      ['Trade / ट्रेड', 'Electrician & Fitter'],
      ['Duration of Course / कोर्स अवधि', '2 Years [ अगस्त से जुलाई ]'],
      ['Prospectus & Admission Form / प्रोस्पेक्टस एवं प्रवेश फॉर्म', 'Rs. 200/-'],
      ['Tuition Fee (to be paid at enrollment) / नामांकन के समय भुगतान करना है', 'Rs. 10,000/-'],
      ['Tuition Fee — 6 installments of Rs. 7,000 every 3 months / प्रत्येक तीन माह पर 7,000 रु का 6 किस्त', 'Rs. 42,000/-'],
      ['Total Tuition Fee (2-Year Course) / दो वर्षीय कोर्स का कुल प्रशिक्षण शुल्क', 'Rs. 52,200/-'],
      ['AITT Exam Fee [ NCVT का परीक्षा शुल्क ]', 'NCVT के आदेशानुसार प्रति वर्ष 500/- रुपये'],
  ];
  $year1Installments = [
      ['1st Installment [ किस्त ]', '01 अक्टूबर से', '15 अक्टूबर तक'],
      ['2nd Installment [ किस्त ]', '01 जनवरी से', '15 जनवरी तक'],
      ['3rd Installment [ किस्त ]', '01 अप्रैल से', '15 अप्रैल तक'],
  ];
  $year2Installments = [
      ['4th Installment [ किस्त ]', '01 अक्टूबर से', '15 अक्टूबर तक'],
      ['5th Installment [ किस्त ]', '01 जनवरी से', '15 जनवरी तक'],
      ['6th Installment [ किस्त ]', '01 अप्रैल से', '15 अप्रैल तक'],
  ];
  $bankName = $settings['fee_bank_name'] ?? '';
  $bankAddress = $settings['fee_bank_address'] ?? '';
  $bankHolder = $settings['fee_bank_holder'] ?? $logoText;
  $bankAccount = $settings['fee_bank_account'] ?? '';
  $bankIfsc = $settings['fee_bank_ifsc'] ?? '';
  ?>

  <section class="py-section-gap max-w-container-max mx-auto px-gutter">
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
      <div>
        <p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2"><?= e(strtoupper($logoText)) ?></p>
        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">सरकार द्वारा निर्धारित कोर्स शुल्क / Fee</h2>
        <p class="font-body-md text-on-surface-variant">FOR SESSION AUG 2026 – 2028</p>
      </div>
      <div class="flex flex-wrap gap-3">
        <a href="<?= site_url('fee-structure/pdf') ?>" target="_blank" class="border border-primary text-primary px-4 py-2 flex items-center gap-2 hover:bg-primary-container hover:text-white transition-colors">
          <span class="material-symbols-outlined">picture_as_pdf</span>
          Download Fee PDF
        </a>
        <?php if ($pdfUrl): ?>
        <a href="<?= e($pdfUrl) ?>" target="_blank" class="border border-outline text-on-surface-variant px-4 py-2 flex items-center gap-2 hover:border-primary hover:text-primary transition-colors">
          <span class="material-symbols-outlined">download</span>
          Uploaded PDF
        </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="bg-white border border-outline-variant overflow-hidden mb-12">
      <table class="w-full text-left font-body-md">
        <tbody class="divide-y divide-outline-variant/50">
          <?php foreach ($feeTableRows as $i => [$label, $value]): ?>
          <tr class="<?= $i === 5 ? 'bg-surface-container-low' : '' ?>">
            <td class="py-4 px-6 font-semibold text-primary align-top w-1/2"><?= e($label) ?></td>
            <td class="py-4 px-6 font-bold <?= $i === 5 ? 'text-primary text-lg' : 'text-on-surface' ?>"><?= e($value) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <h3 class="font-headline-md text-headline-md text-primary mb-2">किस्त जमा करने का निर्धारित तिथि</h3>
    <p class="font-body-md text-on-surface-variant mb-8">Installment payment schedule for Session Aug 2026 – 2028</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
      <div class="bg-white border border-outline-variant overflow-hidden">
        <div class="bg-primary-container text-white px-6 py-4">
          <h4 class="font-headline-md text-headline-md">1st Year Installments</h4>
          <p class="font-label-sm text-secondary-container mt-1">प्रथम वर्ष का किस्त जमा करने का निर्धारित तिथि</p>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left font-body-md">
            <thead>
              <tr class="border-b border-outline-variant text-on-surface-variant uppercase text-xs tracking-widest">
                <th class="py-3 px-4 font-medium">1st Year</th>
                <th class="py-3 px-4 font-medium">From / कब से</th>
                <th class="py-3 px-4 font-medium">To / कब तक</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
              <?php foreach ($year1Installments as $row): ?>
              <tr>
                <td class="py-3 px-4 font-semibold"><?= e($row[0]) ?></td>
                <td class="py-3 px-4"><?= e($row[1]) ?></td>
                <td class="py-3 px-4"><?= e($row[2]) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white border border-outline-variant overflow-hidden">
        <div class="bg-primary-container text-white px-6 py-4">
          <h4 class="font-headline-md text-headline-md">2nd Year Installments</h4>
          <p class="font-label-sm text-secondary-container mt-1">द्वितीय वर्ष का किस्त जमा करने का निर्धारित तिथि</p>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left font-body-md">
            <thead>
              <tr class="border-b border-outline-variant text-on-surface-variant uppercase text-xs tracking-widest">
                <th class="py-3 px-4 font-medium">2nd Year</th>
                <th class="py-3 px-4 font-medium">From / कब से</th>
                <th class="py-3 px-4 font-medium">To / कब तक</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
              <?php foreach ($year2Installments as $row): ?>
              <tr>
                <td class="py-3 px-4 font-semibold"><?= e($row[0]) ?></td>
                <td class="py-3 px-4"><?= e($row[1]) ?></td>
                <td class="py-3 px-4"><?= e($row[2]) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="bg-white border border-outline-variant p-8">
      <h3 class="font-headline-md text-headline-md text-primary mb-6">INSTITUTE BANK ACCOUNT DETAILS</h3>
      <?php if ($bankAccount !== '' && $bankIfsc !== ''): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-body-md">
        <p><span class="text-on-surface-variant">BANK NAME :</span> <strong><?= e($bankName) ?></strong><?php if ($bankAddress): ?>, <span><?= e($bankAddress) ?></span><?php endif; ?></p>
        <p><span class="text-on-surface-variant">ACCOUNT HOLDER :</span> <strong><?= e($bankHolder) ?></strong></p>
        <p><span class="text-on-surface-variant">ACCOUNT NO :</span> <strong><?= e($bankAccount) ?></strong></p>
        <p><span class="text-on-surface-variant">IFSC CODE :</span> <strong><?= e($bankIfsc) ?></strong></p>
      </div>
      <?php else: ?>
      <p class="font-body-md text-on-surface-variant mb-4">
        Fee payment bank details ke liye institute office se contact karein, ya Admin → Settings mein bank details add karein.
      </p>
      <a href="<?= site_url('contact') ?>" class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
        Contact Institute Office
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <?php endif; ?>
    </div>
  </section>

  <section class="bg-tertiary-container py-section-gap">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1">
          <div class="inline-flex items-center gap-2 bg-on-tertiary-container/10 border border-on-tertiary-container/20 px-4 py-2 rounded-full mb-6">
            <span class="w-3 h-3 bg-on-tertiary-container rounded-full animate-pulse"></span>
            <span class="font-label-sm text-on-tertiary-container">OFFICIAL GOVT SCHEME</span>
          </div>
          <h2 class="font-headline-lg text-headline-lg text-white mb-6">Bihar Student Credit Card (BSCC) Support</h2>
          <p class="font-body-lg text-white/80 mb-8 leading-relaxed">
            Maner Private ITI is a registered partner for the MNSSBY scheme. Eligible students from Bihar can avail of loans up to ₹4 Lakhs with zero or minimal interest to cover their tuition, workshop, and living expenses.
          </p>
          <div class="space-y-4 mb-10">
            <div class="flex items-start gap-4">
              <span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
              <div>
                <p class="font-bold text-white">100% Course Coverage</p>
                <p class="text-white/60">No out-of-pocket tuition fees for qualified applicants.</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
              <div>
                <p class="font-bold text-white">Dedicated Helpdesk</p>
                <p class="text-white/60">In-house staff to assist with your online BSCC application.</p>
              </div>
            </div>
          </div>
          <a href="<?= site_url('bscc-info') ?>" class="inline-flex bg-secondary-container text-on-secondary-container px-8 py-4 font-bold items-center gap-3 hover:scale-105 transition-transform">
            Check BSCC Eligibility
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>
        <div class="order-1 lg:order-2">
          <div class="relative">
            <div class="absolute -top-6 -right-6 w-32 h-32 border-t-4 border-r-4 border-secondary-container opacity-30"></div>
            <div class="bg-white/5 backdrop-blur-md p-1 border border-white/10 shadow-2xl">
              <div class="aspect-video bg-cover bg-center" style="background-image: url('<?= e($bsccImage) ?>')"></div>
            </div>
            <div class="absolute -bottom-10 -left-10 bg-white p-6 border border-outline-variant hidden md:block max-w-xs shadow-xl">
              <p class="font-label-sm text-secondary mb-2">SCHOLARSHIP STATUS</p>
              <p class="font-headline-md text-primary leading-tight">400+ Students Funded via BSCC</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-section-gap max-w-container-max mx-auto px-gutter">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
      <div class="border-l-4 border-primary pl-8 py-4">
        <h3 class="font-headline-md text-headline-md text-primary mb-4">Merit-Based Scholarships</h3>
        <p class="font-body-md text-on-surface-variant mb-6">
          We reward excellence. Students scoring above 85% in their 10th standard board exams are eligible for a 20% waiver on first-year tuition fees.
        </p>
        <a class="text-primary font-bold flex items-center gap-2 group" href="<?= site_url('contact') ?>">
          Inquire about scholarships
          <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">trending_flat</span>
        </a>
      </div>
      <div class="border-l-4 border-secondary pl-8 py-4">
        <h3 class="font-headline-md text-headline-md text-primary mb-4">Interest-Free Installments</h3>
        <p class="font-body-md text-on-surface-variant mb-6">
          Fees can be divided into 4 equal quarterly installments per year, making quality technical education manageable for every household in Patna and beyond.
        </p>
        <a class="text-secondary font-bold flex items-center gap-2 group" href="<?= site_url('contact') ?>">
          View payment schedule
          <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">trending_flat</span>
        </a>
      </div>
    </div>
  </section>

  <section class="py-section-gap bg-surface-container-low">
    <div class="max-w-3xl mx-auto px-gutter">
      <h2 class="font-headline-lg text-headline-lg text-primary text-center mb-12">Frequently Asked Questions</h2>
      <div class="space-y-4">
        <details class="bg-white border border-outline-variant p-6 group open:shadow-md transition-all">
          <summary class="flex justify-between items-center cursor-pointer font-bold text-primary list-none">
            Are workshop materials included in the fee?
            <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <p class="mt-4 text-on-surface-variant border-t border-outline-variant pt-4">
            Yes, the Workshop &amp; Lab Maintenance fee covers all raw materials, tools usage, and safety gear required for the practical training sessions. There are no additional charges for basic consumables.
          </p>
        </details>
        <details class="bg-white border border-outline-variant p-6 group open:shadow-md transition-all">
          <summary class="flex justify-between items-center cursor-pointer font-bold text-primary list-none">
            What documents are required for BSCC application?
            <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <p class="mt-4 text-on-surface-variant border-t border-outline-variant pt-4">
            You will need your 10th/12th Marksheet, Admission Letter from Maner ITI, Fee Structure document, Residence Proof of Bihar, and Aadhar Card. Our helpdesk assists with the entire document preparation.
          </p>
        </details>
        <details class="bg-white border border-outline-variant p-6 group open:shadow-md transition-all">
          <summary class="flex justify-between items-center cursor-pointer font-bold text-primary list-none">
            Is there a refund policy?
            <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
          </summary>
          <p class="mt-4 text-on-surface-variant border-t border-outline-variant pt-4">
            Refunds are processed as per the NCVT and State Government norms. Typically, admission fees are non-refundable after the course commencement. Please refer to the full prospectus for detailed terms.
          </p>
        </details>
      </div>
    </div>
  </section>

  <section class="py-section-gap">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="bg-primary-container p-12 md:p-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
          <div class="grid grid-cols-12 gap-4 h-full">
            <div class="border-r border-white/20 h-full"></div>
            <div class="border-r border-white/20 h-full"></div>
            <div class="border-r border-white/20 h-full"></div>
          </div>
        </div>
        <div class="relative z-10">
          <h2 class="font-headline-lg text-headline-lg text-white mb-4">Start Your Admission Today</h2>
          <p class="text-on-primary-container mb-8 max-w-xl mx-auto">Review requirements, understand fees, and submit your online application for the 2026 session.</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-10 py-4 font-bold hover:opacity-90 transition-all">Apply Online</a>
            <a href="<?= site_url('admission-process') ?>" class="border border-white/30 text-white px-10 py-4 font-bold hover:bg-white/10 transition-all">Career Pathways &amp; Requirements</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

</body>
</html>
