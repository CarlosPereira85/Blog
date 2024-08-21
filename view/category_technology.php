<?php
require_once 'includes/PDOConnection.inc.php';
require_once 'model/BlogPost.php';
require_once 'includes/functions.inc.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);

// Define the category you want to fetch posts for
$category = 'Technology'; // Change this to the category you want to filter by

// Fetch posts by category
$posts = $blogPost->getPostsByCategory($category);
$title = "Technology";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .truncated {
            max-width: 200px; /* Adjust as needed */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($title); ?></h1>
    </header>
    <main>
        <section>
            <table>
                <tr>
                    <th>Profile</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Creation Date</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <?php if (!empty($post['profile_image'])): ?>
                                <img src="<?= htmlspecialchars($post['profile_image']); ?>" alt="<?= htmlspecialchars($post['username']); ?>" class="profile-image">
                            <?php else: ?>
                                <?= htmlspecialchars($post['username']); ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($post['title']); ?></td>
                        <td class="truncated"><?= htmlspecialchars($post['content']); ?></td>
                        <td><?= htmlspecialchars($post['creation_date']); ?></td>
                        <td><?= htmlspecialchars($post['category']); ?></td>
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
        </section>
    </main>
    <footer>
        <nav>
            <?php require_once "view/_meta_navi.php"; ?>
        </nav>
    </footer>
</body>
</html>
