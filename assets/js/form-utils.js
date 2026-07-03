(function () {
  var ADMISSION_SKIP_UPPERCASE = {
    uidai_number: true,
    mobile: true,
    pincode: true,
    dob: true,
    class_10th_marks_obtained: true,
    class_10th_total_marks: true,
    class_10th_percentage: true,
    class_12th_marks_obtained: true,
    class_12th_total_marks: true,
    class_12th_percentage: true,
    student_credit_card_account: true,
    pwd_percentage: true
  };

  function onlyDigits(value, max) {
    return String(value || '').replace(/\D/g, '').slice(0, max);
  }

  function formatAadhaarInput(input) {
    var digits = onlyDigits(input.value, 12);
    var parts = [];
    if (digits.length > 0) parts.push(digits.slice(0, 4));
    if (digits.length > 4) parts.push(digits.slice(4, 8));
    if (digits.length > 8) parts.push(digits.slice(8, 12));
    input.value = parts.join(' ');
    return digits;
  }

  function setFieldMessage(input, message, isError) {
    if (!input) return;
    var msgId = input.id ? input.id + '-msg' : '';
    var el = msgId ? document.getElementById(msgId) : null;
    if (!el) {
      el = input.parentNode ? input.parentNode.querySelector('.field-msg') : null;
    }
    if (!el) {
      el = document.createElement('small');
      el.className = 'field-msg';
      el.style.display = 'block';
      el.style.marginTop = '4px';
      el.style.fontSize = '12px';
      if (input.parentNode) input.parentNode.appendChild(el);
    }
    el.textContent = message || '';
    el.style.color = isError ? '#ba1a1a' : '#16a34a';
    input.style.borderColor = message && isError ? '#ba1a1a' : '';
  }

  function bindAadhaarInput(input) {
    if (!input) return;

    input.setAttribute('inputmode', 'numeric');
    input.setAttribute('autocomplete', 'off');
    input.setAttribute('maxlength', '14');
    input.setAttribute('placeholder', 'XXXX XXXX XXXX');

    input.addEventListener('keydown', function (e) {
      var allowed = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
      if (allowed.indexOf(e.key) !== -1 || e.ctrlKey || e.metaKey) return;
      if (!/^\d$/.test(e.key)) e.preventDefault();
      var digits = onlyDigits(input.value, 12);
      if (/^\d$/.test(e.key) && digits.length >= 12) e.preventDefault();
    });

    input.addEventListener('input', function () {
      var digits = formatAadhaarInput(input);
      if (digits.length === 0) {
        setFieldMessage(input, '', false);
      } else if (digits.length < 12) {
        setFieldMessage(input, 'Aadhaar must be 12 digits (' + digits.length + '/12)', true);
      } else {
        setFieldMessage(input, 'Valid 12-digit Aadhaar', false);
      }
    });

    input.addEventListener('paste', function (e) {
      e.preventDefault();
      var text = (e.clipboardData || window.clipboardData).getData('text');
      input.value = onlyDigits(text, 12);
      formatAadhaarInput(input);
      input.dispatchEvent(new Event('input'));
    });

    if (input.value) formatAadhaarInput(input);
  }

  function bindMobileInput(input) {
    if (!input) return;

    input.setAttribute('type', 'tel');
    input.setAttribute('inputmode', 'numeric');
    input.setAttribute('maxlength', '10');
    input.setAttribute('pattern', '\\d{10}');
    input.setAttribute('placeholder', '10-digit mobile');

    input.addEventListener('keydown', function (e) {
      var allowed = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
      if (allowed.indexOf(e.key) !== -1 || e.ctrlKey || e.metaKey) return;
      if (!/^\d$/.test(e.key)) e.preventDefault();
      var digits = onlyDigits(input.value, 10);
      if (/^\d$/.test(e.key) && digits.length >= 10) e.preventDefault();
    });

    input.addEventListener('input', function () {
      var digits = onlyDigits(input.value, 10);
      input.value = digits;
      if (digits.length === 0) {
        setFieldMessage(input, '', false);
      } else if (digits.length < 10) {
        setFieldMessage(input, 'Mobile must be 10 digits (' + digits.length + '/10)', true);
      } else {
        setFieldMessage(input, 'Valid 10-digit mobile', false);
      }
    });

    input.addEventListener('paste', function (e) {
      e.preventDefault();
      var text = (e.clipboardData || window.clipboardData).getData('text');
      input.value = onlyDigits(text, 10);
      input.dispatchEvent(new Event('input'));
    });

    if (input.value) {
      input.value = onlyDigits(input.value, 10);
    }
  }

  function validateAdmissionForm(form) {
    if (!form) return true;
    var ok = true;

    var mobile = form.querySelector('[name="mobile"]');
    if (mobile) {
      var m = onlyDigits(mobile.value, 10);
      mobile.value = m;
      if (mobile.required || m.length > 0) {
        if (m.length !== 10) {
          setFieldMessage(mobile, 'Mobile must be exactly 10 digits', true);
          ok = false;
        }
      }
    }

    var aadhaar = form.querySelector('[name="uidai_number"]');
    if (aadhaar) {
      var a = onlyDigits(aadhaar.value, 12);
      formatAadhaarInput(aadhaar);
      if (aadhaar.required || a.length > 0) {
        if (a.length !== 12) {
          setFieldMessage(aadhaar, 'Aadhaar must be exactly 12 digits', true);
          ok = false;
        }
      }
    }

    return ok;
  }

  function uppercaseField(el) {
    if (!el || ADMISSION_SKIP_UPPERCASE[el.name]) return;
    if (el.tagName === 'SELECT') return;
    if (el.type === 'file' || el.type === 'date' || el.type === 'number' || el.type === 'tel') return;
    if (el.type === 'radio' || el.type === 'checkbox') return;
    if (el.type !== 'text' && el.type !== 'email' && el.tagName !== 'TEXTAREA') return;

    var pos = el.selectionStart;
    var next = el.value.toUpperCase();
    if (next !== el.value) {
      el.value = next;
      if (typeof pos === 'number') {
        el.setSelectionRange(pos, pos);
      }
    }
  }

  function bindAdmissionUppercase(form) {
    if (!form) return;

    form.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(function (el) {
      if (ADMISSION_SKIP_UPPERCASE[el.name]) return;
      el.style.textTransform = 'uppercase';
      el.addEventListener('input', function () {
        uppercaseField(el);
      });
      uppercaseField(el);
    });

    form.addEventListener('submit', function (e) {
      form.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(function (el) {
        uppercaseField(el);
      });
      if (!validateAdmissionForm(form)) {
        e.preventDefault();
        alert('Please enter valid Mobile (10 digits) and Aadhaar (12 digits).');
        return false;
      }
    });
  }

  function calcPct(obtainedId, totalId, pctId) {
    var o = document.getElementById(obtainedId);
    var t = document.getElementById(totalId);
    var p = document.getElementById(pctId);
    if (!o || !t || !p) return;
    function update() {
      var ov = parseFloat(o.value);
      var tv = parseFloat(t.value);
      if (ov > 0 && tv > 0) {
        p.value = ((ov / tv) * 100).toFixed(2);
      } else {
        p.value = '';
      }
    }
    o.addEventListener('input', update);
    t.addEventListener('input', update);
    update();
  }

  // Bind Aadhaar fields
  document.querySelectorAll('#uidai_number, input[name="uidai_number"]').forEach(bindAadhaarInput);

  // Bind Mobile fields
  document.querySelectorAll('input[name="mobile"]').forEach(bindMobileInput);

  calcPct('class_10th_marks_obtained', 'class_10th_total_marks', 'class_10th_percentage');
  calcPct('class_12th_marks_obtained', 'class_12th_total_marks', 'class_12th_percentage');

  ['admissionForm', 'adminAdmissionForm', 'adminStudentForm'].forEach(function (id) {
    bindAdmissionUppercase(document.getElementById(id));
  });

  window.bindAdmissionUppercase = bindAdmissionUppercase;
  window.validateAdmissionForm = validateAdmissionForm;
})();
