<?php
/**
 * Admin Header Include
 */
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';

require_admin();

$adminUser = get_logged_admin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = $adminTitle ?? 'Admin Dashboard | JY TOUR and TRAVELS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Admin Custom CSS -->
    <link rel="stylesheet" href="<?= e(BASE_URL); ?>/assets/css/admin.css">
</head>
<body class="admin-body">

<div class="d-flex">
<?php require_once __DIR__ . '/admin_sidebar.php'; ?>

<div class="admin-main-wrap">
    <!-- Topbar -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-bold text-dark d-none d-sm-block">JY TOUR and TRAVELS Control Center</h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="<?= e(BASE_URL); ?>/index.php" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Website
            </a>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="small fw-bold"><?= e($adminUser['username']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><h6 class="dropdown-header">Signed in as <strong><?= e($adminUser['username']); ?></strong></h6></li>
                    <li><a class="dropdown-item" href="<?= e(BASE_URL); ?>/admin/settings.php"><i class="fa-solid fa-gear me-2"></i> Settings</a></li>
                    <li><a class="dropdown-item" href="<?= e(BASE_URL); ?>/admin/change-password.php"><i class="fa-solid fa-key me-2"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= e(BASE_URL); ?>/admin/logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign Out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="admin-content-container">
        <?php $flash = get_flash(); if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']); ?> alert-dismissible fade show mb-4" role="alert">
            <?= $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
