<?php
/**
 * Delete Blog Post
 * Confirmation page for deleting a post
 */

require_once 'config.php';
require_once 'functions.php';

// Require authentication
requireAuth();

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    setFlashMessage('error', 'Post not found');
    redirect('posts.php');
}

// Load existing post
$post = findPostBySlug($slug);

if (!$post) {
    setFlashMessage('error', 'Post not found');
    redirect('posts.php');
}

$error = '';

// Handle delete confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        // Verify confirmation
        $confirm = $_POST['confirm'] ?? '';

        if ($confirm !== 'DELETE') {
            $error = 'Please type DELETE to confirm deletion.';
        } else {
            try {
                // Delete from JSON
                deletePost($slug);

                // Delete HTML file
                deletePostHtml($slug);

                // Set success message
                setFlashMessage('success', 'Post "' . $post['title'] . '" has been deleted successfully.');

                // Redirect to posts list
                redirect('posts.php');

            } catch (Exception $e) {
                $error = 'Failed to delete post: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post | Revive Cafe Admin</title>
    <link rel="stylesheet" href="admin-styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f7fafc;
            color: #2d3748;
        }

        .admin-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .admin-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .admin-nav a {
            color: #4a5568;
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.95rem;
        }

        .admin-nav a:hover {
            color: #2d3748;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem 3rem 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #c53030;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #cbd5e0;
            color: #2d3748;
        }

        .btn-secondary:hover {
            background: #a0aec0;
        }

        .btn-danger {
            background: #f56565;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .warning-card {
            background: white;
            border-radius: 8px;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-left: 4px solid #f56565;
        }

        .warning-icon {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .warning-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: #c53030;
        }

        .warning-text {
            color: #718096;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .post-preview {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 6px;
            margin: 1.5rem 0;
        }

        .post-preview h3 {
            margin-bottom: 0.5rem;
            color: #2d3748;
        }

        .post-preview-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 1rem;
        }

        .post-preview-excerpt {
            color: #4a5568;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #2d3748;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #fed7d7;
            border-radius: 6px;
            font-size: 1rem;
            font-family: 'Courier New', monospace;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f56565;
        }

        .form-group small {
            display: block;
            margin-top: 0.5rem;
            color: #718096;
            font-size: 0.875rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-title">Revive Cafe Admin</div>
            <nav class="admin-nav">
                <a href="posts.php">Posts</a>
                <a href="../" target="_blank">View Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>⚠️ Delete Post</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="warning-card">
            <div class="warning-icon">🗑️</div>

            <div class="warning-title">
                Are you absolutely sure?
            </div>

            <p class="warning-text">
                This action <strong>cannot be undone</strong>. This will permanently delete the blog post and remove it from your website.
            </p>

            <div class="post-preview">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <div class="post-preview-meta">
                    <span><?php echo ucfirst($post['category']); ?></span>
                    <span>•</span>
                    <span><?php echo formatDate($post['date']); ?></span>
                    <span>•</span>
                    <span><?php echo htmlspecialchars($post['slug']); ?></span>
                </div>
                <p class="post-preview-excerpt">
                    <?php echo htmlspecialchars($post['excerpt']); ?>
                </p>
            </div>

            <p class="warning-text">
                The following will be deleted:
            </p>
            <ul style="margin: 1rem 0 1.5rem 2rem; color: #718096;">
                <li>Post metadata from database</li>
                <li>HTML file: <code>/blog/<?php echo htmlspecialchars($post['slug']); ?>.html</code></li>
                <li>URL: <code>/blog/post/<?php echo htmlspecialchars($post['slug']); ?></code></li>
            </ul>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                <div class="form-group">
                    <label for="confirm">
                        Type <code>DELETE</code> to confirm:
                    </label>
                    <input
                        type="text"
                        id="confirm"
                        name="confirm"
                        required
                        placeholder="DELETE"
                        autocomplete="off"
                    >
                    <small>This confirmation is required to prevent accidental deletion.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Delete This Post</button>
                    <a href="posts.php" class="btn btn-secondary">Cancel</a>
                    <a href="post-edit.php?slug=<?php echo urlencode($slug); ?>" class="btn btn-secondary">Edit Instead</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
