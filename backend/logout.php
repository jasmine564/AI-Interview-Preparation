<?php
// logout.php

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

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

header('Content-Type: application/json');
http_response_code(200);
echo json_encode(["message" => "Logged out successfully"]);
?>
