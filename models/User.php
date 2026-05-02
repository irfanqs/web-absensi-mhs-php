<?php
require_once __DIR__ . '/../core/Database.php';

class User {

    public static function findAll(): array {
        $db = Database::getInstance();
        return $db->fetchAll('SELECT id, name, email, created_at, updated_at FROM users ORDER BY id ASC');
    }

    public static function findById(int $id): array|false {
        $db = Database::getInstance();
        return $db->fetch('SELECT id, name, email, created_at, updated_at FROM users WHERE id = ?', [$id]);
    }

    public static function findByEmail(string $email): array|false {
        $db = Database::getInstance();
        return $db->fetch('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public static function create(array $data): int {
        $db   = Database::getInstance();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $db->execute(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)',
            [$data['name'], $data['email'], $hash]
        );
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getInstance();
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            return $db->execute(
                'UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?',
                [$data['name'], $data['email'], $hash, $id]
            );
        }
        return $db->execute(
            'UPDATE users SET name = ?, email = ? WHERE id = ?',
            [$data['name'], $data['email'], $id]
        );
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance();
        return $db->execute('DELETE FROM users WHERE id = ?', [$id]);
    }

    public static function getRoles(int $userId): array {
        $db = Database::getInstance();
        return $db->fetchAll(
            'SELECT r.* FROM roles r
             INNER JOIN user_roles ur ON ur.role_id = r.id
             WHERE ur.user_id = ?',
            [$userId]
        );
    }

    public static function assignRole(int $userId, int $roleId): bool {
        $db = Database::getInstance();
        $existing = $db->fetch(
            'SELECT 1 FROM user_roles WHERE user_id = ? AND role_id = ?',
            [$userId, $roleId]
        );
        if ($existing) {
            return true;
        }
        return $db->execute(
            'INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)',
            [$userId, $roleId]
        );
    }

    public static function removeRole(int $userId, int $roleId): bool {
        $db = Database::getInstance();
        return $db->execute(
            'DELETE FROM user_roles WHERE user_id = ? AND role_id = ?',
            [$userId, $roleId]
        );
    }

    public static function syncRoles(int $userId, array $roleIds): void {
        $db = Database::getInstance();
        $db->execute('DELETE FROM user_roles WHERE user_id = ?', [$userId]);
        foreach ($roleIds as $roleId) {
            self::assignRole($userId, (int) $roleId);
        }
    }

    public static function emailExists(string $email, int $excludeId = 0): bool {
        $db  = Database::getInstance();
        $row = $db->fetch(
            'SELECT id FROM users WHERE email = ? AND id != ?',
            [$email, $excludeId]
        );
        return (bool) $row;
    }
}
