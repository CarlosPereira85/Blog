<?php
require_once 'includes/PDOConnection.inc.php';
require_once 'model/Post.php';
require_once 'model/Comment.php';

$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$postId = $_GET['id'] ?? null;

if (!$postId) {
    die('Post ID is required.');
}

$postModel = new Post($db, $currentUserId);
$commentModel = new Comment($db, $currentUserId);

// Fetch post details
$post = $postModel->getPostById($postId);

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $commentModel->addComment($postId, $comment);
        header("Location: post.php?id=" . $postId);
        exit;
    } else {
        $error = "Comment cannot be empty.";
    }
}

// Get comments for this post
$comments = $commentModel->getComments($postId);

$title = "Post Details";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($title); ?></h1>
    </header>
    <main>
        <section class="post-details">
            <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
            <?php if (!empty($post['image'])): ?>
                <img src="<?= htmlspecialchars($post['image']); ?>" alt="Post Image" style="max-width: 500px;">
            <?php endif; ?>
        </section>

        <section class="comments">
            <h2>Comments</h2>
            <?php if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?= htmlspecialchars($comment['username']); ?></strong> said:
                        <p><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        <em>On <?= htmlspecialchars($comment['creation_date']); ?></em>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($currentUserId): ?>
                <div class="comment-form">
                    <h3>Leave a Comment</h3>
                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <form method="post">
                        <textarea name="comment" rows="5" style="width: 100%;" placeholder="Write your comment here..."></textarea>
                        <br>
                        <button type="submit" style="margin-top: 10px;">Submit Comment</button>
                    </form>
                </div>
            <?php else: ?>
                <p>You must be logged in to leave a comment.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
