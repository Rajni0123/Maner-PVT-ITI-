<?php
$syllabusPdf = $syllabusPdf ?? '';
$pageTitle = 'Fitter Trade Syllabus | Maner Private ITI';
$pageDescription = 'Fitter Trade syllabus at Maner Private ITI — NCVT affiliated 2-year precision engineering program in Patna, Bihar.';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .industrial-grid {
            background-image: radial-gradient(circle, #e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-container selection:text-on-secondary-container">
<!-- Top Navigation Bar -->
<header class="fixed top-0 w-full z-50 bg-surface-container-lowest border-b border-outline-variant transition-all duration-200 h-20">
<nav class="max-w-container-max mx-auto px-gutter flex justify-between items-center h-20">
<div class="flex items-center gap-base">
<span class="font-headline-md text-headline-md font-bold text-primary">Maner Private ITI</span>
</div>
<div class="hidden md:flex gap-8 items-center">
<a class="font-body-md text-body-md font-semibold text-on-surface-variant hover:text-primary transition-colors" href="<?= site_url() ?>">Home</a>
<a class="font-body-md text-body-md font-semibold text-primary border-b-2 border-secondary-container pb-1" href="<?= site_url('trades') ?>">Courses</a>
<a class="font-body-md text-body-md font-semibold text-on-surface-variant hover:text-primary transition-colors" href="<?= site_url('admission-process') ?>">Admission</a>
<a class="font-body-md text-body-md font-semibold text-on-surface-variant hover:text-primary transition-colors" href="<?= site_url('bscc-info') ?>">BSCC Info</a>
</div>
<button type="button" onclick="window.location.href='<?= site_url('apply-admission') ?>'" class="bg-secondary-container text-on-secondary-container font-body-md font-bold px-6 py-2 rounded transition-all duration-200 active:scale-95 hover:bg-secondary hover:text-white">
                Apply Now
            </button>
</nav>
</header>
<main class="pt-20">
<!-- Hero Section -->
<section class="relative h-[450px] flex items-center overflow-hidden bg-primary-container">
<div class="absolute inset-0 z-0 opacity-40">
<div class="w-full h-full bg-cover bg-center" data-alt="A cinematic, high-contrast photograph of an industrial fitter workshop in Bihar, featuring heavy-duty machinery, precision measuring tools like vernier calipers on a steel workbench, and students in dark blue technical uniforms. The lighting is dramatic and focused, highlighting the metallic textures and the serious, hardworking atmosphere of vocational training. Deep navy and polished steel tones dominate the palette." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCz7QtQA_-KTNjDB2Vccm7pRCNNeZNGJBDDkPCwmZTTqD6hFabGBZrzeUEpTLtZn7VNpBcT4B83mTMoexYK7CNMKUIn2Vn2AbwKDOVcHmK3_sLHVcXUTQ-yztPKbNvD9yn95DhVLsTFNRE0ZeozAIEm8AxV5kBkGB5Wj0oONjygPPbdIZ_RoHan6CzjyKrGdrVceTpP0vmR58IvLqNaq_nq7xp_OMZihkQbIy7DDApOuWQb-qBs6OopAE00afI5v0Y-snF-d3s0dEA')"></div>
</div>
<div class="absolute inset-0 bg-gradient-to-r from-primary-container via-primary-container/80 to-transparent z-10"></div>
<div class="relative z-20 max-w-container-max mx-auto px-gutter w-full">
<div class="max-w-2xl">
<div class="inline-flex items-center gap-2 bg-secondary-container/20 border border-secondary-container px-3 py-1 mb-6">
<span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">verified</span>
<span class="text-secondary-container font-label-sm text-label-sm uppercase tracking-wider">NCVT Affiliated Trade</span>
</div>
<h1 class="font-display text-display text-white mb-4">Fitter Trade Syllabus</h1>
<p class="font-body-lg text-body-lg text-white/80 mb-8 max-w-lg">
                        Master the art of precision engineering. Our comprehensive 2-year program prepares you for the high-demand world of industrial assembly and maintenance.
                    </p>
<div class="flex flex-wrap gap-4">
<div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 border border-white/20">
<span class="material-symbols-outlined text-white">schedule</span>
<div class="flex flex-col">
<span class="text-white/60 font-label-sm text-label-sm uppercase">Duration</span>
<span class="text-white font-bold">2-Year Course</span>
</div>
</div>
<div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 border border-white/20">
<span class="material-symbols-outlined text-white">military_tech</span>
<div class="flex flex-col">
<span class="text-white/60 font-label-sm text-label-sm uppercase">Certification</span>
<span class="text-white font-bold">DGT - NCVT</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Syllabus Content -->
<section class="py-section-gap industrial-grid">
<div class="max-w-container-max mx-auto px-gutter">
<div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
<div class="max-w-xl">
<h2 class="font-headline-lg text-headline-lg text-primary mb-2">Curriculum Breakdown</h2>
<p class="text-on-surface-variant">The course is structured into four distinct semesters, moving from foundational manual skills to complex industrial automation and assembly techniques.</p>
</div>
<button type="button"<?php if ($syllabusPdf): ?> onclick="window.open('<?= e($syllabusPdf) ?>', '_blank')"<?php endif; ?> class="flex items-center gap-3 bg-primary text-white px-8 py-4 font-bold hover:bg-on-surface-variant transition-all">
<span class="material-symbols-outlined">download</span>
                        Download PDF Syllabus
                    </button>
</div>
<!-- Semester Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<!-- Semester 1 -->
<div class="bg-white border border-outline-variant p-8 relative hover:shadow-xl transition-shadow group">
<div class="absolute top-0 right-0 p-4 font-display text-surface-container-high text-6xl font-bold group-hover:text-secondary-container/20 transition-colors">01</div>
<div class="flex items-center gap-3 mb-6">
<div class="w-12 h-12 bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-white">straighten</span>
</div>
<h3 class="font-headline-md text-headline-md text-primary">Semester 1: Foundations</h3>
</div>
<ul class="space-y-4">
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Linear Measurement</span>
<p class="text-sm text-on-surface-variant">Precision measuring with Vernier Calipers and Micrometers.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Marking Tools</span>
<p class="text-sm text-on-surface-variant">Techniques using Dividers, Punches, and Surface Plates.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Bench Work: Filing &amp; Sawing</span>
<p class="text-sm text-on-surface-variant">Manual metal removal and precision cutting basics.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Drilling Operations</span>
<p class="text-sm text-on-surface-variant">Understanding drill bits, speeds, and machine safety.</p>
</div>
</li>
</ul>
</div>
<!-- Semester 2 -->
<div class="bg-white border border-outline-variant p-8 relative hover:shadow-xl transition-shadow group">
<div class="absolute top-0 right-0 p-4 font-display text-surface-container-high text-6xl font-bold group-hover:text-secondary-container/20 transition-colors">02</div>
<div class="flex items-center gap-3 mb-6">
<div class="w-12 h-12 bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-white">faucet</span>
</div>
<h3 class="font-headline-md text-headline-md text-primary">Semester 2: Joining &amp; Fabrication</h3>
</div>
<ul class="space-y-4">
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Sheet Metal Work</span>
<p class="text-sm text-on-surface-variant">Development of surfaces and bending operations.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Soldering &amp; Brazing</span>
<p class="text-sm text-on-surface-variant">Soft and hard soldering techniques for various metals.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Welding (Arc &amp; Gas)</span>
<p class="text-sm text-on-surface-variant">Manual metal arc welding and oxy-acetylene processes.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Riveting</span>
<p class="text-sm text-on-surface-variant">Permanent fastening techniques for industrial structures.</p>
</div>
</li>
</ul>
</div>
<!-- Semester 3 -->
<div class="bg-white border border-outline-variant p-8 relative hover:shadow-xl transition-shadow group">
<div class="absolute top-0 right-0 p-4 font-display text-surface-container-high text-6xl font-bold group-hover:text-secondary-container/20 transition-colors">03</div>
<div class="flex items-center gap-3 mb-6">
<div class="w-12 h-12 bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-white">settings_input_component</span>
</div>
<h3 class="font-headline-md text-headline-md text-primary">Semester 3: Machining</h3>
</div>
<ul class="space-y-4">
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Lathe Work</span>
<p class="text-sm text-on-surface-variant">Turning, facing, and knurling operations on a Lathe machine.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Precision Grinding</span>
<p class="text-sm text-on-surface-variant">Surface finishing and sharpening of cutting tools.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Heat Treatment</span>
<p class="text-sm text-on-surface-variant">Hardening, tempering, and annealing of steel parts.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Screw Threads</span>
<p class="text-sm text-on-surface-variant">Internal and external thread cutting and standards.</p>
</div>
</li>
</ul>
</div>
<!-- Semester 4 -->
<div class="bg-white border border-outline-variant p-8 relative hover:shadow-xl transition-shadow group">
<div class="absolute top-0 right-0 p-4 font-display text-surface-container-high text-6xl font-bold group-hover:text-secondary-container/20 transition-colors">04</div>
<div class="flex items-center gap-3 mb-6">
<div class="w-12 h-12 bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-white">build</span>
</div>
<h3 class="font-headline-md text-headline-md text-primary">Semester 4: Advanced Fitting</h3>
</div>
<ul class="space-y-4">
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Hydraulics &amp; Pneumatics</span>
<p class="text-sm text-on-surface-variant">Understanding fluid power and automated systems.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Preventive Maintenance</span>
<p class="text-sm text-on-surface-variant">Developing maintenance schedules for industrial plants.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Assembly Techniques</span>
<p class="text-sm text-on-surface-variant">Fitting complex components with close tolerances.</p>
</div>
</li>
<li class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
<div>
<span class="font-bold text-primary block">Machine Installation</span>
<p class="text-sm text-on-surface-variant">Foundation work and leveling of heavy machinery.</p>
</div>
</li>
</ul>
</div>
</div>
</div>
</section>
<!-- Career Paths & Partners -->
<section class="py-section-gap bg-surface-container-low border-y border-outline-variant">
<div class="max-w-container-max mx-auto px-gutter">
<div class="flex flex-col md:flex-row gap-12">
<div class="w-full md:w-1/3">
<h2 class="font-headline-lg text-headline-lg text-primary mb-6">Career Opportunities</h2>
<p class="text-on-surface-variant mb-8">Graduates of the Fitter Trade at Maner Private ITI are highly valued by major industrial giants across India.</p>
<div class="space-y-4">
<div class="flex items-center gap-4 bg-white p-4 border border-outline-variant shadow-sm">
<div class="w-12 h-12 flex items-center justify-center bg-surface-container">
<span class="material-symbols-outlined text-primary">directions_bus</span>
</div>
<span class="font-bold text-primary">Tata Motors</span>
</div>
<div class="flex items-center gap-4 bg-white p-4 border border-outline-variant shadow-sm">
<div class="w-12 h-12 flex items-center justify-center bg-surface-container">
<span class="material-symbols-outlined text-primary">factory</span>
</div>
<span class="font-bold text-primary">BHEL</span>
</div>
<div class="flex items-center gap-4 bg-white p-4 border border-outline-variant shadow-sm">
<div class="w-12 h-12 flex items-center justify-center bg-surface-container">
<span class="material-symbols-outlined text-primary">precision_manufacturing</span>
</div>
<span class="font-bold text-primary">Heavy Engineering Corp</span>
</div>
</div>
</div>
<div class="w-full md:w-2/3">
<div class="bg-primary-container p-10 h-full relative overflow-hidden">
<div class="relative z-10">
<h3 class="font-headline-lg text-white mb-6">Thinking of a different path?</h3>
<p class="text-white/70 mb-10 max-w-lg">If you are more interested in electrical circuits and power systems, explore our NCVT affiliated Electrician Trade.</p>
<a class="inline-flex items-center gap-4 group" href="<?= site_url('trades/electrician') ?>">
<div class="p-6 bg-secondary-container group-hover:bg-secondary transition-colors">
<span class="material-symbols-outlined text-on-primary-fixed font-bold" style="font-variation-settings: 'FILL' 1;">bolt</span>
</div>
<div class="flex flex-col">
<span class="text-secondary-container font-label-sm text-label-sm uppercase tracking-widest">Related Course</span>
<span class="text-white font-headline-md text-headline-md group-hover:translate-x-2 transition-transform">Explore Electrician Trade →</span>
</div>
</a>
</div>
<div class="absolute -right-20 -bottom-20 opacity-10 rotate-12 scale-150">
<span class="material-symbols-outlined text-[300px] text-white">electric_bolt</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- BSCC Section (Official Banner) -->
<section class="py-16">
<div class="max-w-container-max mx-auto px-gutter">
<div class="border-2 border-on-tertiary-container bg-surface-container-lowest p-8 flex flex-col md:flex-row items-center gap-8 shadow-lg">
<div class="w-24 h-24 flex-shrink-0 bg-surface-container flex items-center justify-center p-2">
<!-- Bihar Gov Logo Placeholder -->
<div class="w-full h-full bg-cover bg-center" data-alt="The official emblem and logo of the Bihar Government, featuring a Bodhi tree in a stylized gold and blue design. The logo is clean and professional, centered on a white background with high contrast." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDitc1XzR-wvx_KGNFLXMbhYE7C2nroGapHF47QvDijanrq56OV4wRCGSwU8hV_n-csgdqVQdkWZZbw0mUau7DI0NMSD5gIZqhZllBDPMNe-2JMAbhWsL0WIaDuNSEv58ujgBlp2h70REsO4XIOrmOnXWmK7r1M3EYAPKQ6675mAZgKQt3EYoQy3wbWP5Yw0fsa6hwjFlw44FLweGI_rfhJwwORk34ESVekTummNCj69_VKPOFST1JR3jQ4RBk1CfSA5Gg9mWZklMM')"></div>
</div>
<div class="flex-grow text-center md:text-left">
<span class="inline-block px-3 py-1 bg-on-tertiary-container/10 text-on-tertiary-container text-label-sm font-bold uppercase mb-2">Student Support Scheme</span>
<h3 class="font-headline-md text-headline-md text-primary mb-2">Bihar Student Credit Card (BSCC) Scheme</h3>
<p class="text-on-surface-variant">Maner Private ITI supports BSCC. Get zero-interest state-supported loans for your professional training.</p>
</div>
<button type="button" onclick="window.location.href='<?= site_url('bscc-info') ?>'" class="bg-primary text-white px-8 py-3 font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all whitespace-nowrap">
                        Check Eligibility
                    </button>
</div>
</div>
</section>
</main>
<?php require base_path('views/partials/design-footer.php'); ?>
<script>
        // Micro-interaction for scroll effects
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-md', 'h-16');
                header.classList.remove('h-20');
            } else {
                header.classList.remove('shadow-md', 'h-16');
                header.classList.add('h-20');
            }
        });

        // Simple tab/semester interaction could be added here if needed
    </script>
</body></html>
