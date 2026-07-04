(function () {
  var cfg = window.ENQUIRY_POPUP || {};
  var popup = document.getElementById('enquiryPopup');
  var form = document.getElementById('enquiryPopupForm');
  if (!popup || !form) return;

  var storageKey = cfg.storageKey || 'maner_enquiry_popup_seen';
  var msgEl = document.getElementById('enquiryPopupMsg');
  var submitBtn = document.getElementById('enquirySubmitBtn');
  var courseSelect = document.getElementById('enquiryCourse');
  var subjectSelect = document.getElementById('enquirySubject');
  var subjectOptions = subjectSelect
    ? Array.prototype.slice.call(subjectSelect.querySelectorAll('option[data-category]'))
    : [];

  function hasSeen() {
    try {
      return sessionStorage.getItem(storageKey) === '1';
    } catch (e) {
      return false;
    }
  }

  function markSeen() {
    try {
      sessionStorage.setItem(storageKey, '1');
    } catch (e) { /* ignore */ }
  }

  function openPopup() {
    popup.hidden = false;
    popup.setAttribute('aria-hidden', 'false');
    document.body.classList.add('enquiry-popup-open');
    var first = document.getElementById('enquiryName');
    if (first) {
      setTimeout(function () { first.focus(); }, 80);
    }
  }

  function closePopup() {
    popup.hidden = true;
    popup.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('enquiry-popup-open');
    markSeen();
  }

  function showMsg(text, isError) {
    if (!msgEl) return;
    msgEl.hidden = false;
    msgEl.textContent = text;
    msgEl.classList.toggle('is-error', !!isError);
    msgEl.classList.toggle('is-success', !isError);
  }

  function filterSubjects() {
    if (!courseSelect || !subjectSelect) return;
    var cat = courseSelect.value;
    var current = subjectSelect.value;
    var firstVisible = '';

    subjectOptions.forEach(function (opt) {
      var match = !cat || !opt.getAttribute('data-category') || opt.getAttribute('data-category') === cat;
      opt.hidden = !match;
      opt.disabled = !match;
      if (match && !firstVisible) firstVisible = opt.value;
    });

    var selected = subjectSelect.options[subjectSelect.selectedIndex];
    if (!selected || selected.disabled || selected.hidden || !selected.value) {
      subjectSelect.value = firstVisible || '';
      if (!subjectSelect.value) {
        subjectSelect.selectedIndex = 0;
      }
    } else if (current) {
      subjectSelect.value = current;
    }
  }

  popup.querySelectorAll('[data-enquiry-close]').forEach(function (el) {
    el.addEventListener('click', closePopup);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !popup.hidden) {
      closePopup();
    }
  });

  if (courseSelect) {
    courseSelect.addEventListener('change', filterSubjects);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var name = (form.name.value || '').trim();
    var phone = (form.phone.value || '').trim();
    var course = form.course.value || '';
    var subject = form.subject.value || '';

    if (!name || !phone || !course || !subject) {
      showMsg('Please fill all fields.', true);
      return;
    }

    var digits = phone.replace(/\D/g, '');
    if (digits.length < 10) {
      showMsg('Enter a valid 10-digit mobile number.', true);
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Submitting...';
    }

    var body = new FormData(form);

    fetch(cfg.url || form.action, {
      method: 'POST',
      body: body,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin'
    })
      .then(function (res) { return res.json().then(function (data) {
        return { ok: res.ok, data: data };
      }); })
      .then(function (result) {
        if (result.data && result.data.ok) {
          showMsg(result.data.message || 'Thank you! We will contact you shortly.', false);
          form.reset();
          if (subjectSelect) subjectSelect.selectedIndex = 0;
          setTimeout(closePopup, 1800);
        } else {
          showMsg((result.data && result.data.message) || 'Something went wrong. Please try again.', true);
        }
      })
      .catch(function () {
        showMsg('Unable to submit. Please try again.', true);
      })
      .finally(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Submit';
        }
      });
  });

  if (!hasSeen()) {
    setTimeout(openPopup, 1200);
  }
})();
