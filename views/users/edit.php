<?php
$pageTitle = 'Edit User';
require __DIR__ . '/../../views/layouts/header.php';
?>

<div class="page-header">
    <h2>Edit User</h2>
    <p>Update user information and role assignments.</p>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Editing: <?= htmlspecialchars($user['name']) ?></span>
        <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="btn btn-secondary btn-sm">&larr; Back</a>
    </div>

    <form action="<?= BASE_URL ?>/index.php?page=users&action=update&id=<?= $user['id'] ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

        <div class="form-group">
            <label for="name">Full Name <span style="color:#e74c3c;">*</span></label>
            <input type="text" id="name" name="name" class="form-control"
                   value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="email">Email Address <span style="color:#e74c3c;">*</span></label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Leave blank to keep current password">
            <p class="form-text">Only fill this in if you want to change the password. Must be at least 6 characters.</p>
        </div>

        <div class="form-group">
            <label>Assigned Roles</label>
            <?php if (!empty($roles)): ?>
            <div class="checkbox-grid">
                <?php foreach ($roles as $role): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>"
                        <?= in_array($role['id'], $userRoleIds) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($role['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-muted">No roles available.</p>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/index.php?page=users&action=index" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layouts/sidebar.php'; ?>
