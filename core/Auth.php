<?php
require_once __DIR__ . '/../models/User.php';

class Auth {

    public static function login(string $email, string $password): bool {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['auth_user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
            ];
            $_SESSION['auth_roles']       = self::loadRoles($user['id']);
            $_SESSION['auth_permissions'] = self::loadPermissions($_SESSION['auth_roles']);
            return true;
        }
        return false;
    }

    public static function logout(): void {
        unset(
            $_SESSION['auth_user'],
            $_SESSION['auth_roles'],
            $_SESSION['auth_permissions'],
            $_SESSION['csrf_token']
        );
    }

    public static function check(): bool {
        return isset($_SESSION['auth_user']);
    }

    public static function user(): array|null {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function hasRole(string $role): bool {
        $roles = $_SESSION['auth_roles'] ?? [];
        return in_array($role, $roles, true);
    }

    public static function hasPermission(string $permission): bool {
        $permissions = $_SESSION['auth_permissions'] ?? [];
        return in_array($permission, $permissions, true);
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }
    }

    public static function requirePermission(string $permission): void {
        self::requireLogin();
        if (!self::hasPermission($permission)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Access denied: you do not have the required permission.'];
            header('Location: ' . BASE_URL . '/index.php?page=dashboard&action=index');
            exit;
        }
    }

    private static function loadRoles(int $userId): array {
        $db    = Database::getInstance();
        $rows  = $db->fetchAll(
            'SELECT r.name FROM roles r
             INNER JOIN user_roles ur ON ur.role_id = r.id
             WHERE ur.user_id = ?',
            [$userId]
        );
        return array_column($rows, 'name');
    }

    private static function loadPermissions(array $roleNames): array {
        if (empty($roleNames)) {
            return [];
        }
        $db          = Database::getInstance();
        $placeholders = implode(',', array_fill(0, count($roleNames), '?'));
        $rows        = $db->fetchAll(
            "SELECT DISTINCT p.name FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             INNER JOIN roles r ON r.id = rp.role_id
             WHERE r.name IN ($placeholders)",
            $roleNames
        );
        return array_column($rows, 'name');
    }

    public static function generateCsrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
