<?php

require_once 'includes/PDOConnection.inc.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$db = (new Database())->getConnection();

// Fetch user data
$stmt = $db->prepare("SELECT profile_image, profile_image_set FROM Users WHERE id = ?");
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
                // Update profile image path in database and set flag
                $stmt = $db->prepare("UPDATE Users SET profile_image = ?, profile_image_set = TRUE WHERE id = ?");
                $stmt->execute([$uploadFile, $userId]);
                $user['profile_image'] = $uploadFile; // Update the user data array
                $uploadSuccess = true;
            } else {
                $uploadError = "Failed to upload image. Check directory permissions.";
            }
        } else {
            $uploadError = "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Image</title>
    <link rel="stylesheet" href="css/settings.css">
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .overlay-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .container {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile Image</h1>

        <!-- Profile Image Upload Form -->
        <form method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Profile Image</legend>
                <img src="<?= htmlspecialchars($user['profile_image'] ?? 'bilder/default-profile.png'); ?>" alt="Profile Image" style="width: 100px; height: 100px;">
                <input type="file" name="profile_image" accept="image/*">
                <button type="submit">Upload Image</button>
            </fieldset>
        </form>

        <!-- Show upload success or error messages -->
        <?php if (isset($uploadSuccess) && $uploadSuccess): ?>
            <p>Profile image updated successfully.</p>
            <script>window.location.href = 'index.php';</script> <!-- Redirect to dashboard or home page -->
        <?php elseif (isset($uploadError)): ?>
            <p><?= htmlspecialchars($uploadError); ?></p>
        <?php endif; ?>

    </div>

    <!-- Overlay for image upload prompt -->
    <div id="imageUploadOverlay" class="overlay">
        <div class="overlay-content">
            <h2>Update Your Profile Image</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="profile_image" accept="image/*" required>
                <button type="submit">Upload</button>
            </form>
            <button onclick="closeOverlay()">Close</button>
        </div>
    </div>

    <script>
        // Function to show the overlay
        function showOverlay() {
            document.getElementById('imageUploadOverlay').style.display = 'flex';
        }

        // Function to close the overlay
        function closeOverlay() {
            document.getElementById('imageUploadOverlay').style.display = 'none';
        }

        // Show the overlay if the user needs to upload an image
        window.onload = function() {
            if (<?php echo json_encode(!$user['profile_image_set']); ?>) {
                showOverlay();
            }
        }
    </script>
</body>
</html>
