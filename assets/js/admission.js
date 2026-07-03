let currentStep = 1;
const totalSteps = 3;

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const submitBtn = document.getElementById('submitBtn');
const form = document.getElementById('admissionForm');

function getStepSection(step) {
  return document.getElementById(`step${step}-content`);
}

function validateStep(step) {
  const section = getStepSection(step);
  if (!section) return true;
  const fields = section.querySelectorAll('input, select, textarea');
  for (const field of fields) {
    if (field.type === 'radio') continue;
    if (field.type === 'checkbox' && field.name === 'declaration') continue;
    if (field.type === 'file' && step !== totalSteps) continue;
    if (field.hasAttribute('required') && !field.value && field.type !== 'file') {
      field.reportValidity();
      return false;
    }
    if (field.type === 'file' && field.hasAttribute('required') && !field.files?.length) {
      field.reportValidity();
      return false;
    }
    if (!field.checkValidity()) {
      field.reportValidity();
      return false;
    }
  }
  if (step === 3) {
    const trade = form.querySelector('input[name="trade"]:checked');
    if (!trade) {
      alert('Please select a trade.');
      return false;
    }
    const declaration = form.querySelector('input[name="declaration"]');
    if (declaration && !declaration.checked) {
      declaration.setCustomValidity('Please accept the declaration.');
      declaration.reportValidity();
      declaration.setCustomValidity('');
      return false;
    }
  }
  return true;
}

function updateForm() {
  document.querySelectorAll('.step-form-section').forEach((section) => section.classList.add('hidden'));
  getStepSection(currentStep)?.classList.remove('hidden');

  for (let i = 1; i <= totalSteps; i++) {
    const indicator = document.getElementById(`step${i}-indicator`);
    if (!indicator) continue;
    const circle = indicator.querySelector('div:first-child');
    const textTitle = indicator.querySelector('p.font-bold');

    if (i < currentStep) {
      indicator.classList.remove('opacity-50');
      circle.className = 'w-8 h-8 rounded-full bg-on-tertiary-container text-white flex items-center justify-center font-bold text-sm';
      circle.innerHTML = '<span class="material-symbols-outlined text-sm">check</span>';
      textTitle?.classList.remove('text-outline');
      textTitle?.classList.add('text-primary');
    } else if (i === currentStep) {
      indicator.classList.remove('opacity-50');
      circle.className = 'w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm';
      circle.textContent = String(i);
      textTitle?.classList.remove('text-outline');
      textTitle?.classList.add('text-primary');
    } else {
      indicator.classList.add('opacity-50');
      circle.className = 'w-8 h-8 rounded-full bg-surface-container-highest border border-outline text-outline flex items-center justify-center font-bold text-sm';
      circle.textContent = String(i);
      textTitle?.classList.remove('text-primary');
      textTitle?.classList.add('text-outline');
    }
  }

  if (currentStep === 1) {
    prevBtn.classList.add('invisible');
    nextBtn.classList.remove('hidden');
    submitBtn.classList.add('hidden');
  } else if (currentStep === totalSteps) {
    prevBtn.classList.remove('invisible');
    nextBtn.classList.add('hidden');
    submitBtn.classList.remove('hidden');
  } else {
    prevBtn.classList.remove('invisible');
    nextBtn.classList.remove('hidden');
    submitBtn.classList.add('hidden');
  }
}

nextBtn?.addEventListener('click', () => {
  if (!validateStep(currentStep)) return;
  if (currentStep < totalSteps) {
    currentStep++;
    updateForm();
    form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
});

prevBtn?.addEventListener('click', () => {
  if (currentStep > 1) {
    currentStep--;
    updateForm();
    form?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
});

form?.addEventListener('submit', (e) => {
  for (let s = 1; s <= totalSteps; s++) {
    if (!validateStep(s)) {
      e.preventDefault();
      currentStep = s;
      updateForm();
      form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      return;
    }
  }
});

// UIDAI live check
const uidaiInput = document.getElementById('uidai_number');
if (uidaiInput) {
  uidaiInput.addEventListener('input', function () {
    const digits = uidaiInput.value.replace(/\D/g, '').slice(0, 12);
    const parts = [];
    if (digits.length > 0) parts.push(digits.slice(0, 4));
    if (digits.length > 4) parts.push(digits.slice(4, 8));
    if (digits.length > 8) parts.push(digits.slice(8, 12));
    uidaiInput.value = parts.join(' ');
  });
  uidaiInput.addEventListener('blur', function () {
    const v = uidaiInput.value.replace(/\D/g, '');
    if (v.length !== 12) return;
    fetch((window.APP_BASE || '') + '/api/check-uidai?uidai=' + v)
      .then((r) => r.json())
      .then((d) => {
        const el = document.getElementById('uidai-msg');
        if (!el) return;
        el.textContent = d.message;
        el.style.color = d.available ? '#16a34a' : '#dc2626';
      });
  });
}

// Auto calc 10th percentage
function calcPct(obtainedId, totalId, pctId) {
  const o = document.getElementById(obtainedId);
  const t = document.getElementById(totalId);
  const p = document.getElementById(pctId);
  if (!o || !t || !p) return;
  function update() {
    const ov = parseFloat(o.value);
    const tv = parseFloat(t.value);
    if (ov > 0 && tv > 0) p.value = ((ov / tv) * 100).toFixed(2);
  }
  o.addEventListener('input', update);
  t.addEventListener('input', update);
  update();
}
calcPct('class_10th_marks_obtained', 'class_10th_total_marks', 'class_10th_percentage');

// Trade card highlight
function refreshTradeCards() {
  const selectedText = document.getElementById('tradeSelectedText');
  document.querySelectorAll('.trade-card').forEach((card) => {
    const radio = card.querySelector('input[name="trade"]');
    const badge = card.querySelector('.trade-selected-badge');
    const active = !!(radio && radio.checked);
    card.classList.toggle('border-primary', active);
    card.classList.toggle('bg-surface-container-low', active);
    card.classList.toggle('border-outline-variant', !active);
    if (badge) badge.classList.toggle('hidden', !active);
    if (active && selectedText) {
      selectedText.textContent = 'Selected trade: ' + radio.value;
    }
  });
  const any = document.querySelector('input[name="trade"]:checked');
  if (!any && selectedText) {
    selectedText.textContent = 'Please select a trade above.';
  }
}

document.querySelectorAll('.trade-card').forEach((card) => {
  card.addEventListener('click', (e) => {
    const radio = card.querySelector('input[name="trade"]');
    if (!radio) return;
    radio.checked = true;
    radio.dispatchEvent(new Event('change', { bubbles: true }));
    refreshTradeCards();
  });
});

document.querySelectorAll('input[name="trade"]').forEach((radio) => {
  radio.addEventListener('change', refreshTradeCards);
});
refreshTradeCards();

// BSCC / PWD / Category document toggles
(function () {
  const bsccSelect = document.getElementById('student_credit_card');
  const bsccBox = document.getElementById('bscc_details_box');
  const bankInput = document.getElementById('student_credit_card_bank');
  const accountInput = document.getElementById('student_credit_card_account');
  const pwdSelect = document.getElementById('pwd_claim');
  const pwdBox = document.getElementById('pwd_details_box');
  const pwdDocs = document.getElementById('pwd_docs_box');
  const pwdCategory = document.getElementById('pwd_category');
  const categorySelect = document.getElementById('category');
  const categoryDocs = document.getElementById('category_docs_box');

  function toggleBscc() {
    if (!bsccSelect || !bsccBox) return;
    const show = bsccSelect.value === 'Yes';
    bsccBox.classList.toggle('hidden', !show);
    if (bankInput) bankInput.required = show;
    if (accountInput) accountInput.required = show;
  }

  function togglePwd() {
    if (!pwdSelect) return;
    const show = pwdSelect.value === 'Yes';
    if (pwdBox) pwdBox.classList.toggle('hidden', !show);
    if (pwdDocs) pwdDocs.classList.toggle('hidden', !show);
    if (pwdCategory) pwdCategory.required = show;
  }

  function toggleCategoryDocs() {
    if (!categorySelect || !categoryDocs) return;
    const cat = (categorySelect.value || '').toUpperCase();
    const show = ['SC', 'ST', 'OBC', 'EWS'].includes(cat);
    categoryDocs.classList.toggle('hidden', !show);
  }

  if (bsccSelect) {
    bsccSelect.addEventListener('change', toggleBscc);
    toggleBscc();
  }
  if (pwdSelect) {
    pwdSelect.addEventListener('change', togglePwd);
    togglePwd();
  }
  if (categorySelect) {
    categorySelect.addEventListener('change', toggleCategoryDocs);
    toggleCategoryDocs();
  }
})();

updateForm();
