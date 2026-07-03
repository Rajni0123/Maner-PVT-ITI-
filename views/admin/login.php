<!-- Login Card -->
<div class="bg-surface-container-lowest border border-outline-variant auth-card-shadow p-8 md:p-10 relative overflow-hidden">
  <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>

  <div class="mb-8">
    <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Institutional Login</h1>
    <p class="font-body-md text-on-surface-variant">Enter your secure credentials to access the administrative dashboard.</p>
  </div>

  <?php if ($msg = flash('error')): ?>
  <div class="mb-6 flex items-center gap-2 bg-error-container border border-error/20 text-on-error-container p-3 rounded-lg text-sm">
    <span class="material-symbols-outlined text-error text-[18px]">error</span>
    <span><?= e($msg) ?></span>
  </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('admin/login') ?>" class="space-y-6" id="loginForm">
    <?= csrf_field() ?>

    <!-- Email -->
    <div class="space-y-2">
      <label class="block font-label-sm text-label-sm text-on-surface font-bold uppercase tracking-wider" for="email">Institutional ID / Email</label>
      <div class="relative group">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">badge</span>
        <input class="w-full bg-surface-container-low border border-outline-variant px-10 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all font-body-md" id="email" name="email" type="email" placeholder="Enter admin email" required />
      </div>
    </div>

    <!-- Password -->
    <div class="space-y-2">
      <div class="flex justify-between items-center">
        <label class="block font-label-sm text-label-sm text-on-surface font-bold uppercase tracking-wider" for="password">Security Password</label>
      </div>
      <div class="relative group">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">lock</span>
        <input class="w-full bg-surface-container-low border border-outline-variant px-10 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all font-body-md" id="password" name="password" type="password" placeholder="••••••••••••" required />
        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary" type="button" onclick="togglePassword()">
          <span class="material-symbols-outlined" id="toggleIcon">visibility</span>
        </button>
      </div>
    </div>

    <!-- Submit Button -->
    <button class="w-full bg-secondary-container text-on-secondary-container font-headline-md py-4 flex items-center justify-center gap-3 hover:bg-secondary-container/90 transition-all active:scale-[0.98] relative group" type="submit">
      <span class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100 transition-opacity"></span>
      <span class="relative z-10">Sign In to Dashboard</span>
      <span class="material-symbols-outlined relative z-10" style="font-variation-settings: 'FILL' 1;">login</span>
    </button>
  </form>

  <!-- Footer elements inside card -->
  <div class="mt-8 pt-6 border-t border-outline-variant flex flex-col gap-4">
    <div class="flex items-center gap-4 text-on-surface-variant">
      <div class="flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">verified_user</span>
        <span class="text-[11px] font-label-sm">SSL SECURE</span>
      </div>
      <div class="flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">gpp_good</span>
        <span class="text-[11px] font-label-sm">PROTECTED</span>
      </div>
    </div>
  </div>
</div>

<!-- Supporting Text -->
<p class="mt-8 text-center font-body-md text-on-surface-variant text-sm px-4">
  Technical issues? Contact the <a class="text-primary font-bold hover:underline" href="mailto:manerpvtiti@gmail.com">IT Support Desk</a>
</p>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = 'visibility';
    }
}
</script>
