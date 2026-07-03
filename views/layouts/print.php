<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= e($title ?? 'Print') ?></title>
  <link rel="stylesheet" href="<?= asset('css/print.css') ?>">
</head>
<body class="print-page">
  <div class="no-print">
    <button type="button" onclick="window.print()">Print / Save PDF</button>
    <button type="button" onclick="window.close()">Close</button>
  </div>
  <?= $content ?>
</body>
</html>
