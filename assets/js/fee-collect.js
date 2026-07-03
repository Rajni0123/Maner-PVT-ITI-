(function () {
  var searchInput = document.getElementById('studentSearch');
  var resultsBox = document.getElementById('studentSearchResults');
  var selectedBox = document.getElementById('selectedStudent');
  var form = document.getElementById('collectFeeForm');
  var clearBtn = document.getElementById('clearStudent');
  var searchUrl = window.FEE_SEARCH_URL;
  var debounceTimer = null;

  if (!searchInput || !resultsBox) return;

  function money(n) {
    return '₹ ' + Number(n || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function setHidden(el, hide) {
    if (!el) return;
    el.classList.toggle('hidden', hide);
  }

  function fillForm(row) {
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
    if (row.pending_due > 0) {
      dueEl.textContent = 'Previous pending due: ' + money(row.pending_due);
      dueEl.classList.remove('hidden');
    } else {
      dueEl.textContent = '';
      dueEl.classList.add('hidden');
    }

    setHidden(selectedBox, false);
    setHidden(form, false);
    setHidden(resultsBox, true);
    searchInput.value = row.name || '';
  }

  function clearSelection() {
    setHidden(selectedBox, true);
    setHidden(form, true);
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
      return (
        '<button type="button" class="fee-search-item" data-row="' + encodeURIComponent(JSON.stringify(row)) + '">' +
        '<span class="fee-search-item-name">' + escapeHtml(row.name) + '</span>' +
        '<span class="fee-search-item-meta">' + escapeHtml(row.label || row.trade || '') + '</span>' +
        (row.mobile ? '<span class="fee-search-item-phone">' + escapeHtml(row.mobile) + '</span>' : '') +
        (row.pending_due > 0 ? '<span class="fee-search-item-due">Due ' + money(row.pending_due) + '</span>' : '') +
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

  var amount = document.getElementById('feeAmount');
  var paid = document.getElementById('paidAmount');
  if (amount && paid) {
    amount.addEventListener('input', function () {
      if (!paid.value || paid.value === '0') {
        paid.value = amount.value;
      }
    });
  }

  if (window.FEE_PREFILL) {
    fillForm(window.FEE_PREFILL);
  }
})();
