<?php
// setup_pins_table.php
include 'db.php';

try {
    // Drop old table to ensure schema change (role_id -> role_title)
    $conn->exec("DROP TABLE IF EXISTS pinned_questions");

    $sql = "
    CREATE TABLE pinned_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        role_title VARCHAR(255) NOT NULL,
        question_identifier VARCHAR(255),
        question_data TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_pin (user_id, role_title, question_identifier),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $conn->exec($sql);
    echo "Table 'pinned_questions' created successfully with role_title.";

} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
