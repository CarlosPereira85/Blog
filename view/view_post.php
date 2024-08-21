<?php
// Include Rating class
include_once 'model/Rating.php';
include_once 'controller/View_Post.php';

$rating = new Rating($db);

// Handle form submission for rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $userId = $currentUserId; // Assuming this is available from the session or context
    $postId = isset($post['id']) ? $post['id'] : null; // Post ID from the current post being viewed
    $ratingValue = isset($_POST['rating']) ? intval($_POST['rating']) : null; // Rating value from form submission

    if ($postId && $ratingValue >= 1 && $ratingValue <= 5) {
        try {
            $rating->addRating($postId, $userId, $ratingValue);
            $successMessage = "Thank you for rating!";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid rating. Please provide a rating between 1 and 5.";
    }
}

// Fetch current ratings for the post
$postId = isset($post['id']) ? $post['id'] : null;
$currentRatings = $postId ? $rating->getRatings($postId) : [];
$userHasRated = $postId && $currentUserId ? $rating->userHasRated($postId, $currentUserId) : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Post View'); ?></title>
    <link rel="stylesheet" href="css/view_post.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($title ?? 'Post Title'); ?></h1>
        <div class="post-content">
            <p><?= nl2br(htmlspecialchars($post['content'] ?? 'No content available.')); ?></p>
            <p>Created on: <?= htmlspecialchars($post['creation_date'] ?? 'Date not available.'); ?></p>
            <p>Views: <?= htmlspecialchars($post['views_count'] ?? 'Views not available.'); ?></p>
        </div>

        <!-- Rating Section -->
        <div class="rating">
            <h2>Rate this Post</h2>
            <?php if ($currentUserId): ?>
                <?php if ($userHasRated): ?>
                    <p style="color: red;">You have already rated this post.</p>
                <?php else: ?>
                    <?php if (isset($successMessage)): ?>
                        <p style="color: green;"><?= htmlspecialchars($successMessage); ?></p>
                    <?php elseif (isset($errorMessage)): ?>
                        <p style="color: red;"><?= htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>
                    <form method="post">
                        <label for="rating">Rating (1-5):</label>
                        <select name="rating" id="rating">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <br>
                        <button type="submit" style="margin-top: 10px;">Submit Rating</button>
                    </form>
                <?php endif; ?>
                <?php if ($currentRatings): ?>
                    <h3>Average Rating: <?= number_format($currentRatings['average_rating'] ?? 0, 2); ?> (<?= $currentRatings['rating_count'] ?? 0; ?> votes)</h3>
                <?php else: ?>
                    <p>No ratings yet.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>You must be logged in to rate this post.</p>
            <?php endif; ?>
        </div>

        <div class="comments">
            <h2>Comments</h2>
            <?php if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?= htmlspecialchars($comment['username'] ?? 'Anonymous'); ?></strong> said:
                        <p><?= nl2br(htmlspecialchars($comment['comment'] ?? 'No comment text.')); ?></p>
                        <em>On <?= htmlspecialchars($comment['creation_date'] ?? 'Date not available.'); ?></em>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

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

        <a href="index.php?action=home" class="back-link">Back to Home</a>
    </div>
</body>
</html>