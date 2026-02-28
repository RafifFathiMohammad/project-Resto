<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id_payment'] ?? '';
$type = $data['type'] ?? '';

if ($id === '' || $type === '') {
    jsonResponse(false, "Invalid data", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE payment SET type=? WHERE id_payment=?"
);
mysqli_stmt_bind_param($stmt, "si", $type, $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Payment updated");