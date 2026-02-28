<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id_pemesanan'] ?? '';
$catatan = $data['catatan'] ?? '';

if ($id === '' || $catatan === '') {
    jsonResponse(false, "Invalid data", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE pemesanan SET catatan=? WHERE id_pemesanan=?"
);
mysqli_stmt_bind_param($stmt, "si", $catatan, $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Pemesanan updated");