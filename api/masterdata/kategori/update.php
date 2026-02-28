<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id_kategori'] ?? '';
$kategori = $data['kategori'] ?? '';

if ($id === '' || $kategori === '') {
    jsonResponse(false, "Invalid data", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE kategori SET kategori=? WHERE id_kategori=?"
);
mysqli_stmt_bind_param($stmt, "si", $kategori, $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Kategori updated");