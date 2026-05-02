<?php
require_once __DIR__ . '/../core/Database.php';

class Permission {

    public static function findAll(): array {
        $db = Database::getInstance();
        return $db->fetchAll('SELECT * FROM permissions ORDER BY id ASC');
    }

    public static function findById(int $id): array|false {
        $db = Database::getInstance();
        return $db->fetch('SELECT * FROM permissions WHERE id = ?', [$id]);
    }

    public static function create(array $data): int {
        $db = Database::getInstance();
        $db->execute(
            'INSERT INTO permissions (name, description) VALUES (?, ?)',
            [$data['name'], $data['description'] ?? '']
        );
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getInstance();
        return $db->execute(
            'UPDATE permissions SET name = ?, description = ? WHERE id = ?',
            [$data['name'], $data['description'] ?? '', $id]
        );
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance();
        return $db->execute('DELETE FROM permissions WHERE id = ?', [$id]);
    }

    public static function nameExists(string $name, int $excludeId = 0): bool {
        $db  = Database::getInstance();
        $row = $db->fetch(
            'SELECT id FROM permissions WHERE name = ? AND id != ?',
            [$name, $excludeId]
        );
        return (bool) $row;
    }
}
