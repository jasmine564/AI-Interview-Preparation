<?php
$username = 'root';
$password = '';

$configs = [
    "localhost_no_db" => "mysql:host=localhost",
    "localhost_mysql_db" => "mysql:host=localhost;dbname=mysql",
    "localhost_test_db" => "mysql:host=localhost;dbname=test",
    "127_no_db" => "mysql:host=127.0.0.1",
    "127_mysql_db" => "mysql:host=127.0.0.1;dbname=mysql",
    "127_port_3306" => "mysql:host=127.0.0.1;port=3306"
];

foreach ($configs as $name => $dsn) {
    echo "Testing $name ($dsn): ";
    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "SUCCESS!\n";
    } catch (PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
?>
