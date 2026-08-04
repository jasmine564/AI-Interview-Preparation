<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h3>MySQL Port Probe</h3>";

$ports = [3306, 3307, 3308, 3309];
$hosts = ['127.0.0.1', 'localhost'];

foreach ($ports as $port) {
    foreach ($hosts as $host) {
        try {
            // timeout=1 to fail fast
            $dsn = "mysql:host=$host;port=$port;timeout=1";
            echo "Trying $dsn ... ";
            $conn = new PDO($dsn, "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<b style='color:green'>SUCCESS! Connected to MySQL on port $port</b><br>";
            // If connection works, check DB
            try {
                $conn->exec("USE ai_interview_db");
                echo "&nbsp;&nbsp;-> Database 'ai_interview_db' FOUND.<br>";
                $stmt = $conn->query("SELECT count(*) FROM roles");
                echo "&nbsp;&nbsp;-> Roles count: " . $stmt->fetchColumn() . "<br>";
            } catch (Exception $e) {
                echo "&nbsp;&nbsp;-> <span style='color:orange'>Database 'ai_interview_db' NOT FOUND or inaccessible: " . $e->getMessage() . "</span><br>";
            }
            exit; // Found a working connection, stop.
        } catch (Exception $e) {
            echo "<span style='color:red'>FAILED</span> (" . $e->getMessage() . ")<br>";
        }
    }
}
echo "<hr>Could not connect to MySQL on any standard port.";
?>
