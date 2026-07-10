<?php
use App\Models\SiteData;

if (!empty($hideEnquiryPopup)) {
    return;
}

$enquiryTrades = $trades ?? SiteData::activeTrades();
?>
<button type="button" class="pti-fab" id="ptiEnquiryOpen">Enquiry</button>

<div class="pti-enquiry" id="ptiEnquiry" hidden>
  <div class="pti-enquiry__backdrop" data-pti-close></div>
  <div class="pti-enquiry__dialog" role="dialog" aria-modal="true" aria-labelledby="ptiEnquiryTitle">
    <div class="pti-enquiry__head">
      <h2 id="ptiEnquiryTitle">Admission Enquiry</h2>
      <button type="button" class="pti-enquiry__close" data-pti-close aria-label="Close">&times;</button>
    </div>
    <form class="pti-form" id="ptiEnquiryForm" method="post" action="<?= site_url('enquiry') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="ajax" value="1">
      <input type="hidden" name="source" value="patna-popup">
      <label for="ptiName">Full Name *</label>
      <input type="text" id="ptiName" name="name" required maxlength="120" autocomplete="name">
      <label for="ptiPhone">Mobile Number *</label>
      <input type="tel" id="ptiPhone" name="phone" required maxlength="15" inputmode="numeric" autocomplete="tel">
      <label for="ptiCourse">Select Course *</label>
      <select id="ptiCourse" name="course" required>
        <option value="" disabled selected>-- Select Course --</option>
        <?php foreach ($enquiryTrades as $t): ?>
        <option value="<?= e($t['name']) ?>"><?= e($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="subject" id="ptiSubject" value="">
      <p id="ptiEnquiryMsg" class="pti-flash" hidden></p>
      <button type="submit" class="pti-btn pti-btn--primary" style="width:100%">Submit Enquiry</button>
    </form>
  </div>
</div>
<script>
(function () {
  var modal = document.getElementById('ptiEnquiry');
  var openBtn = document.getElementById('ptiEnquiryOpen');
  var form = document.getElementById('ptiEnquiryForm');
  var msg = document.getElementById('ptiEnquiryMsg');
  var course = document.getElementById('ptiCourse');
  var subject = document.getElementById('ptiSubject');
  if (!modal || !openBtn || !form) return;

  function open() { modal.hidden = false; document.body.style.overflow = 'hidden'; }
  function close() { modal.hidden = true; document.body.style.overflow = ''; }

  openBtn.addEventListener('click', open);
  modal.querySelectorAll('[data-pti-close]').forEach(function (el) {
    el.addEventListener('click', close);
  });

  if (course && subject) {
    course.addEventListener('change', function () { subject.value = course.value; });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (course && subject) subject.value = course.value;
    var fd = new FormData(form);
    msg.hidden = true;
    fetch(form.action, {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function (r) { return r.json(); }).then(function (data) {
      msg.hidden = false;
      msg.className = 'pti-flash ' + (data.ok ? 'pti-flash--success' : 'pti-flash--error');
      msg.textContent = data.message || (data.ok ? 'Submitted.' : 'Failed.');
      if (data.ok) form.reset();
    }).catch(function () {
      msg.hidden = false;
      msg.className = 'pti-flash pti-flash--error';
      msg.textContent = 'Network error. Please try again.';
    });
  });
})();
</script>
