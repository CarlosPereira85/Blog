<?php
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';
require_once 'includes/functions.inc.php';

// Initialize database connection
$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;

// Fetch current user's profile information
$userProfile = null;
if ($currentUserId) {
    $stmt = $db->prepare("SELECT username, profile_image FROM Users WHERE id = ?");
    $stmt->execute([$currentUserId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Other necessary logic for your page...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script src="js/functionality.js"></script>
    <style>
        .user-profile {
            display: flex;
            align-items: center;
            margin-left: auto;
        }
        .user-profile img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .user-profile span {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Blog</h1>
        <p>Welcome to my blog where I share my thoughts and experiences.</p>
        <?php if ($currentUserId && $userProfile): ?>
            <div class="user-profile">
                <?php if (!empty($userProfile['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($userProfile['profile_image']); ?>" alt="<?= htmlspecialchars($userProfile['username']); ?>">
                <?php else: ?>
                    <img src="default-profile.png" alt="<?= htmlspecialchars($userProfile['username']); ?>">
                <?php endif; ?>
                <span><?= htmlspecialchars($userProfile['username']); ?></span>
            </div>
        <?php endif; ?>
    </header>
    <nav>
        <?php require_once "view/_navi_public.php"; ?>
    </nav>
    <main>
        <section>
            <?php
            // Load content based on the action
            switch ($action) {
                case 'home':
                    include 'view/home.php';
                    break;
                case 'post':
                    include 'view/post.php';
                    break;
                case 'category':
                    include 'view/category.php';
                    break;
                case 'about':
                    include 'view/about.php';
                    break;
                case 'login':
                    include 'view/login.php';
                    break;
                case 'logout':
                    include 'view/logout.php';
                    break;
                case 'contact':
                    include 'view/contact.php';
                    break;
                case 'register':
                    include 'view/register.php';
                    break;
                case 'create_post':
                    include 'view/create_post.php';
                    break;
                case 'update_post':
                    include 'view/update_post.php';
                    break;
                case 'delete_post':
                    include 'view/delete_post.php';
                    break;
                case 'view_post':
                    include 'view/view_post.php'; // Posts view for logged-in users
                    break;
                case 'settings':
                    include 'view/settings.php'; // Posts view for logged-in users
                    break;
                case 'category_food':
                    include 'view/category_food.php'; // Posts view for logged-in users
                    break;
                case 'chat':
                    include 'view/chat.php'; // Posts view for logged-in users
                    break;
                case 'view_message':
                    include 'view_message.php'; // Posts view for logged-in users
                    break;
                case 'admin_dashboard':
                    include 'admin/admin_dashboard.php'; // Posts view for logged-in users
                    break;
                case 'profile_image':
                    include 'profile_image.php'; // Posts view for logged-in users
                    break;

                case 'dashboard':
                    include 'dashboard_user.php'; // Posts view for logged-in users
                    break;

                    

                
                case 'category_lifestyle':
                    include 'view/category_lifestyle.php'; // Posts view for logged-in users
                    break;
                        
                case 'category_technology':
                    include 'view/category_technology.php'; // Posts view for logged-in users
                    break;
                default:
                    include 'view/home.php';
                    break;
            }
            ?>
        </section>
    </main>
    <footer>
        <nav>
            <?php require_once "view/_meta_navi.php"; ?>
        </nav>
    </footer>
</body>
</html>
