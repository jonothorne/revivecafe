<?php
/**
 * Admin Logout
 * Destroys admin session and redirects to login
 */

require_once 'config.php';

// Destroy the session
destroyAdminSession();

// Redirect to login with success message
header('Location: login.php?logout=success');
exit;
