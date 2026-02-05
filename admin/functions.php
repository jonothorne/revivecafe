<?php
/**
 * Admin Utility Functions
 * Helper functions for post management, validation, and file operations
 */

// ============================================================================
// POST DATA FUNCTIONS
// ============================================================================

/**
 * Load all posts from JSON file
 */
function loadAllPosts() {
    if (!file_exists(POSTS_JSON)) {
        return [];
    }

    $json = file_get_contents(POSTS_JSON);
    $posts = json_decode($json, true);

    if (!is_array($posts)) {
        return [];
    }

    return $posts;
}

/**
 * Find a post by slug
 */
function findPostBySlug($slug) {
    $posts = loadAllPosts();

    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            return $post;
        }
    }

    return null;
}

/**
 * Check if slug already exists
 */
function slugExists($slug, $excludeSlug = null) {
    $posts = loadAllPosts();

    foreach ($posts as $post) {
        if ($post['slug'] === $slug && $slug !== $excludeSlug) {
            return true;
        }
    }

    return false;
}

// ============================================================================
// JSON FILE OPERATIONS
// ============================================================================

/**
 * Safely update posts JSON file with file locking and backups
 */
function updatePostsJson($posts) {
    // Create backup directory if it doesn't exist
    if (!is_dir(BACKUP_DIR)) {
        mkdir(BACKUP_DIR, 0755, true);
    }

    // Create backup of current file
    if (file_exists(POSTS_JSON)) {
        $backupFile = BACKUP_DIR . '/posts-' . date('Y-m-d-His') . '.json';
        copy(POSTS_JSON, $backupFile);

        // Clean up old backups (keep last MAX_BACKUPS)
        $backups = glob(BACKUP_DIR . '/posts-*.json');
        if (count($backups) > MAX_BACKUPS) {
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });

            // Delete oldest backups
            $toDelete = count($backups) - MAX_BACKUPS;
            for ($i = 0; $i < $toDelete; $i++) {
                unlink($backups[$i]);
            }
        }
    }

    // Write with file locking
    $fp = fopen(POSTS_JSON, 'c');
    if (!$fp) {
        throw new Exception('Could not open posts file for writing');
    }

    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        fwrite($fp, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fflush($fp);
        flock($fp, LOCK_UN);
    } else {
        fclose($fp);
        throw new Exception('Could not lock posts file');
    }

    fclose($fp);
    return true;
}

/**
 * Add a new post to JSON
 */
function addPost($postData) {
    $posts = loadAllPosts();
    $posts[] = $postData;

    // Sort by date (newest first)
    usort($posts, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return updatePostsJson($posts);
}

/**
 * Update an existing post in JSON
 */
function updatePost($slug, $postData) {
    $posts = loadAllPosts();
    $updated = false;

    foreach ($posts as $i => $post) {
        if ($post['slug'] === $slug) {
            $posts[$i] = $postData;
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        throw new Exception('Post not found');
    }

    // Re-sort by date
    usort($posts, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return updatePostsJson($posts);
}

/**
 * Delete a post from JSON
 */
function deletePost($slug) {
    $posts = loadAllPosts();
    $newPosts = [];

    foreach ($posts as $post) {
        if ($post['slug'] !== $slug) {
            $newPosts[] = $post;
        }
    }

    return updatePostsJson($newPosts);
}

// ============================================================================
// HTML FILE OPERATIONS
// ============================================================================

/**
 * Load HTML content from blog post file
 */
function loadPostHtmlContent($slug) {
    $htmlFile = BLOG_DIR . '/' . $slug . '.html';

    if (!file_exists($htmlFile)) {
        return null;
    }

    $html = file_get_contents($htmlFile);

    // Extract content from <article class="blog-post">
    preg_match('/<article class="blog-post">(.*?)<\/article>/s', $html, $matches);

    if (!isset($matches[1])) {
        return null;
    }

    $content = $matches[1];

    // Extract just the blog-content div
    preg_match('/<div class="blog-content">(.*?)<\/div>\s*<\/div>\s*<\/article>/s', $html, $contentMatches);

    if (isset($contentMatches[1])) {
        return trim($contentMatches[1]);
    }

    return trim($content);
}

/**
 * Generate complete HTML file for blog post
 */
function generatePostHtml($post, $content) {
    $title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
    $metaDesc = htmlspecialchars($post['meta_description'], ENT_QUOTES, 'UTF-8');
    $keywords = htmlspecialchars($post['keywords'], ENT_QUOTES, 'UTF-8');
    $slug = htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars(ucfirst($post['category']), ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8');
    $featuredImage = htmlspecialchars($post['featured_image'], ENT_QUOTES, 'UTF-8');
    $excerpt = htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8');
    $date = date('F j, Y', strtotime($post['date']));
    $year = date('Y', strtotime($post['date']));

    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} | Revive Cafe</title>
    <meta name="description" content="{$metaDesc}">
    <meta name="keywords" content="{$keywords}">
    <link rel="canonical" href="https://revive-cafe.co.uk/blog/post/{$slug}">
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="https://revive-cafe.co.uk/blog/post/{$slug}">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$metaDesc}">
    <meta property="og:image" content="https://revive-cafe.co.uk/photos/{$featuredImage}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://revive-cafe.co.uk/blog/post/{$slug}">
    <meta property="twitter:title" content="{$title}">
    <meta property="twitter:description" content="{$metaDesc}">
    <meta property="twitter:image" content="https://revive-cafe.co.uk/photos/{$featuredImage}">

    <!-- Schema.org BlogPosting -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": "{$title}",
      "description": "{$metaDesc}",
      "image": "https://revive-cafe.co.uk/photos/{$featuredImage}",
      "author": {
        "@type": "Organization",
        "name": "{$author}"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Revive Cafe",
        "logo": {
          "@type": "ImageObject",
          "url": "https://revive-cafe.co.uk/photos/revive-cafe-logo.png"
        }
      },
      "datePublished": "{$post['date']}",
      "dateModified": "{$post['date']}"
    }
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="../../">
                    <img src="../../photos/revive-cafe-logo.png" alt="Revive Cafe Norwich" class="nav-logo">
                </a>
            </div>
            <ul class="nav-menu">
                <li><a href="../../#home">Home</a></li>
                <li><a href="../../#about">Our Story</a></li>
                <li><a href="../../#menu">Menu</a></li>
                <li><a href="../../#faq">FAQ</a></li>
                <li><a href="../../blog/">Blog</a></li>
                <li><a href="../../#impact">Community Impact</a></li>
                <li><a href="../../#contact">Contact</a></li>
            </ul>
            <button class="nav-toggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="../../">Home</a> / <a href="../../blog/">Blog</a> / <span>{$title}</span>
        </div>
    </div>

    <!-- Blog Post Content -->
    <article class="blog-post">
        <div class="container">
            <div class="blog-header">
                <div class="blog-meta">
                    <span class="blog-date">{$date}</span>
                    <span class="blog-category">{$category}</span>
                </div>
                <h1>{$title}</h1>
                <p class="blog-intro">{$excerpt}</p>
            </div>

            <div class="blog-featured-image">
                <img src="../photos/{$featuredImage}" alt="{$title}" loading="eager">
            </div>

            <div class="blog-content">
{$content}
            </div>
        </div>
    </article>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {$year} Revive Cafe. All rights reserved.</p>
            <p class="footer-tagline">Brewing community, one cup at a time.</p>
        </div>
    </footer>

    <script src="../../script.js"></script>
</body>
</html>
HTML;

    return $html;
}

/**
 * Save HTML file for blog post
 */
function savePostHtml($slug, $post, $content) {
    $htmlFile = BLOG_DIR . '/' . $slug . '.html';
    $html = generatePostHtml($post, $content);

    if (file_put_contents($htmlFile, $html) === false) {
        throw new Exception('Could not save HTML file');
    }

    return true;
}

/**
 * Delete HTML file for blog post
 */
function deletePostHtml($slug) {
    $htmlFile = BLOG_DIR . '/' . $slug . '.html';

    if (file_exists($htmlFile)) {
        return unlink($htmlFile);
    }

    return true;
}

// ============================================================================
// SLUG GENERATION
// ============================================================================

/**
 * Generate slug from title
 */
function generateSlug($title) {
    // Convert to lowercase
    $slug = strtolower($title);

    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    // Remove multiple consecutive hyphens
    $slug = preg_replace('/-+/', '-', $slug);

    // Trim hyphens from start and end
    $slug = trim($slug, '-');

    // Ensure uniqueness
    return ensureUniqueSlug($slug);
}

/**
 * Ensure slug is unique by appending counter if needed
 */
function ensureUniqueSlug($slug, $excludeSlug = null) {
    if (!slugExists($slug, $excludeSlug)) {
        return $slug;
    }

    $originalSlug = $slug;
    $counter = 1;

    while (slugExists($slug, $excludeSlug)) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

// ============================================================================
// VALIDATION FUNCTIONS
// ============================================================================

/**
 * Validate post data
 */
function validatePostData($data) {
    $errors = [];

    // Title
    if (empty($data['title'])) {
        $errors[] = 'Title is required';
    } elseif (strlen($data['title']) < TITLE_MIN_LENGTH) {
        $errors[] = 'Title must be at least ' . TITLE_MIN_LENGTH . ' characters';
    } elseif (strlen($data['title']) > TITLE_MAX_LENGTH) {
        $errors[] = 'Title must not exceed ' . TITLE_MAX_LENGTH . ' characters';
    }

    // Slug
    if (empty($data['slug'])) {
        $errors[] = 'Slug is required';
    } elseif (!preg_match('/^[a-z0-9-]+$/', $data['slug'])) {
        $errors[] = 'Slug can only contain lowercase letters, numbers, and hyphens';
    }

    // Category
    if (empty($data['category'])) {
        $errors[] = 'Category is required';
    } elseif (!in_array($data['category'], POST_CATEGORIES)) {
        $errors[] = 'Invalid category';
    }

    // Date
    if (empty($data['date'])) {
        $errors[] = 'Date is required';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
        $errors[] = 'Date must be in YYYY-MM-DD format';
    }

    // Excerpt
    if (empty($data['excerpt'])) {
        $errors[] = 'Excerpt is required';
    } elseif (strlen($data['excerpt']) < EXCERPT_MIN_LENGTH) {
        $errors[] = 'Excerpt must be at least ' . EXCERPT_MIN_LENGTH . ' characters';
    } elseif (strlen($data['excerpt']) > EXCERPT_MAX_LENGTH) {
        $errors[] = 'Excerpt must not exceed ' . EXCERPT_MAX_LENGTH . ' characters';
    }

    // Featured Image
    if (empty($data['featured_image'])) {
        $errors[] = 'Featured image is required';
    }

    // Meta Description
    if (empty($data['meta_description'])) {
        $errors[] = 'Meta description is required';
    } elseif (strlen($data['meta_description']) < META_DESC_MIN_LENGTH) {
        $errors[] = 'Meta description must be at least ' . META_DESC_MIN_LENGTH . ' characters';
    } elseif (strlen($data['meta_description']) > META_DESC_MAX_LENGTH) {
        $errors[] = 'Meta description must not exceed ' . META_DESC_MAX_LENGTH . ' characters';
    }

    // Keywords
    if (!empty($data['keywords']) && strlen($data['keywords']) > KEYWORDS_MAX_LENGTH) {
        $errors[] = 'Keywords must not exceed ' . KEYWORDS_MAX_LENGTH . ' characters';
    }

    // Author
    if (empty($data['author'])) {
        $errors[] = 'Author is required';
    }

    // Content
    if (empty($data['content'])) {
        $errors[] = 'Content is required';
    } elseif (strlen(strip_tags($data['content'])) < CONTENT_MIN_LENGTH) {
        $errors[] = 'Content must be at least ' . CONTENT_MIN_LENGTH . ' characters';
    }

    return $errors;
}

// ============================================================================
// SANITIZATION FUNCTIONS
// ============================================================================

/**
 * Sanitize post data
 */
function sanitizePostData($data) {
    return [
        'title' => sanitizeText($data['title'] ?? ''),
        'slug' => sanitizeSlug($data['slug'] ?? ''),
        'category' => sanitizeText($data['category'] ?? ''),
        'date' => sanitizeText($data['date'] ?? ''),
        'excerpt' => sanitizeText($data['excerpt'] ?? ''),
        'featured_image' => sanitizeFilename($data['featured_image'] ?? ''),
        'meta_description' => sanitizeText($data['meta_description'] ?? ''),
        'keywords' => sanitizeText($data['keywords'] ?? ''),
        'author' => sanitizeText($data['author'] ?? ''),
        'content' => sanitizeHtml($data['content'] ?? '')
    ];
}

/**
 * Sanitize regular text
 */
function sanitizeText($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize slug
 */
function sanitizeSlug($slug) {
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
    return $slug;
}

/**
 * Sanitize filename
 */
function sanitizeFilename($filename) {
    $filename = basename($filename);
    $filename = preg_replace('/[^a-z0-9._-]/i', '', $filename);
    return $filename;
}

/**
 * Sanitize HTML content
 */
function sanitizeHtml($html) {
    // Allowed tags
    $allowedTags = '<p><br><strong><b><em><i><h2><h3><h4><ul><ol><li><a><img><blockquote><code><pre><div>';

    // Strip unwanted tags
    $html = strip_tags($html, $allowedTags);

    // Remove script tags (extra protection)
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

    // Remove event handlers (onclick, onload, etc.)
    $html = preg_replace('/\s*on\w+\s*=\s*["\'].*?["\']/i', '', $html);

    // Remove javascript: in hrefs
    $html = preg_replace('/href\s*=\s*["\']javascript:[^"\']*["\']/i', '', $html);

    return $html;
}

// ============================================================================
// FILE UPLOAD FUNCTIONS
// ============================================================================

/**
 * Handle file upload
 */
function handleFileUpload($file) {
    // Validate file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new Exception('No file uploaded');
    }

    // Validate file size
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        throw new Exception('File size exceeds maximum allowed (' . (UPLOAD_MAX_SIZE / 1024 / 1024) . 'MB)');
    }

    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        throw new Exception('Invalid file type. Allowed types: ' . implode(', ', ALLOWED_IMAGE_TYPES));
    }

    // Validate file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        throw new Exception('Invalid file extension');
    }

    // Generate unique filename
    $filename = uniqid('blog-') . '-' . time() . '.' . $extension;
    $uploadPath = PHOTOS_DIR . '/' . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    return $filename;
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

/**
 * Format date for display
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Redirect helper
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Get flash message from session
 */
function getFlashMessage($key) {
    startSecureSession();

    if (isset($_SESSION['flash_' . $key])) {
        $message = $_SESSION['flash_' . $key];
        unset($_SESSION['flash_' . $key]);
        return $message;
    }

    return null;
}

/**
 * Set flash message in session
 */
function setFlashMessage($key, $message) {
    startSecureSession();
    $_SESSION['flash_' . $key] = $message;
}
