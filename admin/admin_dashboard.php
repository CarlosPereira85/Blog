<?php
include_once "admin/admin_controller.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
   
    <link rel="stylesheet" href="admin/admin.css">
    <link rel="stylesheet" href="css/chat.css">
    <style>

    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Home Button -->
        <a href="index.php?action=home" class="back-link">Back to Home</a>
        
        <?php if ($statusMessage): ?>
            <p class="status-message"><?= htmlspecialchars($statusMessage); ?></p>
            <?php endif; ?>
            
            <!-- Blog Post Management -->
            <h2>Unapproved Posts</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['title']); ?></td>
                            <td><?= htmlspecialchars($post['content']); ?></td>
                            <td><?= htmlspecialchars($post['category']); ?></td>
                            
                            <td>
                                <a href="index.php?action=admin_dashboard&id=<?= urlencode($post['id']); ?>&post_action=approve">Approve</a>
                                <a href="index.php?action=admin_dashboard&id=<?= urlencode($post['id']); ?>&post_action=reject">Reject</a>
                                <a href="index.php?action=view_post&id=<?= urlencode($post['id']); ?>" class="view-post">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No posts pending approval.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Chat Management -->
        <h2>Chat Messages</h2>
        <div class="chat-wrapper">
            <div class="header">
                <span>Chat Application</span>
            </div>
            <div class="chat-content">
                <div class="user-list" id="user-list">
                    <?php foreach ($users as $user): ?>
                        <div class="user-item" data-user-id="<?= $user['id']; ?>">
                            <?= htmlspecialchars($user['username']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="messages" id="message-container">
                    <?php if ($messages): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message-container <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                                <div class="message <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                                    <div class="sender-name">
                                        <?= $message['sender_id'] == $_SESSION['user_id'] ? 'You' : htmlspecialchars($message['sender_username']); ?>
                                    </div>
                                    <p><?= htmlspecialchars($message['message']); ?></p>
                                    <span class="timestamp"><?= htmlspecialchars($message['creation_date']); ?></span>
                                    <?php if ($message['sender_id'] != $_SESSION['user_id']): ?>
                                        <a href="index.php?action=admin_dashboard&user_id=<?= $selectedUserId; ?>&delete_message_id=<?= $message['id']; ?>" class="delete-message">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages to display.</p>
                    <?php endif; ?>
                </div>

                <form class="send-message-form" method="post" action="">
                    <textarea name="message" id="message" placeholder="Type a message..." required></textarea>
                    <input type="hidden" name="receiver_id" id="receiver_id" value="<?= $selectedUserId; ?>">
                    <input type="submit" value="Send">
                </form>

                <!-- Save Chat Form -->
                <form method="post" action="">
                    <input type="hidden" name="user_id" value="<?= $selectedUserId; ?>">
                    <input type="submit" name="save_chat" value="Save Chat" class="save-button">
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // JavaScript to handle chat UI
            document.querySelectorAll('.user-item').forEach(item => {
                item.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    window.location.href = `index.php?action=admin_dashboard&user_id=${userId}`;
                });
            });
        });
    </script>
</body>
</html>
