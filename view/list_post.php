<?php
session_start();
include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'includes/functions.inc.php';

// Get the database connection
$db = (new Database())->getConnection();
$blogPost = new BlogPost($db, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);

// Fetch all posts
$posts = $blogPost->getAllPosts();

foreach ($posts as $post) {
    echo '<h2>' . htmlspecialchars($post['title']) . '</h2>';
    echo '<p>' . nl2br(htmlspecialchars(substr($post['content'], 0, 100))) . '...</p>'; // Show a snippet of the content
    echo '<a href="read_posts.php?id=' . urlencode($post['id']) . '">View Post</a><br>';
}
?>
