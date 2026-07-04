<section class="section"><div class="container" style="max-width:700px;text-align:center">
  <div class="card" style="padding:2rem">
    <div style="font-size:3rem;color:var(--success)">✓</div>
    <h1 style="color:var(--success);margin:1rem 0">Application Submitted!</h1>
    <p>Your application has been received successfully.</p>
    <div style="background:#eff6ff;border:2px dashed var(--primary);padding:1.5rem;margin:1.5rem 0;border-radius:10px">
      <small style="text-transform:uppercase;letter-spacing:.1em;color:var(--muted)">Application ID</small>
      <div style="font-size:2rem;font-weight:800;color:var(--primary)"><?= e($appId) ?></div>
    </div>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="<?= site_url('apply-admission/print/' . $admission['id']) ?>" target="_blank" class="btn btn-primary">Print / Download PDF</a>
      <a href="<?= site_url() ?>" class="btn btn-outline">Back to Home</a>
    </div>
  </div>
</div></section>
