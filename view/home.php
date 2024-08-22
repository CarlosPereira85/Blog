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
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 0 6px;
            font-size: 12px;
            vertical-align: top;
            position: relative;
            top: -10px;
            right: -10px;
        }
        .rejected-post {
            color: red;
            font-style: italic;
        }
        .approved-post {
            color: green;
            font-style: italic;
        }
        .pending-post {
            color: orange;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?action=create_post">Create Post</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <table>
            <tr>
                <th>Profile</th>
                <th>Title</th>
                <th>Content</th>
                <th>Creation Date</th>
                <th>Category</th>
                <?php if (isset($_SESSION['user_id'])): // Only show this column if a user is logged in ?>
                    <th>Status</th>
                <?php endif; ?>
                <th>Actions</th>
            </tr>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td>
                        <?php if (!empty($post['profile_image'])): ?>
                            <img src="<?= htmlspecialchars($post['profile_image']); ?>" alt="<?= htmlspecialchars($post['username']); ?>" style="width: 50px; height: 50px;">
                        <?php else: ?>
                            <?= htmlspecialchars($post['username']); ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($post['title']); ?></td>
                    <td class="truncated"><?= htmlspecialchars($post['content']); ?></td>
                    <td><?= htmlspecialchars($post['creation_date']); ?></td>
                    <td><?= htmlspecialchars($post['category']); ?></td>
                    <?php if ($post['user_id'] == $currentUserId): // Only show status if this is the user's post ?>
                        <td>
                            <?php if ($post['status'] == 'rejected'): ?>
                                <span class="rejected-post">Not Approved</span>
                            <?php elseif ($post['status'] == 'approved'): ?>
                                <span class="approved-post">Approved</span>
                            <?php else: ?>
                                <span class="pending-post">Pending Approval</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="index.php?action=login" onclick="return confirm('Please log in to view more details.');">Show</a>
                        <?php else: ?>
                            <a href="index.php?action=view_post&id=<?= urlencode($post['id']); ?>">Show</a>
                            <?php if ($post['user_id'] == $currentUserId): ?>
                                <a href="index.php?action=update_post&id=<?= urlencode($post['id']); ?>">Edit</a>
                                <a href="index.php?action=delete_post&id=<?= urlencode($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
