<?php
/**
 * Admin Authentication & Security Guard
 */

require_once __DIR__ . '/config.php';

/**
 * Check if admin is logged in
 */
function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

/**
 * Enforce Admin Authentication
 */
function require_admin(): void {
    if (!is_admin_logged_in()) {
        set_flash('danger', 'Please log in to access the administration dashboard.');
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Get currently logged-in admin data
 */
function get_logged_admin(): ?array {
    if (!is_admin_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['admin_user_id'],
        'username' => $_SESSION['admin_username'],
        'name'     => $_SESSION['admin_name'] ?? 'Admin',
        'email'    => $_SESSION['admin_email'] ?? '',
        'role'     => $_SESSION['admin_role'] ?? 'superadmin'
    ];
}
