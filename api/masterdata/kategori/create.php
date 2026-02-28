<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$kategori = $data['kategori'] ?? '';

if ($kategori === '') {
    jsonResponse(false, "Kategori is required", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO kategori (kategori) VALUES (?)"
);
mysqli_stmt_bind_param($stmt, "s", $kategori);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Kategori created");