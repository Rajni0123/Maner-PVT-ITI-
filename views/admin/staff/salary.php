<?php
$prefillStaffId = (int) ($_GET['staff_id'] ?? 0);
$staffJson = json_encode($staff ?? []);
?>
<div class="admin-page-header">
  <h1>Staff Salary Slip</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/staff') ?>" class="btn btn-outline btn-sm">Manage Staff</a>
  </div>
</div>

<?php if (!$staff): ?>
<div class="admin-alert admin-alert-error">
  No active staff found. <a href="<?= site_url('admin/staff') ?>">Add staff first</a> to generate salary slips.
</div>
<?php else: ?>
<form method="post" action="<?= site_url('admin/staff/salary/generate') ?>" class="card" id="salaryForm">
  <h3>Generate Salary Slip</h3>
  <?= csrf_field() ?>

  <div class="form-grid">
    <div>
      <label>Staff Member *</label>
      <select name="staff_id" id="salaryStaffId" required>
        <option value="">Select staff</option>
        <?php foreach ($staff as $s): ?>
        <option value="<?= (int) $s['id'] ?>" <?= $prefillStaffId === (int) $s['id'] ? 'selected' : '' ?>>
          <?= e($s['name']) ?> — <?= e($s['designation']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Salary Month *</label>
      <select name="slip_month" required>
        <?php for ($m = 1; $m <= 12; $m++): ?>
        <option value="<?= $m ?>" <?= $currentMonth === $m ? 'selected' : '' ?>><?= month_name($m) ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div>
      <label>Salary Year *</label>
      <input type="number" name="slip_year" min="2000" max="2100" value="<?= (int) $currentYear ?>" required>
    </div>
    <div><label>Working Days</label><input type="number" name="working_days" id="salWorkingDays" min="1" max="31" value="30"></div>
    <div><label>Paid Days</label><input type="number" name="paid_days" id="salPaidDays" min="1" max="31" value="30"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Earnings</h4>
  <div class="form-grid">
    <div><label>Basic Salary</label><input type="number" step="0.01" min="0" name="basic_salary" id="salBasic" value="0" class="salary-calc"></div>
    <div><label>HRA</label><input type="number" step="0.01" min="0" name="hra" id="salHra" value="0" class="salary-calc"></div>
    <div><label>DA</label><input type="number" step="0.01" min="0" name="da" id="salDa" value="0" class="salary-calc"></div>
    <div><label>Other Allowances</label><input type="number" step="0.01" min="0" name="other_allowances" id="salAllow" value="0" class="salary-calc"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Deductions</h4>
  <div class="form-grid">
    <div><label>PF</label><input type="number" step="0.01" min="0" name="pf_deduction" id="salPfDed" value="0" class="salary-calc"></div>
    <div><label>ESI</label><input type="number" step="0.01" min="0" name="esi_deduction" id="salEsiDed" value="0" class="salary-calc"></div>
    <div><label>Tax (TDS)</label><input type="number" step="0.01" min="0" name="tax_deduction" id="salTaxDed" value="0" class="salary-calc"></div>
    <div><label>Other Deductions</label><input type="number" step="0.01" min="0" name="other_deductions" id="salOtherDed" value="0" class="salary-calc"></div>
  </div>

  <div class="stat-grid" style="margin-top:1.25rem">
    <div class="stat-card"><span>Gross Pay</span><strong id="salGross">₹ 0.00</strong></div>
    <div class="stat-card"><span>Total Deductions</span><strong id="salDeductions">₹ 0.00</strong></div>
    <div class="stat-card"><span>Net Pay</span><strong id="salNet">₹ 0.00</strong></div>
  </div>

  <label style="margin-top:1rem">Notes (optional)</label>
  <textarea name="notes" rows="2" placeholder="Any remarks for this salary slip"></textarea>

  <button type="submit" class="btn btn-primary" style="margin-top:1rem">Generate &amp; Print Slip</button>
</form>
<?php endif; ?>

<div class="card">
  <h3>Recent Salary Slips</h3>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Slip No.</th><th>Staff</th><th>Period</th><th>Net Pay</th><th>Generated</th><th></th></tr>
      </thead>
      <tbody>
      <?php if (!$slips): ?>
      <tr><td colspan="6">No salary slips generated yet.</td></tr>
      <?php else: foreach ($slips as $sl): ?>
      <tr>
        <td><?= e($sl['slip_number'] ?: '—') ?></td>
        <td><?= e($sl['staff_name']) ?></td>
        <td><?= month_name((int) $sl['slip_month']) ?> <?= (int) $sl['slip_year'] ?></td>
        <td><?= format_inr($sl['net_pay']) ?></td>
        <td><?= format_date($sl['generated_at']) ?></td>
        <td><a href="<?= site_url('admin/staff/salary/print/' . $sl['id']) ?>" target="_blank" class="btn btn-sm btn-primary">Print</a></td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
(function () {
  var staffList = <?= $staffJson ?>;
  var select = document.getElementById('salaryStaffId');
  if (!select) return;

  function money(n) {
    return '₹ ' + Number(n || 0).toFixed(2);
  }

  function calcTotals() {
    var gross = ['salBasic', 'salHra', 'salDa', 'salAllow'].reduce(function (sum, id) {
      return sum + parseFloat(document.getElementById(id).value || 0);
    }, 0);
    var ded = ['salPfDed', 'salEsiDed', 'salTaxDed', 'salOtherDed'].reduce(function (sum, id) {
      return sum + parseFloat(document.getElementById(id).value || 0);
    }, 0);
    document.getElementById('salGross').textContent = money(gross);
    document.getElementById('salDeductions').textContent = money(ded);
    document.getElementById('salNet').textContent = money(Math.max(0, gross - ded));
  }

  function fillFromStaff(id) {
    var row = staffList.find(function (s) { return String(s.id) === String(id); });
    if (!row) return;
    document.getElementById('salBasic').value = row.basic_salary || 0;
    document.getElementById('salHra').value = row.hra || 0;
    document.getElementById('salDa').value = row.da || 0;
    document.getElementById('salAllow').value = row.other_allowances || 0;
    document.getElementById('salPfDed').value = row.pf_deduction || 0;
    document.getElementById('salEsiDed').value = row.esi_deduction || 0;
    document.getElementById('salTaxDed').value = row.tax_deduction || 0;
    document.getElementById('salOtherDed').value = row.other_deductions || 0;
    calcTotals();
  }

  select.addEventListener('change', function () { fillFromStaff(this.value); });
  document.querySelectorAll('.salary-calc').forEach(function (el) {
    el.addEventListener('input', calcTotals);
  });

  if (select.value) fillFromStaff(select.value);
  calcTotals();
})();
</script>
