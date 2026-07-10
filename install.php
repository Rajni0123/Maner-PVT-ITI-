<?php
/**
 * One-time installer — delete or protect after setup.
 */
require __DIR__ . '/bootstrap.php';

if (is_installed()) {
    $siteUrl = site_url();
    $adminUrl = site_url('admin/login');
    $pageTitle = 'Already Installed | Maner Private ITI';
    $extraCss = ['install.css'];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-surface font-body-md min-h-screen flex flex-col">
<main class="flex-1 flex items-center justify-center p-gutter">
  <div class="install-card install-done-card max-w-2xl w-full bg-surface-container-lowest border border-outline-variant p-8">
    <div class="install-done-icon"><span class="material-symbols-outlined">check_circle</span></div>
    <h1 class="font-headline-lg text-primary text-center">Site Already Installed</h1>
    <p class="text-on-surface-variant text-center" style="margin:0.75rem 0 1.5rem">Website pehle se setup hai. Install dubara chalane ki zaroorat nahi.</p>
    <div class="install-done-actions">
      <a class="install-btn install-btn-primary" href="<?= e($siteUrl) ?>">Open Website</a>
      <a class="install-btn install-btn-outline" href="<?= e($adminUrl) ?>">Admin Login</a>
    </div>
    <div class="install-alert install-alert-info" style="margin-top:1.5rem">
      <span class="material-symbols-outlined">info</span>
      <div>
        <p style="margin:0 0 0.5rem"><strong>Password bhool gaye?</strong> <code>reset-admin.php</code> chalayein, phir file delete kar dena.</p>
        <p style="margin:0"><strong>Reinstall</strong> ke liye server se <code>storage/installed.lock</code> delete karein.</p>
      </div>
    </div>
  </div>
</main>
</body>
</html>
    <?php
    exit;
}

if (($_GET['action'] ?? '') === 'test-db' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $host = trim($_POST['db_host'] ?? 'localhost');
    $name = trim($_POST['db_name'] ?? '');
    $user = trim($_POST['db_user'] ?? '');
    $pass = $_POST['db_pass'] ?? '';
    if ($name === '' || $user === '') {
        echo json_encode(['ok' => false, 'message' => 'Database name and username are required.']);
        exit;
    }
    try {
        $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->query('SELECT 1');
        echo json_encode(['ok' => true, 'message' => 'Connection successful.']);
    } catch (Throwable $e) {
        echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

function install_env_checks(): array
{
    $uploadDir = upload_dir_path();
    $storageDir = dirname(config('installed_lock', base_path('storage/installed.lock')));

    $uploadWritable = is_dir($uploadDir) ? is_writable($uploadDir) : @mkdir($uploadDir, 0755, true) && is_writable($uploadDir);
    $storageWritable = is_dir($storageDir) ? is_writable($storageDir) : @mkdir($storageDir, 0755, true) && is_writable($storageDir);

    return [
        [
            'label' => 'PHP VERSION',
            'value' => 'v' . PHP_VERSION,
            'ok' => version_compare(PHP_VERSION, '8.0.0', '>='),
        ],
        [
            'label' => 'MYSQLI EXT.',
            'value' => extension_loaded('mysqli') || extension_loaded('pdo_mysql') ? 'Enabled' : 'Missing',
            'ok' => extension_loaded('mysqli') || extension_loaded('pdo_mysql'),
        ],
        [
            'label' => 'GD / WEBP',
            'value' => extension_loaded('gd') && function_exists('imagewebp') ? 'Enabled' : 'Recommended',
            'ok' => extension_loaded('gd') && function_exists('imagewebp'),
            'optional' => true,
        ],
        [
            'label' => 'UPLOADS FOLDER',
            'value' => $uploadWritable ? 'Writable' : 'Not Writable',
            'ok' => $uploadWritable,
        ],
        [
            'label' => 'STORAGE FOLDER',
            'value' => $storageWritable ? 'Writable' : 'Not Writable',
            'ok' => $storageWritable,
        ],
    ];
}

function seedDefaults(PDO $pdo, array $profile = []): void
{
    $logoText = $profile['logo_text'] ?? 'Maner Private ITI';
    $phone = $profile['phone'] ?? '+91-9155401839';
    $email = $profile['email'] ?? 'manerpvtiti@gmail.com';
    $tagline = $profile['tagline'] ?? 'Industrial Training Institute (ITI)';

    $menus = [
        ['Home', '/'], ['About', '/about'], ['Trades', '/trades'], ['Admission', '/admission-process'],
        ['Fee Structure', '/fee-structure'], ['Contact', '/contact'],
    ];
    $i = 1;
    foreach ($menus as [$title, $url]) {
        $pdo->prepare('INSERT IGNORE INTO menus (title, url, order_index, is_active) VALUES (?,?,?,1)')
            ->execute([$title, $url, $i++]);
    }

    $pdo->prepare('INSERT IGNORE INTO header_settings (phone, email, logo_text, tagline, student_portal_link, student_portal_text, ncvt_mis_link, ncvt_mis_text)
        VALUES (?,?,?,?,"#","Student Portal","https://ncvtmis.gov.in","NCVT MIS")')
        ->execute([$phone, $email, $logoText, $tagline]);

    $pdo->prepare('INSERT IGNORE INTO footer_settings (about_text, address, phone, email, copyright_text)
        VALUES (?,?,?,?,?)')
        ->execute([
            'Premier private ITI for vocational training.',
            'Maner Mahinawan, Near Vishwakarma Mandir, Maner, Patna - 801108',
            $phone,
            $email,
            '© ' . $logoText . '. All Rights Reserved.',
        ]);

    $settings = [
        ['header_text', 'Admission Open — Apply online for Electrician & Fitter trades.'],
        ['principal_name', 'Principal'],
        ['principal_message', 'Welcome to ' . $logoText . '.'],
        ['seo_title', $logoText . ' - Technical Education Patna'],
        ['seo_description', 'Official website of ' . $logoText . ', Patna.'],
        ['mis_code', 'PR10001156'],
        ['public_template', 'modern'],
    ];
    foreach ($settings as [$k, $v]) {
        $pdo->prepare('INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES (?,?)')->execute([$k, $v]);
    }

    $year = (int) date('Y');
    for ($j = 0; $j < 3; $j++) {
        $sy = $year + $j;
        $ey = $sy + 2;
        $sn = "{$sy}-" . substr((string) $ey, 2);
        $pdo->prepare('INSERT IGNORE INTO sessions (session_name, start_year, end_year, is_active) VALUES (?,?,?,1)')
            ->execute([$sn, $sy, $ey]);
    }

    $pdo->exec("INSERT IGNORE INTO trades (name, slug, category, description, duration, eligibility, seats, is_active) VALUES
        ('Electrician','electrician','Engineering','Industrial electrician training program.','2 Years','10th Pass','60',1),
        ('Fitter','fitter','Engineering','Fitter trade vocational training.','2 Years','10th Pass','60',1)");

    $pdo->prepare('INSERT IGNORE INTO hero_section (title, subtitle, description, cta_text, cta_link, cta2_text, cta2_link, is_active) VALUES (?,?,?,?,?,?,?,1)')
        ->execute([
            $logoText,
            "Bihar's Leading Technical Institute",
            "Join Bihar's premier NCVT-affiliated institution. We combine industrial rigor with modern technical proficiency to prepare you for a high-impact engineering career.",
            'Fast-Track Your Career',
            '/apply-admission',
            'Download Prospectus',
            '/fee-structure',
        ]);

    $pdo->prepare('INSERT IGNORE INTO about_page (hero_title, hero_subtitle, about_title, about_description, mission_title, mission_description, vision_title, vision_description) VALUES (?,?,?,?,?,?,?,?)')
        ->execute([
            'About ' . $logoText,
            'Building skilled workforce',
            'Who We Are',
            $logoText . ' provides quality vocational education.',
            'Our Mission',
            'To deliver industry-ready technicians.',
            'Our Vision',
            'To be a leading ITI in Bihar.',
        ]);

    $pdo->exec("INSERT IGNORE INTO admission_process (hero_title, hero_subtitle, hero_description, cta_title, cta_button_text, cta_button_link)
        VALUES ('Admission Process','Session 2024-28','Follow these steps to apply online.','Ready to Apply?','Apply Online','/apply-admission')");
}

$step = $_GET['step'] ?? 'form';
$error = '';
$success = '';
$adminEmailDone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $name = trim($_POST['db_name'] ?? '');
    $user = trim($_POST['db_user'] ?? '');
    $pass = $_POST['db_pass'] ?? '';
    $adminEmail = trim($_POST['admin_email'] ?? 'admin@iticollege.edu');
    $adminPass = $_POST['admin_password'] ?? 'admin123';
    $adminConfirm = $_POST['admin_password_confirm'] ?? '';
    $logoText = trim($_POST['logo_text'] ?? 'Maner Private ITI');
    $phone = trim($_POST['phone'] ?? '+91-9155401839');
    $email = trim($_POST['email'] ?? 'manerpvtiti@gmail.com');
    $tagline = trim($_POST['tagline'] ?? 'Industrial Training Institute (ITI)');

    if (!$name || !$user) {
        $error = 'Database name and user are required.';
    } elseif ($adminPass !== $adminConfirm) {
        $error = 'Admin passwords do not match.';
    } elseif (strlen($adminPass) < 6) {
        $error = 'Admin password must be at least 6 characters.';
    } else {
        try {
            $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");

            $schema = file_get_contents(__DIR__ . '/database/schema.sql');
            $pdo->exec($schema);

            $configFile = __DIR__ . '/config.php';
            $config = require $configFile;
            $config['db_host'] = $host;
            $config['db_name'] = $name;
            $config['db_user'] = $user;
            $config['db_pass'] = $pass;
            file_put_contents(
                $configFile,
                "<?php\nreturn " . var_export($config, true) . ";\n"
            );

            $hash = password_hash($adminPass, PASSWORD_DEFAULT);
            $pdo->prepare('DELETE FROM users WHERE LOWER(email) = LOWER(?)')->execute([$adminEmail]);
            $stmt = $pdo->prepare('INSERT INTO users (email, password, name, role, is_active) VALUES (?, ?, ?, ?, 1)');
            $stmt->execute([strtolower($adminEmail), $hash, 'Administrator', 'admin']);

            seedDefaults($pdo, [
                'logo_text' => $logoText,
                'phone' => $phone,
                'email' => $email,
                'tagline' => $tagline,
            ]);

            if (!is_dir(__DIR__ . '/storage')) {
                mkdir(__DIR__ . '/storage', 0755, true);
            }
            file_put_contents(config('installed_lock'), date('c'));

            $adminEmailDone = $adminEmail;
            $step = 'done';
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}

$envChecks = install_env_checks();
$criticalOk = true;
foreach ($envChecks as $check) {
    if (empty($check['optional']) && empty($check['ok'])) {
        $criticalOk = false;
        break;
    }
}

$pageTitle = 'System Setup | Maner Private ITI';
$pageDescription = 'Install and configure Maner Private ITI website.';
$extraCss = ['install.css'];
$installBase = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen flex flex-col">

<header class="fixed top-0 w-full z-50 bg-surface-container-highest border-b border-outline-variant">
  <div class="flex justify-between items-center px-gutter h-16 max-w-container-max mx-auto w-full">
    <div class="flex items-center gap-3">
      <span class="material-symbols-outlined text-primary-container">precision_manufacturing</span>
      <span class="font-headline-md text-headline-md font-bold text-primary">Maner Private ITI</span>
    </div>
    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest hidden sm:inline">System Setup Wizard</span>
  </div>
</header>

<main class="flex-grow pt-24 pb-16 px-gutter max-w-container-max mx-auto w-full">
  <?php if ($step === 'done'): ?>
  <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden max-w-4xl mx-auto p-10 md:p-16 text-center">
    <div class="install-success-icon">
      <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1">check_circle</span>
    </div>
    <h1 class="font-headline-lg text-headline-lg text-primary-container mb-3">Installation Complete</h1>
    <p class="font-body-md text-on-surface-variant mb-8 max-w-lg mx-auto">Your ITI website is ready. Use the credentials below to access the admin panel.</p>
    <div class="bg-surface-container-low border border-outline-variant p-6 text-left max-w-md mx-auto mb-8 space-y-3">
      <p><span class="font-label-sm text-label-sm text-on-surface-variant">ADMIN EMAIL</span><br><strong><?= e($adminEmailDone) ?></strong></p>
      <p><span class="font-label-sm text-label-sm text-on-surface-variant">PASSWORD</span><br><strong>The password you entered during setup</strong></p>
    </div>
    <div class="flex flex-wrap gap-4 justify-center">
      <a href="<?= site_url('admin/login') ?>" class="px-10 py-3 bg-secondary-container text-on-secondary-container font-bold uppercase tracking-widest hover:brightness-95 transition-all">Go to Admin Login</a>
      <a href="<?= site_url() ?>" class="px-10 py-3 border-2 border-primary-container text-primary-container font-bold uppercase tracking-widest hover:bg-primary-container hover:text-white transition-all">View Website</a>
    </div>
    <p class="font-label-sm text-label-sm text-on-surface-variant mt-8">Delete or protect <code>install.php</code> after setup for security.</p>
  </div>
  <?php else: ?>

  <?php if ($error): ?>
  <div class="install-alert install-alert-error max-w-4xl mx-auto mb-6">
    <span class="material-symbols-outlined">error</span>
    <p class="text-sm"><strong>Installation failed:</strong> <?= e($error) ?></p>
  </div>
  <?php endif; ?>

  <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col max-w-4xl mx-auto">
    <div class="bg-surface-container-low border-b border-outline-variant px-6 md:px-8 py-6">
      <div class="flex items-center justify-between relative">
        <div class="absolute top-1/2 left-0 w-full h-px bg-outline-variant -translate-y-1/2 z-0"></div>
        <?php
        $steps = ['Environment', 'Database', 'Profile', 'Admin'];
        foreach ($steps as $i => $label):
            $n = $i + 1;
        ?>
        <div class="install-step-item relative z-10 flex flex-col items-center gap-2 px-2 md:px-4 bg-surface-container-low <?= $n === 1 ? 'install-step-active' : 'install-step-pending' ?>">
          <div class="install-step-dot w-8 h-8 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-label-sm font-bold"><?= $n ?></div>
          <span class="font-label-sm text-label-sm text-center hidden sm:block"><?= e($label) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <form method="post" id="installForm" class="p-8 md:p-12 space-y-10">
      <!-- Step 1: Environment -->
      <div class="install-panel space-y-8" data-step="1">
        <div class="space-y-2">
          <h1 class="font-headline-lg text-headline-lg text-primary-container">Server Environment Check</h1>
          <p class="font-body-md text-on-surface-variant">Step 1: Verify PHP, database extensions, and folder permissions.</p>
        </div>
        <section class="space-y-6">
          <div class="flex items-center gap-3 border-b-2 border-primary-container pb-2">
            <span class="material-symbols-outlined text-primary-container">settings_ethernet</span>
            <h2 class="font-headline-md text-headline-md text-primary-container uppercase tracking-wider text-sm md:text-base">01. Server Environment Check</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($envChecks as $check): ?>
            <div class="flex items-center justify-between p-4 bg-surface-container rounded-lg border border-outline-variant">
              <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant"><?= e($check['label']) ?></span>
                <span class="font-body-md font-bold text-primary"><?= e($check['value']) ?></span>
              </div>
              <span class="material-symbols-outlined <?= !empty($check['ok']) ? 'install-check-ok' : (!empty($check['optional']) ? 'text-secondary' : 'install-check-fail') ?>">
                <?= !empty($check['ok']) ? 'check_circle' : (!empty($check['optional']) ? 'info' : 'cancel') ?>
              </span>
            </div>
            <?php endforeach; ?>
          </div>
        </section>
        <div class="flex justify-end pt-4 border-t border-outline-variant">
          <button type="button" data-install-next class="px-10 py-3 bg-secondary-container text-on-secondary-container font-bold uppercase tracking-widest hover:brightness-95 transition-all flex items-center gap-2" <?= $criticalOk ? '' : 'disabled title="Fix server requirements first"' ?>>
            Continue to Database
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </button>
        </div>
      </div>

      <!-- Step 2: Database -->
      <div class="install-panel hidden space-y-8" data-step="2">
        <div class="space-y-2">
          <h1 class="font-headline-lg text-headline-lg text-primary-container">Database Configuration</h1>
          <p class="font-body-md text-on-surface-variant">Step 2: Connect to MySQL and verify credentials.</p>
        </div>
        <section class="space-y-6">
          <div class="flex items-center gap-3 border-b-2 border-primary-container pb-2">
            <span class="material-symbols-outlined text-primary-container">database</span>
            <h2 class="font-headline-md text-headline-md text-primary-container uppercase tracking-wider text-sm md:text-base">02. Database Connectivity</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Database Host</label>
              <input class="install-input w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="db_host" value="<?= e($_POST['db_host'] ?? 'localhost') ?>" placeholder="localhost" required type="text"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Database Name</label>
              <input class="install-input w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="db_name" value="<?= e($_POST['db_name'] ?? 'maner_iti') ?>" placeholder="maner_iti" required type="text"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Username</label>
              <input class="install-input w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="db_user" value="<?= e($_POST['db_user'] ?? 'root') ?>" placeholder="Database user" required type="text"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Password</label>
              <div class="relative">
                <input class="install-input w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="db_pass" placeholder="••••••••" type="password"/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant" data-toggle-password>visibility</button>
              </div>
            </div>
          </div>
        </section>
        <div class="flex flex-col md:flex-row items-center justify-between pt-4 border-t border-outline-variant gap-4">
          <button type="button" data-install-prev class="w-full md:w-auto px-8 py-3 border-2 border-outline-variant text-primary font-bold uppercase tracking-widest hover:bg-surface-container transition-all">Back</button>
          <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <button type="button" id="testDbBtn" class="w-full md:w-auto px-8 py-3 border-2 border-primary-container text-primary-container font-bold uppercase tracking-widest hover:bg-primary-container hover:text-white transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-sm">sync</span>
              <span class="test-label">Test Connection</span>
            </button>
            <button type="button" data-install-next class="w-full md:w-auto px-10 py-3 bg-secondary-container text-on-secondary-container font-bold uppercase tracking-widest hover:brightness-95 transition-all flex items-center justify-center gap-2">
              Continue to Profile
              <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Step 3: Profile -->
      <div class="install-panel hidden space-y-8" data-step="3">
        <div class="space-y-2">
          <h1 class="font-headline-lg text-headline-lg text-primary-container">Institute Profile</h1>
          <p class="font-body-md text-on-surface-variant">Step 3: Basic institute details for header, footer, and SEO.</p>
        </div>
        <section class="space-y-6">
          <div class="flex items-center gap-3 border-b-2 border-primary-container pb-2">
            <span class="material-symbols-outlined text-primary-container">domain</span>
            <h2 class="font-headline-md text-headline-md text-primary-container uppercase tracking-wider text-sm md:text-base">03. Institute Profile</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-2 md:col-span-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Institute Name</label>
              <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="logo_text" value="<?= e($_POST['logo_text'] ?? 'Maner Private ITI') ?>" required type="text"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Phone</label>
              <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="phone" value="<?= e($_POST['phone'] ?? '+91-9155401839') ?>" required type="text"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Email</label>
              <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="email" value="<?= e($_POST['email'] ?? 'manerpvtiti@gmail.com') ?>" required type="email"/>
            </div>
            <div class="space-y-2 md:col-span-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Tagline</label>
              <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="tagline" value="<?= e($_POST['tagline'] ?? 'Industrial Training Institute (ITI)') ?>" type="text"/>
            </div>
          </div>
        </section>
        <div class="flex flex-col md:flex-row items-center justify-between pt-4 border-t border-outline-variant gap-4">
          <button type="button" data-install-prev class="w-full md:w-auto px-8 py-3 border-2 border-outline-variant text-primary font-bold uppercase tracking-widest hover:bg-surface-container transition-all">Back</button>
          <button type="button" data-install-next class="w-full md:w-auto px-10 py-3 bg-secondary-container text-on-secondary-container font-bold uppercase tracking-widest hover:brightness-95 transition-all flex items-center justify-center gap-2">
            Continue to Admin
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </button>
        </div>
      </div>

      <!-- Step 4: Admin -->
      <div class="install-panel hidden space-y-8" data-step="4">
        <div class="space-y-2">
          <h1 class="font-headline-lg text-headline-lg text-primary-container">Administrator Account</h1>
          <p class="font-body-md text-on-surface-variant">Step 4: Create the primary admin login for the CMS.</p>
        </div>
        <section class="space-y-6">
          <div class="flex items-center gap-3 border-b-2 border-primary-container pb-2">
            <span class="material-symbols-outlined text-primary-container">admin_panel_settings</span>
            <h2 class="font-headline-md text-headline-md text-primary-container uppercase tracking-wider text-sm md:text-base">04. Admin Credentials</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-2 md:col-span-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Admin Email</label>
              <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="admin_email" value="<?= e($_POST['admin_email'] ?? 'admin@iticollege.edu') ?>" required type="email"/>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Password</label>
              <div class="relative">
                <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="admin_password" value="<?= e($_POST['admin_password'] ?? 'admin123') ?>" required type="password" minlength="6"/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant" data-toggle-password>visibility</button>
              </div>
            </div>
            <div class="space-y-2">
              <label class="block font-body-md font-bold text-primary uppercase text-xs tracking-widest">Confirm Password</label>
              <div class="relative">
                <input class="w-full bg-surface-container-low border border-outline-variant px-4 py-3 focus:border-primary-container focus:ring-0 font-body-md" name="admin_password_confirm" value="<?= e($_POST['admin_password_confirm'] ?? 'admin123') ?>" required type="password" minlength="6"/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant" data-toggle-password>visibility</button>
              </div>
            </div>
          </div>
        </section>
        <div class="flex flex-col md:flex-row items-center justify-between pt-4 border-t border-outline-variant gap-4">
          <button type="button" data-install-prev class="w-full md:w-auto px-8 py-3 border-2 border-outline-variant text-primary font-bold uppercase tracking-widest hover:bg-surface-container transition-all">Back</button>
          <button type="submit" class="w-full md:w-auto px-10 py-3 bg-secondary-container text-on-secondary-container font-bold uppercase tracking-widest hover:brightness-95 transition-all shadow-md flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-sm">rocket_launch</span>
            Install Now
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="install-alert install-alert-info max-w-4xl mx-auto mt-8">
    <span class="material-symbols-outlined">info</span>
    <p class="text-sm"><strong>Note:</strong> Database user must have <code>CREATE</code>, <code>INSERT</code>, and <code>ALTER</code> privileges to complete installation successfully.</p>
  </div>
  <?php endif; ?>
</main>

<footer class="bg-surface-container border-t border-outline-variant mt-auto">
  <div class="flex justify-between items-center px-gutter py-6 w-full max-w-container-max mx-auto flex-wrap gap-4">
    <div class="flex flex-col gap-1">
      <span class="font-label-sm text-label-sm text-on-surface-variant">© <?= date('Y') ?> Maner Private ITI Management System</span>
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
        <span class="font-label-sm text-[10px] text-green-600 font-bold uppercase tracking-widest">System Status: Ready for Installation</span>
      </div>
    </div>
  </div>
</footer>

<?php if ($step !== 'done'): ?>
<script>window.INSTALL_BASE = <?= json_encode($installBase) ?>;</script>
<script src="<?= asset('js/install.js') ?>"></script>
<?php endif; ?>
</body>
</html>
