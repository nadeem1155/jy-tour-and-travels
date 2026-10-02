<?php
/**
 * Admin Logout
 */
require_once __DIR__ . '/../config/config.php';

// Unset admin session parameters
unset($_SESSION['admin_user_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

set_flash('success', 'You have been successfully signed out.');
header('Location: ' . BASE_URL . '/admin/login.php');
exit;
