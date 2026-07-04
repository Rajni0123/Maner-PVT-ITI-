(function () {
  var form = document.getElementById('installForm');
  if (!form) return;

  var panels = Array.prototype.slice.call(document.querySelectorAll('.install-panel'));
  var stepItems = Array.prototype.slice.call(document.querySelectorAll('.install-step-item'));
  var currentStep = 1;
  var totalSteps = panels.length;
  var testBtn = document.getElementById('testDbBtn');
  var dbTested = false;

  function showStep(step) {
    currentStep = step;
    panels.forEach(function (panel, index) {
      panel.classList.toggle('hidden', index + 1 !== step);
    });
    stepItems.forEach(function (item, index) {
      var n = index + 1;
      item.classList.remove('install-step-active', 'install-step-done', 'install-step-pending');
      if (n < step) {
        item.classList.add('install-step-done');
      } else if (n === step) {
        item.classList.add('install-step-active');
      } else {
        item.classList.add('install-step-pending');
      }
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function validateStep(step) {
    var panel = panels[step - 1];
    if (!panel) return true;
    var fields = panel.querySelectorAll('input, select, textarea');
    for (var i = 0; i < fields.length; i++) {
      var field = fields[i];
      if (field.hasAttribute('required') && !field.value.trim()) {
        field.reportValidity();
        field.focus();
        return false;
      }
    }
    if (step === 2 && !dbTested) {
      alert('Please test the database connection before continuing.');
      return false;
    }
    if (step === 4) {
      var pass = form.querySelector('[name="admin_password"]');
      var confirm = form.querySelector('[name="admin_password_confirm"]');
      if (pass && confirm && pass.value !== confirm.value) {
        alert('Admin passwords do not match.');
        confirm.focus();
        return false;
      }
    }
    return true;
  }

  document.querySelectorAll('[data-install-next]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!validateStep(currentStep)) return;
      if (currentStep < totalSteps) showStep(currentStep + 1);
    });
  });

  document.querySelectorAll('[data-install-prev]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (currentStep > 1) showStep(currentStep - 1);
    });
  });

  if (testBtn) {
    testBtn.addEventListener('click', function () {
      var host = form.querySelector('[name="db_host"]');
      var name = form.querySelector('[name="db_name"]');
      var user = form.querySelector('[name="db_user"]');
      var pass = form.querySelector('[name="db_pass"]');
      if (!name.value.trim() || !user.value.trim()) {
        alert('Database name and username are required.');
        return;
      }

      var icon = testBtn.querySelector('.material-symbols-outlined');
      var label = testBtn.querySelector('.test-label');
      testBtn.disabled = true;
      if (icon) icon.classList.add('animate-spin');
      if (label) label.textContent = 'Testing...';

      var body = new FormData();
      body.append('db_host', host.value);
      body.append('db_name', name.value);
      body.append('db_user', user.value);
      body.append('db_pass', pass.value);

      fetch((window.INSTALL_BASE || '') + '/install.php?action=test-db', {
        method: 'POST',
        body: body
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          dbTested = !!data.ok;
          testBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
          if (data.ok) {
            testBtn.classList.add('bg-green-600', 'text-white', 'border-green-600');
            if (label) label.textContent = 'Connection Successful';
            if (icon) {
              icon.textContent = 'check';
              icon.classList.remove('animate-spin');
            }
          } else {
            dbTested = false;
            if (label) label.textContent = 'Test Connection';
            if (icon) {
              icon.textContent = 'sync';
              icon.classList.remove('animate-spin');
            }
            alert(data.message || 'Connection failed.');
          }
        })
        .catch(function () {
          dbTested = false;
          if (label) label.textContent = 'Test Connection';
          if (icon) {
            icon.textContent = 'sync';
            icon.classList.remove('animate-spin');
          }
          alert('Could not test connection.');
        })
        .finally(function () {
          testBtn.disabled = false;
        });
    });
  }

  form.querySelectorAll('[name="db_host"], [name="db_name"], [name="db_user"], [name="db_pass"]').forEach(function (el) {
    el.addEventListener('input', function () {
      dbTested = false;
      if (testBtn) {
        testBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
        var label = testBtn.querySelector('.test-label');
        if (label) label.textContent = 'Test Connection';
      }
    });
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = btn.parentElement.querySelector('input');
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.textContent = show ? 'visibility_off' : 'visibility';
    });
  });

  showStep(1);
})();
