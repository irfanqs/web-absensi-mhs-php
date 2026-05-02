<?php
require_once __DIR__ . '/../core/Database.php';

class Role {

    public static function findAll(): array {
        $db = Database::getInstance();
        return $db->fetchAll('SELECT * FROM roles ORDER BY id ASC');
    }

    public static function findById(int $id): array|false {
        $db = Database::getInstance();
        return $db->fetch('SELECT * FROM roles WHERE id = ?', [$id]);
    }

    public static function create(array $data): int {
        $db = Database::getInstance();
        $db->execute(
            'INSERT INTO roles (name, description) VALUES (?, ?)',
            [$data['name'], $data['description'] ?? '']
        );
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getInstance();
        return $db->execute(
            'UPDATE roles SET name = ?, description = ? WHERE id = ?',
            [$data['name'], $data['description'] ?? '', $id]
        );
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance();
        return $db->execute('DELETE FROM roles WHERE id = ?', [$id]);
    }

    public static function getPermissions(int $roleId): array {
        $db = Database::getInstance();
        return $db->fetchAll(
            'SELECT p.* FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = ?',
            [$roleId]
        );
    }

    public static function assignPermission(int $roleId, int $permissionId): bool {
        $db = Database::getInstance();
        $existing = $db->fetch(
            'SELECT 1 FROM role_permissions WHERE role_id = ? AND permission_id = ?',
            [$roleId, $permissionId]
        );
        if ($existing) {
            return true;
        }
        return $db->execute(
            'INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)',
            [$roleId, $permissionId]
        );
    }

    public static function removePermission(int $roleId, int $permissionId): bool {
        $db = Database::getInstance();
        return $db->execute(
            'DELETE FROM role_permissions WHERE role_id = ? AND permission_id = ?',
            [$roleId, $permissionId]
        );
    }

    public static function syncPermissions(int $roleId, array $permissionIds): void {
        $db = Database::getInstance();
        $db->execute('DELETE FROM role_permissions WHERE role_id = ?', [$roleId]);
        foreach ($permissionIds as $permId) {
            self::assignPermission($roleId, (int) $permId);
        }
    }

    public static function nameExists(string $name, int $excludeId = 0): bool {
        $db  = Database::getInstance();
        $row = $db->fetch(
            'SELECT id FROM roles WHERE name = ? AND id != ?',
            [$name, $excludeId]
        );
        return (bool) $row;
    }

    public static function permissionCount(int $roleId): int {
        $db  = Database::getInstance();
        $row = $db->fetch(
            'SELECT COUNT(*) AS cnt FROM role_permissions WHERE role_id = ?',
            [$roleId]
        );
        return (int) ($row['cnt'] ?? 0);
    }
}
