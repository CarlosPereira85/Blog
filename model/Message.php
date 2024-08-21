<?php

class Message {
    private $db;
    private $currentUserId;

    public function __construct($db, $currentUserId) {
        $this->db = $db;
        $this->currentUserId = $currentUserId;
    }

    public function sendMessage($receiverId, $message) {
        $stmt = $this->db->prepare("INSERT INTO Messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        return $stmt->execute([$this->currentUserId, $receiverId, $message]);
    }

    public function getMessages($receiverId) {
        $stmt = $this->db->prepare("
            SELECT 
                m.id, 
                m.sender_id, 
                m.receiver_id, 
                m.message, 
                m.creation_date, 
                u.username AS sender_username,
                u.profile_image AS sender_profile_image,
                m.read_status
            FROM Messages m
            JOIN Users u ON m.sender_id = u.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) 
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.creation_date ASC
        ");
        $stmt->execute([$receiverId, $this->currentUserId, $this->currentUserId, $receiverId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markMessagesAsRead() {
        $stmt = $this->db->prepare("UPDATE Messages SET read_status = 1 WHERE receiver_id = ? AND read_status = 0");
        $stmt->execute([$this->currentUserId]);
    }

    public function getUnreadMessages() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM Messages WHERE receiver_id = ? AND read_status = 0");
        $stmt->execute([$this->currentUserId]);
        return $stmt->fetchColumn();
    }
}
?>
