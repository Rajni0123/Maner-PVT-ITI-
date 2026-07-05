<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Mail;
use App\Core\View;

class AdminAuthController
{
    public static function loginForm(): void
    {
        if (Auth::check()) {
            redirect('admin');
        }
        View::render('admin/login', ['title' => 'Admin Login'], 'admin-auth');
    }

    public static function login(): void
    {
        verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            redirect('admin');
        }

        try {
            $count = (int) (Database::fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0);
            if ($count === 0) {
                flash('error', 'No admin account in database. Run install.php or reset-admin.php once.');
            } else {
                flash('error', 'Invalid email or password.');
            }
        } catch (\Throwable $e) {
            flash('error', 'Login failed. Check database settings in config.php.');
        }

        redirect('admin/login');
    }

    public static function forgotForm(): void
    {
        if (Auth::check()) {
            redirect('admin');
        }
        View::render('admin/forgot-password', ['title' => 'Forgot Password'], 'admin-auth');
    }

    public static function forgotSubmit(): void
    {
        verify_csrf();
        ensure_password_reset_schema();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $message = 'If this email is registered as admin, a password reset link has been sent.';

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Enter a valid admin email address.');
            redirect('admin/forgot-password');
        }

        $user = Database::fetch(
            'SELECT id, email, name FROM users WHERE LOWER(TRIM(email)) = ? AND is_active = 1 LIMIT 1',
            [$email]
        );

        if ($user) {
            $token = bin2hex(random_bytes(32));
            Database::update('users', [
                'password_reset_token' => hash('sha256', $token),
                'password_reset_expires' => date('Y-m-d H:i:s', time() + 3600),
            ], 'id = ?', [(int) $user['id']]);

            $resetUrl = site_url('admin/reset-password?token=' . urlencode($token));
            $name = trim((string) ($user['name'] ?? 'Admin'));
            $html = '<div style="font-family:Arial,sans-serif;line-height:1.5;color:#191c1e">'
                . '<h2 style="margin:0 0 12px;color:#131b2e">Password Reset Request</h2>'
                . '<p>Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>'
                . '<p>We received a request to reset your admin password for Maner Private ITI.</p>'
                . '<p><a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '" '
                . 'style="display:inline-block;padding:10px 16px;background:#131b2e;color:#fff;text-decoration:none;border-radius:4px">'
                . 'Reset Password</a></p>'
                . '<p style="font-size:13px;color:#45464d">This link expires in 1 hour. If you did not request this, ignore this email.</p>'
                . '<p style="font-size:12px;color:#76777d">Reset link:<br>' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '</p>'
                . '</div>';

            if (!Mail::send($user['email'], 'Reset your admin password', $html)) {
                flash('error', 'Could not send reset email. Check server mail settings or contact support.');
                redirect('admin/forgot-password');
            }
        }

        flash('success', $message);
        redirect('admin/forgot-password');
    }

    public static function resetForm(): void
    {
        if (Auth::check()) {
            redirect('admin');
        }

        $token = trim($_GET['token'] ?? '');
        if ($token === '') {
            flash('error', 'Invalid or expired reset link.');
            redirect('admin/forgot-password');
        }

        ensure_password_reset_schema();
        $user = Database::fetch(
            'SELECT id FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW() AND is_active = 1 LIMIT 1',
            [hash('sha256', $token)]
        );
        if (!$user) {
            flash('error', 'Invalid or expired reset link. Request a new one.');
            redirect('admin/forgot-password');
        }

        View::render('admin/reset-password', [
            'title' => 'Reset Password',
            'token' => $token,
        ], 'admin-auth');
    }

    public static function resetSubmit(): void
    {
        verify_csrf();
        ensure_password_reset_schema();

        $token = trim($_POST['token'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        if ($token === '') {
            flash('error', 'Invalid or expired reset link.');
            redirect('admin/forgot-password');
        }
        if (strlen($password) < 6) {
            flash('error', 'Password must be at least 6 characters.');
            redirect('admin/reset-password?token=' . urlencode($token));
        }
        if ($password !== $confirm) {
            flash('error', 'Password confirmation does not match.');
            redirect('admin/reset-password?token=' . urlencode($token));
        }

        $user = Database::fetch(
            'SELECT id FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW() AND is_active = 1 LIMIT 1',
            [hash('sha256', $token)]
        );
        if (!$user) {
            flash('error', 'Invalid or expired reset link. Request a new one.');
            redirect('admin/forgot-password');
        }

        Database::update('users', [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'password_reset_token' => null,
            'password_reset_expires' => null,
        ], 'id = ?', [(int) $user['id']]);

        flash('success', 'Password updated successfully. You can login now.');
        redirect('admin/login');
    }

    public static function logout(): void
    {
        Auth::logout();
        redirect('admin/login');
    }

    public static function profileForm(): void
    {
        Auth::require();
        $sessionUser = Auth::user();
        $user = Database::fetch('SELECT * FROM users WHERE id = ?', [(int) $sessionUser['id']]);
        if (!$user) {
            redirect('admin');
        }
        View::render('admin/profile', [
            'title' => 'My Profile',
            'user' => $user,
        ], 'admin');
    }

    public static function profileSave(): void
    {
        Auth::require();
        verify_csrf();

        $sessionUser = Auth::user();
        $userId = (int) $sessionUser['id'];
        $user = Database::fetch('SELECT * FROM users WHERE id = ?', [$userId]);
        if (!$user) {
            redirect('admin');
        }

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($name === '') {
            flash('error', 'Name is required.');
            redirect('admin/profile');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Enter a valid email address.');
            redirect('admin/profile');
        }

        $existing = Database::fetch(
            'SELECT id FROM users WHERE LOWER(email) = ? AND id != ?',
            [$email, $userId]
        );
        if ($existing) {
            flash('error', 'This email is already used by another account.');
            redirect('admin/profile');
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone ?: null,
        ];

        if ($password !== '' || $passwordConfirm !== '') {
            if (strlen($password) < 6) {
                flash('error', 'Password must be at least 6 characters.');
                redirect('admin/profile');
            }
            if ($password !== $passwordConfirm) {
                flash('error', 'Password confirmation does not match.');
                redirect('admin/profile');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            $avatar = \App\Core\Upload::save($_FILES['avatar'] ?? [], 'avatar');
            if ($avatar) {
                $data['avatar'] = $avatar;
            }
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            redirect('admin/profile');
        }

        Database::update('users', $data, 'id = ?', [$userId]);
        Auth::refreshUser($userId);
        flash('success', 'Profile updated successfully.');
        redirect('admin/profile');
    }
}
