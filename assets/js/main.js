document.querySelectorAll('form[data-confirm]').forEach(function (form) {
  form.addEventListener('submit', function (e) {
    if (!confirm(form.getAttribute('data-confirm'))) e.preventDefault();
  });
});

// UIDAI live check on admission form
var uidaiInput = document.getElementById('uidai_number');
if (uidaiInput) {
  uidaiInput.addEventListener('blur', function () {
    var v = uidaiInput.value.replace(/\D/g, '');
    if (v.length !== 12) return;
    fetch((window.APP_BASE || '') + '/api/check-uidai?uidai=' + v)
      .then(function (r) { return r.json(); })
      .then(function (d) {
        var el = document.getElementById('uidai-msg');
        if (!el) return;
        el.textContent = d.message;
        el.style.color = d.available ? '#16a34a' : '#dc2626';
      });
  });
}

// Auto calc 10th percentage
function calcPct(obtainedId, totalId, pctId) {
  var o = document.getElementById(obtainedId);
  var t = document.getElementById(totalId);
  var p = document.getElementById(pctId);
  if (!o || !t || !p) return;
  function update() {
    var ov = parseFloat(o.value), tv = parseFloat(t.value);
    if (ov > 0 && tv > 0) p.value = ((ov / tv) * 100).toFixed(2);
  }
  o.addEventListener('input', update);
  t.addEventListener('input', update);
}
calcPct('class_10th_marks_obtained', 'class_10th_total_marks', 'class_10th_percentage');
