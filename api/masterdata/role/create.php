<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$pekerjaan = $data['pekerjaan'] ?? '';

if ($pekerjaan === '') {
    jsonResponse(false, "Pekerjaan is required", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO role (pekerjaan) VALUES (?)"
);
mysqli_stmt_bind_param($stmt, "s", $pekerjaan);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Role created");