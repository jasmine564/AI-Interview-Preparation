<?php
// me.php

include_once 'cors.php';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    http_response_code(200);
    echo json_encode([
        "authenticated" => true,
        "user" => [
            "id" => $_SESSION['user_id'],
            "full_name" => $_SESSION['full_name'],
            "email" => $_SESSION['email']
        ]
    ]);
} else {
    // Return 200 OK even if not authenticated to prevent browser console 401 errors
    http_response_code(200);
    echo json_encode([
        "authenticated" => false,
        "message" => "Not logged in"
    ]);
}
?>
