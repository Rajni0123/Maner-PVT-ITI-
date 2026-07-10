<?php
$navActive = 'home';
$header = $header ?? [];
$hero = $hero ?? [];
$trades = $trades ?? [];
$notices = $notices ?? [];
$faculty = $faculty ?? [];
$settings = $settings ?? [];
$flashNews = $flashNews ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}

$defaultHeroBg = 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1600&q=80';
$heroBg = !empty($hero['background_image']) ? upload_url($hero['background_image']) : $defaultHeroBg;
$heroEyebrow = $hero['subtitle'] ?? 'NCVT Affiliated';
$heroTitle = !empty($hero['title']) ? $hero['title'] : ($logoText);
$heroDesc = $hero['description'] ?? 'Empowering youth with skills for a brighter future through industry-relevant vocational training and hands-on practical education.';
$cta1Text = $hero['cta_text'] ?? 'Explore Courses';
$cta1Link = site_url(ltrim($hero['cta_link'] ?? 'trades', '/'));
$cta2Text = $hero['cta2_text'] ?? 'Apply Now';
$cta2Link = site_url(ltrim($hero['cta2_link'] ?? 'apply-admission', '/'));

$tradeImages = [
    'electrician' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=600&q=80',
    'fitter' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=600&q=80',
];

$whyItems = [
    ['icon' => '1', 'title' => 'Vocational Training', 'text' => 'NCVT certified programs with strong practical focus.'],
    ['icon' => '2', 'title' => 'Expert Faculty', 'text' => 'Industry-experienced instructors and mentors.'],
    ['icon' => '3', 'title' => 'Modern Facilities', 'text' => 'Workshops, labs, and tools for job-ready skills.'],
    ['icon' => '4', 'title' => 'Industry Partnerships', 'text' => 'Internships, apprenticeships, and campus drives.'],
    ['icon' => '5', 'title' => 'Professional Growth', 'text' => 'Seminars, workshops, and career counseling.'],
    ['icon' => '6', 'title' => 'Hands-On Experience', 'text' => 'Live projects and practice-oriented sessions.'],
    ['icon' => '7', 'title' => 'Flexible Pathways', 'text' => 'Trade courses aligned to industry demand.'],
    ['icon' => '8', 'title' => 'Recognized Certification', 'text' => 'Government-recognized NCVT credentials.'],
];

$testimonials = [
    ['quote' => 'Excellent practical training and placement support. The course gave me skills to start my career.', 'name' => 'Rohit Kumar', 'meta' => 'Electrician Batch'],
    ['quote' => 'Industry-oriented training with supportive instructors. Workshop facilities are excellent.', 'name' => 'Priya Singh', 'meta' => 'Fitter Batch'],
    ['quote' => 'Practical knowledge and confidence I gained here helped me secure a good job.', 'name' => 'Amit Sharma', 'meta' => 'Alumni'],
];

$defaultNotices = [
    ['title' => 'Admission Open — New Batch', 'created_at' => date('Y-m-d')],
    ['title' => 'NCVT affiliated trades available', 'created_at' => date('Y-m-d', strtotime('-3 days'))],
    ['title' => 'BSCC scheme accepted for eligible students', 'created_at' => date('Y-m-d', strtotime('-7 days'))],
    ['title' => 'Campus counseling available on working days', 'created_at' => date('Y-m-d', strtotime('-10 days'))],
];
if (empty($notices)) {
    $notices = $defaultNotices;
}

$defaultFaculty = [
    ['name' => 'Principal', 'designation' => 'Principal', 'phone' => $header['phone'] ?? ''],
    ['name' => 'Administration', 'designation' => 'Administration', 'phone' => ''],
];
?>

<section class="pti-hero" style="background-image:url('<?= e($heroBg) ?>')">
  <div class="pti-hero__overlay"></div>
  <div class="pti-container pti-hero__grid">
    <div>
      <span class="pti-hero__eyebrow"><?= e($heroEyebrow) ?></span>
      <h1><?= e($heroTitle) ?></h1>
      <p><?= e($heroDesc) ?></p>
      <div class="pti-hero__ctas">
        <a class="pti-btn pti-btn--primary pti-btn--lg" href="<?= e($cta1Link) ?>"><?= e($cta1Text) ?></a>
        <a class="pti-btn pti-btn--ghost pti-btn--lg" href="<?= e($cta2Link) ?>"><?= e($cta2Text) ?></a>
      </div>
    </div>
    <aside class="pti-notice" aria-label="Notice board">
      <div class="pti-notice__head">Notice Board</div>
      <ul class="pti-notice__list">
        <?php foreach (array_slice($notices, 0, 8) as $n): ?>
        <li>
          <time><?= e(date('d M Y', strtotime($n['created_at'] ?? 'now'))) ?></time>
          <?= e($n['title'] ?? 'Notice') ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <a class="pti-notice__foot" href="<?= site_url('notices') ?>">View All</a>
    </aside>
  </div>
</section>

<section class="pti-section pti-section--alt">
  <div class="pti-container pti-intro">
    <div>
      <h2><?= e($logoText) ?></h2>
      <p>Welcome to our NCVT-affiliated Industrial Training Institute. We provide vocational training and technical education designed to shape skilled professionals for modern industry.</p>
      <p>Our programs combine theoretical understanding with hands-on workshop practice, strong faculty support, and placement assistance through industry partnerships.</p>
      <div class="pti-hero__ctas" style="margin-top:1.25rem">
        <a class="pti-btn pti-btn--primary" href="<?= site_url('trades') ?>">Explore Courses</a>
        <a class="pti-btn pti-btn--outline" href="<?= site_url('about') ?>">About Us</a>
      </div>
    </div>
    <div class="pti-intro__media" style="background-image:url('<?= e($heroBg) ?>')"></div>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Explore Our Institute</h2>
      <p>Your gateway to courses, admissions, facilities, and student resources</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-explore">
      <div class="pti-explore__card">
        <h3>Courses &amp; Training</h3>
        <p>NCVT certified programs with hands-on practical training</p>
        <ul>
          <?php foreach (array_slice($trades, 0, 4) as $t): ?>
          <li><a href="<?= site_url('trades/' . ($t['slug'] ?? '')) ?>"><?= e($t['name']) ?></a></li>
          <?php endforeach; ?>
          <?php if (empty($trades)): ?>
          <li><a href="<?= site_url('trades') ?>">View all courses</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="pti-explore__card">
        <h3>Admissions</h3>
        <p>Start your technical career journey with us</p>
        <ul>
          <li><a href="<?= site_url('apply-admission') ?>">Apply Online</a></li>
          <li><a href="<?= site_url('fee-structure') ?>">Course &amp; Fee List</a></li>
          <li><a href="<?= site_url('notices') ?>">Admission Notice</a></li>
        </ul>
      </div>
      <div class="pti-explore__card">
        <h3>Facilities &amp; Campus</h3>
        <p>Modern infrastructure for skill development</p>
        <ul>
          <li><a href="<?= site_url('infrastructure') ?>">Labs &amp; Workshops</a></li>
          <li><a href="<?= site_url('faculty') ?>">Faculty</a></li>
          <li><a href="<?= site_url('infrastructure') ?>">Campus Gallery</a></li>
        </ul>
      </div>
      <div class="pti-explore__card">
        <h3>Resources &amp; Support</h3>
        <p>Important documents and student assistance</p>
        <ul>
          <li><a href="<?= site_url('trades') ?>">Syllabus</a></li>
          <li><a href="<?= site_url('bscc-info') ?>">BSCC Scheme</a></li>
          <li><a href="<?= site_url('contact') ?>">Contact / Feedback</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="pti-section pti-section--alt">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Featured Courses</h2>
      <p>Industry-aligned NCVT certified courses designed for skill development and career success</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-courses">
      <?php if (empty($trades)): ?>
      <div class="pti-card"><p>Courses will appear here once added in admin.</p></div>
      <?php endif; ?>
      <?php foreach ($trades as $t):
        $slug = $t['slug'] ?? '';
        $img = !empty($t['image']) ? upload_url($t['image']) : ($tradeImages[$slug] ?? $heroBg);
        $desc = $t['description'] ?: 'Hands-on vocational training with NCVT certification and placement support.';
      ?>
      <article class="pti-course">
        <div class="pti-course__img" style="background-image:url('<?= e($img) ?>')"></div>
        <div class="pti-course__body">
          <span class="pti-badge"><?= e($t['duration'] ?? 'NCVT Course') ?></span>
          <h3><?= e($t['name']) ?></h3>
          <p><?= e($desc) ?></p>
          <a class="pti-btn pti-btn--outline" href="<?= site_url('trades/' . $slug) ?>">Learn More</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:1.5rem">
      <a class="pti-btn pti-btn--primary" href="<?= site_url('trades') ?>">View All Courses</a>
    </p>
  </div>
</section>

<?php if (!empty($faculty) || !empty($defaultFaculty)): ?>
<section class="pti-section">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Meet Our Team</h2>
      <p>Dedicated professionals committed to excellence in technical education</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-team">
      <?php
      $team = !empty($faculty) ? $faculty : $defaultFaculty;
      foreach (array_slice($team, 0, 4) as $person):
        $photo = !empty($person['photo']) ? upload_url($person['photo']) : '';
      ?>
      <div class="pti-team__card">
        <div class="pti-team__avatar"<?= $photo ? ' style="background-image:url(\'' . e($photo) . '\')"' : '' ?>></div>
        <h3><?= e($person['name'] ?? 'Faculty') ?></h3>
        <div class="role"><?= e($person['designation'] ?? (!empty($person['is_principal']) ? 'Principal' : 'Faculty')) ?></div>
        <?php if (!empty($person['phone'])): ?>
        <div class="phone"><?= e($person['phone']) ?></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="pti-section pti-section--alt">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Mission, Vision &amp; Objectives</h2>
      <p>The foundation of our industrial training institute</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-mv">
      <div class="pti-mv__card">
        <h3>Our Mission</h3>
        <p>Provide high-quality technical education and skill development to empower youth with industry-relevant training and contribute to nation-building.</p>
      </div>
      <div class="pti-mv__card">
        <h3>Our Vision</h3>
        <p>Become a center of excellence in technical education, nurturing employment-ready graduates through quality training and modern infrastructure.</p>
      </div>
      <div class="pti-mv__card">
        <h3>Our Objectives</h3>
        <ul>
          <li>Industry-aligned technical training</li>
          <li>Skilled workforce for manufacturing &amp; services</li>
          <li>Entrepreneurship among youth</li>
          <li>Strong placement partnerships</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Why Choose Us</h2>
      <p>Practical skills, industry knowledge, and career-focused vocational education</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-why">
      <?php foreach ($whyItems as $item): ?>
      <div class="pti-why__item">
        <div class="pti-why__icon"><?= e($item['icon']) ?></div>
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['text']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="pti-section pti-section--alt">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>What Our Students Say</h2>
      <p>Hear from students and alumni about their experience</p>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-quotes">
      <?php foreach ($testimonials as $t): ?>
      <blockquote class="pti-quote">
        <div class="pti-quote__stars">★★★★★</div>
        <p>“<?= e($t['quote']) ?>”</p>
        <strong><?= e($t['name']) ?></strong>
        <span><?= e($t['meta']) ?></span>
      </blockquote>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="pti-cta-band">
  <div class="pti-container">
    <h2>Start Your Journey</h2>
    <p>Admissions open. Apply online or visit the campus for counseling.</p>
    <a class="pti-btn pti-btn--primary pti-btn--lg" href="<?= site_url('apply-admission') ?>">Apply for Admission</a>
    &nbsp;
    <a class="pti-btn pti-btn--ghost pti-btn--lg" href="<?= site_url('contact') ?>">Contact Us</a>
  </div>
</section>
