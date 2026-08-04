<?php
// verify_full_flow.php

$baseUrl = 'http://localhost/ai-interview-project/backend';
$email = 'verify_' . time() . '@example.com';
$password = 'FlowTest123!';
$fullName = 'Verification User';

function makeRequest($url, $method, $data = [], $cookies = "") {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_HEADER, true); // Get headers to capture cookies
    
    if (!empty($cookies)) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    }

    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    curl_close($ch);
    
    // Extract cookies
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
    $newCookies = [];
    foreach($matches[1] as $item) {
        $newCookies[] = $item;
    }
    
    return ['code' => $httpCode, 'body' => $body, 'cookies' => implode('; ', $newCookies)];
}

echo "Starting Enhanced Flow Verification...\n";

// 1. Test Registration with Auto-Login
echo "[1] Testing Registration (Expect Auto-Login)...\n";
$regResponse = makeRequest("$baseUrl/register.php", 'POST', [
    'full_name' => $fullName,
    'email' => $email,
    'password' => $password
]);

echo "Status: " . $regResponse['code'] . "\n";
echo "Cookies: " . $regResponse['cookies'] . "\n";

$cookies = $regResponse['cookies'];
$regBody = json_decode($regResponse['body'], true);

if ($regResponse['code'] === 201 && isset($regBody['user'])) {
    echo "SUCCESS: Registered and received user data.\n";
} else {
    echo "FAILURE: Registration did not auto-login or failed.\n";
    exit(1);
}

// 2. Test Persistence (using cookies)
echo "\n[2] Testing Session Persistence (accessing me.php)...\n";
$meResponse = makeRequest("$baseUrl/me.php", 'GET', [], $cookies);
$meBody = json_decode($meResponse['body'], true);

if ($meResponse['code'] === 200 && $meBody['authenticated'] === true) {
    echo "SUCCESS: Session is active. User: " . $meBody['user']['full_name'] . "\n";
} else {
    echo "FAILURE: Session not persisted.\n";
    echo "Body: " . $meResponse['body'] . "\n";
    exit(1);
}

// 3. Test Logout
echo "\n[3] Testing Logout...\n";
$logoutResponse = makeRequest("$baseUrl/logout.php", 'GET', [], $cookies);

if ($logoutResponse['code'] === 200) {
    echo "SUCCESS: Logout call succeeded.\n";
} else {
    echo "FAILURE: Logout call failed.\n";
    exit(1);
}

// 4. Verify Logout (accessing me.php again)
echo "\n[4] Verifying Logout (accessing me.php)...\n";
$meResponse2 = makeRequest("$baseUrl/me.php", 'GET', [], $cookies); // using old cookies

if ($meResponse2['code'] === 401) {
    echo "SUCCESS: Access denied after logout.\n";
} else {
    echo "FAILURE: Still able to access me.php after logout.\n";
    exit(1);
}

echo "\nALL TESTS PASSED!\n";
?>
