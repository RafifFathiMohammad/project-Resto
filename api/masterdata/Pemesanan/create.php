<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$catatan = $data['catatan'] ?? '';

if ($catatan === '') {
    $catatan = '-';
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO pemesanan (catatan) VALUES (?)"
);
mysqli_stmt_bind_param($stmt, "s", $catatan);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Pemesanan created");