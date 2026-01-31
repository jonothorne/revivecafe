<?php
// Page metadata
$page_title = "Revive Cafe Norwich | Dog-Friendly Community Cafe near Nelson Street";
$page_description = "Dog-friendly community cafe in Norwich near Nelson Street. Quality coffee & food at Revive Cafe, where every purchase supports local community work through Alive Church.";
$page_keywords = "cafe norwich, dog friendly cafe norwich, community cafe norwich, nelson street cafe, alive church norwich, cafe near nelson pub, norwich social enterprise cafe, revive cafe";
$page_url = "https://revive-cafe.co.uk/";
$canonical_url = "https://revive-cafe.co.uk/";
$og_title = "Revive Cafe Norwich | Dog-Friendly Community Cafe near Nelson Street";
$og_description = "Dog-friendly social enterprise cafe in Norwich near Nelson Street. Quality coffee where 100% of proceeds support local community through Alive Church.";
$og_image = "https://revive-cafe.co.uk/photos/revive-cafe-logo.png";
$base_path = "";

// Include header
include __DIR__ . '/includes/header.php';

// Include homepage schema
include __DIR__ . '/includes/home-schema.php';
?>
</head>
<body>
<?php include __DIR__ . '/includes/nav.php'; ?>
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <img src="photos/revive-cafe-logo.png" alt="Revive Cafe Norwich Logo - Social Enterprise Dog-Friendly Cafe" class="hero-logo" loading="eager">
            <h1 class="hero-title">WARM UP YOUR WINTER</h1>
            <p class="hero-subtitle">POP IN & REVIVE</p>
            <p class="hero-tagline">Your dog-friendly Norwich cafe near Nelson Street</p>
            <a href="#menu" class="btn btn-primary">Explore Our Menu</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Your Local Norwich Community Cafe Near Nelson Street</h2>
                    <p>What started as Revive Coffee Hut has blossomed into Revive Cafe, a dog-friendly community cafe in the heart of Norwich. We've moved from our beloved outdoor hut into a warm, welcoming indoor space at Alive House where we can serve you better, rain or shine.</p>
                    <p class="highlight">Run by Alive Church Norwich, all proceeds go towards supporting local people in need. Every cup of coffee you enjoy at our Nelson Street cafe helps us make a real difference in the Norwich community.</p>
                    <p class="lead highlight-box">Your coffee funds support for those in need.</p>

                    <div class="features">
                        <div class="feature">
                            <span class="feature-icon">☕</span>
                            <h3>Quality Coffee</h3>
                            <p>Expertly crafted drinks made with care</p>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">🐶</span>
                            <h3>Dog Friendly</h3>
                            <p>Your furry friends are always welcome</p>
                        </div>
                        <div class="feature">
                            <span class="feature-icon">❤️</span>
                            <h3>Community First</h3>
                            <p>A space for Norwich locals to connect</p>
                        </div>
                    </div>
                </div>
                <div class="about-images">
                    <div class="about-image">
                        <img src="photos/revive-community-cafe-norwich.jpg" alt="Revive Cafe Indoor Space Norwich - Warm Community Cafe on Nelson Street" loading="lazy">
                        <p class="image-caption">Your new cozy spot in Norwich</p>
                    </div>
                    <div class="about-image">
                        <img src="photos/jenna-serving-in-revive-coffee-hut-in-norwich.jpg" alt="Friendly Barista Service at Revive Cafe Norwich - Welcoming Staff" loading="lazy">
                        <p class="image-caption">Friendly faces ready to serve you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu">
        <div class="container">
            <h2 class="section-title">What We Serve</h2>
            <p class="section-subtitle">Crafted with love, served with care</p>

            <!-- Menu Showcase -->
            <div class="menu-showcase">
                <div class="menu-showcase-item">
                    <img src="photos/norwich-dreamy-hot-chocolate-revive-cafe.jpg" alt="Indulgent Hot Chocolate Norwich - Revive Cafe Specialty Drink" loading="lazy">
                    <h3>Indulgent Hot Chocolate</h3>
                    <p>Rich, creamy, and absolutely dreamy</p>
                </div>
                <div class="menu-showcase-item">
                    <img src="photos/beautiful-fresh-cupcakes-norwich.jpg" alt="Fresh Baked Cupcakes Norwich - Homemade Treats at Revive Cafe" loading="lazy">
                    <h3>Fresh Baked Treats</h3>
                    <p>Homemade goodness daily</p>
                </div>
                <div class="menu-showcase-item">
                    <img src="photos/hot-chocolate-and-mexican-style-scotch-egg-norwich-revive-cafe.jpg" alt="Coffee and Food Pairings Norwich - Revive Cafe Menu" loading="lazy">
                    <h3>Food & Drinks</h3>
                    <p>Perfect pairings for any time of day</p>
                </div>
            </div>

            <div class="menu-grid">
                <div class="menu-category">
                    <h3>Coffee & Hot Drinks</h3>
                    <div class="menu-items">
                        <div class="menu-item">
                            <h4>Espresso</h4>
                            <p>Rich and bold, the perfect pick-me-up</p>
                        </div>
                        <div class="menu-item">
                            <h4>Cappuccino</h4>
                            <p>Smooth espresso with velvety steamed milk</p>
                        </div>
                        <div class="menu-item">
                            <h4>Latte</h4>
                            <p>Creamy and comforting in every sip</p>
                        </div>
                        <div class="menu-item">
                            <h4>Flat White</h4>
                            <p>Strong coffee with silky microfoam</p>
                        </div>
                        <div class="menu-item">
                            <h4>Specialty Teas</h4>
                            <p>A curated selection of premium teas</p>
                        </div>
                        <div class="menu-item">
                            <h4>Hot Chocolate</h4>
                            <p>Rich, indulgent, and topped with cream</p>
                        </div>
                    </div>
                </div>

                <div class="menu-category">
                    <h3>Cold Drinks</h3>
                    <div class="menu-items">
                        <div class="menu-item">
                            <h4>Iced Coffee</h4>
                            <p>Refreshingly smooth and perfectly chilled</p>
                        </div>
                        <div class="menu-item">
                            <h4>Iced Latte</h4>
                            <p>Cool and creamy coffee perfection</p>
                        </div>
                        <div class="menu-item">
                            <h4>Frappe</h4>
                            <p>Blended iced coffee treat</p>
                        </div>
                        <div class="menu-item">
                            <h4>Smoothies</h4>
                            <p>Fresh fruit blended to perfection</p>
                        </div>
                    </div>
                </div>

                <div class="menu-category">
                    <h3>Food & Treats</h3>
                    <div class="menu-items">
                        <div class="menu-item">
                            <h4>Fresh Pastries</h4>
                            <p>Daily selection of baked goods</p>
                        </div>
                        <div class="menu-item">
                            <h4>Sandwiches & Paninis</h4>
                            <p>Made fresh with quality ingredients</p>
                        </div>
                        <div class="menu-item">
                            <h4>Cakes & Treats</h4>
                            <p>Homemade sweetness to brighten your day</p>
                        </div>
                        <div class="menu-item">
                            <h4>Breakfast Options</h4>
                            <p>Start your day right with us</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="news">
        <div class="container">
            <h2 class="section-title">Latest News</h2>
            <p class="section-subtitle">Stories from our Norwich community</p>

            <div class="news-grid">
                <article class="news-card">
                    <div class="news-image">
                        <img src="photos/revive-community-cafe-norwich.jpg" alt="Revive Cafe Norwich Indoor Space - Community Cafe Nelson Street" loading="lazy">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">January 2026</span>
                            <span class="news-category">Community</span>
                        </div>
                        <h3><a href="blog/post/welcome-indoor-space">Welcome to Our New Indoor Space!</a></h3>
                        <p>We're thrilled to announce that Revive Cafe has moved from our beloved outdoor coffee hut into a warm, welcoming indoor space at Alive House on Nelson Street. This exciting move means we can serve our Norwich community better, rain or shine, while continuing our mission to support local people in need.</p>
                        <a href="blog/post/welcome-indoor-space" class="read-more">Read More →</a>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-image">
                        <img src="photos/community-action-norwich.jpg" alt="Social Enterprise Community Impact Norwich - Revive Cafe Alive Church" loading="lazy">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">December 2025</span>
                            <span class="news-category">Impact</span>
                        </div>
                        <h3><a href="blog/post/2025-community-impact">How Your Coffee Helped Norwich This Year</a></h3>
                        <p>As we reflect on 2025, we're amazed by the impact our community has made together. Every cup of coffee purchased at our dog-friendly Norwich cafe has directly supported local families, provided safe spaces, and funded community initiatives through Alive Church.</p>
                        <a href="blog/post/2025-community-impact" class="read-more">Read More →</a>
                    </div>
                </article>

                <article class="news-card">
                    <div class="news-image">
                        <img src="photos/dog-friendly-cafe-in-norwich.jpg" alt="Dog-Friendly Cafe Norwich - Dogs Welcome at Revive Cafe Nelson Street" loading="lazy">
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">November 2025</span>
                            <span class="news-category">Community</span>
                        </div>
                        <h3><a href="blog/post/dog-friendly-commitment">Why We're Dog-Friendly (And Always Will Be)</a></h3>
                        <p>At Revive Cafe, your furry friends aren't just tolerated—they're celebrated! Learn about our commitment to being Norwich's most welcoming dog-friendly cafe and why we believe everyone deserves a warm welcome, paws and all.</p>
                        <a href="blog/post/dog-friendly-commitment" class="read-more">Read More →</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Community Impact Section -->
    <section id="impact" class="impact">
        <div class="container">
            <h2 class="section-title">Our Community Impact</h2>
            <p class="section-subtitle">How your coffee makes a difference in Norwich</p>

            <div class="impact-content">
                <div class="impact-text">
                    <h3>Every Cup Supports Norwich Residents</h3>
                    <p>Revive Cafe is more than just a coffee shop in Norwich - we're a social enterprise run by Alive Church. 100% of our proceeds go directly toward supporting people in need across our local Norwich community.</p>

                    <h3>What We Support</h3>
                    <ul class="impact-list">
                        <li>Emergency food and essentials for Norwich families in crisis</li>
                        <li>Safe space and support services at Alive House</li>
                        <li>Community events and activities in the Nelson Street area</li>
                        <li>Mental health and wellbeing support for local residents</li>
                        <li>Youth programs and community development initiatives</li>
                    </ul>

                    <h3>A Safe Space for Everyone</h3>
                    <p>Located at Alive House on the corner of Nelson Street and Arms Street, we're proud to be a <strong>dog-friendly cafe in Norwich</strong> where everyone is welcome. Whether you're popping in for a coffee, need a quiet space to work, or want to connect with others in the community, our doors are open.</p>

                    <p class="impact-callout">When you choose Revive Cafe, you're not just getting great coffee - you're investing in a stronger, more connected Norwich community.</p>
                </div>

                <div class="impact-stats">
                    <div class="stat-box">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Proceeds Support Local Community</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">🐶</div>
                        <div class="stat-label">Dog-Friendly Welcome</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">🤝</div>
                        <div class="stat-label">Community First</div>
                    </div>
                </div>
            </div>

            <div class="impact-cta">
                <h3>Want to Learn More About Our Work?</h3>
                <p>Connect with us on social media to see how your coffee is making a difference in Norwich</p>
                <div class="impact-social-links">
                    <a href="https://instagram.com/revive.coffee.hut" target="_blank" class="social-link">Instagram</a>
                    <a href="https://tiktok.com/@revive.coffee.hut" target="_blank" class="social-link">TikTok</a>
                    <a href="https://www.facebook.com/profile.php?id=61574707858861" target="_blank" class="social-link">Facebook</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Everything you need to know about visiting Revive Cafe</p>

            <div class="faq-grid">
                <div class="faq-item">
                    <h3>Are dogs allowed at Revive Cafe?</h3>
                    <p>Absolutely! We're a proudly dog-friendly cafe in Norwich. Your furry friends are genuinely welcome - not just tolerated. We provide water bowls and treats (with your permission), and there's plenty of space for dogs to relax while you enjoy your coffee. All we ask is that dogs stay on a lead while inside.</p>
                </div>

                <div class="faq-item">
                    <h3>Where is Revive Cafe located in Norwich?</h3>
                    <p>We're located at Alive House on the corner of Nelson Street and Arms Street in Norwich, directly opposite The Nelson pub. Our indoor space is warm and welcoming, perfect for popping in rain or shine. We're easily accessible from Norwich city centre.</p>
                </div>

                <div class="faq-item">
                    <h3>What are Revive Cafe's opening hours?</h3>
                    <p>We're open Wednesday to Sunday: Wednesday-Thursday 10am-3pm, Friday 10am-12noon, Saturday 10am-3pm, and Sunday 12:45pm-2pm. We're closed Monday and Tuesday. Follow us on social media for any updates or special opening times.</p>
                </div>

                <div class="faq-item">
                    <h3>Is there WiFi at Revive Cafe?</h3>
                    <p>Yes! We offer free WiFi to all customers. Whether you're working remotely, studying, or just browsing, you're welcome to use our internet connection while enjoying quality coffee in a comfortable environment.</p>
                </div>

                <div class="faq-item">
                    <h3>Is there parking near Revive Cafe Norwich?</h3>
                    <p>There's street parking available on Nelson Street and surrounding roads. As we're located near Norwich city centre, we're also easily accessible by bus, bike, or on foot. Many customers incorporate us into their daily dog walking route!</p>
                </div>

                <div class="faq-item">
                    <h3>What makes Revive Cafe a social enterprise?</h3>
                    <p>Revive Cafe is run by Alive Church Norwich, and 100% of our proceeds go towards supporting local people in need. Every coffee you buy funds emergency support for families, mental health services, youth programs, and community development. Your coffee genuinely makes a difference in the Norwich community.</p>
                </div>

                <div class="faq-item">
                    <h3>Do you have vegan and vegetarian options?</h3>
                    <p>Yes! We offer plant-based milk alternatives for all our coffee drinks, and we have vegetarian and vegan food options available. Just ask our friendly staff what's available on the day you visit.</p>
                </div>

                <div class="faq-item">
                    <h3>Can I book Revive Cafe for private events?</h3>
                    <p>We're located within Alive House which hosts various community events. For enquiries about using our space or Alive House facilities, please contact us at office@alive.me.uk and we'll be happy to discuss options with you.</p>
                </div>

                <div class="faq-item">
                    <h3>What payment methods do you accept?</h3>
                    <p>We accept both cash and card payments (credit and debit cards). We want to make it as easy as possible for you to enjoy great coffee and support the Norwich community at the same time.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Visit Us</h2>
                    <div class="info-block">
                        <h3>Location</h3>
                        <p>Alive House<br>
                        Corner of Nelson Street and Arms Street<br>
                        Opposite The Nelson pub<br>
                        Norwich, UK</p>
                    </div>
                    <div class="info-block">
                        <h3>Opening Hours</h3>
                        <ul class="hours-list">
                            <li><span>Monday</span><span>Closed</span></li>
                            <li><span>Tuesday</span><span>Closed</span></li>
                            <li><span>Wednesday</span><span>10am - 3pm</span></li>
                            <li><span>Thursday</span><span>10am - 3pm</span></li>
                            <li><span>Friday</span><span>10am - 12 noon</span></li>
                            <li><span>Saturday</span><span>10am - 3pm</span></li>
                            <li><span>Sunday</span><span>12:45pm - 2pm</span></li>
                        </ul>
                    </div>
                    <div class="info-block">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="https://instagram.com/revive.coffee.hut" target="_blank" class="social-link">Instagram</a>
                            <a href="https://tiktok.com/@revive.coffee.hut" target="_blank" class="social-link">TikTok</a>
                            <a href="https://www.facebook.com/profile.php?id=61574707858861" target="_blank" class="social-link">Facebook</a>
                        </div>
                    </div>
                </div>
                <div class="contact-form-wrapper">
                    <h2>Get In Touch</h2>
                    <form id="contactForm" class="contact-form">
                        <input type="hidden" name="access_key" value="76460ee4-a78a-4e75-a402-9140241636ae">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone (optional)</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                        <div id="formMessage" class="form-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>


<?php include __DIR__ . '/includes/footer.php'; ?>
