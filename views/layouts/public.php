<?php
$header = \App\Models\SiteData::header();
$footer = \App\Models\SiteData::footer();
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php
$pageTitle = ($title ?? 'Maner Pvt ITI') . ' | Maner Private ITI';
require base_path('views/partials/design-head.php');
?>
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
  <?php require base_path('views/partials/design-nav.php'); ?>

  <?php if ($msg = flash('success')): ?><div class="container px-gutter" style="margin-top:1rem"><div class="alert alert-success"><?= e($msg) ?></div></div><?php endif; ?>
  <?php if ($msg = flash('error')): ?><div class="container px-gutter" style="margin-top:1rem"><div class="alert alert-error"><?= e($msg) ?></div></div><?php endif; ?>

  <main><?= $content ?></main>

  <?php require base_path('views/partials/design-footer.php'); ?>

  <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
