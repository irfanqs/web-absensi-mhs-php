<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Permission.php';

class PermissionController {

    public function index(): void {
        Auth::requirePermission('permission.view');
        $permissions = Permission::findAll();
        require __DIR__ . '/../views/permissions/index.php';
    }

    public function create(): void {
        Auth::requirePermission('permission.create');
        require __DIR__ . '/../views/permissions/create.php';
    }

    public function store(): void {
        Auth::requirePermission('permission.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=create');
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if (empty($name)) $errors[] = 'Permission name is required.';
        if (Permission::nameExists($name)) $errors[] = 'Permission name already exists.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=create');
            exit;
        }

        Permission::create(['name' => $name, 'description' => $description]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Permission created successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
        exit;
    }

    public function edit(int $id): void {
        Auth::requirePermission('permission.edit');
        $permission = Permission::findById($id);
        if (!$permission) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permission not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
            exit;
        }
        require __DIR__ . '/../views/permissions/edit.php';
    }

    public function update(int $id): void {
        Auth::requirePermission('permission.edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=edit&id=' . $id);
            exit;
        }

        $permission = Permission::findById($id);
        if (!$permission) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permission not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if (empty($name)) $errors[] = 'Permission name is required.';
        if (Permission::nameExists($name, $id)) $errors[] = 'Permission name already in use.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=edit&id=' . $id);
            exit;
        }

        Permission::update($id, ['name' => $name, 'description' => $description]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Permission updated successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
        exit;
    }

    public function delete(int $id): void {
        Auth::requirePermission('permission.delete');

        $permission = Permission::findById($id);
        if (!$permission) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permission not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
            exit;
        }

        Permission::delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Permission deleted successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=permissions&action=index');
        exit;
    }
}
