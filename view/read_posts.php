<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'includes/functions.inc.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'];
$blogPost = new BlogPost($db, $currentUserId);

$posts = $blogPost->getAllPosts();

foreach ($posts as $post) {
    echo '<h2>' . htmlspecialchars($post['title']) . '</h2>';
    echo '<p>' . nl2br(htmlspecialchars($post['content'])) . '</p>';
    echo '<p>Created on: ' . htmlspecialchars($post['creation_date']) . '</p>';
    echo '<p>Views: ' . htmlspecialchars($post['views_count']) . '</p>';
    echo '<a href="view_post.php?id=' . urlencode($post['id']) . '">View Post</a><br>';
    
    if ($post['user_id'] == $currentUserId) {
        echo '<a href="update_post.php?id=' . urlencode($post['id']) . '">Edit Post</a> | ';
        echo '<a href="delete_post.php?id=' . urlencode($post['id']) . '">Delete Post</a><br>';
    }
}
?>
