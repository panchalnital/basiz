<?php
// Database connection settings - update to match your environment
$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "student_db";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    http_response_code(500);
    die(json_encode(["success" => false, "message" => "Database connection failed: " . mysqli_connect_error()]));
}

mysqli_set_charset($conn, "utf8mb4");
