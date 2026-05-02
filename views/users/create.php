<?php
$pageTitle = 'Create User';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Create New User</h2>
    <p>Fill in the form below to add a new user to the system.</p>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">User Details</span>
        <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=users&action=store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Full Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   placeholder="John Doe" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address <span style="color:#e74c3c;">*</span></label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   placeholder="john@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password <span style="color:#e74c3c;">*</span></label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Minimum 6 characters" required>
            <p class="form-text">Password must be at least 6 characters long.</p>
        </div>

        <div class="form-group">
            <label>Assign Roles</label>
            <?php if (!empty($roles)): ?>
            <div class="checkbox-grid">
                <?php foreach ($roles as $role): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>">
                    <?= htmlspecialchars($role['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-muted">No roles available. <a href="<?= BASE_URL ?>/index.php?page=roles&action=create">Create a role first</a>.</p>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
