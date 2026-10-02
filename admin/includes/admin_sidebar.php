<?php
/**
 * Admin Sidebar Navigation
 */
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Count new bookings if DB is connected
$newBookingsCount = 0;
$newMessagesCount = 0;
$pdo = get_db_connection();
if ($pdo) {
    try {
        $bCountStmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'New'");
        $newBookingsCount = (int)$bCountStmt->fetchColumn();

        $mCountStmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'New'");
        $newMessagesCount = (int)$mCountStmt->fetchColumn();
    } catch (Exception $e) {
        // ignore
    }
}
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <img src="<?= e(BASE_URL); ?>/assets/images/logo-white.svg" alt="JY TOUR and TRAVELS" class="img-fluid" width="180">
    </div>

    <ul class="admin-nav-list">
        <li class="admin-nav-header">Main</li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/index.php" class="admin-nav-link <?= $currentPage === 'index' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="admin-nav-header">Enquiries & Inquiries</li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="admin-nav-link <?= in_array($currentPage, ['bookings', 'booking-detail']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Booking Enquiries</span>
                <?php if ($newBookingsCount > 0): ?>
                <span class="badge bg-danger admin-badge-count"><?= $newBookingsCount; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/messages.php" class="admin-nav-link <?= $currentPage === 'messages' ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope-open-text"></i>
                <span>Contact Messages</span>
                <?php if ($newMessagesCount > 0): ?>
                <span class="badge bg-warning text-dark admin-badge-count"><?= $newMessagesCount; ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="admin-nav-header">Fleet & Services</li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/vehicles.php" class="admin-nav-link <?= in_array($currentPage, ['vehicles', 'vehicle-add', 'vehicle-edit']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-car-side"></i>
                <span>Manage Vehicles</span>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/categories.php" class="admin-nav-link <?= $currentPage === 'categories' ? 'active' : ''; ?>">
                <i class="fa-solid fa-tags"></i>
                <span>Vehicle Categories</span>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/services.php" class="admin-nav-link <?= $currentPage === 'services' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bell-concierge"></i>
                <span>Manage Services</span>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/gallery.php" class="admin-nav-link <?= $currentPage === 'gallery' ? 'active' : ''; ?>">
                <i class="fa-solid fa-images"></i>
                <span>Manage Gallery</span>
            </a>
        </li>

        <li class="admin-nav-header">Configuration</li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/settings.php" class="admin-nav-link <?= $currentPage === 'settings' ? 'active' : ''; ?>">
                <i class="fa-solid fa-sliders"></i>
                <span>Website Settings</span>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/change-password.php" class="admin-nav-link <?= $currentPage === 'change-password' ? 'active' : ''; ?>">
                <i class="fa-solid fa-key"></i>
                <span>Change Password</span>
            </a>
        </li>
        <li>
            <a href="<?= e(BASE_URL); ?>/admin/logout.php" class="admin-nav-link text-danger mt-3">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</aside>
