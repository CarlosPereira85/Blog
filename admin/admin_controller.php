<?php

include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'model/Message.php';

// Ensure the user is logged in and has admin privileges

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "You do not have admin privileges.";
    exit;
}

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'];
$blogPost = new BlogPost($db, $currentUserId);
$messageModel = new Message($db, $currentUserId);

// Handle approval or rejection of posts
if (isset($_GET['id']) && isset($_GET['post_action'])) { 
    $postId = intval($_GET['id']);
    $postAction = $_GET['post_action'];

    try {
        if ($postAction === 'approve') {
            if ($blogPost->approvePost($postId)) {
                header('Location: index.php?action=admin_dashboard&status=approved');
                exit;
            } else {
                echo "Failed to approve the post.";
            }
        } elseif ($postAction === 'reject') {
            if ($blogPost->rejectPost($postId)) {
                header('Location: index.php?action=admin_dashboard&status=rejected');
                exit;
            } else {
                echo "Failed to reject the post.";
            }
        } else {
            echo "Invalid action.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// Handle message deletion
if (isset($_GET['delete_message_id'])) {
    $messageId = intval($_GET['delete_message_id']);
    if ($messageModel->deleteMessage($messageId)) {
        header('Location: index.php?action=admin_dashboard&user_id=' . $selectedUserId);
        exit;
    } else {
        echo "Failed to delete the message.";
    }
}

// Handle saving chat messages
if (isset($_POST['save_chat'])) {
    $selectedUserId = intval($_POST['user_id']);
    $messages = $messageModel->getMessages($selectedUserId);
    
    if ($messages) {
        $filename = 'data/chat_' . $selectedUserId . '_' . date('Ymd_His') . '.txt';
        $fileContent = '';
        
        foreach ($messages as $message) {
            $sender = $message['sender_id'] == $_SESSION['user_id'] ? 'You' : htmlspecialchars($message['sender_username']);
            $fileContent .= "[{$message['creation_date']}] {$sender}: " . htmlspecialchars($message['message']) . PHP_EOL;
        }
        
        if (file_put_contents($filename, $fileContent)) {
            $statusMessage = "Chat saved successfully to $filename.";
        } else {
            $statusMessage = "Failed to save chat.";
        }
    } else {
        $statusMessage = "No messages to save.";
    }
}

// Fetch unapproved posts
$posts = $blogPost->getUnapprovedPosts();

// Handle chat messages
$users = $db->query("SELECT id, username FROM Users WHERE id != $currentUserId")->fetchAll(PDO::FETCH_ASSOC);
$selectedUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : (count($users) ? $users[0]['id'] : 0);
$messages = $messageModel->getMessages($selectedUserId);

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['receiver_id'])) {
    $message = trim($_POST['message']);
    $receiverId = intval($_POST['receiver_id']);

    if (!empty($message) && $receiverId > 0) {
        $messageModel->sendMessage($receiverId, $message);
        header('Location: index.php?action=admin_dashboard&user_id=' . $receiverId);
        exit;
    }
}

$statusMessage = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'approved':
            $statusMessage = 'Post approved successfully.';
            break;
        case 'rejected':
            $statusMessage = 'Post rejected successfully.';
            break;
        default:
            $statusMessage = 'Action failed.';
            break;
    }
}