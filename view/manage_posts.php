<?php

require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);

// Fetch posts created by the current user
$posts = $blogPost->getAllPosts(true);

$title = "Manage Posts";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/home.css">
</head>
<style>
    .post-item p {
    margin: 10px 0;
}

.post-item .actions a {
    margin-right: 10px;
    color: #007bff;
    text-decoration: none;
}

.post-item .actions a:hover {
    text-decoration: underline;
}

.post-item .actions .approve {
    color: #28a745;
}

.post-item .actions .reject {
    color: #dc3545;
}

.post-item .status {
    font-weight: bold;
    padding: 5px;
    border-radius: 3px;
}

.status.approved {
    background-color: #d4edda;
    color: #155724;
}

.status.rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.status.pending {
    background-color: #fff3cd;
    color: #856404;
}
</style>
<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php?action=create_post">Create Post</a></li>
                <li><a href="index.php?action=manage_posts">Manage Posts</a></li>
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
                        <p class="meta"><?= htmlspecialchars($post['creation_date']); ?> | <?= htmlspecialchars($post['category']); ?> | 
                            <span class="status <?= htmlspecialchars($post['status']); ?>">
                                <?= htmlspecialchars(ucfirst($post['status'])); ?>
                            </span>
                        </p>
                        <p><?= htmlspecialchars(substr($post['content'], 0, 150)); ?>...</p>
                        <div class="actions">
                            <a href="index.php?action=view_post&id=<?= urlencode($post['id']); ?>">View</a>
                            <a href="index.php?action=update_post&id=<?= urlencode($post['id']); ?>">Edit</a>
                            <a href="index.php?action=delete_post&id=<?= urlencode($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
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
