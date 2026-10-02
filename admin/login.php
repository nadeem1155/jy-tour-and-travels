<?php
/**
 * JY TOUR and TRAVELS - Admin Login
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';

// If already logged in, redirect to dashboard
if (is_admin_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$error = '';
$usernameVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Security session expired. Please refresh the page.';
    } else {
        $usernameVal = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($usernameVal) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            $pdo = get_db_connection();
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("SELECT * FROM admins WHERE (username = :u OR email = :u) AND status = 1 LIMIT 1");
                    $stmt->execute([':u' => $usernameVal]);
                    $admin = $stmt->fetch();

                    if ($admin && password_verify($password, $admin['password_hash'])) {
                        // Success: Regenerate session ID for security
                        session_regenerate_id(true);
                        $_SESSION['admin_user_id']  = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['admin_name']     = $admin['full_name'];
                        $_SESSION['admin_email']    = $admin['email'];
                        $_SESSION['admin_role']     = $admin['role'];

                        // Update last login
                        $upStmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
                        $upStmt->execute([':id' => $admin['id']]);

                        set_flash('success', "Welcome back, {$admin['full_name']}!");
                        header('Location: ' . BASE_URL . '/admin/index.php');
                        exit;
                    } else {
                        $error = 'Invalid credentials. Please verify username and password.';
                    }
                } catch (PDOException $e) {
                    $error = 'Database authentication error. Please ensure database is configured.';
                }
            } else {
                // Fallback demo login when DB not imported yet
                if ($usernameVal === 'admin' && $password === 'Admin@JY2026#Secure') {
                    session_regenerate_id(true);
                    $_SESSION['admin_user_id']  = 1;
                    $_SESSION['admin_username'] = 'admin';
                    $_SESSION['admin_name']     = 'JY Administrator';
                    $_SESSION['admin_email']    = 'jytourandtravels32@gmail.com';
                    $_SESSION['admin_role']     = 'superadmin';

                    set_flash('warning', 'Logged in using standby mode. Please configure MySQL to enable full database persistence.');
                    header('Location: ' . BASE_URL . '/admin/index.php');
                    exit;
                } else {
                    $error = 'Database offline and credentials do not match default system setup.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | JY TOUR and TRAVELS</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #04361c 0%, #074e28 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
            border-top: 5px solid #ffd700;
        }
        .login-header {
            background: #f8faf9;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e5ebe7;
        }
        .login-header img {
            max-width: 220px;
            height: auto;
        }
        .login-body {
            padding: 32px 30px;
        }
        .btn-login {
            background: linear-gradient(135deg, #ffd700 0%, #f5a623 100%);
            color: #111827;
            font-weight: 800;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.25s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #ffe033 0%, #ffd700 100%);
            box-shadow: 0 4px 12px rgba(245, 166, 35, 0.4);
        }
        .credentials-box {
            background: #f0f7f3;
            border-left: 3px solid #074e28;
            padding: 12px;
            font-size: 0.85rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <a href="<?= e(BASE_URL); ?>/index.php">
            <img src="<?= e(BASE_URL); ?>/assets/images/logo.svg" alt="JY TOUR and TRAVELS" class="img-fluid">
        </a>
        <div class="mt-2 text-muted small fw-semibold">Administration Management Portal</div>
    </div>

    <div class="login-body">
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <?= e($error); ?>
        </div>
        <?php endif; ?>

        <?php $flash = get_flash(); if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']); ?> py-2 small" role="alert">
            <?= $flash['message']; ?>
        </div>
        <?php endif; ?>

        <form action="<?= e(BASE_URL); ?>/admin/login.php" method="POST">
            <?= csrf_field(); ?>

            <div class="mb-3">
                <label for="username" class="form-label fw-bold small text-secondary">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" class="form-control" id="username" name="username" value="<?= e($usernameVal); ?>" placeholder="admin" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-login">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Sign In to Dashboard
                </button>
            </div>
        </form>

        <div class="credentials-box mt-4">
            <strong>Default Setup Credentials:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>Admin@JY2026#Secure</code>
        </div>

        <div class="text-center mt-3">
            <a href="<?= e(BASE_URL); ?>/index.php" class="text-muted small text-decoration-none">
                <i class="fa-solid fa-arrow-left me-1"></i> Return to Main Website
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
