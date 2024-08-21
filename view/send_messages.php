<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection and BlogPost class
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiverId = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    // Create database connection and BlogPost instance
    $db = (new Database())->getConnection();
    $blogPost = new BlogPost($db, $_SESSION['user_id']);

    try {
        // Send the message
        $blogPost->sendMessage($receiverId, $message);
        echo "Message sent successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
</head>
<body>
    <form method="post" action="">
        <label for="receiver_id">Select User:</label>
        <select name="receiver_id" id="receiver_id">
            <?php
            // Fetch users for the dropdown
            $db = (new Database())->getConnection();
            $stmt = $db->query("SELECT id, username FROM Users");
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$user['id']}'>{$user['username']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="message">Message:</label>
        <textarea name="message" id="message" required></textarea>
        <br>
        <input type="submit" value="Send Message">
    </form>
</body>
</html>
