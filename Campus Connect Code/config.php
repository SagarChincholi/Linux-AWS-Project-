<?php
// config.php — database connection for CampusConnect 2026
// Update the four values below to match your MariaDB/MySQL setup.

$db_host = "localhost";
$db_name = "studentdb";
$db_user = "root";
$db_pass = "sagar";

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die("Database connection failed. Check the credentials in config.php.");
}
