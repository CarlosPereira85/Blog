<?php
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);

// Fetch approved posts only
$posts = $blogPost->getAllPosts();

$title = "Blog";
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
                    <li><a href="index.php?action=login"></a></li>
                <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?action=manage_posts">Manage Posts</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login"></a></li>
                <?php endif; ?>
               
            </ul>
        </nav>
        <h1><?= htmlspecialchars($title); ?></h1>
        <div class="post-list">
            <?php if (!empty($posts)): ?>
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
                        <p class="meta"><?= htmlspecialchars($post['creation_date']); ?> | <?= htmlspecialchars($post['category']); ?> </p>
                        <p><?= htmlspecialchars(substr($post['content'], 0, 150)); ?>...</p>
                        <div class="actions">
                            <a href="index.php?action=view_post&id=<?= urlencode($post['id']); ?>">View</a>
                           
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>