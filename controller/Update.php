

<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'includes/functions.inc.php';



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

// Fetch post details for editing
$post = $blogPost->getPostById($postId);

if (!$post) {
    die('Post not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageFile = isset($_FILES['image']) ? $_FILES['image'] : null;

    try {
        if ($blogPost->updatePost($postId, $title, $content, $imageFile)) {
            header('Location: index.php?action=home');
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
