<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php
$pageTitle = 'Admin Login | Maner Private ITI';
require base_path('views/partials/design-head.php');
?>
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="bg-background text-on-surface">
  <div class="login-wrap">
    <div class="login-card">
      <?= $content ?>
    </div>
  </div>
</body>
</html>
