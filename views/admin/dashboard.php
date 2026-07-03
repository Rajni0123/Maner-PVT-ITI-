<?php
$s = $stats ?? [];
$pipeline = $pipeline ?? [];
$pipelineMax = max(1, (int) ($pipelineMax ?? 1));
$fillPct = (float) ($s['admission_fill_pct'] ?? 0);
?>

<div class="admin-page-header">
  <div>
    <h1>Dashboard</h1>
    <p style="margin:0.35rem 0 0;font-size:0.9rem;color:var(--admin-on-surface-variant)"><?= e(date('F Y')) ?> · live institute data</p>
  </div>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/admissions/export') ?>" class="btn btn-primary btn-sm">
      <span class="material-symbols-outlined" style="font-size:18px">download</span>
      Export Admissions
    </a>
  </div>
</div>

<div class="stat-grid dashboard-stat-grid">
  <div class="stat-card">
    <span>Approved Admissions</span>
    <strong><?= (int) ($s['approved'] ?? 0) ?> <small style="font-size:0.55em;font-weight:600;color:var(--admin-on-surface-variant)">/ <?= (int) ($s['total_seats'] ?? 0) ?> seats</small></strong>
    <div class="dashboard-bar"><i style="width:<?= e((string) $fillPct) ?>%"></i></div>
    <p class="dashboard-stat-note"><?= (int) ($s['pending'] ?? 0) ?> pending review</p>
  </div>
  <div class="stat-card">
    <span>Unread Inquiries</span>
    <strong><?= (int) ($s['unread_inquiries'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= (int) ($s['inquiries_today'] ?? 0) ?> today<?php if (($s['inquiries_delta'] ?? 0) > 0): ?> · +<?= (int) $s['inquiries_delta'] ?> vs yesterday<?php endif; ?></p>
  </div>
  <div class="stat-card">
    <span>Fees Collected</span>
    <strong><?= format_inr($s['fees_paid'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= (int) ($s['fees_pct'] ?? 0) ?>% of <?= format_inr($s['fees_total'] ?? 0) ?> billed</p>
  </div>
  <div class="stat-card">
    <span>Active Students</span>
    <strong><?= (int) ($s['students'] ?? 0) ?></strong>
    <p class="dashboard-stat-note"><?= (int) ($s['total_applications'] ?? 0) ?> total applications</p>
  </div>
</div>

<div class="dashboard-main-grid">
  <div class="card dashboard-chart-card">
    <div class="dashboard-card-head">
      <h3>Admissions (last 6 months)</h3>
    </div>
    <div class="dashboard-chart">
      <?php foreach ($pipeline as $bar): ?>
      <?php $h = $bar['total'] > 0 ? max(8, round(($bar['total'] / $pipelineMax) * 100)) : 4; ?>
      <div class="dashboard-chart-col" title="<?= (int) $bar['total'] ?> applications">
        <div class="dashboard-chart-bar" style="height:<?= $h ?>%"><em><?= (int) $bar['total'] ?></em></div>
        <span><?= e($bar['month_label']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card dashboard-actions-card">
    <h3>Quick Actions</h3>
    <div class="dashboard-actions">
      <a href="<?= site_url('admin/contacts') ?>"><span class="material-symbols-outlined">mail</span> Inquiries<?php if (($s['unread_inquiries'] ?? 0) > 0): ?> <b><?= (int) $s['unread_inquiries'] ?></b><?php endif; ?></a>
      <a href="<?= site_url('admin/admissions') ?>"><span class="material-symbols-outlined">person_add</span> Admissions<?php if (($s['pending'] ?? 0) > 0): ?> <b><?= (int) $s['pending'] ?></b><?php endif; ?></a>
      <a href="<?= site_url('admin/students') ?>"><span class="material-symbols-outlined">school</span> Students</a>
      <a href="<?= site_url('admin/fees/collect') ?>"><span class="material-symbols-outlined">payments</span> Collect Fee</a>
      <a href="<?= site_url('admin/staff/salary') ?>"><span class="material-symbols-outlined">receipt_long</span> Salary Slip</a>
      <a href="<?= site_url('admin/finance') ?>"><span class="material-symbols-outlined">account_balance</span> Finance Report</a>
    </div>
  </div>
</div>

<div class="card">
  <div class="dashboard-card-head">
    <h3>Recent Activity</h3>
    <a href="<?= site_url('admin/admissions') ?>" class="btn btn-outline btn-sm">View all</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Name</th>
          <th>Detail</th>
          <th>Status</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php
      $activity = [];
      foreach ($recentContacts as $c) {
          $activity[] = [
              'sort' => strtotime($c['created_at'] ?? ''),
              'type' => 'Inquiry',
              'name' => $c['name'],
              'detail' => $c['trade_interest'] ?: str_limit($c['message'] ?? '', 40),
              'status' => !empty($c['is_read']) ? 'Read' : 'New',
              'badge' => !empty($c['is_read']) ? 'inactive' : 'pending',
              'url' => site_url('admin/contacts'),
              'date' => $c['created_at'],
          ];
      }
      foreach ($recent as $r) {
          $activity[] = [
              'sort' => strtotime($r['created_at'] ?? ''),
              'type' => 'Application',
              'name' => $r['name'],
              'detail' => ($r['trade'] ?? '') . ($r['session'] ? ' · ' . $r['session'] : ''),
              'status' => $r['status'] ?? 'Pending',
              'badge' => strtolower($r['status'] ?? 'pending'),
              'url' => site_url('admin/admissions/view/' . $r['id']),
              'date' => $r['created_at'],
          ];
      }
      usort($activity, static fn($a, $b) => ($b['sort'] ?? 0) <=> ($a['sort'] ?? 0));
      $activity = array_slice($activity, 0, 8);
      ?>
      <?php if (!$activity): ?>
      <tr><td colspan="6">No inquiries or applications yet.</td></tr>
      <?php else: foreach ($activity as $row): ?>
      <tr>
        <td><?= e($row['type']) ?></td>
        <td><?= e($row['name']) ?></td>
        <td><?= e($row['detail']) ?></td>
        <td><span class="badge badge-<?= e($row['badge']) ?>"><?= e($row['status']) ?></span></td>
        <td><?= format_date($row['date']) ?></td>
        <td><a href="<?= e($row['url']) ?>" class="btn btn-sm btn-outline">Open</a></td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
