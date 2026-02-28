<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "app_resto";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}