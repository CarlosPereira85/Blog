<?php
require_once 'includes/PDOConnection.inc.php';
 // Ensure session is started

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$db = (new Database())->getConnection();

// Fetch user data
$stmt = $db->prepare("SELECT username, profile_image FROM Users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'bilder/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if it does not exist
        }

        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
        $fileExtension = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Validate file type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                // Update profile image path in database
                $stmt = $db->prepare("UPDATE Users SET profile_image = ? WHERE id = ?");
                $stmt->execute([$uploadFile, $userId]);
                echo "<p>Profile image updated successfully.</p>";
            } else {
                echo "<p>Failed to upload image. Check directory permissions.</p>";
            }
        } else {
            echo "<p>Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.</p>";
        }
    }

    // Handle password change
    if (!empty($_POST['new_password'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE Users SET password = ? WHERE id = ?");
        $stmt->execute([$newPassword, $userId]);
        echo "<p>Password updated successfully.</p>";
    }

    // Handle account deletion
    if (isset($_POST['delete_account'])) {
        // Delete user-related blog posts first
        $stmt = $db->prepare("DELETE FROM BlogPosts WHERE user_id = ?");
        $stmt->execute([$userId]);

        // Delete the user
        $stmt = $db->prepare("DELETE FROM Users WHERE id = ?");
        $stmt->execute([$userId]);

        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/settings.css">
</head>
<body>
    <div class="container">
        <h1>Settings</h1>

        <!-- Profile Image Upload Form -->
        <form method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Profile Image</legend>
                <img src="<?= htmlspecialchars($user['profile_image'] ?? 'bilder/default-profile.png'); ?>" alt="Profile Image" style="width: 100px; height: 100px;">
                <input type="file" name="profile_image" accept="image/*">
                <button type="submit">Upload Image</button>
            </fieldset>
        </form>

        <!-- Change Password Form -->
        <form method="post">
            <fieldset>
                <legend>Change Password</legend>
                <input type="password" name="new_password" placeholder="New Password" required>
                <button type="submit">Change Password</button>
            </fieldset>
        </form>

        <!-- Delete Account Form -->
        <form method="post">
            <fieldset>
                <legend>Delete Account</legend>
                <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
            </fieldset>
        </form>
    </div>
</body>
</html>
