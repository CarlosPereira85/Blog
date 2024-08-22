<?php
include_once 'includes/PDOConnection.inc.php';
include_once 'model/BlogPost.php';
include_once 'model/Comment.php';
include_once 'model/Rating.php';
include_once 'includes/functions.inc.php';

// Initialize database connection and models
$db = (new Database())->getConnection();
$currentUserId = $_SESSION['user_id'] ?? null;
$blogPost = new BlogPost($db, $currentUserId);
$commentModel = new Comment($db, $currentUserId);
$rating = new Rating($db);

// Fetch post ID from the URL
$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = $blogPost->getPost($postId);

if (!$post) {
    die('Post not found.');
}

// Increment the view count
$blogPost->incrementViewCount($postId);

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $userId = $currentUserId;
    $ratingValue = isset($_POST['rating']) ? intval($_POST['rating']) : null;

    if ($ratingValue >= 1 && $ratingValue <= 5) {
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
$currentRatings = $rating->getRatings($postId);
$userHasRated = $currentUserId ? $rating->userHasRated($postId, $currentUserId) : false;

// Get comments for this post
$comments = $commentModel->getComments($postId);

// Fetch featured image for this post
$imageQuery = $db->prepare("SELECT image_path FROM PostImages WHERE post_id = ? AND is_featured = 1");
$imageQuery->execute([$postId]);
$featuredImage = $imageQuery->fetchColumn();

$title = htmlspecialchars($post['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/view_post.css">
    <style>
        .back-arrow {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 24px;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }
        .back-arrow:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Arrow Button -->
        <a href="javascript:history.back()" class="back-arrow">&larr; Back</a>

        <h1><?= htmlspecialchars($title); ?></h1>
        <div class="post-content">
            <?php if ($featuredImage): ?>
                <!-- Debugging: Check the actual image path -->
                <?php $imagePath = htmlspecialchars($featuredImage); ?>
                <img src="<?= $imagePath; ?>" alt="Featured Image" style="max-width: 100%; height: auto;">
            <?php else: ?>
                <p>No featured image available.</p>
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
            <p>Created on: <?= htmlspecialchars($post['creation_date']); ?></p>
            <p>Views: <?= htmlspecialchars($post['views_count']); ?></p>
            <p>Posted by: <?= htmlspecialchars($post['username']); ?></p> <!-- Display poster's name -->
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
                        <button type="submit">Submit Rating</button>
                    </form>
                <?php endif; ?>
                <?php if ($currentRatings): ?>
                    <h3>Average Rating: <?= number_format($currentRatings['average_rating'], 2); ?> (<?= $currentRatings['rating_count']; ?> votes)</h3>
                <?php else: ?>
                    <p>No ratings yet.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>You must be logged in to rate this post.</p>
            <?php endif; ?>
        </div>

        <!-- Comments Section -->
        <div class="comments">
            <h2>Comments</h2>
            <?php if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <strong><?= htmlspecialchars($comment['username'] ?? 'Anonymous'); ?></strong> said:
                        <p><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        <em>On <?= htmlspecialchars($comment['creation_date']); ?></em>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Comment Form -->
        <?php if ($currentUserId): ?>
            <div class="comment-form">
                <h3>Leave a Comment</h3>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="post">
                    <textarea name="comment" rows="5" placeholder="Write your comment here..."></textarea>
                    <button type="submit">Submit Comment</button>
                </form>
            </div>
        <?php else: ?>
            <p>You must be logged in to leave a comment.</p>
        <?php endif; ?>

        <!-- Back to Home Button -->
        <a href="index.php?action=home" class="back-link">Back to Home</a>
    </div>
</body>
</html>
