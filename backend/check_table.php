<?php
require 'db.php';
try {
    $stmt = $conn->query("SELECT count(*) FROM user_feedback");
    echo "Table 'user_feedback' exists. Count: " . $stmt->fetchColumn() . "\n";
} catch (Exception $e) {
    echo "Table 'user_feedback' MISSING or Error: " . $e->getMessage() . "\n";
}
?>
