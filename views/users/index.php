<?php
$pageTitle = 'Users';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>User Management</h2>
    <p>View, create and manage system users and their role assignments.</p>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">All Users <span style="color:#9ca3af;font-weight:400;">(<?= count($users) ?>)</span></span>
        <?php if (Auth::hasPermission('user.create')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=users&action=create" class="btn btn-primary btn-sm">+ Add User</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Created</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af;">
                        No users found.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $user): ?>
                <tr>
                    <td style="color:#9ca3af;"><?= $i + 1 ?></td>
                    <td>
                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if (!empty($user['roles'])): ?>
                            <?php foreach ($user['roles'] as $role): ?>
                                <span class="badge badge-blue"><?= htmlspecialchars($role['name']) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">No roles</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#6b7280;font-size:13px;">
                        <?= date('d M Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="actions">
                        <?php if (Auth::hasPermission('user.edit')): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=users&action=edit&id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('user.delete')): ?>
                        <form class="confirm-delete" action="<?= BASE_URL ?>/index.php?page=users&action=delete&id=<?= $user['id'] ?>" method="POST"
                              onsubmit="return confirm('Delete user \'<?= htmlspecialchars(addslashes($user['name'])) ?>\'? This cannot be undone.');">
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
