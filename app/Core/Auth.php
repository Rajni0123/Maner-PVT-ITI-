<?php

namespace App\Core;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $email = strtolower(trim($email));
        if ($email === '' || $password === '') {
            return false;
        }

        $user = Database::fetch(
            'SELECT * FROM users WHERE LOWER(TRIM(email)) = ? AND is_active = 1 LIMIT 1',
            [$email]
        );

        if (!$user) {
            return false;
        }

        $hash = (string) ($user['password'] ?? '');
        if ($hash === '' || !password_verify($password, $hash)) {
            return false;
        }

        $_SESSION['admin_user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'avatar' => $user['avatar'] ?? null,
            'permissions' => json_decode_safe($user['permissions'] ?? null),
        ];
        return true;
    }

    public static function user(): ?array
    {
        return $_SESSION['admin_user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function require(): void
    {
        if (!self::check()) {
            redirect('admin/login');
        }
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        if (($user['role'] ?? '') === 'admin') {
            return true;
        }
        $perms = $user['permissions'] ?? [];
        return in_array($permission, $perms, true) || in_array('*', $perms, true);
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_user']);
    }

    public static function refreshUser(int $userId): void
    {
        $user = Database::fetch('SELECT * FROM users WHERE id = ? AND is_active = 1', [$userId]);
        if (!$user) {
            return;
        }
        $_SESSION['admin_user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'avatar' => $user['avatar'] ?? null,
            'permissions' => json_decode_safe($user['permissions'] ?? null),
        ];
    }
}
