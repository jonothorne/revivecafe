<?php
// Get the slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header('Location: /blog/');
    exit;
}

// Load posts data
// Use absolute path to handle both direct access and router includes
$postsFile = __DIR__ . '/../blog-data/posts.json';
if (!file_exists($postsFile)) {
    header('HTTP/1.0 404 Not Found');
    echo "Posts data not found at: " . $postsFile;
    exit;
}

$posts = json_decode(file_get_contents($postsFile), true);
if (!$posts) {
    header('HTTP/1.0 500 Internal Server Error');
    echo "Error loading posts";
    exit;
}

// Find the post
$post = null;
foreach ($posts as $p) {
    if ($p['slug'] === $slug) {
        $post = $p;
        break;
    }
}

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    echo "Post not found";
    exit;
}

// Check if HTML file exists
$htmlFile = __DIR__ . '/' . $slug . '.html';
if (!file_exists($htmlFile)) {
    header('HTTP/1.0 404 Not Found');
    echo "Post content not found at: " . $htmlFile;
    exit;
}

// Set page metadata
$page_title = $post['title'] . " | Revive Cafe";
$page_description = $post['meta_description'];
$page_keywords = $post['keywords'];
$page_url = "https://revive-cafe.co.uk/blog/post/" . $post['slug'];
$canonical_url = "https://revive-cafe.co.uk/blog/post/" . $post['slug'];
$og_title = $post['title'];
$og_description = $post['meta_description'];
$og_image = "https://revive-cafe.co.uk/photos/" . $post['featured_image'];
$base_path = "../../"; // /blog/post/slug is 3 levels deep, need ../../ to get to root

// Article Schema
$additional_schema = '
    <!-- Schema.org Article Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": "' . addslashes($post['title']) . '",
      "description": "' . addslashes($post['meta_description']) . '",
      "image": "' . $og_image . '",
      "author": {
        "@type": "Organization",
        "name": "' . $post['author'] . '"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Revive Cafe",
        "logo": {
          "@type": "ImageObject",
          "url": "https://revive-cafe.co.uk/photos/revive-cafe-logo.png"
        }
      },
      "datePublished": "' . $post['date'] . '",
      "dateModified": "' . $post['date'] . '"
    }
    </script>

    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://revive-cafe.co.uk/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "Blog",
        "item": "https://revive-cafe.co.uk/blog/"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "' . addslashes($post['title']) . '",
        "item": "' . $canonical_url . '"
      }]
    }
    </script>
';

// Include header
include __DIR__ . '/../includes/header.php';
?>
</head>
<body>
<?php include __DIR__ . '/../includes/nav.php'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="<?php echo $base_path; ?>index.php">Home</a> / <a href="<?php echo $base_path; ?>blog/">Blog</a> / <span><?php echo htmlspecialchars($post['title']); ?></span>
        </div>
    </div>

    <!-- Blog Post Content -->
    <?php
    // Load and display the HTML content
    // We need to extract just the article content from the HTML file
    $html = file_get_contents($htmlFile);

    // Extract the article section
    preg_match('/<article class="blog-post">(.*?)<\/article>/s', $html, $matches);
    if (isset($matches[1])) {
        $content = $matches[1];

        // Fix image and link paths for the dynamic URL structure
        // Replace ../photos/ with ../../photos/ (since we're at /blog/post/slug)
        $content = str_replace('src="../photos/', 'src="../../photos/', $content);

        // Fix links to index.html to point to index.php
        $content = str_replace('href="../index.html', 'href="../../index.php', $content);

        echo '<article class="blog-post">' . $content . '</article>';
    } else {
        echo '<p>Error loading post content</p>';
    }
    ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
