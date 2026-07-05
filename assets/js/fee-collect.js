(function () {
  var searchInput = document.getElementById('studentSearch');
  var resultsBox = document.getElementById('studentSearchResults');
  var selectedBox = document.getElementById('selectedStudent');
  var form = document.getElementById('collectFeeForm');
  var clearBtn = document.getElementById('clearStudent');
  var searchUrl = window.FEE_SEARCH_URL;
  var debounceTimer = null;
  var currentRow = null;

  if (!searchInput || !resultsBox) return;

  function money(n) {
    return '₹ ' + Number(n || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function setHidden(el, hide) {
    if (!el) return;
    el.classList.toggle('hidden', hide);
  }

  function updateFeeTypeOptions(row) {
    var select = document.getElementById('feeTypeSelect');
    if (!select) return;
    select.innerHTML = '';
    var options = row.installment_options || [];
    if (!options.length && row.next_installment) {
      options = [row.next_installment];
    }
    if (!options.length) {
      var opt = document.createElement('option');
      opt.value = '';
      opt.textContent = row.balance_due > 0 ? 'No installment available' : 'Fully paid';
      select.appendChild(opt);
      select.disabled = true;
      return;
    }
    options.forEach(function (label) {
      var opt = document.createElement('option');
      opt.value = label;
      opt.textContent = label;
      select.appendChild(opt);
    });
    select.disabled = false;
  }

  function updateFeePlanSummary(row) {
    var box = document.getElementById('feePlanSummary');
    if (!box) return;
    if (!row.has_fee_plan && !(row.total_admission_amount > 0)) {
      setHidden(box, true);
      return;
    }
    setHidden(box, false);
    document.getElementById('feePlanTotal').textContent = money(row.total_admission_amount);
    document.getElementById('feePlanAdvance').textContent = money(row.advance_paid);
    document.getElementById('feePlanPaid').textContent = money(row.total_paid);
    document.getElementById('feePlanBalance').textContent = money(row.balance_due);
  }

  function updateAmountLimits(row) {
    var paid = document.getElementById('paidAmount');
    var amount = document.getElementById('feeAmount');
    var submitBtn = document.getElementById('collectSubmitBtn');
    var balance = Number(row.balance_due || row.pending_due || 0);
    if (paid) {
      paid.max = balance > 0 ? balance : '';
      paid.value = balance > 0 ? balance : '';
      paid.disabled = balance <= 0;
    }
    if (amount && paid) {
      amount.value = paid.value;
    }
    if (submitBtn) {
      submitBtn.disabled = balance <= 0;
    }
  }

  function fillForm(row) {
    currentRow = row;
    document.getElementById('studentName').value = row.name || '';
    document.getElementById('fatherName').value = row.father_name || '';
    document.getElementById('mobile').value = row.mobile || '';
    document.getElementById('trade').value = row.trade || '';
    document.getElementById('admissionId').value = row.admission_id || '';
    document.getElementById('studentSource').value = row.key || '';

    document.getElementById('selectedStudentName').textContent = row.name || '';
    var meta = [row.trade, row.session, row.enrollment, row.mobile].filter(Boolean).join(' · ');
    document.getElementById('selectedStudentMeta').textContent = meta;

    var dueEl = document.getElementById('selectedStudentDue');
    var balance = Number(row.balance_due || row.pending_due || 0);
    if (balance > 0) {
      dueEl.textContent = 'Balance due: ' + money(balance);
      dueEl.classList.remove('hidden');
    } else if (row.has_fee_plan) {
      dueEl.textContent = 'All fees collected';
      dueEl.classList.remove('hidden');
    } else {
      dueEl.textContent = '';
      dueEl.classList.add('hidden');
    }

    updateFeePlanSummary(row);
    updateFeeTypeOptions(row);
    updateAmountLimits(row);

    setHidden(selectedBox, false);
    setHidden(form, false);
    setHidden(resultsBox, true);
    searchInput.value = row.name || '';
  }

  function clearSelection() {
    currentRow = null;
    setHidden(selectedBox, true);
    setHidden(form, true);
    setHidden(document.getElementById('feePlanSummary'), true);
    searchInput.value = '';
    searchInput.focus();
    resultsBox.innerHTML = '';
    setHidden(resultsBox, true);
  }

  function renderResults(items) {
    if (!items.length) {
      resultsBox.innerHTML = '<p class="fee-search-empty">No student found. Try name or mobile.</p>';
      setHidden(resultsBox, false);
      return;
    }
    resultsBox.innerHTML = items.map(function (row) {
      var due = Number(row.balance_due || row.pending_due || 0);
      return (
        '<button type="button" class="fee-search-item" data-row="' + encodeURIComponent(JSON.stringify(row)) + '">' +
        '<span class="fee-search-item-name">' + escapeHtml(row.name) + '</span>' +
        '<span class="fee-search-item-meta">' + escapeHtml(row.label || row.trade || '') + '</span>' +
        (row.mobile ? '<span class="fee-search-item-phone">' + escapeHtml(row.mobile) + '</span>' : '') +
        (due > 0 ? '<span class="fee-search-item-due">Due ' + money(due) + '</span>' : '') +
        '</button>'
      );
    }).join('');
    setHidden(resultsBox, false);

    resultsBox.querySelectorAll('.fee-search-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        fillForm(JSON.parse(decodeURIComponent(btn.getAttribute('data-row'))));
      });
    });
  }

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
  }

  function runSearch(q) {
    if (!searchUrl || q.length < 2) {
      resultsBox.innerHTML = '';
      setHidden(resultsBox, true);
      return;
    }
    fetch(searchUrl + '?q=' + encodeURIComponent(q), {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
    })
      .then(function (r) { return r.json(); })
      .then(renderResults)
      .catch(function () {
        resultsBox.innerHTML = '<p class="fee-search-empty">Search failed. Try again.</p>';
        setHidden(resultsBox, false);
      });
  }

  searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function () {
      runSearch(searchInput.value.trim());
    }, 280);
  });

  searchInput.addEventListener('focus', function () {
    if (searchInput.value.trim().length >= 2) {
      runSearch(searchInput.value.trim());
    }
  });

  document.addEventListener('click', function (e) {
    if (!resultsBox.contains(e.target) && e.target !== searchInput) {
      setHidden(resultsBox, true);
    }
  });

  if (clearBtn) {
    clearBtn.addEventListener('click', clearSelection);
  }

  var paid = document.getElementById('paidAmount');
  var amount = document.getElementById('feeAmount');
  if (paid && amount) {
    paid.addEventListener('input', function () {
      amount.value = paid.value;
      if (currentRow && currentRow.has_fee_plan) {
        var max = Number(currentRow.balance_due || 0);
        if (parseFloat(paid.value) > max) {
          paid.value = max;
          amount.value = max;
        }
      }
    });
  }

  if (form) {
    form.addEventListener('submit', function () {
      if (paid && amount) {
        amount.value = paid.value;
      }
    });
  }

  if (window.FEE_PREFILL) {
    fillForm(window.FEE_PREFILL);
  }
})();
