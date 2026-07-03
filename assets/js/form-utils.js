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
    student_credit_card_account: true
  };

  function formatAadhaarInput(input) {
    var digits = input.value.replace(/\D/g, '').slice(0, 12);
    var parts = [];
    if (digits.length > 0) parts.push(digits.slice(0, 4));
    if (digits.length > 4) parts.push(digits.slice(4, 8));
    if (digits.length > 8) parts.push(digits.slice(8, 12));
    input.value = parts.join(' ');
  }

  function bindAadhaarInput(id) {
    var input = document.getElementById(id);
    if (!input) return;
    input.addEventListener('input', function () {
      formatAadhaarInput(input);
    });
    if (input.value) formatAadhaarInput(input);
  }

  function uppercaseField(el) {
    if (!el || ADMISSION_SKIP_UPPERCASE[el.name]) return;
    // Never force-uppercase <select> values — option values are case-sensitive
    // (e.g. "Electrician", "Yes") and uppercasing clears the selection.
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

    form.addEventListener('submit', function () {
      form.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(function (el) {
        uppercaseField(el);
      });
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

  bindAadhaarInput('uidai_number');
  calcPct('class_10th_marks_obtained', 'class_10th_total_marks', 'class_10th_percentage');
  calcPct('class_12th_marks_obtained', 'class_12th_total_marks', 'class_12th_percentage');

  ['admissionForm', 'adminAdmissionForm', 'adminStudentForm'].forEach(function (id) {
    bindAdmissionUppercase(document.getElementById(id));
  });

  window.bindAdmissionUppercase = bindAdmissionUppercase;
})();
