<?php
$pageTitle = 'Edit Role';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Edit Role</h2>
    <p>Update role details and permission assignments.</p>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Editing: <span class="badge badge-blue"><?= htmlspecialchars($role['name']) ?></span></span>
        <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=roles&action=update&id=<?= $role['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Role Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? $role['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control"
                   value="<?= htmlspecialchars($_POST['description'] ?? $role['description']) ?>"
                   placeholder="Brief description of this role">
        </div>

        <div class="form-group">
            <label>Permissions</label>
            <?php if (!empty($permissions)): ?>
            <div class="checkbox-grid">
                <?php foreach ($permissions as $perm): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>"
                        <?= in_array($perm['id'], $rolePermIds) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($perm['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-muted">No permissions available.</p>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
