<?php
$pageTitle = 'Roles';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Role Management</h2>
    <p>Define roles and assign permissions to control access levels.</p>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">All Roles <span style="color:#9ca3af;font-weight:400;">(<?= count($roles) ?>)</span></span>
        <?php if (Auth::hasPermission('role.create')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=roles&action=create" class="btn btn-primary btn-sm">+ Add Role</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Permissions</th>
                    <th>Created</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($roles)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af;">
                        No roles found.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($roles as $i => $role): ?>
                <tr>
                    <td style="color:#9ca3af;"><?= $i + 1 ?></td>
                    <td>
                        <span class="badge badge-blue"><?= htmlspecialchars($role['name']) ?></span>
                    </td>
                    <td style="color:#6b7280;font-size:13px;">
                        <?= htmlspecialchars($role['description'] ?: '—') ?>
                    </td>
                    <td>
                        <span class="badge badge-gray"><?= $role['permission_count'] ?> permission(s)</span>
                    </td>
                    <td style="color:#6b7280;font-size:13px;">
                        <?= date('d M Y', strtotime($role['created_at'])) ?>
                    </td>
                    <td class="actions">
                        <?php if (Auth::hasPermission('role.edit')): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=roles&action=edit&id=<?= $role['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('role.delete')): ?>
                        <form class="confirm-delete" action="<?= BASE_URL ?>/index.php?page=roles&action=delete&id=<?= $role['id'] ?>" method="POST"
                              onsubmit="return confirm('Delete role \'<?= htmlspecialchars(addslashes($role['name'])) ?>\'? Users with this role will lose it.');">
                            <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
