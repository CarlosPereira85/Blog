<?php
// Function to sanitize user input
function sanitizeInput($data) {
    // Trim whitespace from the beginning and end of the string
    // Strip HTML and PHP tags
    // Convert special characters to HTML entities
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>
