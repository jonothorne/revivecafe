<?php
// Load posts data
$postsFile = __DIR__ . '/../blog-data/posts.json';
$posts = json_decode(file_get_contents($postsFile), true);

// Sort by date (newest first)
usort($posts, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Page metadata
$page_title = "Blog | Revive Cafe Norwich - Community Stories & Coffee Culture";
$page_description = "Read stories from Revive Cafe Norwich about community impact, dog-friendly cafes, social enterprise, and coffee culture. Discover what makes our Nelson Street cafe special.";
$page_keywords = "revive cafe blog, norwich cafe blog, dog friendly cafe norwich, community cafe stories, social enterprise blog, norwich coffee culture";
$page_url = "https://revive-cafe.co.uk/blog/";
$canonical_url = "https://revive-cafe.co.uk/blog/";
$og_title = "Revive Cafe Blog";
$og_description = "Read stories from Revive Cafe Norwich about community impact, dog-friendly cafes, social enterprise, and coffee culture.";
$og_image = "https://revive-cafe.co.uk/photos/revive-cafe-logo.png";
$base_path = "../";

// Schema markup
$additional_schema = '
    <!-- Schema.org CollectionPage Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "Revive Cafe Blog",
      "description": "Stories, guides, and insights from Revive Cafe Norwich - your dog-friendly community cafe on Nelson Street",
      "url": "https://revive-cafe.co.uk/blog/",
      "publisher": {
        "@type": "Organization",
        "name": "Revive Cafe",
        "logo": {
          "@type": "ImageObject",
          "url": "https://revive-cafe.co.uk/photos/revive-cafe-logo.png"
        }
      }
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
            <a href="<?php echo $base_path; ?>">Home</a> / <span>Blog</span>
        </div>
    </div>

    <!-- Blog Index -->
    <section class="blog-index">
        <div class="container">
            <div class="blog-index-header">
                <h1>Revive Cafe Blog</h1>
                <p class="blog-index-intro">Stories from our Norwich community cafe - celebrating coffee culture, community impact, and dog-friendly hospitality</p>
            </div>

            <div class="blog-categories">
                <button class="category-filter active" data-category="all">All Posts</button>
                <button class="category-filter" data-category="community">Community</button>
                <button class="category-filter" data-category="impact">Impact</button>
                <button class="category-filter" data-category="guide">Guides</button>
            </div>

            <div class="blog-grid">
                <?php foreach ($posts as $post): ?>
                <article class="blog-card" data-category="<?php echo htmlspecialchars($post['category']); ?>">
                    <div class="blog-card-image">
                        <img src="../photos/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy">
                        <span class="blog-card-category"><?php echo ucfirst(htmlspecialchars($post['category'])); ?></span>
                    </div>
                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span class="blog-card-date"><?php echo date('F j, Y', strtotime($post['date'])); ?></span>
                        </div>
                        <h2><a href="post/<?php echo htmlspecialchars($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                        <p><?php echo htmlspecialchars($post['excerpt']); ?></p>
                        <a href="post/<?php echo htmlspecialchars($post['slug']); ?>" class="blog-card-link">Read More →</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="blog-cta">
                <h2>Stay Updated</h2>
                <p>Follow us on social media for the latest stories, community updates, and behind-the-scenes glimpses of life at Revive Cafe Norwich.</p>
                <div class="blog-cta-links">
                    <a href="https://instagram.com/revive.coffee.hut" target="_blank" class="social-link">Instagram</a>
                    <a href="https://tiktok.com/@revive.coffee.hut" target="_blank" class="social-link">TikTok</a>
                    <a href="https://www.facebook.com/profile.php?id=61574707858861" target="_blank" class="social-link">Facebook</a>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        // Blog category filtering
        const filterButtons = document.querySelectorAll('.category-filter');
        const blogCards = document.querySelectorAll('.blog-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const category = button.dataset.category;

                // Filter blog cards
                blogCards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
