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
    'password_hash' => '$2y$10$7BKv/GcD8shi128pqre5Xu/QWgMV9nXQ2upIwDso2VLgN9CdW78Ze',
    'session_timeout' => 3600, // 1 hour in seconds
];
