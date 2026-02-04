    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Revive Cafe. All rights reserved.</p>
            <p class="footer-tagline">Brewing community, one cup at a time.</p>
        </div>
    </footer>

    <?php
    // Cache-busting for JS - use file modification time
    $jsPath = isset($base_path) ? $base_path : '';
    $jsFile = __DIR__ . '/../script.js';
    $jsVersion = file_exists($jsFile) ? '?v=' . filemtime($jsFile) : '';
    ?>
    <script src="<?php echo $jsPath; ?>script.js<?php echo $jsVersion; ?>"></script>
</body>
</html>
