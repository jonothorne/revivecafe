<?php
/**
 * Admin Dashboard
 * Main landing page after login - redirects to posts listing
 */

require_once 'config.php';

// Require authentication
requireAuth();

// For now, redirect to posts listing
// Later we can add a dashboard with stats
header('Location: posts.php');
exit;
