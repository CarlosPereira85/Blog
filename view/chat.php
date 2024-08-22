<?php
include_once "controller/Chat.php"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
</head>
<body>
    <div class="chat-wrapper">
        <div class="header">
            <span>Chat Application</span>
        </div>
        <div class="chat-content">
            <div class="user-list" id="user-list">
                <?php foreach ($users as $user): ?>
                    <div class="user-item" data-user-id="<?= $user['id']; ?>">
                        <img src="<?= htmlspecialchars($user['profile_image']); ?>" alt="<?= htmlspecialchars($user['username']); ?>" class="profile-picture">
                        <?= htmlspecialchars($user['username']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="messages" id="message-container">
                <?php if ($messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message-container <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                            <?php if ($message['sender_id'] != $_SESSION['user_id']): ?>
                                <img src="<?= htmlspecialchars($message['sender_profile_image']); ?>" alt="<?= htmlspecialchars($message['sender_username']); ?>" class="profile-picture">
                            <?php endif; ?>
                            <div class="message <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                                <div class="sender-name">
                                    <?= $message['sender_id'] == $_SESSION['user_id'] ? 'You' : htmlspecialchars($message['sender_username']); ?>
                                </div>
                                <p><?= htmlspecialchars($message['message']); ?></p>
                                <span class="timestamp"><?= htmlspecialchars($message['creation_date']); ?></span>
                            </div>
                            <?php if ($message['sender_id'] == $_SESSION['user_id']): ?>
                                <img src="<?= htmlspecialchars($message['sender_profile_image']); ?>" alt="<?= htmlspecialchars($message['sender_username']); ?>" class="profile-picture sent">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No messages to display.</p>
                <?php endif; ?>
            </div>

            <div class="message-status <?php echo isset($success) ? 'success' : (isset($error) ? 'error' : ''); ?>">
                <?php echo isset($success) ? $success : (isset($error) ? $error : ''); ?>
            </div>

            <form class="send-message-form" method="post" action="">
                <textarea name="message" id="message" placeholder="Type a message..." required></textarea>
                <input type="hidden" name="receiver_id" id="receiver_id" value="<?= $selectedUserId; ?>">
                <input type="submit" value="Send">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to scroll to the bottom of the chat container
            function scrollToBottom() {
                const messageContainer = document.getElementById('message-container');
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }

            // Scroll to the bottom when the page loads
            scrollToBottom();

            // Scroll to the bottom when a new message is sent or received
            const observer = new MutationObserver(scrollToBottom);
            observer.observe(document.getElementById('message-container'), { childList: true });

            // Handle user list click
            document.getElementById('user-list').addEventListener('click', function(e) {
                if (e.target.classList.contains('user-item')) {
                    const userId = e.target.getAttribute('data-user-id');
                    // Update the URL to include the selected user ID
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('user_id', userId);
                    window.location.href = newUrl;
                }
            });

            // Highlight the selected user
            const selectedUserId = <?= json_encode($selectedUserId); ?>;
            const userItems = document.querySelectorAll('.user-item');
            userItems.forEach(item => {
                if (item.getAttribute('data-user-id') == selectedUserId) {
                    item.classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>
