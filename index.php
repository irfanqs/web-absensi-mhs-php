<?php
declare(strict_types=1);

// ── Bootstrap ────────────────────────────────────────────────────────────────
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';

// ── Routing ───────────────────────────────────────────────────────────────────
$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Sanitise inputs
$page   = preg_replace('/[^a-z0-9_\-]/', '', strtolower($page));
$action = preg_replace('/[^a-z0-9_\-]/', '', strtolower($action));

// ── Dispatch ──────────────────────────────────────────────────────────────────
switch ($page) {

    // ── AUTH ──────────────────────────────────────────────────────────────────
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        match ($action) {
            'login'  => $controller->login(),
            'logout' => $controller->logout(),
            default  => $controller->showLogin(),
        };
        break;

    // ── DASHBOARD ─────────────────────────────────────────────────────────────
    case 'dashboard':
        Auth::requireLogin();
        require __DIR__ . '/views/dashboard/index.php';
        break;

    // ── USERS ─────────────────────────────────────────────────────────────────
    case 'users':
        Auth::requireLogin();
        require_once __DIR__ . '/controllers/UserController.php';
        $controller = new UserController();
        match ($action) {
            'index'  => $controller->index(),
            'create' => $controller->create(),
            'store'  => $controller->store(),
            'edit'   => $controller->edit($id),
            'update' => $controller->update($id),
            'delete' => $controller->delete($id),
            default  => $controller->index(),
        };
        break;

    // ── ROLES ─────────────────────────────────────────────────────────────────
    case 'roles':
        Auth::requireLogin();
        require_once __DIR__ . '/controllers/RoleController.php';
        $controller = new RoleController();
        match ($action) {
            'index'  => $controller->index(),
            'create' => $controller->create(),
            'store'  => $controller->store(),
            'edit'   => $controller->edit($id),
            'update' => $controller->update($id),
            'delete' => $controller->delete($id),
            default  => $controller->index(),
        };
        break;

    // ── PERMISSIONS ───────────────────────────────────────────────────────────
    case 'permissions':
        Auth::requireLogin();
        require_once __DIR__ . '/controllers/PermissionController.php';
        $controller = new PermissionController();
        match ($action) {
            'index'  => $controller->index(),
            'create' => $controller->create(),
            'store'  => $controller->store(),
            'edit'   => $controller->edit($id),
            'update' => $controller->update($id),
            'delete' => $controller->delete($id),
            default  => $controller->index(),
        };
        break;

    // ── FALLBACK ──────────────────────────────────────────────────────────────
    default:
        Auth::requireLogin();
        header('Location: ' . BASE_URL . '/index.php?page=dashboard&action=index');
        exit;
}
