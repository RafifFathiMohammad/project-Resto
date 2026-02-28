<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? '';

if ($type === '') {
    jsonResponse(false, "Type is required", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO pengalaman (type) VALUES (?)"
);
mysqli_stmt_bind_param($stmt, "s", $type);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Pengalaman created");