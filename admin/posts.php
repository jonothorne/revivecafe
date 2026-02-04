<?php
/**
 * Posts Listing Page
 * View all blog posts with edit/delete actions
 */

require_once 'config.php';
require_once 'functions.php';

// Require authentication
requireAuth();

// Load all posts
$posts = loadAllPosts();

// Get flash messages
$success = getFlashMessage('success');
$error = getFlashMessage('error');

// Filter by category if specified
$filterCategory = $_GET['category'] ?? '';
if ($filterCategory && in_array($filterCategory, POST_CATEGORIES)) {
    $posts = array_filter($posts, function($post) use ($filterCategory) {
        return $post['category'] === $filterCategory;
    });
}

// Search by title if specified
$searchQuery = $_GET['search'] ?? '';
if ($searchQuery) {
    $posts = array_filter($posts, function($post) use ($searchQuery) {
        return stripos($post['title'], $searchQuery) !== false ||
               stripos($post['slug'], $searchQuery) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts | Revive Cafe Admin</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
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
        }

        .btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-danger {
            background: #f56565;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .filters-row {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.625rem;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .posts-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        .post-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .post-slug {
            font-size: 0.875rem;
            color: #718096;
        }

        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .category-guide {
            background: #bee3f8;
            color: #2c5282;
        }

        .category-impact {
            background: #c6f6d5;
            color: #276749;
        }

        .category-community {
            background: #feebc8;
            color: #7c2d12;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .actions a {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .action-edit {
            background: #edf2f7;
            color: #2d3748;
        }

        .action-edit:hover {
            background: #e2e8f0;
        }

        .action-delete {
            background: #fed7d7;
            color: #c53030;
        }

        .action-delete:hover {
            background: #fc8181;
            color: white;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #718096;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
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
            <h1>Blog Posts</h1>
            <a href="post-create.php" class="btn">+ New Post</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="filters">
            <form method="GET" action="">
                <div class="filters-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            placeholder="Search by title or slug..."
                            value="<?php echo htmlspecialchars($searchQuery); ?>"
                        >
                    </div>

                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach (POST_CATEGORIES as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $filterCategory === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group" style="padding-top: 1.75rem;">
                        <button type="submit" class="btn btn-small">Filter</button>
                        <?php if ($searchQuery || $filterCategory): ?>
                            <a href="posts.php" class="btn btn-small" style="background: #cbd5e0; color: #2d3748;">Clear</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($posts)): ?>
            <div class="posts-table">
                <div class="empty-state">
                    <h3>No posts found</h3>
                    <p>Get started by creating your first blog post.</p>
                    <a href="post-create.php" class="btn">Create Your First Post</a>
                </div>
            </div>
        <?php else: ?>
            <div class="posts-table">
                <table>
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                                    <div class="post-slug"><?php echo htmlspecialchars($post['slug']); ?></div>
                                </td>
                                <td>
                                    <span class="category-badge category-<?php echo $post['category']; ?>">
                                        <?php echo ucfirst($post['category']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($post['date']); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="post-edit.php?slug=<?php echo urlencode($post['slug']); ?>" class="action-edit">Edit</a>
                                        <a href="post-delete.php?slug=<?php echo urlencode($post['slug']); ?>" class="action-delete">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem; color: #718096; font-size: 0.875rem;">
                Total: <?php echo count($posts); ?> post<?php echo count($posts) !== 1 ? 's' : ''; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
