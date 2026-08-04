<?php
// force_init_db.php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("CREATE DATABASE IF NOT EXISTS ai_interview_db");
    echo "Database 'ai_interview_db' created or exists.\n";
    
    // Now setup tables
    $conn->exec("USE ai_interview_db");
    
    // Coding problems
    $sql_problems = "CREATE TABLE IF NOT EXISTS coding_problems (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description TEXT NOT NULL,
        difficulty ENUM('Easy', 'Medium', 'Hard') NOT NULL,
        starter_code JSON NOT NULL,
        test_cases JSON,
        solution_code TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql_problems);
    echo "Table 'coding_problems' created.\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
