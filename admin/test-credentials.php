<?php
/**
 * Test Credentials - DELETE THIS FILE AFTER TESTING
 * This helps verify if credentials are working on production
 */

// Define the constant that admin-credentials.php needs
define('ADMIN_ACCESS', true);

// Try to load credentials
$credentialsFile = __DIR__ . '/../blog-data/admin-credentials.php';

echo "<h2>Credentials Test</h2>";
echo "<p><strong>Credentials file path:</strong> $credentialsFile</p>";

if (!file_exists($credentialsFile)) {
    echo "<p style='color: red;'><strong>ERROR:</strong> Credentials file does not exist!</p>";
    echo "<p>Make sure you've pulled the latest changes from git.</p>";
    exit;
}

echo "<p style='color: green;'>✓ Credentials file exists</p>";

// Load credentials
$credentials = require $credentialsFile;

echo "<p><strong>Username:</strong> " . htmlspecialchars($credentials['username']) . "</p>";
echo "<p><strong>Password hash:</strong> " . htmlspecialchars(substr($credentials['password_hash'], 0, 20)) . "...</p>";

// Test password verification
$testPassword = 'ReviveCafe2026!';
$isValid = password_verify($testPassword, $credentials['password_hash']);

echo "<h3>Password Verification Test</h3>";
echo "<p>Testing password: <code>ReviveCafe2026!</code></p>";

if ($isValid) {
    echo "<p style='color: green;'><strong>✓ SUCCESS:</strong> Password verification works!</p>";
    echo "<p>The credentials are correct. The issue might be with session or form submission.</p>";
} else {
    echo "<p style='color: red;'><strong>✗ FAILED:</strong> Password does not match!</p>";
    echo "<p>The password hash in the file doesn't match 'ReviveCafe2026!'</p>";

    // Generate a new hash
    $newHash = password_hash($testPassword, PASSWORD_BCRYPT);
    echo "<h3>New Hash Generated</h3>";
    echo "<p>Copy this hash to your admin-credentials.php file:</p>";
    echo "<pre>'{$newHash}'</pre>";
}

echo "<hr>";
echo "<p style='color: orange;'><strong>⚠️ IMPORTANT:</strong> Delete this file (test-credentials.php) after testing for security!</p>";
?>
