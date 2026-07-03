<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
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

    private static function isRateLimited(): bool
    {
        $maxAttempts = 5;
        $lockoutSeconds = 300;
        $attempts = $_SESSION['_login_attempts'] ?? 0;
        $lastAttempt = $_SESSION['_login_last_attempt'] ?? 0;

        if ($attempts >= $maxAttempts && (time() - $lastAttempt) < $lockoutSeconds) {
            return true;
        }

        if ((time() - $lastAttempt) >= $lockoutSeconds) {
            $_SESSION['_login_attempts'] = 0;
        }

        return false;
    }

    private static function recordFailedAttempt(): void
    {
        $_SESSION['_login_attempts'] = ($_SESSION['_login_attempts'] ?? 0) + 1;
        $_SESSION['_login_last_attempt'] = time();
    }

    private static function clearLoginAttempts(): void
    {
        unset($_SESSION['_login_attempts'], $_SESSION['_login_last_attempt']);
    }

    public static function login(): void
    {
        verify_csrf();

        if (self::isRateLimited()) {
            flash('error', 'Too many failed attempts. Please try again after 5 minutes.');
            redirect('admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            self::clearLoginAttempts();
            session_regenerate_id(true);
            redirect('admin');
        }

        self::recordFailedAttempt();

        try {
            $count = (int) (Database::fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0);
            if ($count === 0) {
                flash('error', 'No admin account found. Contact the system administrator.');
            } else {
                flash('error', 'Invalid email or password.');
            }
        } catch (\Throwable $e) {
            flash('error', 'Login failed. Please try again later.');
        }

        redirect('admin/login');
    }

    public static function logout(): void
    {
        Auth::logout();
        session_regenerate_id(true);
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
