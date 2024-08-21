<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'model/Comment.php';
include_once 'includes/functions.inc.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);
$commentModel = new Comment($db, $currentUserId);

$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = $blogPost->getPost($postId);

if (!$post) {
    die('Post not found.');
}

// Increment the view count
$blogPost->incrementViewCount($postId);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        $commentModel->addComment($postId, $comment);
        header("Location: index.php?action=view_post&id=" . $postId);
        exit;
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Get comments for this post using the Comment model
$comments = $commentModel->getComments($postId);

$title = htmlspecialchars($post['title']);
?>
