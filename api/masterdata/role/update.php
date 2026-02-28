<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id_role'] ?? '';
$pekerjaan = $data['pekerjaan'] ?? '';

if ($id === '' || $pekerjaan === '') {
    jsonResponse(false, "Invalid data", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE role SET pekerjaan=? WHERE id_role=?"
);
mysqli_stmt_bind_param($stmt, "si", $pekerjaan, $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Role updated");