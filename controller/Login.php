<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/User.php';
include_once 'includes/functions.inc.php';


$db = (new Database())->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = sanitizeInput($_POST['login']); // Can be username or email
    $password = sanitizeInput($_POST['password']);

    $authenticatedUser = $user->authenticate($login, $password);

    if ($authenticatedUser) {
        $_SESSION['user_id'] = $authenticatedUser['id'];
        $_SESSION['username'] = $authenticatedUser['username'];
        $_SESSION['is_admin'] = $authenticatedUser['is_admin']; // Store admin status

        // Check if the user has set a profile image
        $stmt = $db->prepare("SELECT profile_image FROM Users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userProfile['profile_image']) {
            // Redirect to the profile image upload page
            redirect('index.php?action=profile_image');
        } else {
            // Redirect to the home page or dashboard
            redirect('index.php?action=home');
        }
    } else {
        echo 'Invalid username/email or password';
    }
}
?>
