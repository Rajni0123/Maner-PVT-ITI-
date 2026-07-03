<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Page Not Found</title>
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<section class="section"><div class="container" style="text-align:center;padding:4rem 0">
  <h1 style="font-size:4rem;color:var(--primary)">404</h1>
  <h2>Page Not Found</h2>
  <p style="margin:1rem 0;color:var(--muted)">The page you requested does not exist.</p>
  <a href="<?= site_url() ?>" class="btn btn-primary">Go Home</a>
  <a href="<?= site_url('admin/login') ?>" class="btn btn-outline" style="margin-left:.5rem">Admin</a>
</div></section>
</body>
</html>
