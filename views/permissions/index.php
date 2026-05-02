<?php
$pageTitle = 'Permissions';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Permission Management</h2>
    <p>Define the permission slugs that control actions within the system.</p>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">All Permissions <span style="color:#9ca3af;font-weight:400;">(<?= count($permissions) ?>)</span></span>
        <?php if (Auth::hasPermission('permission.create')): ?>
        <a href="<?= BASE_URL ?>/index.php?page=permissions&action=create" class="btn btn-primary btn-sm">+ Add Permission</a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($permissions)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;padding:30px;color:#9ca3af;">
                        No permissions found.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($permissions as $i => $perm): ?>
                <tr>
                    <td style="color:#9ca3af;"><?= $i + 1 ?></td>
                    <td>
                        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:13px;color:#475569;">
                            <?= htmlspecialchars($perm['name']) ?>
                        </code>
                    </td>
                    <td style="color:#6b7280;font-size:13px;">
                        <?= htmlspecialchars($perm['description'] ?: '—') ?>
                    </td>
                    <td style="color:#6b7280;font-size:13px;">
                        <?= date('d M Y', strtotime($perm['created_at'])) ?>
                    </td>
                    <td class="actions">
                        <?php if (Auth::hasPermission('permission.edit')): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=permissions&action=edit&id=<?= $perm['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('permission.delete')): ?>
                        <form class="confirm-delete" action="<?= BASE_URL ?>/index.php?page=permissions&action=delete&id=<?= $perm['id'] ?>" method="POST"
                              onsubmit="return confirm('Delete permission \'<?= htmlspecialchars(addslashes($perm['name'])) ?>\'?');">
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
