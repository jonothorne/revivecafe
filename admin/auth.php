<?php
/**
 * Authentication Helper Functions
 * Handles session management, authentication, and security
 */

// Start secure session
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_lifetime' => 0,              // Session only
            'cookie_httponly' => true,           // No JavaScript access
            'cookie_secure' => true,             // HTTPS only
            'cookie_samesite' => 'Strict',       // CSRF protection
            'use_strict_mode' => true,           // Reject uninitialized IDs
            'sid_length' => 48,                  // Longer session IDs
            'sid_bits_per_character' => 6
        ]);
    }
}

// Load admin credentials
function loadCredentials() {
    define('ADMIN_ACCESS', true);
    $credentialsFile = __DIR__ . '/../blog-data/admin-credentials.php';

    if (!file_exists($credentialsFile)) {
        die('Admin credentials file not found');
    }

    return require $credentialsFile;
}

// Check if user is authenticated
function isAdminAuthenticated() {
    startSecureSession();

    if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
        return false;
    }

    // Check session timeout
    $credentials = loadCredentials();
    if (!checkSessionTimeout($credentials['session_timeout'])) {
        return false;
    }

    return true;
}

// Require authentication (redirect to login if not authenticated)
function requireAuth() {
    if (!isAdminAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}

// Check session timeout
function checkSessionTimeout($timeout = 3600) {
    if (isset($_SESSION['last_activity']) &&
        (time() - $_SESSION['last_activity']) > $timeout) {
        // Session expired
        session_unset();
        session_destroy();
        return false;
    }

    $_SESSION['last_activity'] = time();
    return true;
}

// Verify login credentials
function verifyLogin($username, $password) {
    $credentials = loadCredentials();

    // Check username
    if ($username !== $credentials['username']) {
        return false;
    }

    // Verify password
    return password_verify($password, $credentials['password_hash']);
}

// Create admin session
function createAdminSession() {
    startSecureSession();

    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    $_SESSION['admin_authenticated'] = true;
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Destroy admin session
function destroyAdminSession() {
    startSecureSession();

    $_SESSION = [];

    // Delete session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    session_destroy();
}

// Generate CSRF token
function generateCsrfToken() {
    startSecureSession();

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validateCsrfToken($token) {
    startSecureSession();

    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

// Rate limiting for login attempts
function checkLoginAttempts($maxAttempts = 5, $timeWindow = 900) {
    startSecureSession();

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    // Clean old attempts (older than time window)
    $cutoff = time() - $timeWindow;
    $_SESSION['login_attempts'] = array_filter(
        $_SESSION['login_attempts'],
        function($timestamp) use ($cutoff) {
            return $timestamp > $cutoff;
        }
    );

    // Check if exceeded max attempts
    if (count($_SESSION['login_attempts']) >= $maxAttempts) {
        return false;
    }

    return true;
}

// Record failed login attempt
function recordFailedLogin() {
    startSecureSession();

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    $_SESSION['login_attempts'][] = time();
}

// Reset login attempts (on successful login)
function resetLoginAttempts() {
    startSecureSession();
    $_SESSION['login_attempts'] = [];
}
