<?php
// Test Script for Judge0 C Execution

$url = 'http://localhost/ai-interview-project/backend/run_code.php';
// $url = 'http://localhost:8000/backend/run_code.php'; // If using built-in server

$code_c = "#include <stdio.h>

int main() {
    printf(\"Hello from Judge0 C!\");
    return 0;
}";

$data = [
    'problem_id' => 1,
    'language' => 'c',
    'code' => $code_c,
    'mode' => 'run'
];

echo "Testing URL: $url\n";
echo "Sending C Code...\n";

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
    $json = json_decode($response, true);
    file_put_contents('debug_c_res.json', json_encode($json, JSON_PRETTY_PRINT));
    
    if ($json) {
        $stdout = $json['stdout'] ?? '';
        echo "STDOUT: " . $stdout . "\n";
        
        if (strpos($stdout, 'Hello from Judge0 C!') !== false) {
             echo "VERIFICATION: SUCCESS (C executed)\n";
        } else {
             echo "VERIFICATION: FAILURE (Output mismatch)\n";
             // print_r($json); // Don't print to stdout to avoid clutter
        }
    } else {
        echo "Response (Raw):\n$response\n";
    }
}
?>
