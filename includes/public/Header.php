<?php
// This file is included by CoreEssentials->header($title)
// So, $title variable is available in this scope.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($title); ?></title>
    <?php // Potentially other common head elements, like CSS links ?>
</head>
<body>
<?php // The rest of the original header content, if any, can follow ?>
<?php // For instance, if the original "Header " was meaningful site branding: ?>
<header>
    <h1><?php echo esc_html($title); ?> - Main Site</h1> <?php // Example usage of title again ?>
     <?php // Original: echo "Header "; ?>
</header>