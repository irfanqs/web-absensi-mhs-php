<?php
require_once __DIR__ . '/../../core/Database.php';
$db = Database::getInstance();

// Stats Admin
$userCount       = (int) ($db->fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0);
$roleCount       = (int) ($db->fetch('SELECT COUNT(*) AS c FROM roles')['c'] ?? 0);
$permissionCount = (int) ($db->fetch('SELECT COUNT(*) AS c FROM permissions')['c'] ?? 0);

// Stats Dosen (Harian Keseluruhan)
$hadirHariIni = (int) ($db->fetch("SELECT COUNT(*) AS c FROM attendances WHERE status = 'Hadir' AND DATE(check_in) = CURDATE()")['c'] ?? 0);
$izinSakitHariIni = (int) ($db->fetch("SELECT COUNT(*) AS c FROM attendances WHERE status IN ('Izin', 'Sakit') AND DATE(check_in) = CURDATE()")['c'] ?? 0);

// Stats Mahasiswa (Pribadi)
$userId = Auth::user()['id'];
$hadirBulanIni = (int) ($db->fetch("SELECT COUNT(*) AS c FROM attendances WHERE user_id = ? AND status = 'Hadir' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())", [$userId])['c'] ?? 0);
$izinSakit = (int) ($db->fetch("SELECT COUNT(*) AS c FROM attendances WHERE user_id = ? AND status IN ('Izin', 'Sakit') AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())", [$userId])['c'] ?? 0);
$alpa = (int) ($db->fetch("SELECT COUNT(*) AS c FROM attendances WHERE user_id = ? AND status = 'Alpa' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())", [$userId])['c'] ?? 0);

$pageTitle = 'Dashboard';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h2>Selamat datang, <?= htmlspecialchars(Auth::user()['name'] ?? 'User') ?>!</h2>
    <p>Berikut adalah ringkasan dari Sistem Absensi Anda.</p>
</div>

<div class="stats-grid">
    <?php if (Auth::hasPermission('user.view')): ?>
    <div class="stat-card green">
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?= $userCount ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total Roles</div>
        <div class="stat-value"><?= $roleCount ?></div>
    </div>
    <div class="stat-card orange">
        <div class="stat-label">Total Permissions</div>
        <div class="stat-value"><?= $permissionCount ?></div>
    </div>
    <?php elseif (Auth::hasPermission('absensi.view_all')): ?>
    <div class="stat-card green">
        <div class="stat-label">Mhs Hadir Hari Ini</div>
        <div class="stat-value"><?= $hadirHariIni ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Mhs Izin / Sakit</div>
        <div class="stat-value"><?= $izinSakitHariIni ?></div>
    </div>
    <?php else: ?>
    <div class="stat-card green">
        <div class="stat-label">Kehadiran Bulan Ini</div>
        <div class="stat-value"><?= $hadirBulanIni ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Izin / Sakit</div>
        <div class="stat-value"><?= $izinSakit ?></div>
    </div>
    <div class="stat-card orange">
        <div class="stat-label">Alpa</div>
        <div class="stat-value"><?= $alpa ?></div>
    </div>
    <?php endif; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <?php if (Auth::hasPermission('absensi.create') && !Auth::hasPermission('user.view')): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Presensi Mahasiswa</span>
        </div>
        <p style="font-size:14px;color:#6b7280;line-height:1.6;">
            Silakan klik tombol di bawah untuk mencatat kehadiran Anda hari ini.
        </p>
        <div style="margin-top:14px; display:flex; gap:10px;">
            <form action="<?= BASE_URL ?>/index.php?page=absensi&action=masuk" method="POST">
                 <button type="submit" class="btn btn-success btn-sm">Check-In (Hadir)</button>
            </form>
            <form action="<?= BASE_URL ?>/index.php?page=absensi&action=pulang" method="POST">
                 <button type="submit" class="btn btn-primary btn-sm">Check-Out (Pulang)</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if (Auth::hasPermission('user.view')): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Quick Actions &mdash; Users</span>
            <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="btn btn-primary btn-sm">View All</a>
        </div>
        <p style="font-size:14px;color:#6b7280;line-height:1.6;">
            Manage system users, assign roles, and control access. There are currently <strong><?= $userCount ?></strong> registered user(s).
        </p>
        <?php if (Auth::hasPermission('user.create')): ?>
        <div style="margin-top:14px;">
            <a href="<?= BASE_URL ?>/index.php?page=users&action=create" class="btn btn-success btn-sm">+ Add New User</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (Auth::hasPermission('role.view')): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Quick Actions &mdash; Roles</span>
            <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="btn btn-primary btn-sm">View All</a>
        </div>
        <p style="font-size:14px;color:#6b7280;line-height:1.6;">
            Define roles and assign permissions to them. There are currently <strong><?= $roleCount ?></strong> role(s) in the system.
        </p>
        <?php if (Auth::hasPermission('role.create')): ?>
        <div style="margin-top:14px;">
            <a href="<?= BASE_URL ?>/index.php?page=roles&action=create" class="btn btn-success btn-sm">+ Add New Role</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (Auth::hasPermission('permission.view')): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Quick Actions &mdash; Permissions</span>
            <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="btn btn-primary btn-sm">View All</a>
        </div>
        <p style="font-size:14px;color:#6b7280;line-height:1.6;">
            Create and manage permission slugs that define what actions are allowed in the system. Currently <strong><?= $permissionCount ?></strong> permission(s) defined.
        </p>
        <?php if (Auth::hasPermission('permission.create')): ?>
        <div style="margin-top:14px;">
            <a href="<?= BASE_URL ?>/index.php?page=permissions&action=create" class="btn btn-success btn-sm">+ Add New Permission</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (Auth::hasPermission('user.view')): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title">Your Roles &amp; Permissions</span>
        </div>
        <div style="font-size:13px;color:#555;line-height:1.8;">
            <strong>Roles:</strong><br>
            <?php foreach (($_SESSION['auth_roles'] ?? []) as $r): ?>
                <span class="badge badge-blue"><?= htmlspecialchars($r) ?></span>
            <?php endforeach; ?>
            <?php if (empty($_SESSION['auth_roles'])): ?>
                <span class="text-muted">No roles assigned</span>
            <?php endif; ?>
            <br><br>
            <strong>Permissions:</strong><br>
            <?php foreach (($_SESSION['auth_permissions'] ?? []) as $p): ?>
                <span class="badge badge-gray"><?= htmlspecialchars($p) ?></span>
            <?php endforeach; ?>
            <?php if (empty($_SESSION['auth_permissions'])): ?>
                <span class="text-muted">No permissions</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>
