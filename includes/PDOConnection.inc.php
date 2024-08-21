<?php
class Database {
    private $db;
    
    public function __construct() {
        $dsn = 'mysql:host=localhost;port=3306; dbname=carlos_blog';
        $user = 'root';
        $pwd = '';

        try {
            $this->db = new PDO($dsn, $user, $pwd);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
            die();
        }
    }

    public function getConnection() {
        return $this->db;
    }
}
?>
