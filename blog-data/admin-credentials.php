<?php
/**
 * Admin Credentials
 * Stores hashed admin credentials securely
 * DO NOT commit to version control - add to .gitignore
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS')) {
    http_response_code(403);
    die('Direct access not permitted');
}

// Generate password hash:
// php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
// CHANGE THE DEFAULT PASSWORD IMMEDIATELY!

return [
    'username' => 'admin',
    // Password: "ReviveCafe2026!"
    'password_hash' => '$2y$10$qcGslad3DUhlEkaSkf/jRetc/tBvj4mjWoz0FF/oio7cFleToNGnC',
    'session_timeout' => 3600, // 1 hour in seconds
];
