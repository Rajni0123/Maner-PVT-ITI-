<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= e($title ?? 'Print') ?></title>
  <link rel="stylesheet" href="<?= asset('css/print.css') ?>">
</head>
<body class="print-page">
  <div class="no-print" style="padding:12px;text-align:center;background:#f1f5f9;margin-bottom:12px">
    <button type="button" onclick="window.print()" style="padding:10px 18px;font-weight:700;cursor:pointer;background:#131b2e;color:#fff;border:0;margin-right:8px">Print / Save as PDF</button>
    <button type="button" onclick="window.close()" style="padding:10px 18px;cursor:pointer">Close</button>
    <p style="margin:8px 0 0;font-size:12px;color:#64748b">Tip: Print dialog mein Destination = <strong>Save as PDF</strong> choose karein.</p>
  </div>
  <?= $content ?>
</body>
</html>
