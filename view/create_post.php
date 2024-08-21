<?php

include_once 'controller/Create_Post.php';

?>
<link rel="stylesheet" href="css/create_post.css">
<form method="post" action="index.php?action=create_post">
    Title: <input type="text" name="title" maxlength="60" required><br>
    Content: <textarea name="content" required></textarea><br>
    Category: 
    <select name="category" required> <!-- Dropdown for category selection -->
        <option value="Technology">Technology</option>
        <option value="Food">Food</option>
        <option value="Lifestyle">Lifestyle</option>
    </select><br>
   
    <input type="submit" value="Create Post">
</form>

