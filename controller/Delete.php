<?php

require_once 'includes/PDOConnection.inc.php'; // Adjust path as necessary
require_once 'model/BlogPost.php'; // Adjust path as necessary

if (!isset($_SESSION['user_id'])) {
    die('User is not logged in.');
}

$currentUserId = $_SESSION['user_id'];
$db = (new Database())->getConnection();
$blogPost = new BlogPost($db, $currentUserId);

$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($postId <= 0) {
    die('Invalid post ID.');
}

try {
    if ($blogPost->deletePost($postId)) {
        header('Location: index.php?action=home');
        exit();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>