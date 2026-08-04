<?php
require_once 'db.php';

try {
    echo "Setting up feedback table...\n";

    $sql = "CREATE TABLE IF NOT EXISTS user_feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        user_name VARCHAR(255) NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        category VARCHAR(50) DEFAULT 'general',
        feedback_text TEXT,
        is_public BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )";

    $conn->exec($sql);
    echo "Table 'user_feedback' created or checks out.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
