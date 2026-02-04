<?php
/**
 * AJAX Image Upload Handler
 * Handles image uploads from TinyMCE editor
 */

require_once 'config.php';
require_once 'functions.php';

// Set JSON response header
header('Content-Type: application/json');

// Verify admin is authenticated
if (!isAdminAuthenticated()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

try {
    // Handle the file upload
    $filename = handleFileUpload($_FILES['file']);

    // Return success with image URL
    echo json_encode([
        'location' => '/photos/' . $filename,
        'filename' => $filename
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
