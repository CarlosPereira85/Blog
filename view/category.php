<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Posts</title>
</head>
<body>
    <h2>Posts in <?php echo htmlspecialchars($category); ?> Category</h2>
    <?php if (!empty($posts)): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <a href="index.php?action=view_post&id=<?php echo htmlspecialchars($post['id']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No posts available in this category.</p>
    <?php endif; ?>
</body>
</html>
