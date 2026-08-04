<?php
include 'db.php';

try {
    echo "Time: " . time() . "<br>";
    $stmt = $conn->query("SELECT id, title, description FROM roles ORDER BY title");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    foreach ($roles as $role) {
        echo "ID: " . $role['id'] . " | Title: " . $role['title'] . " | Desc: " . $role['description'] . "<br>";
    }
    echo "</pre>";
    
    // Check indexes
    $stmt = $conn->query("SHOW INDEX FROM roles");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Indexes:</h3><pre>";
    print_r($indexes);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
