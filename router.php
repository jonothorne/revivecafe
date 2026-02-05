<?php
// Router for PHP built-in development server
// This simulates .htaccess URL rewriting

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Admin routes
// /admin or /admin/ -> /admin/index.php
if ($uri === '/admin' || $uri === '/admin/') {
    require 'admin/index.php';
    return true;
}

// /admin/anything -> /admin/anything.php
if (preg_match('#^/admin/([a-z0-9-]+)$#', $uri, $matches)) {
    $adminFile = __DIR__ . '/admin/' . $matches[1] . '.php';
    if (file_exists($adminFile)) {
        require $adminFile;
        return true;
    }
}

// Blog post URLs: /blog/post/slug-name -> /blog/post.php?slug=slug-name
if (preg_match('#^/blog/post/([a-z0-9-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require 'blog/post.php';
    return true;
}

// Blog index: /blog/ -> /blog/index.php
if ($uri === '/blog' || $uri === '/blog/') {
    require 'blog/index.php';
    return true;
}

// Homepage: / -> /index.php
if ($uri === '/' || $uri === '') {
    require 'index.php';
    return true;
}

// Handle .php extension if directly accessed
if (preg_match('/\.php$/', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        require $file;
        return true;
    }
}

// Handle extensionless URLs by checking for .php file
$phpFile = __DIR__ . $uri . '.php';
if (file_exists($phpFile) && !is_dir(__DIR__ . $uri)) {
    require $phpFile;
    return true;
}

// Check if file exists (CSS, JS, images, etc.)
$file = __DIR__ . $uri;
if (file_exists($file) && !is_dir($file)) {
    return false; // Let PHP serve the file normally
}

// 404 - Page not found
http_response_code(404);
require '404.php';
return true;
