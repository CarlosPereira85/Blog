<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

// Include database connection and BlogPost class
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

$db = (new Database())->getConnection();
$blogPost = new BlogPost($db, $_SESSION['user_id']);

$selectedUserId = intval($_GET['user_id']);
$messages = $blogPost->getMessages($selectedUserId);

echo json_encode($messages);
?>
