<?php

include_once 'controller/Update.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <link rel="stylesheet" href="css/update_post.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Update Post</h1>
    </header>
    <main>
        <form action="index.php?action=update_post&id=<?php echo urlencode($postId); ?>" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

        

            <input type="submit" value="Update Post">
        </form>
    </main>
    <footer>
        <nav>
            <?php require_once "view/_meta_navi.php"; ?>
        </nav>
    </footer>
</body>
</html>
