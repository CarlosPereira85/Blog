<?php

class Comment {
    private $db;
    private $currentUserId;

    public function __construct($db, $currentUserId) {
        $this->db = $db;
        $this->currentUserId = $currentUserId;
    }

    public function addComment($postId, $comment) {
        $stmt = $this->db->prepare("INSERT INTO Comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        return $stmt->execute([$postId, $this->currentUserId, $comment]);
    }

    public function getComments($postId) {
        $stmt = $this->db->prepare("
            SELECT Comments.*, Users.username 
            FROM Comments 
            JOIN Users ON Comments.user_id = Users.id 
            WHERE post_id = ? 
            ORDER BY creation_date DESC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
