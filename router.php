<?php
// Router for PHP built-in development server
// This simulates .htaccess URL rewriting

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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

// Remove .php extension
if (preg_match('/\.php$/', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        require $file;
        return true;
    }
}

// Check if file exists (CSS, JS, images, etc.)
$file = __DIR__ . $uri;
if (file_exists($file) && !is_dir($file)) {
    return false; // Let PHP serve the file normally
}

// Default: let PHP handle it
return false;
