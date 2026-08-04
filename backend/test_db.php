<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

try {
    echo "Connected successfully to DB.<br>";
    $stmt = $conn->query("SELECT * FROM roles ORDER BY title ASC");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Query successful. Found " . count($roles) . " roles.<br>";
    echo "<pre>";
    print_r($roles);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
