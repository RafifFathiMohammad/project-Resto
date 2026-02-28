<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id_kategori'] ?? '';

if ($id === '') {
    jsonResponse(false, "ID required", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM kategori WHERE id_kategori=?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Kategori deleted");