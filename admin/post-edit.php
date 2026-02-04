<?php
/**
 * Edit Blog Post
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

// Load HTML content
$htmlContent = loadPostHtmlContent($slug);

if ($htmlContent === null) {
    setFlashMessage('error', 'Could not load post content');
    redirect('posts.php');
}

$errors = [];
$formData = $post;
$formData['content'] = $htmlContent;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        // Collect form data
        $formData = [
            'title' => $_POST['title'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'category' => $_POST['category'] ?? '',
            'date' => $_POST['date'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
            'featured_image' => $_POST['featured_image'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'keywords' => $_POST['keywords'] ?? '',
            'author' => $_POST['author'] ?? 'Revive Cafe',
            'content' => $_POST['content'] ?? ''
        ];

        // Handle featured image upload
        if (isset($_FILES['featured_image_upload']) && $_FILES['featured_image_upload']['error'] === UPLOAD_ERR_OK) {
            try {
                $formData['featured_image'] = handleFileUpload($_FILES['featured_image_upload']);
            } catch (Exception $e) {
                $errors[] = 'Featured image upload failed: ' . $e->getMessage();
            }
        }

        // Validate post data
        $validationErrors = validatePostData($formData);
        $errors = array_merge($errors, $validationErrors);

        // Check if slug is unique (excluding current post)
        if (empty($validationErrors) && slugExists($formData['slug'], $slug)) {
            $errors[] = 'A post with this slug already exists. Please choose a different slug.';
        }

        // If no errors, save the post
        if (empty($errors)) {
            try {
                // Sanitize data
                $postData = sanitizePostData($formData);

                // Check if slug changed
                $slugChanged = ($slug !== $postData['slug']);

                // Update JSON
                updatePost($slug, $postData);

                // Update HTML file
                savePostHtml($postData['slug'], $postData, $postData['content']);

                // If slug changed, delete old HTML file
                if ($slugChanged) {
                    deletePostHtml($slug);
                }

                // Set success message
                setFlashMessage('success', 'Post updated successfully!');

                // Redirect to same page (with new slug if changed)
                redirect('post-edit.php?slug=' . urlencode($postData['slug']));

            } catch (Exception $e) {
                $errors[] = 'Failed to update post: ' . $e->getMessage();
            }
        }
    }
}

// Get success message
$success = getFlashMessage('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post | Revive Cafe Admin</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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

        .alert ul {
            margin: 0.5rem 0 0 1.5rem;
        }

        .form-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
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

        .form-group label .required {
            color: #e53e3e;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group small {
            display: block;
            margin-top: 0.5rem;
            color: #718096;
            font-size: 0.875rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .char-counter {
            text-align: right;
            font-size: 0.875rem;
            color: #718096;
            margin-top: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .file-upload {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .file-upload input[type="file"] {
            flex: 1;
        }

        .post-actions {
            display: flex;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .page-header,
            .post-actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-title">Revive Cafe Admin</div>
            <nav class="admin-nav">
                <a href="posts.php">Posts</a>
                <a href="../blog/post/<?php echo urlencode($slug); ?>" target="_blank">View Post</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Edit Post</h1>
            <div class="post-actions">
                <a href="posts.php" class="btn btn-secondary">← Back to Posts</a>
                <a href="post-delete.php?slug=<?php echo urlencode($slug); ?>" class="btn btn-danger">Delete Post</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

            <div class="form-card">
                <h2 style="margin-bottom: 1.5rem;">Basic Information</h2>

                <div class="form-group">
                    <label for="title">
                        Post Title <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        value="<?php echo htmlspecialchars($formData['title']); ?>"
                        placeholder="Enter post title..."
                    >
                    <small>The main title of your blog post (5-100 characters)</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="slug">
                            URL Slug <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            required
                            value="<?php echo htmlspecialchars($formData['slug']); ?>"
                            placeholder="auto-generated-from-title"
                            pattern="[a-z0-9-]+"
                        >
                        <small>Changing slug will break existing links!</small>
                    </div>

                    <div class="form-group">
                        <label for="date">
                            Publish Date <span class="required">*</span>
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            required
                            value="<?php echo htmlspecialchars($formData['date']); ?>"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">
                            Category <span class="required">*</span>
                        </label>
                        <select id="category" name="category" required>
                            <?php foreach (POST_CATEGORIES as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $formData['category'] === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="author">
                            Author <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            required
                            value="<?php echo htmlspecialchars($formData['author']); ?>"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="excerpt">
                        Excerpt <span class="required">*</span>
                    </label>
                    <textarea
                        id="excerpt"
                        name="excerpt"
                        required
                        maxlength="<?php echo EXCERPT_MAX_LENGTH; ?>"
                        placeholder="Short summary of your post..."
                    ><?php echo htmlspecialchars($formData['excerpt']); ?></textarea>
                    <div class="char-counter">
                        <span id="excerpt-count">0</span> / <?php echo EXCERPT_MAX_LENGTH; ?> characters
                    </div>
                    <small>Brief description shown on blog index (<?php echo EXCERPT_MIN_LENGTH; ?>-<?php echo EXCERPT_MAX_LENGTH; ?> characters)</small>
                </div>
            </div>

            <div class="form-card">
                <h2 style="margin-bottom: 1.5rem;">Featured Image</h2>

                <div class="form-group">
                    <label>Current Image</label>
                    <div style="margin-bottom: 1rem;">
                        <img src="../photos/<?php echo htmlspecialchars($formData['featured_image']); ?>" alt="Featured Image" style="max-width: 300px; border-radius: 6px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="featured_image_upload">Upload New Image</label>
                    <div class="file-upload">
                        <input
                            type="file"
                            id="featured_image_upload"
                            name="featured_image_upload"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                        >
                    </div>
                    <small>Or keep existing:</small>
                    <input
                        type="text"
                        name="featured_image"
                        value="<?php echo htmlspecialchars($formData['featured_image']); ?>"
                        style="margin-top: 0.5rem;"
                        readonly
                    >
                    <small>JPG, PNG, WebP, or GIF (max 5MB)</small>
                </div>
            </div>

            <div class="form-card">
                <h2 style="margin-bottom: 1.5rem;">Post Content</h2>

                <div class="form-group">
                    <label for="content">
                        Content <span class="required">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        required
                    ><?php echo htmlspecialchars($formData['content']); ?></textarea>
                    <small>Edit your blog post content using the editor above</small>
                </div>
            </div>

            <div class="form-card">
                <h2 style="margin-bottom: 1.5rem;">SEO Settings</h2>

                <div class="form-group">
                    <label for="meta_description">
                        Meta Description <span class="required">*</span>
                    </label>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        required
                        maxlength="<?php echo META_DESC_MAX_LENGTH; ?>"
                        placeholder="Description for search engines..."
                    ><?php echo htmlspecialchars($formData['meta_description']); ?></textarea>
                    <div class="char-counter">
                        <span id="meta-count">0</span> / <?php echo META_DESC_MAX_LENGTH; ?> characters
                    </div>
                    <small>Description shown in search results (<?php echo META_DESC_MIN_LENGTH; ?>-<?php echo META_DESC_MAX_LENGTH; ?> characters optimal)</small>
                </div>

                <div class="form-group">
                    <label for="keywords">Keywords</label>
                    <input
                        type="text"
                        id="keywords"
                        name="keywords"
                        value="<?php echo htmlspecialchars($formData['keywords']); ?>"
                        placeholder="cafe norwich, dog friendly, community..."
                    >
                    <small>Comma-separated keywords for SEO</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Post</button>
                <a href="posts.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'preview', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'link image | removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size:16px }',

            // Image upload handler
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                fetch('ajax-upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        reject(result.error);
                    } else {
                        resolve(result.location);
                    }
                })
                .catch(error => reject('Upload failed: ' + error));
            }),

            // Allow all HTML elements for blog posts
            valid_elements: '*[*]',
            extended_valid_elements: '*[*]',

            // Relative URLs
            relative_urls: false,
            remove_script_host: false,
            document_base_url: '<?php echo SITE_URL; ?>/'
        });

        // Character counters
        function updateCharCounter(textareaId, counterId) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);

            function update() {
                counter.textContent = textarea.value.length;
            }

            textarea.addEventListener('input', update);
            update();
        }

        updateCharCounter('excerpt', 'excerpt-count');
        updateCharCounter('meta_description', 'meta-count');
    </script>
</body>
</html>
