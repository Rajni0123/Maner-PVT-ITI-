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
                flash('error', 'No admin account in database. Open reset-admin.php to create one.');
            } else {
                flash('error', 'Invalid email or password. Use credentials from install.php or run reset-admin.php');
            }
        } catch (Throwable $e) {
            flash('error', 'Login failed. Check database in config.php or run reset-admin.php');
        }

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
