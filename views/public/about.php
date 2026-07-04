<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$trades = $trades ?? [];
$page = $page ?? [];
$settings = $settings ?? \App\Models\SiteData::settings();
$gallery = $gallery ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}

$misCode = $settings['mis_code'] ?? 'PR10001156';
$affiliationNo = $settings['affiliation_no'] ?? 'DGT-6/4/2021-TC';

$defaultHeroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuA-lbbT1nCliClPKUs8_sFMGE9vWaWnbSAboLUt5oTZoiNewWY93r15EX2dLhVuKua3KeuLeiaqPjftLdENEelhYKav844se7Wd7xmSmxfQ4GBTrVOpw9zk4AF2-MKlz8TerYxxeOJWE4QA7gsQPduuGmDHjYjY2Dkis36f6ATc9NTl8_-Qsb4n4MxWITDCRd126MrEri3umY_SaB2jIFHANipVAubgzqpDYwdlcmKtAJubmG_-icJNtMLu_OMFtW-QlPnD6z0WaS8';
$defaultHeritageImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuDseQAK8A5lA1dS9aM72-JHpF1Aozgoq7en6WJKmRuxLK18sFJzX85Xmt0RXxS2HLYmrq7NIsD5En3hS1_QAnxlNoObCkQOFxckdhOymEkddBA7qSbNJ2gaRx_C3Sa4_NNcfahIai4X-lVAN7iyGuielS-qeiOu_UQ9E5V3ySGMmBkgxKEI6WIWPeWAJuC4n_g5Y9_aWgPWS0SxBckMt1yQ4y3z8Ods3xMIk1nZMP8-LJ1-zFh64cRXqRd1Y6i5PqeyDeMF_8Vl8nc';
$defaultDirectorImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQBiQaiaII0R7piD3W8hU3kGN43h3E8vH273_E5SyQaXMmJz5_r57C6ZyVtR2XeNaMb2Da-Vz2A11Py5176eWqc-ab5k3IqebKaFygk1RVLiuGC9WWLgzW7egPXQ2gb6wdZHqZ4R46zw0qKnj-5PqCcWFbh-Pq_oPBpUG9RiB9_qfn2ge39lzZsc1qXFDY8j1ltxzxGpQ5YzJLkDJIMQZKgSoSo_e6Srpsi694tgFpOP_FZ74EchVUcYoOZV9LsdkqliL6bo0w3Pc';

$defaultCampusImages = [
    ['label' => 'Main Academic Block', 'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZCgBsNa8QjOPc2jnyoYtYIMBB66yLB_4cZAz_ICZEOkrxja_Nf3Xvf7t6Y40pVCcDJwO8edY_D57wOo39_SQc2EDxSij-LlYq4CYKlLubaonAo3eSCWpt74GpqlQG4pq408hkgfcC4p0X80HmmOUXw3J9G9Ksc5RJaYQrwtYohwyWW7PQWpUa5rAixtrH2UH6EqUisXPv0AhE-7wPES5PHnNnwUFDmrzovaF8-0t8e7pAc4cgBGNNrrNkz5gvg705HZe8Ef-nWq8', 'span' => 'md:col-span-8'],
    ['label' => 'Fitter Workshop', 'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCYqI2zrDldJM_iQWT4G0ohhKKgVA4hHcwVSvfol23XUi5_7-Ic7ZjOZ5qur6l_TbEyUfTi4Tnkn4DqJ2inNFqk1qjgpUBKhebe0mWcJ24VvgGc5PtaxyfzLHUForDQXECXPZYex4nen4WnNyttbMWWQxtMBuF4mgXcR88NV0-kXqwtvyntktM_tXk2ntEac18QVKG_zJCGp3te10kLgdP-4Qe7ZCsLlVDU6JGG94woZ-Dl-fQ2jdcKALsvKbsTgZcw7r8aPtW58w', 'span' => 'md:col-span-4'],
    ['label' => 'Electrical Lab', 'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDeBnTmdGEh8uQq-n9CJp8X-XQW9qbg9smwp3FSGhYoV_xpR8o12IVFc4Xpwausw7fhofHPl7HMtDvdfbrKqDKQIMdrtYslgVgYxhNmn2Va4wwJVGzLn4r6XbzPhRi1LEk1KFHg-Cig3vQMc9WwYj6KKLD1CMbVbmipc2xZZ_rvbXLEY46rru-3qMTeSB2yEi9Nciq3IKIIHr_Ez20NNLgr_OHlBZFWAMGnTXwvihTBnaP6A4rqaSK4FHiQy9kIV0SoY-bWmqRZ1QU', 'span' => 'md:col-span-4'],
    ['label' => 'Smart Classrooms', 'src' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBcfz1tZ2BJqxlleksGozoGVh6Pl2y-PK3zAhIb3-UOaSl3TvqXXwv84MZqdTPPX8qppDOS8BHMJ8pJFFGIxC446ZV7Bju3RTlDWqKMFv_EqoP7MMOqJx6zXBRna48h7Hb_B6g9WnlU2MsJN9-H0LJWvZXKY9SJS51u5qJZj4qvTY0RfqOUIRVf0aaY71Y5nmacXnGQ23bnTFQsnvbxbgNQGC5JD0ebPkCaI3VGovxBFauk2JRORIx764ekhcJbA6DobWGsYKX-_Nc', 'span' => 'md:col-span-8'],
];

$heroImage = !empty($page['hero_image']) ? upload_url($page['hero_image']) : $defaultHeroImage;
$heritageImage = !empty($page['about_image']) ? upload_url($page['about_image']) : $defaultHeritageImage;
$directorImage = !empty($page['principal_image']) ? upload_url($page['principal_image']) : $defaultDirectorImage;

$heroEyebrow = $page['hero_subtitle'] ?? 'Excellence in Vocational Training';
$heroTitle = $page['hero_title'] ?? 'Empowering the Next Generation of Technical Professionals';
$heroDesc = $page['hero_description'] ?? "Bihar's leading ITI providing industry-standard technical training, certified by NCVT, and dedicated to bridging the global skill gap.";

$heritageTitle = $page['about_title'] ?? 'Our Institutional Heritage';
$heritageText1 = $page['about_description'] ?? 'Established with a vision to revolutionize technical education in the heart of Bihar, Maner Private ITI has stood as a beacon of vocational excellence for over a decade. Our journey began with a simple mission: to provide the youth of Bihar with tangible, industry-ready skills that translate directly into meaningful employment.';
$heritageText2 = $page['mission_description'] ?? 'We recognized the widening gap between traditional academic paths and the burgeoning needs of the global industrial sector. By focusing on precision trades like Electrician and Fitter, we empower our students to become the backbone of modern infrastructure and manufacturing.';

$stats = json_decode_safe($page['stats_json'] ?? '');
if (empty($stats)) {
    $stats = [
        ['value' => '10+', 'label' => 'Years of Legacy'],
        ['value' => '2500+', 'label' => 'Graduates'],
    ];
}

$features = json_decode_safe($page['features_json'] ?? '');
if (empty($features)) {
    $features = [
        ['icon' => 'school', 'title' => 'Expert Faculty', 'text' => 'Instructors with over 15+ years of industrial experience in heavy machinery and electrical systems.', 'highlight' => false],
        ['icon' => 'precision_manufacturing', 'title' => 'Modern Labs', 'text' => 'Fully equipped workshops for Electrician & Fitter trades featuring latest CNC and testing equipment.', 'highlight' => false],
        ['icon' => 'work', 'title' => 'Placement Support', 'text' => 'Dedicated cell connecting students with top national industrial firms and manufacturing plants.', 'highlight' => false],
        ['icon' => 'account_balance', 'title' => 'Bihar Govt Support', 'text' => 'Seamless Bihar Student Credit Card (BSCC) integration for zero-interest educational financing.', 'highlight' => true],
    ];
}

$directorQuote = $page['principal_message'] ?? 'Our mission is to convert the demographic dividend of Bihar into a technical powerhouse for India.';
$directorBio = $page['vision_description'] ?? "At Maner Private ITI, we don't just teach syllabus; we cultivate discipline, precision, and the grit required for technical excellence. Every tool handled and every circuit completed is a step towards self-reliance.";
$directorName = $page['principal_name'] ?? 'Dr. Rajesh Kumar';

$campusImages = $defaultCampusImages;
if (!empty($gallery)) {
    $spans = ['md:col-span-8', 'md:col-span-4', 'md:col-span-4', 'md:col-span-8'];
    $campusImages = [];
    foreach ($gallery as $i => $img) {
        if (empty($img['image'])) {
            continue;
        }
        $campusImages[] = [
            'label' => $img['category'] ?? 'Campus',
            'src' => upload_url($img['image']),
            'span' => $spans[$i] ?? 'md:col-span-6',
        ];
    }
    if (count($campusImages) < 4) {
        foreach (array_slice($defaultCampusImages, count($campusImages)) as $fallback) {
            $campusImages[] = $fallback;
        }
    }
}

$pageTitle = $title ?? 'About Us | Maner Private ITI';
$pageDescription = $settings['seo_description'] ?? 'Learn about Maner Private ITI — NCVT affiliated vocational training institute in Patna, Bihar.';
$extraCss = ['about.css'];
$navActive = 'about';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container selection:text-on-secondary-container">

<?php require base_path('views/partials/design-nav.php'); ?>

<main>
  <section class="relative h-[600px] flex items-center overflow-hidden about-reveal">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?= e($heroImage) ?>')"></div>
    <div class="absolute inset-0 image-overlay"></div>
    <div class="relative w-full max-w-container-max mx-auto px-gutter">
      <div class="max-w-2xl">
        <span class="text-secondary-container font-label-sm uppercase tracking-widest mb-4 block"><?= e($heroEyebrow) ?></span>
        <h1 class="text-white font-display text-display mb-6"><?= e($heroTitle) ?></h1>
        <p class="text-on-primary-container text-body-lg mb-8 leading-relaxed"><?= e($heroDesc) ?></p>
        <div class="flex flex-wrap gap-4">
          <a class="bg-secondary-container text-on-secondary-container px-8 py-4 font-bold rounded shadow-lg hover:bg-secondary hover:text-white transition-all" href="<?= site_url('trades') ?>">Explore Courses</a>
          <a class="border border-white text-white px-8 py-4 font-bold rounded hover:bg-white/10 transition-all" href="<?= site_url('fee-structure') ?>">Download Brochure</a>
        </div>
      </div>
    </div>
  </section>

  <section class="py-section-gap industrial-grid about-reveal">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
          <h2 class="font-headline-lg text-headline-lg text-primary border-l-4 border-secondary-container pl-6"><?= e($heritageTitle) ?></h2>
          <p class="text-body-lg text-on-surface-variant leading-relaxed"><?= nl2br(e($heritageText1)) ?></p>
          <p class="text-body-md text-on-surface-variant leading-relaxed"><?= nl2br(e($heritageText2)) ?></p>
          <div class="flex gap-8 py-4">
            <?php foreach ($stats as $stat): ?>
            <div>
              <div class="text-display text-primary leading-tight"><?= e($stat['value'] ?? '') ?></div>
              <div class="text-label-sm text-on-surface-variant uppercase"><?= e($stat['label'] ?? '') ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="relative">
          <div class="bg-primary-container p-4 rounded-lg transform rotate-2">
            <img class="rounded shadow-xl grayscale hover:grayscale-0 transition-all duration-700 w-full" alt="Institutional heritage" src="<?= e($heritageImage) ?>"/>
          </div>
          <div class="absolute -bottom-6 -left-6 bg-secondary-container p-8 rounded shadow-2xl hidden md:block">
            <span class="material-symbols-outlined text-4xl text-on-secondary-container mb-2" style="font-variation-settings: 'FILL' 1;">history_edu</span>
            <p class="text-on-secondary-container font-bold italic">"Bridging the skill gap<br/>since day one."</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-primary-container text-white py-20 about-reveal">
    <div class="max-w-container-max mx-auto px-gutter text-center">
      <div class="inline-flex items-center gap-4 bg-white/10 px-6 py-2 rounded-full mb-8">
        <span class="material-symbols-outlined text-secondary-container">verified</span>
        <span class="font-label-sm tracking-wider">OFFICIALLY RECOGNIZED BY DGT / NCVT</span>
      </div>
      <h2 class="font-headline-lg text-headline-lg mb-12">Government Recognized Certification</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white/5 border border-white/10 p-8 rounded-lg backdrop-blur-sm">
          <div class="text-secondary-container font-label-sm mb-2">AFFILIATION NO.</div>
          <div class="text-headline-md font-bold"><?= e($affiliationNo) ?></div>
        </div>
        <div class="bg-white/5 border border-white/10 p-8 rounded-lg backdrop-blur-sm">
          <div class="text-secondary-container font-label-sm mb-2">INSTITUTE CODE</div>
          <div class="text-headline-md font-bold"><?= e($misCode) ?></div>
        </div>
        <div class="bg-white/5 border border-white/10 p-8 rounded-lg backdrop-blur-sm">
          <div class="text-secondary-container font-label-sm mb-2">COMPLIANCE</div>
          <div class="text-headline-md font-bold">NCVT STANDARDS</div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-section-gap about-reveal">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="text-center mb-16">
        <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Why Choose <?= e($logoText) ?>?</h2>
        <p class="text-on-surface-variant max-w-2xl mx-auto">We combine technical rigor with compassionate mentorship to ensure every student succeeds.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($features as $feature):
            $highlight = !empty($feature['highlight']);
            $cardClass = $highlight
                ? 'group p-8 border border-secondary-container/30 bg-secondary-container/5 hover:border-secondary-container transition-all'
                : 'group p-8 border border-outline-variant hover:border-secondary-container transition-all hover:bg-surface-container-low';
            $iconClass = $highlight ? 'text-secondary' : 'text-primary';
            $titleClass = $highlight ? 'font-headline-md text-headline-md mb-4 text-secondary' : 'font-headline-md text-headline-md mb-4';
            $iconFill = $highlight ? " style=\"font-variation-settings: 'FILL' 1;\"" : '';
        ?>
        <div class="<?= $cardClass ?>">
          <span class="material-symbols-outlined text-4xl <?= $iconClass ?> mb-6 group-hover:scale-110 transition-transform"<?= $iconFill ?>><?= e($feature['icon'] ?? 'star') ?></span>
          <h3 class="<?= $titleClass ?>"><?= e($feature['title'] ?? '') ?></h3>
          <p class="text-on-surface-variant text-body-md"><?= e($feature['text'] ?? '') ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="py-section-gap bg-surface-container-low about-reveal">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="bg-white p-12 md:p-20 shadow-xl border border-outline-variant flex flex-col md:flex-row items-center gap-12">
        <div class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0">
          <img class="w-full h-full object-cover rounded-full border-4 border-secondary-container" alt="<?= e($directorName) ?>" src="<?= e($directorImage) ?>"/>
        </div>
        <div class="relative">
          <span class="material-symbols-outlined text-6xl text-secondary-container/30 absolute -top-8 -left-8">format_quote</span>
          <h2 class="font-headline-lg text-headline-lg text-primary mb-6 italic">"<?= e($directorQuote) ?>"</h2>
          <p class="text-body-lg text-on-surface-variant mb-8"><?= nl2br(e($directorBio)) ?></p>
          <div>
            <div class="font-headline-md text-primary"><?= e($directorName) ?></div>
            <div class="text-label-sm text-secondary font-bold uppercase tracking-widest">Director, <?= e($logoText) ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-section-gap overflow-hidden about-reveal">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div class="max-w-xl">
          <h2 class="font-headline-lg text-headline-lg text-primary mb-4">World-Class Infrastructure</h2>
          <p class="text-on-surface-variant">Our campus is designed specifically for technical immersive training with dedicated zones for theory and practice.</p>
        </div>
        <a class="flex items-center gap-2 text-primary font-bold hover:text-secondary transition-all group" href="<?= site_url('infrastructure') ?>">
          View Full Campus Gallery
          <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
        </a>
      </div>
      <div class="grid grid-cols-12 gap-4">
        <?php foreach ($campusImages as $img): ?>
        <div class="col-span-12 <?= e($img['span']) ?> h-80 relative overflow-hidden group">
          <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?= e($img['label']) ?>" src="<?= e($img['src']) ?>"/>
          <div class="absolute bottom-0 left-0 p-6 bg-gradient-to-t from-black/80 to-transparent w-full">
            <span class="text-white font-headline-md"><?= e($img['label']) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="bg-secondary-container py-16 about-reveal">
    <div class="max-w-container-max mx-auto px-gutter flex flex-col md:flex-row items-center justify-between gap-8">
      <div>
        <h2 class="text-on-secondary-container font-headline-lg text-headline-lg mb-2">Ready to Build Your Future?</h2>
        <p class="text-on-secondary-fixed-variant text-body-lg">Admissions are now open. Secure your seat today.</p>
      </div>
      <div class="flex flex-wrap gap-4">
        <a class="bg-primary-container text-white px-10 py-4 font-bold rounded hover:opacity-90 transition-all" href="<?= site_url('apply-admission') ?>">Apply for Admission</a>
        <a class="bg-white text-primary px-10 py-4 font-bold rounded border border-primary/10 hover:bg-surface-container-low transition-all" href="<?= site_url('contact') ?>">Contact Us</a>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.about-reveal').forEach((section) => observer.observe(section));
});
</script>
</body>
</html>
