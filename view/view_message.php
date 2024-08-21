<?php


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection and BlogPost class
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

$db = (new Database())->getConnection();
$blogPost = new BlogPost($db, $_SESSION['user_id']);

// Mark messages as read
$blogPost->markMessagesAsRead();

// Fetch all messages for the logged-in user
$messages = $blogPost->getMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Messages</h1>
    <table>
        <tr>
            <th>Sender</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= htmlspecialchars($message['sender_username']); ?></td>
                <td><?= htmlspecialchars($message['message']); ?></td>
                <td><?= htmlspecialchars($message['creation_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
