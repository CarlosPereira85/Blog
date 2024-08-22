<?php
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);

// Fetch approved posts only
$posts = $blogPost->getAllPosts(true);

$title = "My Blog";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?></title>
   <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?action=create_post">Create Post</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login">Create Post</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <div class="profile">
                        <?php if (!empty($post['profile_image'])): ?>
                            <img src="<?= htmlspecialchars($post['profile_image']); ?>" alt="<?= htmlspecialchars($post['username']); ?>">
                        <?php else: ?>
                            <span><?= htmlspecialchars($post['username']); ?></span>
                        <?php endif; ?>
                    </div>
                    <h2><?= htmlspecialchars($post['title']); ?></h2>
                    <p class="meta"><?= htmlspecialchars($post['creation_date']); ?> | <?= htmlspecialchars($post['category']); ?></p>
                    <p><?= htmlspecialchars(substr($post['content'], 0, 150)); ?>...</p>
                    <div class="actions">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="index.php?action=login" onclick="return confirm('Please log in to view more details.');">Show</a>
                        <?php else: ?>
                            <a href="index.php?action=view_post&id=<?= urlencode($post['id']); ?>">Show</a>
                            <?php if ($post['user_id'] == $currentUserId): ?>
                                <a href="index.php?action=update_post&id=<?= urlencode($post['id']); ?>">Edit</a>
                                <a href="index.php?action=delete_post&id=<?= urlencode($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
