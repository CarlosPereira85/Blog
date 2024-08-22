<?php

require_once 'iDatenbank.php';

class BlogPost implements iDatenbank {
    private $db;
    private $currentUserId;

    public function __construct($db, $currentUserId) {
        $this->db = $db;
        $this->currentUserId = $currentUserId;
    }

    

    public function getAllPosts($forManagement = false) {
        $query = "
            SELECT BlogPosts.id, BlogPosts.title, BlogPosts.content, BlogPosts.creation_date, 
                   BlogPosts.category, BlogPosts.status, BlogPosts.user_id, Users.username, 
                   Users.profile_image
            FROM BlogPosts
            JOIN Users ON BlogPosts.user_id = Users.id
        ";
    
        if ($forManagement) {
            // Fetch all posts created by the current user for management
            $query .= " WHERE BlogPosts.user_id = :currentUserId";
        } else {
            // Fetch only approved posts for the homepage
            $query .= " WHERE BlogPosts.status = 'approved'";
        }
    
        $query .= " ORDER BY BlogPosts.creation_date DESC";
    
        $stmt = $this->db->prepare($query);
    
        if ($forManagement) {
            $stmt->bindParam(':currentUserId', $this->currentUserId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getPost($id) {
        $stmt = $this->db->prepare("SELECT * FROM BlogPosts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addPost($title, $content, $category) {
        $stmt = $this->db->prepare("INSERT INTO BlogPosts (title, content, user_id, category, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$title, $content, $this->currentUserId, $category]);
        return $this->db->lastInsertId();
    }

    public function getPostById($postId) {
        $query = "SELECT * FROM BlogPosts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to update a post
    public function updatePost($postId, $title, $content) {
        $query = "UPDATE BlogPosts SET title = :title, content = :content WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("SELECT user_id FROM BlogPosts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post && $post['user_id'] == $this->currentUserId) {
            $stmt = $this->db->prepare("DELETE FROM BlogPosts WHERE id = ?");
            return $stmt->execute([$id]);
        }

        throw new Exception("You do not have permission to delete this post.");
    }

    public function incrementViewCount($id) {
        $stmt = $this->db->prepare("UPDATE BlogPosts SET views_count = views_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPostsByCategory($category) {
        $stmt = $this->db->prepare("
            SELECT BlogPosts.id, BlogPosts.title, BlogPosts.content, BlogPosts.creation_date, 
                   BlogPosts.category, BlogPosts.user_id, Users.username, Users.profile_image
            FROM BlogPosts
            JOIN Users ON BlogPosts.user_id = Users.id
            WHERE BlogPosts.category = ?
            ORDER BY BlogPosts.creation_date DESC
        ");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Approve a post
    public function approvePost($id) {
        $stmt = $this->db->prepare("UPDATE BlogPosts SET status = 'approved' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Reject a post
    public function rejectPost($id) {
        $stmt = $this->db->prepare("UPDATE BlogPosts SET status = 'rejected' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get unapproved posts
    public function getUnapprovedPosts() {
        $stmt = $this->db->prepare("SELECT id, title, content, creation_date, category FROM BlogPosts WHERE status = 'pending' ORDER BY creation_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}

?>
