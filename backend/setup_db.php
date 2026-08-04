<?php
// setup_db.php
include 'db.php'; // Uses the connection from db.php (which currently has no dbname selected)

try {
    // 1. Create Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS ai_interview_db");
    echo "Database created successfully.\n";
    
    // 2. Refresh connection to use the new DB
    $conn->exec("USE ai_interview_db");

    // 3. Create Users Table
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Users table created.\n";

    // 4. Create Roles Table
    $conn->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        icon VARCHAR(50) DEFAULT 'FaCode',
        question_count INT DEFAULT 20
    )");
    echo "Roles table created.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
