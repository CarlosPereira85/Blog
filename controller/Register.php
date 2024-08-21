<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/User.php';
include_once 'includes/functions.inc.php';

$db = (new Database())->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $isAdmin = isset($_POST['is_admin']) ? 1 : 0;  // Check if the user is an admin

    if ($user->register($username, $email, $password, $isAdmin)) {
        redirect('index.php?action=login');
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>