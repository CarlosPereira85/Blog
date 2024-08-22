<?php
include_once 'includes/PDOConnection.inc.php';

try {
    // First, connect to the default database (or no specific database)
    $db = (new Database())->getConnection();
    
    // Create the database if it doesn't exist
    $db->exec("CREATE DATABASE IF NOT EXISTS carlos_blog");

    // Select the newly created database
    $db->exec("USE carlos_blog");

    // Drop existing tables and views in reverse order of dependencies
    $db->exec("
        DROP VIEW IF EXISTS PostRatings;
        DROP TABLE IF EXISTS PostImages;
        DROP TABLE IF EXISTS Messages;
        DROP TABLE IF EXISTS Comments;
        DROP TABLE IF EXISTS Ratings;
        DROP TABLE IF EXISTS BlogPosts;
        DROP TABLE IF EXISTS Users;
    ");

    // Create the Users table with an additional column for admin role
    $db->exec("
        CREATE TABLE Users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            profile_image VARCHAR(255) DEFAULT NULL,
            profile_image_set BOOLEAN DEFAULT FALSE,
            is_admin BOOLEAN DEFAULT FALSE -- Admin role flag
        );
    ");

    // Create the BlogPosts table with an additional column for approval status
    $db->exec("
        CREATE TABLE BlogPosts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(60) NOT NULL,
            content TEXT NOT NULL,
            category ENUM('Technology', 'Food', 'Lifestyle') NOT NULL,
            creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            views_count INT DEFAULT 0,
            user_id INT NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
        );
    ");

    // Create the Ratings table
    $db->exec("
        CREATE TABLE Ratings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            rating TINYINT CHECK (rating >= 1 AND rating <= 5),
            FOREIGN KEY (post_id) REFERENCES BlogPosts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
        );
    ");
    
    // Create the PostImages table
    $db->exec("
        CREATE TABLE IF NOT EXISTS PostImages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            is_featured BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (post_id) REFERENCES BlogPosts(id) ON DELETE CASCADE
        );
    ");

    // Create the Comments table
    $db->exec("
        CREATE TABLE Comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id INT NOT NULL,
            comment TEXT NOT NULL,
            creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES BlogPosts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
        );
    ");

    // Create the Messages table for chat functionality
    $db->exec("
        CREATE TABLE Messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            message TEXT NOT NULL,
            creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            read_status TINYINT DEFAULT 0,
            FOREIGN KEY (sender_id) REFERENCES Users(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES Users(id) ON DELETE CASCADE
        );
    ");

    // Create the PostRatings view
    $db->exec("
        CREATE VIEW PostRatings AS
        SELECT 
            post_id,
            COUNT(rating) AS rating_count,
            AVG(rating) AS average_rating
        FROM 
            Ratings
        GROUP BY 
            post_id;
    ");

    echo "Database initialized successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>
