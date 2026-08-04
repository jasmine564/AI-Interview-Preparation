<?php
require_once 'db.php';

// Diagnostics Configuration
$problem_id = 1; // Two Sum
$language = 'python';
$user_code = "class Solution:\n    def twoSum(self, nums: List[int], target: int) -> List[int]:\n        num_map = {}\n        for i, num in enumerate(nums):\n            complement = target - num\n            if complement in num_map:\n                return [num_map[complement], i]\n            num_map[num] = i\n        return []";

echo "--- DIAGNOSIS START ---\n";

// 1. Fetch from DB
$stmt = $conn->prepare("SELECT test_cases, driver_code FROM coding_problems WHERE id = :id");
$stmt->execute([':id' => $problem_id]);
$problem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$problem) die("DB Error: Problem not found\n");

echo "1. DB Fetch Successful.\n";
echo "   Driver Code Column Length: " . strlen($problem['driver_code'] ?? '') . "\n";

// 2. Resolve Driver
$driver_map = json_decode($problem['driver_code'] ?? '{}', true);
$driver = $driver_map[$language] ?? null;

if (!$driver) {
    echo "ERROR: No driver found for $language!\n";
    echo "Available keys: " . implode(", ", array_keys($driver_map)) . "\n";
    exit;
}
echo "2. Driver Resolution Successful.\n";
echo "   Driver Length: " . strlen($driver) . "\n";

// 3. Construct Payload
$full_code = $user_code . "\n" . $driver;
echo "3. Code Construction Successful.\n";
// echo "   Full Code Preview:\n" . substr($full_code, -100) . "\n";

// 4. Test Case 1
$tcs = json_decode($problem['test_cases'], true);
$tc1 = $tcs[0];
$stdin = $tc1['input'];
$expected = $tc1['output'];

echo "4. Test Case 1 Input: " . str_replace("\n", "\\n", $stdin) . "\n";

// 5. Judge0 Test
echo "5. Sending to Judge0 (judge0-ce.p.rapidapi.com)...\n";

$full_code_b64 = base64_encode($full_code);
$stdin_b64 = base64_encode($stdin);
$expected_b64 = base64_encode($expected);

$post_fields = [
    'source_code' => $full_code_b64,
    'language_id' => 71, // Python
    'stdin' => $stdin_b64,
    'expected_output' => $expected_b64
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=true&wait=true",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($post_fields),
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "ERROR: cURL failed: $err\n";
} else {
    echo "6. Judge0 Response Received.\n";
    $json = json_decode($response, true);
    
    $stdout = base64_decode($json['stdout'] ?? '');
    $stderr = base64_decode($json['stderr'] ?? '');
    $desc = $json['status']['description'] ?? 'Unknown';
    
    echo "   Status: $desc\n";
    echo "   Stdout: '$stdout' (Length: " . strlen($stdout) . ")\n";
    echo "   Stderr: '$stderr'\n";
    
    if (strlen($stdout) == 0 && empty($stderr)) {
        echo "!!! DIAGNOSIS: Execution successful but output is empty. Driver logic failed to print.\n";
    } elseif ($desc !== 'Accepted') {
        echo "!!! DIAGNOSIS: Runtime/Compile Error.\n";
    } else {
        echo "SUCCESS: Output captured correctly.\n";
    }
}
echo "--- DIAGNOSIS END ---\n";
?>
