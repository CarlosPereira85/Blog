<?php

class Post {
    private $db;
    private $currentUserId;

    public function __construct($db, $currentUserId) {
        $this->db = $db;
        $this->currentUserId = $currentUserId;
    }

    public function createPost($content, $image) {
        $stmt = $this->db->prepare("INSERT INTO Posts (user_id, content, image) VALUES (?, ?, ?)");
        return $stmt->execute([$this->currentUserId, $content, $image]);
    }

    public function getPostsByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Posts WHERE user_id = ? ORDER BY creation_date DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
