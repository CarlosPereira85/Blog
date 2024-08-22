<?php

interface iDatenbank {
    public function getAllPosts($onlyApproved = false);
    public function getPost($id);
    public function addPost($title, $content, $category,$imageFile);
    public function updatePost($id, $title, $content);
    public function deletePost($id);
    public function incrementViewCount($id);
    public function getPostsByCategory($category);
}

?>
