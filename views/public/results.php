<div class="page-header"><div class="container"><h1>Results</h1></div></div>
<section class="section"><div class="container table-wrap">
  <table><tr><th>Title</th><th>Trade</th><th>Year</th><th>PDF</th></tr>
  <?php foreach ($results as $r): ?>
  <tr><td><?= e($r['title']) ?></td><td><?= e($r['trade']) ?></td><td><?= e($r['year']) ?></td>
  <td><a href="<?= upload_url($r['pdf']) ?>" target="_blank">Download</a></td></tr>
  <?php endforeach; ?>
  </table>
</div></section>
