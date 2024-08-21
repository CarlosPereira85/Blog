<?php
class Rating {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addRating($post_id, $user_id, $rating) {
        if ($this->userHasRated($post_id, $user_id)) {
            throw new Exception("You have already rated this post.");
        }

        $stmt = $this->db->prepare("INSERT INTO Ratings (post_id, user_id, rating) VALUES (?, ?, ?)");
        return $stmt->execute([$post_id, $user_id, $rating]);
    }

    public function getRatings($post_id) {
        $stmt = $this->db->prepare("SELECT * FROM PostRatings WHERE post_id = ?");
        $stmt->execute([$post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function userHasRated($post_id, $user_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Ratings WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
