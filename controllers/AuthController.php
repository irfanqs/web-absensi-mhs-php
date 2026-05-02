<?php
require_once __DIR__ . '/../core/Auth.php';

class AuthController {

    public function showLogin(): void {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/index.php?page=dashboard&action=index');
            exit;
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid CSRF token. Please try again.'];
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email and password are required.'];
            header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
            exit;
        }

        if (Auth::login($email, $password)) {
            unset($_SESSION['csrf_token']);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Welcome back, ' . htmlspecialchars(Auth::user()['name']) . '!'];
            header('Location: ' . BASE_URL . '/index.php?page=dashboard&action=index');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid email or password.'];
        header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
        exit;
    }

    public function logout(): void {
        // Preserve the flash message across the session destroy/restart cycle
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'You have been logged out successfully.'];
        header('Location: ' . BASE_URL . '/index.php?page=auth&action=login');
        exit;
    }
}
