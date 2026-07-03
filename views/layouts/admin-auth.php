<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php
$pageTitle = 'Admin Login | Maner Private ITI';
$skipMobileAppCss = true;
require base_path('views/partials/design-head.php');
?>
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
<style>
.technical-grid {
    background-image: 
        linear-gradient(to right, rgba(226, 232, 240, 0.5) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(226, 232, 240, 0.5) 1px, transparent 1px);
    background-size: 40px 40px;
}
.auth-card-shadow {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.shimmer {
    background: linear-gradient(90deg, transparent, rgba(254, 166, 25, 0.2), transparent);
    background-size: 200% 100%;
    animation: shimmer 3s infinite;
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
</style>
</head>
<body class="bg-background text-on-background font-body-md flex flex-col min-h-screen">

<main class="flex-grow flex items-center justify-center relative technical-grid px-margin-mobile py-section-gap overflow-hidden">
  <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container opacity-5 rounded-full blur-3xl"></div>
  <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary-container opacity-5 rounded-full blur-3xl"></div>

  <div class="w-full max-w-md z-10">
    <?= $content ?>
  </div>
</main>

<script>
document.addEventListener('mousemove', (e) => {
    const moveX = (e.clientX - window.innerWidth / 2) * 0.005;
    const moveY = (e.clientY - window.innerHeight / 2) * 0.005;
    document.querySelector('.technical-grid').style.backgroundPosition = moveX + 'px ' + moveY + 'px';
});
</script>
</body>
</html>
