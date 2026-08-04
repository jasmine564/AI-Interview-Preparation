<?php
include 'db.php';

try {
    $stmt = $conn->query("SELECT id, title, description FROM roles ORDER BY title");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    foreach ($roles as $role) {
        echo "ID: " . $role['id'] . " | Title: " . $role['title'] . " | Desc: " . $role['description'] . "<br>";
    }
    echo "</pre>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
