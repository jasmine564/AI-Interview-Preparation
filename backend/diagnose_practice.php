<?php
require_once 'db.php';
header("Content-Type: text/plain");

// Mock input for Two Sum (ID 1) and Python
$problem_id = 1;
$language = 'python';
$user_code = "class Solution:\n    def twoSum(self, nums: List[int], target: int) -> List[int]:\n        pass";

echo "Diag: Fetching problem $problem_id\n";

try {
    $stmt = $conn->prepare("SELECT id, title, driver_code FROM coding_problems WHERE id = :id");
    $stmt->execute([':id' => $problem_id]);
    $problem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$problem) {
        die("Problem not found in DB.");
    }
    
    echo "Diag: Problem Found: " . $problem['title'] . "\n";
    echo "Diag: Raw driver_code from DB type: " . gettype($problem['driver_code']) . "\n";
    echo "Diag: Raw driver_code content: " . $problem['driver_code'] . "\n";
    
    $driver_code_map = json_decode($problem['driver_code'] ?? '{}', true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Diag: JSON Decode Error: " . json_last_error_msg() . "\n";
    }
    
    echo "Diag: Driver map keys: " . implode(", ", array_keys($driver_code_map)) . "\n";
    
    // Simulate selection
    if (isset($driver_code_map[$language])) {
        echo "Diag: Found driver for '$language'\n";
        $final_driver = $driver_code_map[$language];
        echo "Diag: Driver content length: " . strlen($final_driver) . "\n";
        echo "Diag: Driver content start: " . substr($final_driver, 0, 50) . "...\n";
        
        $final_code = $user_code . "\n" . $final_driver;
        echo "Diag: FINAL CODE TO BE SENT:\n------------------\n$final_code\n------------------\n";
    } else {
        echo "Diag: NO driver found for '$language'\n";
    }

} catch (PDOException $e) {
    echo "Diag: DB Error: " . $e->getMessage();
}
?>
