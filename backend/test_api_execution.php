<?php
// Test Script for run_code.php with Function Execution

$url = 'http://localhost/ai-interview-project/backend/run_code.php';

// Mock Data: Class Solution for Two Sum (Problem ID 1) in Python
// User writes ONLY the class logic.
$code_python = "class Solution:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        seen = {}
        for i, num in enumerate(nums):
            diff = target - num
            if diff in seen:
                return [seen[diff], i]
            seen[num] = i
        return []";

$data = [
    'problem_id' => 1,
    'language' => 'python',
    'code' => $code_python
];

echo "Testing URL: $url\n";
echo "Sending Class-based Payload...\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "Filesystem/Network Error: $error\n";
} else {
    echo "HTTP Status: $http_code\n";
    echo "Response:\n$response\n";
}
?>
