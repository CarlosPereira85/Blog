<?php

include_once 'controller/Create_Post.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/create_post.css">
</head>
<body>
    <form method="post" action="index.php?action=create_post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" maxlength="60" required><br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea><br>
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Technology">Technology</option>
            <option value="Food">Food</option>
            <option value="Lifestyle">Lifestyle</option>
        </select><br>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage()"><br>
        <img id="imagePreview" src="" alt="Image Preview" style="display:none; max-width: 300px; height: auto;"><br>
        <input type="submit" value="Create Post">
    </form>

    <script>
        function previewImage() {
            const file = document.getElementById('image').files[0];
            const preview = document.getElementById('imagePreview');
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
