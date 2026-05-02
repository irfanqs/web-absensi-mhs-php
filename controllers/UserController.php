<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class UserController {

    public function index(): void {
        Auth::requirePermission('user.view');
        $users = User::findAll();
        foreach ($users as &$user) {
            $roles        = User::getRoles($user['id']);
            $user['roles'] = $roles;
        }
        unset($user);
        require __DIR__ . '/../views/users/index.php';
    }

    public function create(): void {
        Auth::requirePermission('user.create');
        $roles = Role::findAll();
        require __DIR__ . '/../views/users/create.php';
    }

    public function store(): void {
        Auth::requirePermission('user.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=create');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleIds  = $_POST['roles'] ?? [];

        $errors = [];
        if (empty($name))     $errors[] = 'Name is required.';
        if (empty($email))    $errors[] = 'Email is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (empty($password)) $errors[] = 'Password is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if (User::emailExists($email)) $errors[] = 'Email already in use.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=create');
            exit;
        }

        $userId = User::create(['name' => $name, 'email' => $email, 'password' => $password]);
        if (!empty($roleIds)) {
            User::syncRoles($userId, $roleIds);
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'User created successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
        exit;
    }

    public function edit(int $id): void {
        Auth::requirePermission('user.edit');
        $user = User::findById($id);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }
        $roles      = Role::findAll();
        $userRoles  = User::getRoles($id);
        $userRoleIds = array_column($userRoles, 'id');
        require __DIR__ . '/../views/users/edit.php';
    }

    public function update(int $id): void {
        Auth::requirePermission('user.edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=edit&id=' . $id);
            exit;
        }

        $user = User::findById($id);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleIds  = $_POST['roles'] ?? [];

        $errors = [];
        if (empty($name))  $errors[] = 'Name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (!empty($password) && strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if (User::emailExists($email, $id)) $errors[] = 'Email already in use by another user.';

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => implode('<br>', $errors)];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=edit&id=' . $id);
            exit;
        }

        $data = ['name' => $name, 'email' => $email];
        if (!empty($password)) {
            $data['password'] = $password;
        }

        User::update($id, $data);
        User::syncRoles($id, $roleIds);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'User updated successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
        exit;
    }

    public function delete(int $id): void {
        Auth::requirePermission('user.delete');

        $currentUser = Auth::user();
        if ($currentUser && (int)$currentUser['id'] === $id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'You cannot delete your own account.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }

        $user = User::findById($id);
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
            header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
            exit;
        }

        User::delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'User deleted successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=users&action=index');
        exit;
    }
}
