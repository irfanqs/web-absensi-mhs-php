<?php
$pageTitle = 'Create Role';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Create New Role</h2>
    <p>Define a new role and assign the appropriate permissions to it.</p>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Role Details</span>
        <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=roles&action=store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Role Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   placeholder="e.g. editor, manager, viewer" required>
            <p class="form-text">Use lowercase letters, numbers, hyphens or underscores. No spaces.</p>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control"
                   value="<?= htmlspecialchars($_POST['description'] ?? '') ?>"
                   placeholder="Brief description of this role">
        </div>

        <div class="form-group">
            <label>Permissions</label>
            <?php if (!empty($permissions)): ?>
            <div class="checkbox-grid">
                <?php foreach ($permissions as $perm): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>">
                    <?= htmlspecialchars($perm['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <p class="form-text">Select the permissions this role should have.</p>
            <?php else: ?>
            <p class="text-muted">No permissions available. <a href="<?= BASE_URL ?>/index.php?page=permissions&action=create">Create permissions first</a>.</p>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Create Role</button>
            <a href="<?= BASE_URL ?>/index.php?page=roles&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
