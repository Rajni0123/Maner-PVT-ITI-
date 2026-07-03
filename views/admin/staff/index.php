<div class="admin-page-header">
  <h1>Staff Management</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/staff/salary') ?>" class="btn btn-primary btn-sm">Generate Salary Slip</a>
  </div>
</div>

<div class="grid-2">
  <form method="post" action="<?= site_url('admin/staff') ?>" class="card" id="staffForm">
    <h3>Add / Edit Staff</h3>
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="staffId" value="">

    <h4 style="margin-top:1rem;margin-bottom:0.75rem">Personal Details</h4>
    <div class="form-grid">
      <div><label>Employee Code</label><input name="employee_code" id="staffCode" placeholder="Auto if blank"></div>
      <div><label>Name *</label><input name="name" id="staffName" required></div>
      <div><label>Designation *</label><input name="designation" id="staffDesig" required></div>
      <div><label>Department</label><input name="department" id="staffDept"></div>
      <div><label>Date of Joining</label><input type="date" name="date_of_joining" id="staffDoj"></div>
      <div><label>Mobile</label><input name="mobile" id="staffMobile" maxlength="10"></div>
      <div><label>Email</label><input type="email" name="email" id="staffEmail"></div>
      <div><label>PAN</label><input name="pan_number" id="staffPan"></div>
      <div><label>PF Number</label><input name="pf_number" id="staffPf"></div>
    </div>
    <label style="margin-top:1rem">Address</label>
    <textarea name="address" id="staffAddress" rows="2"></textarea>

    <h4 style="margin-top:1.25rem;margin-bottom:0.75rem">Bank Details</h4>
    <div class="form-grid">
      <div><label>Bank Name</label><input name="bank_name" id="staffBank"></div>
      <div><label>Account Number</label><input name="account_number" id="staffAccount"></div>
    </div>

    <h4 style="margin-top:1.25rem;margin-bottom:0.75rem">Default Salary Structure</h4>
    <div class="form-grid">
      <div><label>Basic Salary</label><input type="number" step="0.01" min="0" name="basic_salary" id="staffBasic" value="0"></div>
      <div><label>HRA</label><input type="number" step="0.01" min="0" name="hra" id="staffHra" value="0"></div>
      <div><label>DA</label><input type="number" step="0.01" min="0" name="da" id="staffDa" value="0"></div>
      <div><label>Other Allowances</label><input type="number" step="0.01" min="0" name="other_allowances" id="staffAllow" value="0"></div>
      <div><label>PF Deduction</label><input type="number" step="0.01" min="0" name="pf_deduction" id="staffPfDed" value="0"></div>
      <div><label>ESI Deduction</label><input type="number" step="0.01" min="0" name="esi_deduction" id="staffEsiDed" value="0"></div>
      <div><label>Tax (TDS)</label><input type="number" step="0.01" min="0" name="tax_deduction" id="staffTaxDed" value="0"></div>
      <div><label>Other Deductions</label><input type="number" step="0.01" min="0" name="other_deductions" id="staffOtherDed" value="0"></div>
    </div>

    <label style="display:flex;align-items:center;gap:.5rem;margin-top:1rem">
      <input type="checkbox" name="is_active" id="staffActive" value="1" checked> Active
    </label>
    <button type="submit" class="btn btn-primary" style="margin-top:1rem">Save Staff</button>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Code</th><th>Name</th><th>Designation</th><th>Basic</th><th></th></tr>
      </thead>
      <tbody>
      <?php if (!$staff): ?>
      <tr><td colspan="5">No staff added yet. Add staff to generate salary slips.</td></tr>
      <?php else: foreach ($staff as $s): ?>
      <tr>
        <td><?= e($s['employee_code'] ?: '—') ?></td>
        <td><?= e($s['name']) ?></td>
        <td><?= e($s['designation']) ?></td>
        <td><?= format_inr($s['basic_salary']) ?></td>
        <td class="action-btns">
          <button type="button" class="btn btn-sm" onclick="editStaff(<?= e(json_encode($s)) ?>)">Edit</button>
          <a href="<?= site_url('admin/staff/salary?staff_id=' . $s['id']) ?>" class="btn btn-sm btn-secondary">Salary</a>
          <form method="post" action="<?= site_url('admin/staff/delete/' . $s['id']) ?>" data-confirm="Delete this staff member?"><?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Del</button>
          </form>
        </td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function editStaff(row) {
  document.getElementById('staffId').value = row.id || '';
  document.getElementById('staffCode').value = row.employee_code || '';
  document.getElementById('staffName').value = row.name || '';
  document.getElementById('staffDesig').value = row.designation || '';
  document.getElementById('staffDept').value = row.department || '';
  document.getElementById('staffDoj').value = row.date_of_joining || '';
  document.getElementById('staffMobile').value = row.mobile || '';
  document.getElementById('staffEmail').value = row.email || '';
  document.getElementById('staffPan').value = row.pan_number || '';
  document.getElementById('staffPf').value = row.pf_number || '';
  document.getElementById('staffAddress').value = row.address || '';
  document.getElementById('staffBank').value = row.bank_name || '';
  document.getElementById('staffAccount').value = row.account_number || '';
  document.getElementById('staffBasic').value = row.basic_salary || 0;
  document.getElementById('staffHra').value = row.hra || 0;
  document.getElementById('staffDa').value = row.da || 0;
  document.getElementById('staffAllow').value = row.other_allowances || 0;
  document.getElementById('staffPfDed').value = row.pf_deduction || 0;
  document.getElementById('staffEsiDed').value = row.esi_deduction || 0;
  document.getElementById('staffTaxDed').value = row.tax_deduction || 0;
  document.getElementById('staffOtherDed').value = row.other_deductions || 0;
  document.getElementById('staffActive').checked = row.is_active == 1;
  document.getElementById('staffForm').scrollIntoView({ behavior: 'smooth' });
}
</script>
