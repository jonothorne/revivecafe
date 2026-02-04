<?php
// Set 404 header
http_response_code(404);

// Page metadata
$page_title = "Page Not Found | Revive Cafe Norwich";
$page_description = "The page you're looking for couldn't be found. Visit our homepage or explore our blog to find what you need.";
$page_keywords = "revive cafe norwich, page not found";
$page_url = "https://revive-cafe.co.uk/404";
$canonical_url = "https://revive-cafe.co.uk/404";
$og_title = "Page Not Found | Revive Cafe Norwich";
$og_description = "The page you're looking for couldn't be found.";
$og_image = "https://revive-cafe.co.uk/photos/revive-cafe-logo.png";
$base_path = "";

// No special schema needed for 404
$additional_schema = '';

// Include header
include __DIR__ . '/includes/header.php';
?>
</head>
<body>
<?php include __DIR__ . '/includes/nav.php'; ?>

    <!-- 404 Section -->
    <section class="error-404">
        <div class="container">
            <div class="error-content">
                <div class="error-number">404</div>
                <h1>Oops! We Can't Find That Page</h1>
                <p class="error-message">It looks like this page took a coffee break and didn't come back. Maybe it's wandering around Norwich with a dog?</p>

                <div class="error-suggestions">
                    <h2>Here's what you can do:</h2>
                    <div class="suggestions-grid">
                        <div class="suggestion-card">
                            <span class="suggestion-icon">☕</span>
                            <h3>Visit Our Cafe</h3>
                            <p>Head to our homepage to learn about our dog-friendly community cafe</p>
                            <a href="/" class="btn btn-primary">Go Home</a>
                        </div>

                        <div class="suggestion-card">
                            <span class="suggestion-icon">📝</span>
                            <h3>Read Our Blog</h3>
                            <p>Explore stories about community impact and Norwich coffee culture</p>
                            <a href="/blog/" class="btn btn-primary">View Blog</a>
                        </div>

                        <div class="suggestion-card">
                            <span class="suggestion-icon">🐶</span>
                            <h3>Learn About Us</h3>
                            <p>Discover why we're Norwich's favorite dog-friendly cafe</p>
                            <a href="/#about" class="btn btn-primary">Our Story</a>
                        </div>
                    </div>
                </div>

                <div class="error-popular-links">
                    <h3>Popular Links</h3>
                    <ul>
                        <li><a href="/#menu">Our Menu</a></li>
                        <li><a href="/#faq">Frequently Asked Questions</a></li>
                        <li><a href="/blog/post/best-dog-friendly-cafes-norwich">Best Dog-Friendly Cafes in Norwich</a></li>
                        <li><a href="/#contact">Contact & Location</a></li>
                        <li><a href="/#impact">Community Impact</a></li>
                    </ul>
                </div>

                <div class="error-help">
                    <p><strong>Still can't find what you're looking for?</strong></p>
                    <p>Get in touch with us at <a href="mailto:office@alive.me.uk">office@alive.me.uk</a> and we'll help you out!</p>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
