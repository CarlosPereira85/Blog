<?php
// Start the session to access session variables


// Include database connection and BlogPost class
require_once 'includes/PDOConnection.inc.php';
require_once 'model/Message.php';
require_once 'model/BlogPost.php';


// Initialize the database connection
$db = (new Database())->getConnection();

// Get the user ID from session
$userId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $userId);

$messageModel = new Message($db, $currentUserId);

// Initialize unread messages count
$unreadMessagesCount = 0;

// Check if the action is 'chat' and mark messages as read
if ($userId !== null) {
    if (isset($_GET['action']) && $_GET['action'] === 'chat') {
        // Mark messages as read
        $messageModel->markMessagesAsRead();
    }

    // Get the updated count of unread messages
    $unreadMessagesCount = $messageModel->getUnreadMessages(); // Directly use the count from getUnreadMessages() method
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to shared CSS -->
    <link rel="stylesheet" href="css/_navi_public.css"> <!-- Link to shared CSS -->
    <style>
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 0 6px;
            font-size: 12px;
            vertical-align: top;
            position: absolute;
            top: -5px;
            right: -10px;
        }
        .menu-item {
            position: relative;
            display: inline-block;
            padding: 10px;
        }
    </style>
</head>
<body>
    <ul>
        <li><a href="index.php?action=home">Home</a></li>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="index.php?action=login" onclick="return confirm('Please log in to view more details.');">Chat</a></li>
        <?php else: ?>
            <li class="menu-item">
                <a href="index.php?action=chat">Chat</a>
                <?php if ($unreadMessagesCount > 0): ?>
                    <span class="notification-badge"><?= htmlspecialchars($unreadMessagesCount); ?></span>
                <?php endif; ?>
            </li>
        <?php endif; ?>

        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Category</a>
            <div class="dropdown-content">
                <a href="index.php?action=category_technology">Technology</a>
                <a href="index.php?action=category_lifestyle">Lifestyle</a>
                <a href="index.php?action=category_food">Food</a>
            </div>
        </li>
        

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Show links for logged-in users -->
            <li><a href="index.php?action=create_post">Create Post</a></li>
            <li><a href="index.php?action=logout">Logout</a></li>
            <li><a href="index.php?action=settings">Settings</a></li>
            
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li><a href="index.php?action=admin_dashboard">Admin Dashboard</a></li>
            <?php endif; ?>
        <?php else: ?>
            <!-- Show links for not logged-in users -->
            <li><a href="index.php?action=login">Login</a></li>
            <li><a href="index.php?action=register">Register</a></li>
        <?php endif; ?>
    </ul>
</body>
</html>
