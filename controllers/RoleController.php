<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Permission.php';

class RoleController {

    public function index(): void {
        Auth::requirePermission('role.view');
        $roles = Role::findAll();
        foreach ($roles as &$role) {
            $role['permission_count'] = Role::permissionCount($role['id']);
        }
        unset($role);
        require __DIR__ . '/../views/roles/index.php';
    }

    public function create(): void {
        Auth::requirePermission('role.create');
        $permissions = Permission::findAll();
        require __DIR__ . '/../views/roles/create.php';
    }

    public function store(): void {
        Auth::requirePermission('role.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=create');
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $permIds     = $_POST['permissions'] ?? [];

        $errors = [];
        if (empty($name)) $errors[] = 'Role name is required.';
        if (Role::nameExists($name)) $errors[] = 'Role name already exists.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=create');
            exit;
        }

        $roleId = Role::create(['name' => $name, 'description' => $description]);
        if (!empty($permIds)) {
            Role::syncPermissions($roleId, $permIds);
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Role created successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
        exit;
    }

    public function edit(int $id): void {
        Auth::requirePermission('role.edit');
        $role = Role::findById($id);
        if (!$role) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Role not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
            exit;
        }
        $permissions    = Permission::findAll();
        $rolePerms      = Role::getPermissions($id);
        $rolePermIds    = array_column($rolePerms, 'id');
        require __DIR__ . '/../views/roles/edit.php';
    }

    public function update(int $id): void {
        Auth::requirePermission('role.edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=edit&id=' . $id);
            exit;
        }

        $role = Role::findById($id);
        if (!$role) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Role not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $permIds     = $_POST['permissions'] ?? [];

        $errors = [];
        if (empty($name)) $errors[] = 'Role name is required.';
        if (Role::nameExists($name, $id)) $errors[] = 'Role name already in use.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=edit&id=' . $id);
            exit;
        }

        Role::update($id, ['name' => $name, 'description' => $description]);
        Role::syncPermissions($id, $permIds);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Role updated successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
        exit;
    }

    public function delete(int $id): void {
        Auth::requirePermission('role.delete');

        $role = Role::findById($id);
        if (!$role) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Role not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
            exit;
        }

        Role::delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Role deleted successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=roles&action=index');
        exit;
    }
}
