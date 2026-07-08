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

$feeData = $feeData ?? [];
$feeTrades = [
    'electrician' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm3LjmDfoCReqsDcapELe4e0B9U-g-tCniQKn7yWJihnq3G6YMY0TzkPgBsI83YZImwTLJSe_R6oIKtdOrwuQJqNPCixxH2dcXmKgs9HR80Ldy7Bj5WkjJgaZMNyiJU_KcRWTqGZGBI1M2Z07p9uSfE2o4jARQi0UOl-tWeJyZ_eRhtV-hfu52G_fhEUVbwics48QYUS3BDC5xJnojoR6ivX0Oy8Uyml8z11ifPezAe58XB6cPyhSfBpi8uNkMD9HwKnMNWT8Fka4',
        'col_span' => 'lg:col-span-7',
        'duration' => '2 Year Trade / NCVT',
        'rows' => [
            ['Admission Fee (One-time)', '5,000'],
            ['Tuition Fee (Per Semester)', '12,500'],
            ['Workshop & Lab Maintenance', '4,000'],
        ],
        'total_label' => 'Total Annual Estimate',
        'total' => '₹ 34,000',
    ],
    'fitter' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDWDvfFDX4tq5hZb8DgttBWr4h5OnUUdIuDuohHd05dob7_hq86OiZ9GCSGZEUxZ_Q524w6W_CmZkgLSUFi_xvGUR3dACsrJrAzsbec7DLZiIefWHZYzfzujvRKEuPfdjd2IjsCNsUuCbWGj5h9z1gtmAqZ1tGE22Aop8o6ynIciRSYl2FfXn71gaf26geWryVIDIeUHNnMkLNnEATvERGqgKeNuzbwIpHaF5hwXMiyImLwYBY24xniGWXkw1mujx0wiCR-f_7Mz9o',
        'col_span' => 'lg:col-span-5',
        'duration' => '2 Year Trade / NCVT',
        'rows' => [
            ['Admission Fee', '5,000'],
            ['Tuition Fee', '11,500'],
            ['Workshop Charges', '4,500'],
        ],
        'total_label' => 'Total Annual',
        'total' => '₹ 32,500',
    ],
];

if ($feeData !== []) {
    foreach ($feeData as $slug => $override) {
        if (!is_array($override)) {
            continue;
        }
        $feeTrades[$slug] = isset($feeTrades[$slug])
            ? array_replace_recursive($feeTrades[$slug], $override)
            : $override;
    }
}
foreach ($trades as $t) {
    $slug = $t['slug'] ?? '';
    if ($slug && !empty($t['image'])) {
        if (!isset($feeTrades[$slug])) {
            $feeTrades[$slug] = ['rows' => [], 'total' => '—', 'col_span' => 'lg:col-span-6'];
        }
        $feeTrades[$slug]['image'] = upload_url($t['image']);
    }
}

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
        <span class="font-label-sm text-label-sm text-secondary-container uppercase tracking-widest mb-4 block">Academic Year 2024-25</span>
        <h1 class="font-display text-display mb-6">Investment in Your <span class="text-secondary-container">Professional Future</span></h1>
        <p class="font-body-lg text-body-lg text-on-primary-container max-w-2xl">
          Transparent fee structure with no hidden costs. We empower students from all backgrounds through flexible payment plans and government-backed financial support.
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

  <section class="py-section-gap max-w-container-max mx-auto px-gutter">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
      <div>
        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Trade Fee Structure</h2>
        <p class="font-body-md text-on-surface-variant">Detailed breakdown of training and workshop charges.</p>
      </div>
      <?php if ($pdfUrl): ?>
      <a href="<?= e($pdfUrl) ?>" target="_blank" class="border border-primary text-primary px-4 py-2 flex items-center gap-2 hover:bg-primary-container hover:text-white transition-colors">
        <span class="material-symbols-outlined">download</span>
        Download Fee PDF
      </a>
      <?php else: ?>
      <span class="border border-outline text-outline px-4 py-2 flex items-center gap-2 text-sm">PDF available at office</span>
      <?php endif; ?>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <?php foreach ($trades as $t):
        $slug = $t['slug'] ?? '';
        $fee = $feeTrades[$slug] ?? null;
        if (!$fee) continue;
      ?>
      <div class="<?= e($fee['col_span']) ?> bg-white border border-outline-variant group hover:shadow-lg transition-shadow duration-300">
        <div class="h-48 relative overflow-hidden">
          <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('<?= e($fee['image']) ?>')"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
          <div class="absolute bottom-4 left-6">
            <h3 class="font-headline-md text-headline-md text-white"><?= e($t['name']) ?></h3>
            <span class="font-label-sm text-label-sm text-secondary-container"><?= e($fee['duration']) ?></span>
          </div>
        </div>
        <div class="p-8">
          <table class="w-full text-left font-body-md">
            <thead>
              <tr class="border-b border-outline-variant text-on-surface-variant uppercase text-xs tracking-widest">
                <th class="pb-4 font-medium">Component</th>
                <th class="pb-4 font-medium text-right">Amount (₹)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50">
              <?php foreach ($fee['rows'] as [$label, $amount]): ?>
              <tr>
                <td class="py-4"><?= e($label) ?></td>
                <td class="py-4 text-right font-label-sm"><?= e($amount) ?></td>
              </tr>
              <?php endforeach; ?>
              <tr class="bg-surface-container-low">
                <td class="py-4 px-2 font-bold"><?= e($fee['total_label']) ?></td>
                <td class="py-4 px-2 text-right font-bold text-primary font-label-sm"><?= e($fee['total']) ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php endforeach; ?>
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
