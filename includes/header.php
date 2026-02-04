<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Revive Cafe Norwich | Dog-Friendly Community Cafe near Nelson Street'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Dog-friendly community cafe in Norwich near Nelson Street. Quality coffee & food at Revive Cafe, where every purchase supports local community work through Alive Church.'; ?>">
    <meta name="keywords" content="<?php echo isset($page_keywords) ? $page_keywords : 'cafe norwich, dog friendly cafe norwich, community cafe norwich, nelson street cafe, alive church norwich, cafe near nelson pub, norwich social enterprise cafe, revive cafe'; ?>">
    <meta name="author" content="Revive Cafe">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($page_url) ? $page_url : 'https://revive-cafe.co.uk/'; ?>">
    <meta property="og:title" content="<?php echo isset($og_title) ? $og_title : 'Revive Cafe Norwich | Dog-Friendly Community Cafe near Nelson Street'; ?>">
    <meta property="og:description" content="<?php echo isset($og_description) ? $og_description : 'Dog-friendly social enterprise cafe in Norwich near Nelson Street. Quality coffee where 100% of proceeds support local community through Alive Church.'; ?>">
    <meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'https://revive-cafe.co.uk/photos/revive-cafe-logo.png'; ?>">
    <meta property="og:locale" content="en_GB">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo isset($page_url) ? $page_url : 'https://revive-cafe.co.uk/'; ?>">
    <meta property="twitter:title" content="<?php echo isset($og_title) ? $og_title : 'Revive Cafe Norwich | Dog-Friendly Community Cafe near Nelson Street'; ?>">
    <meta property="twitter:description" content="<?php echo isset($og_description) ? $og_description : 'Dog-friendly social enterprise cafe in Norwich near Nelson Street. Quality coffee where 100% of proceeds support local community through Alive Church.'; ?>">
    <meta property="twitter:image" content="<?php echo isset($og_image) ? $og_image : 'https://revive-cafe.co.uk/photos/revive-cafe-logo.png'; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo isset($canonical_url) ? $canonical_url : 'https://revive-cafe.co.uk/'; ?>">

    <?php
    // Cache-busting for CSS - use file modification time
    $cssPath = isset($base_path) ? $base_path : '';
    $cssFile = __DIR__ . '/../styles.css';
    $cssVersion = file_exists($cssFile) ? '?v=' . filemtime($cssFile) : '';
    ?>
    <link rel="stylesheet" href="<?php echo $cssPath; ?>styles.css<?php echo $cssVersion; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <?php if (isset($additional_schema)): ?>
    <?php echo $additional_schema; ?>
    <?php endif; ?>
</head>
<body>
