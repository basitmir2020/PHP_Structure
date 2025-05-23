<?php
// This file is included by CoreEssentials->header($title)
// So, $title variable is available in this scope.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($title); ?> - Admin</title>
    <?php // Potentially other common head elements ?>
</head>
<body>
<header>
    <h1>Admin: <?php echo esc_html($title); ?></h1>
    <?php // Original: echo "Header "; ?>
</header>
