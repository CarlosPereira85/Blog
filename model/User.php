<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Check if email already exists
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    // Register a new user
    public function register($username, $email, $password, $isAdmin = 0) {
        // Check if the email already exists
        if ($this->emailExists($email)) {
            return false; // Email is already in use
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO Users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword, $isAdmin]);
    }

    // Authenticate user with username or email
    public function authenticate($login, $password) {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
?>
