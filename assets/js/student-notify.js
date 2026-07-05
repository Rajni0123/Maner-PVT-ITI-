(function () {
  var channel = document.getElementById('notifyChannel');
  var emailSubjectWrap = document.getElementById('emailSubjectWrap');
  var emailBodyWrap = document.getElementById('emailBodyWrap');
  var smsBodyWrap = document.getElementById('smsBodyWrap');
  var smsInput = document.getElementById('notifySmsBody');
  var messageInput = document.getElementById('notifyMessage');
  var smsPreview = document.getElementById('smsPreview');
  var form = document.getElementById('notifySendForm');
  var btn = document.getElementById('notifySendBtn');
  var all = document.getElementById('selectAllStudents');
  var checks = document.querySelectorAll('.notify-student-check');
  var smsConfigured = window.NOTIFY_SMS_CONFIGURED === true;

  function updateChannelUi() {
    var mode = channel ? channel.value : 'email';
    if (emailSubjectWrap) emailSubjectWrap.style.display = (mode === 'email' || mode === 'both') ? '' : 'none';
    if (emailBodyWrap) emailBodyWrap.style.display = (mode === 'email' || mode === 'both') ? '' : 'none';
    if (smsBodyWrap) smsBodyWrap.style.display = (mode === 'sms' || mode === 'both') ? '' : 'none';
    if (btn) {
      if (mode === 'sms') btn.textContent = 'Send SMS';
      else if (mode === 'both') btn.textContent = 'Send Email + SMS';
      else btn.textContent = 'Send Email';
    }
  }

  function renderPreview() {
    if (!smsPreview) return;
    var text = (smsInput && smsInput.value) || '';
    var msg = (messageInput && messageInput.value) || 'Your notice message here';
    var sample = {
      '{name}': 'Rahul Kumar',
      '{father_name}': 'Ram Kumar',
      '{trade}': 'Electrician',
      '{session}': '26-28',
      '{enrollment}': 'ENR-001',
      '{mobile}': '+91 9876543210',
      '{institute}': window.NOTIFY_INSTITUTE || 'Maner Private ITI',
      '{phone}': '+91 9155401839',
      '{message}': msg
    };
    Object.keys(sample).forEach(function (key) {
      text = text.split(key).join(sample[key]);
    });
    smsPreview.textContent = text;
  }

  if (channel) {
    channel.addEventListener('change', updateChannelUi);
    updateChannelUi();
  }

  if (smsInput) smsInput.addEventListener('input', renderPreview);
  if (messageInput) messageInput.addEventListener('input', renderPreview);
  renderPreview();

  if (all) {
    all.addEventListener('change', function () {
      checks.forEach(function (c) { c.checked = all.checked; });
    });
  }

  if (form && btn) {
    form.addEventListener('submit', function (e) {
      var selected = document.querySelectorAll('.notify-student-check:checked');
      if (!selected.length) {
        e.preventDefault();
        alert('Kam se kam ek student select karein.');
        return false;
      }
      var mode = channel ? channel.value : 'email';
      if ((mode === 'sms' || mode === 'both') && !smsConfigured) {
        e.preventDefault();
        alert('SMS gateway configured nahi hai. Pehle setup save karein.');
        return false;
      }
      var label = mode === 'sms' ? 'SMS' : (mode === 'both' ? 'Email + SMS' : 'email');
      if (!confirm('Send ' + label + ' notification to ' + selected.length + ' student(s)?')) {
        e.preventDefault();
        return false;
      }
      btn.disabled = true;
      btn.textContent = 'Sending...';
    });
  }
})();
