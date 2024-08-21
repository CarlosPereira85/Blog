<?php
// Start the session to access session variables


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection and Message class
require_once 'includes/PDOConnection.inc.php';
require_once 'model/Message.php';

$db = (new Database())->getConnection();
$messageModel = new Message($db, $_SESSION['user_id']);

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiverId = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    if (!empty($message) && $receiverId > 0) {
        try {
            $messageModel->sendMessage($receiverId, $message);
            $success = "Message sent successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please provide a valid message and select a user.";
    }
}

// Fetch all users for chat
$stmt = $db->prepare("SELECT id, username, profile_image FROM Users WHERE id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch chat history for the selected user
$selectedUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : (count($users) ? $users[0]['id'] : 0);
$messages = $messageModel->getMessages($selectedUserId);

// Get the logged-in user's profile data
$stmt = $db->prepare("SELECT username, profile_image FROM Users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$loggedInUser = $stmt->fetch(PDO::FETCH_ASSOC);

// Get unread messages count
$unreadMessages = $messageModel->getMessages($_SESSION['user_id']);
$unreadMessagesCount = count(array_filter($unreadMessages, function($message) {
    return $message['read_status'] == 0;
}));
?>