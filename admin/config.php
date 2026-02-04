<?php
/**
 * Admin Configuration
 * Global settings for the admin interface
 */

// Error reporting for admin area
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../blog-data/admin-errors.log');

// Site configuration
define('SITE_URL', 'https://revive-cafe.co.uk');
define('ADMIN_URL', SITE_URL . '/admin');

// File paths
define('ROOT_DIR', dirname(__DIR__));
define('ADMIN_DIR', __DIR__);
define('BLOG_DIR', ROOT_DIR . '/blog');
define('BLOG_DATA_DIR', ROOT_DIR . '/blog-data');
define('PHOTOS_DIR', ROOT_DIR . '/photos');

// Posts JSON file
define('POSTS_JSON', BLOG_DATA_DIR . '/posts.json');

// Backup settings
define('BACKUP_DIR', BLOG_DATA_DIR . '/backups');
define('MAX_BACKUPS', 10);

// Upload settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Post categories
define('POST_CATEGORIES', ['guide', 'impact', 'community']);

// Validation limits
define('TITLE_MIN_LENGTH', 5);
define('TITLE_MAX_LENGTH', 100);
define('EXCERPT_MIN_LENGTH', 50);
define('EXCERPT_MAX_LENGTH', 300);
define('META_DESC_MIN_LENGTH', 50);
define('META_DESC_MAX_LENGTH', 160);
define('KEYWORDS_MAX_LENGTH', 200);
define('CONTENT_MIN_LENGTH', 100);

// Timezone
date_default_timezone_set('Europe/London');

// Load authentication functions
require_once __DIR__ . '/auth.php';
