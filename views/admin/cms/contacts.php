<div class="admin-page-header">
  <h1>Admission Inquiries</h1>
  <p style="margin:0;color:var(--admin-on-surface-variant);font-size:0.9rem">Messages from the Contact page — Admission Inquiry form</p>
</div>

<?php if (!$items): ?>
<div class="card">
  <p style="margin:0">No inquiries yet. When someone submits the form on <a href="<?= site_url('contact') ?>" target="_blank">/contact</a>, it will appear here.</p>
</div>
<?php else: ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Trade</th>
        <th>Message</th>
        <th>Type</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $c): ?>
    <tr>
      <td><?= format_date($c['created_at']) ?></td>
      <td><?= e($c['name']) ?></td>
      <td><?= e(format_mobile($c['phone'] ?? '')) ?></td>
      <td><?= e($c['email']) ?></td>
      <td><?= e($c['trade_interest'] ?? '—') ?></td>
      <td>
        <details>
          <summary style="cursor:pointer;color:var(--admin-primary);font-weight:600"><?= e(str_limit($c['message'], 60)) ?></summary>
          <p style="margin:0.5rem 0 0;white-space:pre-wrap;word-break:break-word"><?= e($c['message']) ?></p>
        </details>
      </td>
      <td><span class="badge badge-approved"><?= e($c['inquiry_type'] ?? 'Admission Inquiry') ?></span></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
