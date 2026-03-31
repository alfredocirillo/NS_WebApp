<?php

$servername = getenv('MYSQL_HOST') ?: 'db';
$username = getenv('MYSQL_USER') ?: 'webapp_user';
$password = getenv('MYSQL_PASSWORD') ?: 'webapp_pass';
$dbname = getenv('MYSQL_DB') ?: 'webapp_db';

$maxRetries = 10;
$retryDelay = 2; // seconds
$attempt = 0;

while ($attempt < $maxRetries) {
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            throw new Exception($conn->connect_error);
        }

        break; // success
    } catch (Exception $e) {
        $attempt++;
        sleep($retryDelay);
    }
}

if ($attempt === $maxRetries) {
    die("Database connection failed after $maxRetries attempts");
}

$conn->set_charset("utf8mb4");
?>