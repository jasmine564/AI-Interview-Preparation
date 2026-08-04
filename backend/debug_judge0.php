<?php
// Debug Script for run_code.php - Inspect Raw Judge0 Response

$url = 'https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=true&wait=true';
$api_host = 'judge0-ce.p.rapidapi.com';
// Note: This key might be missing or invalid in the actual env, leading to issues.
$api_key = getenv('JUDGE0_API_KEY') ?: '';

// Broken Python Code (matches user screenshot)
$code_python = "def two_sum(nums, target):
    pass
# No class Solution defined";

// Driver Code (mimicking run_code.php injection)
$driver_code = "
import sys
if __name__ == '__main__':
    sol = Solution() # This will raise NameError
    print(sol.twoSum([], 0))
";

$full_code = $code_python . $driver_code;
$stdin = "2 7 11 15\n9";
$expected_output = "0 1";

$post_fields = [
    'source_code' => base64_encode($full_code),
    'language_id' => 71, // Python
    'stdin' => base64_encode($stdin),
    'expected_output' => base64_encode($expected_output)
];

echo "Sending Request to: $url\n";
echo "API Key Length: " . strlen($api_key) . "\n";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($post_fields),
    CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "Content-Type: application/json",
        "X-RapidAPI-Host: $api_host",
        "X-RapidAPI-Key: $api_key"
    ],
    // Verify result even if SSL issues (optional for local dev)
    CURLOPT_SSL_VERIFYPEER => false 
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $http_code\n";
if ($err) {
    echo "cURL Error: $err\n";
} else {
    echo "Raw Response:\n$response\n";
    
    $json = json_decode($response);
    if ($json) {
        echo "\nDecoded Status: " . ($json->status->description ?? 'Missing') . "\n";
        echo "Decoded Stderr: " . base64_decode($json->stderr ?? '') . "\n";
    } else {
        echo "\nJSON Decode Failed!\n";
    }
}
?>
