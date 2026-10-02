<?php
/**
 * JY TOUR and TRAVELS - Change Admin Password
 */
$adminTitle = 'Change Password | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Security token invalid.';
    } else {
        $currentPass = $_POST['current_password'] ?? '';
        $newPass     = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            $errors[] = 'All password fields are required.';
        } elseif (strlen($newPass) < 8) {
            $errors[] = 'New password must be at least 8 characters long.';
        } elseif ($newPass !== $confirmPass) {
            $errors[] = 'New password and confirmation password do not match.';
        } elseif ($pdo) {
            $adminId = $_SESSION['admin_user_id'];
            $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $adminId]);
            $hash = $stmt->fetchColumn();

            if ($hash && password_verify($currentPass, $hash)) {
                $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                $uStmt = $pdo->prepare("UPDATE admins SET password_hash = :h WHERE id = :id");
                $uStmt->execute([':h' => $newHash, ':id' => $adminId]);
                set_flash('success', 'Password updated successfully. Use your new password on next login.');
                header('Location: ' . BASE_URL . '/admin/index.php');
                exit;
            } else {
                $errors[] = 'Incorrect current password.';
            }
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Security & Password</h2>
        <p class="text-muted small mb-0">Update your administrator account password.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                <li><?= e($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-lock text-warning me-2"></i> Update Password</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= e(BASE_URL); ?>/admin/change-password.php" method="POST">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimum 8 characters" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold w-100 py-2 mt-3">
                        <i class="fa-solid fa-key me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
