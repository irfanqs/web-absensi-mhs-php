<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel') ?> &mdash; Sistem Absensi</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, sans-serif;
            background: #f0f2f5;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
        }

        .sidebar-brand {
            padding: 20px 24px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #34495e;
            background: #1a252f;
            text-decoration: none;
            color: #fff;
            display: block;
        }

        .sidebar-brand span {
            color: #3498db;
        }

        .sidebar-section {
            padding: 12px 16px 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7f8c8d;
            font-weight: 600;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.15s, color 0.15s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: #34495e;
            color: #fff;
        }

        .sidebar nav a .icon {
            width: 18px;
            text-align: center;
            font-style: normal;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 24px;
            border-top: 1px solid #34495e;
            font-size: 13px;
            color: #7f8c8d;
        }

        .sidebar-footer strong {
            display: block;
            color: #ecf0f1;
            margin-bottom: 4px;
        }

        .sidebar-footer a {
            color: #e74c3c;
            text-decoration: none;
            font-size: 13px;
        }

        .sidebar-footer a:hover { text-decoration: underline; }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            padding: 14px 28px;
            border-bottom: 1px solid #e0e4e8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .topbar h1 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #555;
        }

        .topbar .user-info .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3498db;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── Flash Messages ── */
        .flash {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .flash.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* ── Stats Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 22px 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border-left: 4px solid #3498db;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-card.green { border-left-color: #27ae60; }
        .stat-card.orange { border-left-color: #e67e22; }
        .stat-card.purple { border-left-color: #9b59b6; }

        .stat-card .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #7f8c8d;
            font-weight: 600;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1;
        }

        /* ── Tables ── */
        .table-responsive { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            background: #f8f9fa;
            padding: 11px 16px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e0e4e8;
            white-space: nowrap;
        }

        tbody td {
            padding: 11px 16px;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafbfd; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin: 2px 2px 2px 0;
        }

        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: opacity 0.15s, background 0.15s;
            line-height: 1.4;
        }

        .btn:hover { opacity: 0.88; }

        .btn-primary { background: #3498db; color: #fff; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-warning { background: #f39c12; color: #fff; }
        .btn-danger  { background: #e74c3c; color: #fff; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        /* ── Forms ── */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.15);
        }

        .form-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border: 1px solid #e0e4e8;
            border-radius: 6px;
            max-height: 220px;
            overflow-y: auto;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        .checkbox-item input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #3498db;
            cursor: pointer;
        }

        /* ── Misc ── */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
        }

        .page-header p {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .text-muted { color: #9ca3af; font-style: italic; font-size: 13px; }

        .actions { white-space: nowrap; }

        .confirm-delete {
            display: inline;
        }
    </style>
</head>
<body>
<?php
$currentPage   = $_GET['page'] ?? 'dashboard';
$currentAction = $_GET['action'] ?? 'index';

function isActive(string $page): string {
    global $currentPage;
    return $currentPage === $page ? 'active' : '';
}

$authUser = Auth::user();
$initials = $authUser ? strtoupper(substr($authUser['name'], 0, 1)) : '?';
?>
<aside class="sidebar">
    <a href="<?= BASE_URL ?>/index.php?page=dashboard&action=index" class="sidebar-brand">
        Sistem <span>Absensi</span>
    </a>

    <div class="sidebar-section">Main</div>
    <nav>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&action=index" class="<?= isActive('dashboard') ?>">
            <i class="icon">&#9632;</i> Dashboard
        </a>
        <?php if (Auth::hasPermission('absensi.view_all') || Auth::hasPermission('absensi.view_own')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=absensi&action=index" class="<?= isActive('absensi') ?>">
            <i class="icon">&#128197;</i> Data Absensi
        </a>
        <?php endif; ?>
    </nav>

    <?php if (Auth::hasPermission('user.view')): ?>
    <div class="sidebar-section">Management</div>
    <nav>
        <?php if (Auth::hasPermission('user.view')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="<?= isActive('users') ?>">
            <i class="icon">&#128100;</i> Users
        </a>
        <?php endif; ?>

        <?php if (Auth::hasPermission('role.view')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="<?= isActive('roles') ?>">
            <i class="icon">&#128737;</i> Roles
        </a>
        <?php endif; ?>

        <?php if (Auth::hasPermission('permission.view')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="<?= isActive('permissions') ?>">
            <i class="icon">&#128273;</i> Permissions
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

    <div class="sidebar-footer">
        <strong><?= htmlspecialchars($authUser['name'] ?? '') ?></strong>
        <?= htmlspecialchars($authUser['email'] ?? '') ?>
        <br><br>
        <a href="<?= BASE_URL ?>/index.php?page=auth&action=logout">&#8594; Logout</a>
    </div>
</aside>

<div class="main-wrapper">
    <div class="topbar">
        <h1><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
        <div class="user-info">
            <div class="avatar"><?= $initials ?></div>
            <span><?= htmlspecialchars($authUser['name'] ?? '') ?></span>
        </div>
    </div>
    <div class="content">
<?php
// Render and clear flash message
if (!empty($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
?>
        <div class="flash <?= htmlspecialchars($flash['type']) ?>">
            <?= $flash['message'] ?>
        </div>
<?php endif; ?>
