<?php
/**
 * Local dev setup — run once: php setup-local.php
 */
require __DIR__ . '/bootstrap.php';

if (is_installed()) {
    echo "Already installed. Delete storage/installed.lock to reinstall.\n";
    echo "Login: http://localhost:8765/admin/login\n";
    echo "Email: admin@iticollege.edu | Password: admin123\n";
    exit(0);
}

$host = 'localhost';
$name = 'maner_iti';
$user = 'root';
$pass = '';

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
    $config['debug'] = true;
    file_put_contents($configFile, "<?php\nreturn " . var_export($config, true) . ";\n");

    $adminEmail = 'admin@iticollege.edu';
    $adminPass = 'admin123';
    $hash = password_hash($adminPass, PASSWORD_DEFAULT);
    $pdo->prepare('DELETE FROM users WHERE LOWER(email) = LOWER(?)')->execute([$adminEmail]);
    $pdo->prepare('INSERT INTO users (email, password, name, role, is_active) VALUES (?,?,?,?,1)')
        ->execute([strtolower($adminEmail), $hash, 'Administrator', 'admin']);

    seedLocalDefaults($pdo);

    if (!is_dir(__DIR__ . '/storage')) {
        mkdir(__DIR__ . '/storage', 0755, true);
    }
    file_put_contents(config('installed_lock'), date('c'));

    echo "LOCAL SETUP DONE!\n\n";
    echo "Start server:  php -S localhost:8765 router.php\n";
    echo "Then open:     http://localhost:8765/\n";
    echo "Admin login:   http://localhost:8765/admin/login\n";
    echo "Email:         admin@iticollege.edu\n";
    echo "Password:      admin123\n";
} catch (Throwable $e) {
    echo "SETUP FAILED: " . $e->getMessage() . "\n";
    echo "Make sure XAMPP/WAMP MySQL is running.\n";
    exit(1);
}

function seedLocalDefaults(PDO $pdo): void
{
    $menus = [
        ['Home', '/'], ['About', '/about'], ['Trades', '/trades'], ['Admission', '/admission-process'],
        ['Fee Structure', '/fee-structure'], ['Contact', '/contact'],
    ];
    $i = 1;
    foreach ($menus as [$title, $url]) {
        $pdo->prepare('INSERT IGNORE INTO menus (title, url, order_index, is_active) VALUES (?,?,?,1)')
            ->execute([$title, $url, $i++]);
    }
    $pdo->exec("INSERT IGNORE INTO header_settings (phone, email, logo_text, tagline) VALUES ('+91-9155401839','manerpvtiti@gmail.com','Maner Pvt ITI','Industrial Training Institute (ITI)')");
    $pdo->exec("INSERT IGNORE INTO footer_settings (about_text, address, phone, email, copyright_text) VALUES ('Premier private ITI.','Maner, Patna - 801108','+91-9155401839','manerpvtiti@gmail.com','© Maner Pvt ITI')");
    $year = (int) date('Y');
    for ($j = 0; $j < 3; $j++) {
        $sy = $year + $j;
        $sn = $sy . '-' . substr((string) ($sy + 2), 2);
        $pdo->prepare('INSERT IGNORE INTO sessions (session_name, start_year, end_year, is_active) VALUES (?,?,?,1)')
            ->execute([$sn, $sy, $sy + 2]);
    }
    $pdo->exec("INSERT IGNORE INTO trades (name, slug, category, description, duration, eligibility, seats, is_active) VALUES
        ('Electrician','electrician','Engineering','Electrician training.','2 Years','10th Pass','60',1),
        ('Fitter','fitter','Engineering','Fitter training.','2 Years','10th Pass','60',1)");
    $pdo->exec("INSERT IGNORE INTO hero_section (title, subtitle, description, cta_text, cta_link, cta2_text, cta2_link, is_active) VALUES
        ('Maner Pvt ITI','Bihar''s Leading Technical Institute','Join Bihar''s premier NCVT-affiliated institution. We combine industrial rigor with modern technical proficiency to prepare you for a high-impact engineering career.','Fast-Track Your Career','/apply-admission','Download Prospectus','/fee-structure',1)");
}
