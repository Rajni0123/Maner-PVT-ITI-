<div class="admin-page-header" style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap">
  <div>
    <h1>Newsletter Subscribers</h1>
    <p style="margin:0;color:var(--admin-on-surface-variant);font-size:0.9rem">
      Emails collected from the footer newsletter form — <?= (int) ($total ?? 0) ?> active subscriber<?= ((int) ($total ?? 0)) === 1 ? '' : 's' ?>
    </p>
  </div>
  <?php if (!empty($items)): ?>
  <a class="btn btn-primary" href="<?= site_url('admin/newsletter/export') ?>">Export CSV</a>
  <?php endif; ?>
</div>

<?php if (($settings['newsletter_enabled'] ?? '1') !== '1'): ?>
<div class="admin-alert admin-alert-error" style="margin-bottom:1rem">
  Newsletter form is currently <strong>disabled</strong> on the website. Enable it in <a href="<?= site_url('admin/settings') ?>">Site Settings</a>.
</div>
<?php endif; ?>

<?php if (!$items): ?>
<div class="card">
  <p style="margin:0">No subscribers yet. When someone subscribes via the footer on your website, their email will appear here.</p>
</div>
<?php else: ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Subscribed</th>
        <th>Email</th>
        <th>Source</th>
        <th>IP</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $row): ?>
    <tr>
      <td><?= format_date($row['subscribed_at'] ?? null) ?></td>
      <td><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></td>
      <td><?= e($row['source'] ?? 'footer') ?></td>
      <td><?= e($row['ip_address'] ?? '—') ?></td>
      <td>
        <form method="post" action="<?= site_url('admin/newsletter/delete/' . (int) $row['id']) ?>" data-confirm="Remove this subscriber?">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-sm" style="color:var(--admin-error)">Remove</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
