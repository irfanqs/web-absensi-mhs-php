<?php
$pageTitle = 'Edit Permission';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Edit Permission</h2>
    <p>Update the permission details.</p>
</div>

<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">Editing: <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:13px;"><?= htmlspecialchars($permission['name']) ?></code></span>
        <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=permissions&action=update&id=<?= $permission['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Permission Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? $permission['name']) ?>"
                   required>
            <p class="form-text">Changing this may break existing role permission checks.</p>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control"
                   value="<?= htmlspecialchars($_POST['description'] ?? $permission['description']) ?>"
                   placeholder="Brief description of what this permission allows">
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
