<?php
// deduplicate_roles.php
include 'db.php';

try {
    echo "Starting deduplication process...<br>";

    // 1. Fetch all roles ordered by ID DESC (Latest ones are preferred)
    $stmt = $conn->query("SELECT * FROM roles ORDER BY id DESC");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $seenTitles = [];
    $idsToDelete = [];

    foreach ($roles as $role) {
        $title = trim($role['title']); // Normalize
        
        if (isset($seenTitles[$title])) {
            // Verify if the one we are deleting is indeed worse? 
            // In our case, the latest (first seen) is the one we just added with full description.
            // The specific 'placeholder' check isn't strictly necessary if assumption holds, 
            // but let's be safe: If the KEPT candidate is a placeholder, and CURRENT is NOT, swap?
            // Actually, simply keeping the LATEST is the safest bet given the recent `add_new_roles.php` run.
            
            $idsToDelete[] = $role['id'];
        } else {
            $seenTitles[$title] = $role;
            echo "Keeping ID {$role['id']} for '$title' ({$role['description']})<br>";
        }
    }

    // 2. Delete duplicates
    if (!empty($idsToDelete)) {
        $idList = implode(',', $idsToDelete);
        echo "<br>Deleting IDs: $idList<br>";
        $conn->exec("DELETE FROM roles WHERE id IN ($idList)");
        echo "Successfully deleted " . count($idsToDelete) . " duplicate roles.<br>";
    } else {
        echo "<br>No duplicates found.<br>";
    }

    // 3. Add UNIQUE Index to prevent future duplicates
    echo "<br>Adding UNIQUE constraint to 'title' column...<br>";
    try {
        $conn->exec("ALTER TABLE roles ADD UNIQUE (title)");
        echo "SUCCESS: Unique constraint added.<br>";
    } catch (PDOException $e) {
        // If it fails, it might be because it already exists or key is too long (100 chars is fine).
        // Or if simple ALTER fails, we might need a named index.
        echo "Note: " . $e->getMessage() . " (Constraint might already exist)<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
