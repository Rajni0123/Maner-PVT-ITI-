<?php
$header = $header ?? \App\Models\SiteData::header();
$settings = $settings ?? \App\Models\SiteData::settings();
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$phone = $header['phone'] ?? '';
$email = $header['email'] ?? '';
$misCode = $settings['mis_code'] ?? 'PR10001156';

$feeTableRows = [
    ['Trade / ट्रेड', 'Electrician & Fitter'],
    ['Duration of Course / कोर्स अवधि', '2 Years [ अगस्त से जुलाई ]'],
    ['Prospectus & Admission Form / प्रोस्पेक्टस एवं प्रवेश फॉर्म', 'Rs. 200/-'],
    ['Tuition Fee (at enrollment) / नामांकन के समय भुगतान', 'Rs. 10,000/-'],
    ['Tuition Fee — 6 installments of Rs. 7,000 every 3 months / प्रत्येक तीन माह पर 7,000 रु का 6 किस्त', 'Rs. 42,000/-'],
    ['Total Tuition Fee (2-Year Course) / दो वर्षीय कोर्स का कुल प्रशिक्षण शुल्क', 'Rs. 52,200/-'],
    ['AITT Exam Fee [ NCVT का परीक्षा शुल्क ]', 'NCVT के आदेशानुसार प्रति वर्ष 500/- रुपये'],
];
$year1Installments = [
    ['1st Installment [ किस्त ]', '01 अक्टूबर से', '15 अक्टूबर तक'],
    ['2nd Installment [ किस्त ]', '01 जनवरी से', '15 जनवरी तक'],
    ['3rd Installment [ किस्त ]', '01 अप्रैल से', '15 अप्रैल तक'],
];
$year2Installments = [
    ['4th Installment [ किस्त ]', '01 अक्टूबर से', '15 अक्टूबर तक'],
    ['5th Installment [ किस्त ]', '01 जनवरी से', '15 जनवरी तक'],
    ['6th Installment [ किस्त ]', '01 अप्रैल से', '15 अप्रैल तक'],
];
$bankName = $settings['fee_bank_name'] ?? '';
$bankAddress = $settings['fee_bank_address'] ?? '';
$bankHolder = $settings['fee_bank_holder'] ?? $logoText;
$bankAccount = $settings['fee_bank_account'] ?? '';
$bankIfsc = $settings['fee_bank_ifsc'] ?? '';
?>
<style>
  .fee-pdf-page {
    max-width: 800px;
    margin: 0 auto;
    border: 2px solid #131b2e;
    padding: 18px 20px;
    background: #fff;
    color: #191c1e;
    font-family: Arial, Helvetica, sans-serif;
  }
  .fee-pdf-header {
    text-align: center;
    border-bottom: 3px solid #fea619;
    padding-bottom: 12px;
    margin-bottom: 14px;
  }
  .fee-pdf-header h1 {
    margin: 0;
    font-size: 20px;
    color: #131b2e;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  .fee-pdf-header .sub {
    margin: 4px 0 0;
    font-size: 12px;
    color: #64748b;
  }
  .fee-pdf-header .session {
    margin: 8px 0 0;
    display: inline-block;
    background: #131b2e;
    color: #fea619;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    letter-spacing: 0.04em;
  }
  .fee-pdf-title {
    text-align: center;
    font-size: 15px;
    font-weight: 800;
    color: #131b2e;
    margin: 0 0 12px;
  }
  .fee-pdf-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
    font-size: 12px;
  }
  .fee-pdf-table th,
  .fee-pdf-table td {
    border: 1px solid #cbd5e1;
    padding: 8px 10px;
    text-align: left;
    vertical-align: top;
  }
  .fee-pdf-table th {
    background: #131b2e;
    color: #fff;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  .fee-pdf-table tr.total-row td {
    background: #fff7ed;
    font-weight: 800;
    color: #131b2e;
  }
  .fee-pdf-table .label-col { width: 58%; font-weight: 600; color: #334155; }
  .fee-pdf-table .value-col { width: 42%; font-weight: 700; color: #0f172a; }
  .fee-pdf-section-title {
    font-size: 13px;
    font-weight: 800;
    color: #131b2e;
    margin: 14px 0 8px;
    padding-bottom: 4px;
    border-bottom: 2px solid #fea619;
  }
  .fee-pdf-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 14px;
  }
  .fee-pdf-card {
    border: 1px solid #cbd5e1;
    overflow: hidden;
  }
  .fee-pdf-card h4 {
    margin: 0;
    background: #131b2e;
    color: #fff;
    padding: 8px 10px;
    font-size: 12px;
  }
  .fee-pdf-card h4 span {
    display: block;
    color: #fea619;
    font-size: 10px;
    font-weight: 600;
    margin-top: 2px;
  }
  .fee-pdf-bank {
    border: 1px solid #cbd5e1;
    padding: 10px 12px;
    background: #f8fafc;
    font-size: 12px;
    line-height: 1.6;
  }
  .fee-pdf-bank h4 {
    margin: 0 0 6px;
    font-size: 12px;
    color: #131b2e;
  }
  .fee-pdf-footer {
    margin-top: 14px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
    text-align: center;
    font-size: 10px;
    color: #64748b;
    line-height: 1.5;
  }
  @media print {
    .fee-pdf-page { border: none; max-width: none; padding: 0; }
    .fee-pdf-grid { break-inside: avoid; }
  }
</style>

<div class="fee-pdf-page">
  <div class="fee-pdf-header">
    <h1><?= e($logoText) ?></h1>
    <p class="sub">NCVT Affiliated Industrial Training Institute &nbsp;|&nbsp; MIS CODE: <?= e($misCode) ?></p>
    <?php if ($phone || $email): ?>
    <p class="sub">
      <?= $phone ? 'Phone: ' . e(format_mobile($phone)) : '' ?>
      <?= ($phone && $email) ? ' &nbsp;|&nbsp; ' : '' ?>
      <?= $email ? 'Email: ' . e($email) : '' ?>
    </p>
    <?php endif; ?>
    <div class="session">FOR SESSION AUG 2026 – 2028</div>
  </div>

  <div class="fee-pdf-title">सरकार द्वारा निर्धारित कोर्स शुल्क / Government Prescribed Course Fee</div>

  <table class="fee-pdf-table">
    <thead>
      <tr>
        <th>Particulars / विवरण</th>
        <th>Amount / राशि</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($feeTableRows as $i => [$label, $value]): ?>
      <tr class="<?= $i === 5 ? 'total-row' : '' ?>">
        <td class="label-col"><?= e($label) ?></td>
        <td class="value-col"><?= e($value) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="fee-pdf-section-title">किस्त जमा करने का निर्धारित तिथि / Installment Schedule</div>

  <div class="fee-pdf-grid">
    <div class="fee-pdf-card">
      <h4>1st Year Installments<span>प्रथम वर्ष का किस्त जमा करने का निर्धारित तिथि</span></h4>
      <table class="fee-pdf-table" style="margin:0;border:0">
        <thead>
          <tr>
            <th>Installment</th>
            <th>From</th>
            <th>To</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($year1Installments as $row): ?>
          <tr>
            <td><?= e($row[0]) ?></td>
            <td><?= e($row[1]) ?></td>
            <td><?= e($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="fee-pdf-card">
      <h4>2nd Year Installments<span>द्वितीय वर्ष का किस्त जमा करने का निर्धारित तिथि</span></h4>
      <table class="fee-pdf-table" style="margin:0;border:0">
        <thead>
          <tr>
            <th>Installment</th>
            <th>From</th>
            <th>To</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($year2Installments as $row): ?>
          <tr>
            <td><?= e($row[0]) ?></td>
            <td><?= e($row[1]) ?></td>
            <td><?= e($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="fee-pdf-section-title">Institute Bank Account Details</div>
  <div class="fee-pdf-bank">
    <?php if ($bankAccount !== '' && $bankIfsc !== ''): ?>
      <strong>BANK NAME:</strong> <?= e($bankName) ?><?= $bankAddress ? ', ' . e($bankAddress) : '' ?><br>
      <strong>ACCOUNT HOLDER:</strong> <?= e($bankHolder) ?><br>
      <strong>ACCOUNT NO:</strong> <?= e($bankAccount) ?><br>
      <strong>IFSC CODE:</strong> <?= e($bankIfsc) ?>
    <?php else: ?>
      Bank account details ke liye institute office se contact karein.<br>
      <?= $phone ? 'Phone: ' . e(format_mobile($phone)) : '' ?>
      <?= ($phone && $email) ? ' | ' : '' ?>
      <?= $email ? 'Email: ' . e($email) : '' ?>
    <?php endif; ?>
  </div>

  <div class="fee-pdf-footer">
    <?= e(strtoupper($logoText)) ?> &nbsp;|&nbsp; Fee Structure Session 2026–2028<br>
    Computer Generated Document — Valid with Official Seal of the Institute
  </div>
</div>
