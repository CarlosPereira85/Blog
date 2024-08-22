<?php

include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'includes/functions.inc.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('You need to be logged in to create a post.');
}

$currentUserId = $_SESSION['user_id'];
$db = (new Database())->getConnection();
$blogPost = new BlogPost($db, $currentUserId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $category = sanitizeInput($_POST['category']);
    $imageFile = $_FILES['image'];

    try {
        $postId = $blogPost->addPost($title, $content, $category, $imageFile);
        echo "Post added successfully with ID: $postId";
        redirect('index.php?action=home');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}