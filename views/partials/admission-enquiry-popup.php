<?php
use App\Models\SiteData;

if (!empty($hideEnquiryPopup)) {
    return;
}

$enquiryTrades = $trades ?? SiteData::activeTrades();
$enquirySessions = SiteData::activeSessions();
$enquiryCategories = [];
foreach ($enquiryTrades as $t) {
    $cat = trim((string) ($t['category'] ?? ''));
    if ($cat !== '') {
        $enquiryCategories[$cat] = $cat;
    }
}
if (empty($enquiryCategories)) {
    $enquiryCategories = ['Engineering' => 'Engineering'];
}
?>
<div id="enquiryPopup" class="enquiry-popup" hidden aria-hidden="true" style="display:none">
  <div class="enquiry-popup__backdrop" data-enquiry-close></div>
  <div class="enquiry-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="enquiryPopupTitle">
    <div class="enquiry-popup__header">
      <h2 id="enquiryPopupTitle" class="enquiry-popup__title">Admission Enquiry</h2>
      <button type="button" class="enquiry-popup__close" data-enquiry-close aria-label="Close">
        <span class="material-symbols-outlined" aria-hidden="true">close</span>
      </button>
    </div>

    <form id="enquiryPopupForm" class="enquiry-popup__form" method="post" action="<?= site_url('enquiry') ?>" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="popup">
      <input type="hidden" name="ajax" value="1">

      <div class="enquiry-popup__field">
        <input type="text" name="name" id="enquiryName" placeholder="Enter Name" required autocomplete="name" maxlength="120">
      </div>
      <div class="enquiry-popup__field">
        <input type="tel" name="phone" id="enquiryPhone" placeholder="Enter Mobile Number" required autocomplete="tel" maxlength="15" inputmode="numeric">
      </div>
      <div class="enquiry-popup__field">
        <select name="course" id="enquiryCourse" required>
          <option value="" disabled selected>Select Course</option>
          <?php foreach ($enquiryCategories as $cat): ?>
          <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="enquiry-popup__field">
        <select name="subject" id="enquirySubject" required>
          <option value="" disabled selected>Select Subject</option>
          <?php foreach ($enquiryTrades as $t): ?>
          <option value="<?= e($t['name']) ?>" data-category="<?= e($t['category'] ?? '') ?>"><?= e($t['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <p id="enquiryPopupMsg" class="enquiry-popup__msg" hidden></p>

      <button type="submit" class="enquiry-popup__submit" id="enquirySubmitBtn">Submit</button>
    </form>
  </div>
</div>
<script>
window.ENQUIRY_POPUP = {
  url: <?= json_encode(site_url('enquiry')) ?>,
  storageKey: 'maner_enquiry_popup_seen'
};
</script>
<script src="<?= asset('js/enquiry-popup.js') ?>" defer></script>
