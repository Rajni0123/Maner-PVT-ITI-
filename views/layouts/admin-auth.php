<!DOCTYPE html>
<html class="login-page" lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Admin Login') ?> | Maner Private ITI</title>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=<?= (int) @filemtime(base_path('assets/css/admin.css')) ?>">
</head>
<body class="login-body">
  <div class="login-wrap">
    <div class="login-bg" aria-hidden="true">
      <span class="login-orb login-orb-1"></span>
      <span class="login-orb login-orb-2"></span>
      <span class="login-orb login-orb-3"></span>
    </div>
    <div class="login-card">
      <?php require base_path('views/partials/login-brand.php'); ?>
      <?= $content ?>
    </div>
  </div>
</body>
</html>
