<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo) {
            return self::$pdo;
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            config('db_host'),
            config('db_name'),
            config('db_charset')
        );

        try {
            self::$pdo = new PDO($dsn, config('db_user'), config('db_pass'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            if (!is_installed()) {
                header('Location: ' . site_url('install.php'));
                exit;
            }
            http_response_code(500);
            die('Database connection failed. Check config.php or contact admin.');
        }

        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int
    {
        Security::assertSafeIdentifier($table, 'table');
        if ($data === []) {
            throw new \InvalidArgumentException('Insert data cannot be empty');
        }
        foreach (array_keys($data) as $col) {
            Security::assertSafeIdentifier((string) $col, 'column');
        }
        $cols = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        self::query("INSERT INTO {$table} ({$cols}) VALUES ({$placeholders})", array_values($data));
        return (int) self::connect()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): void
    {
        Security::assertSafeIdentifier($table, 'table');
        if ($data === []) {
            throw new \InvalidArgumentException('Update data cannot be empty');
        }
        foreach (array_keys($data) as $col) {
            Security::assertSafeIdentifier((string) $col, 'column');
        }
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        self::query(
            "UPDATE {$table} SET {$sets} WHERE {$where}",
            array_merge(array_values($data), $whereParams)
        );
    }

    public static function delete(string $table, string $where, array $params = []): void
    {
        Security::assertSafeIdentifier($table, 'table');
        self::query("DELETE FROM {$table} WHERE {$where}", $params);
    }
}
