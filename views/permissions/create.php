<?php
$pageTitle = 'Create Permission';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Create New Permission</h2>
    <p>Add a new permission slug to the system.</p>
</div>

<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">Permission Details</span>
        <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=permissions&action=store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Permission Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   placeholder="e.g. user.create, post.edit, report.view" required>
            <p class="form-text">Use dot-notation slugs: <code>resource.action</code> (e.g. user.create, role.delete).</p>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" class="form-control"
                   value="<?= htmlspecialchars($_POST['description'] ?? '') ?>"
                   placeholder="Brief description of what this permission allows">
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Create Permission</button>
            <a href="<?= BASE_URL ?>/index.php?page=permissions&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
