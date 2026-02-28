<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$meja = $data['meja'] ?? '';
$jumlah_kursi = $data['jumlah_kursi'] ?? '';
$ketersediaan = $data['ketersediaan'] ?? 1; // Default ke 1 (tersedia) jika tidak disediakan

if ($meja === '') {
    jsonResponse(false, "Meja is required", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO meja (meja, jumlah_kursi, ketersediaan) VALUES (?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "ssi",   $meja, $jumlah_kursi, $ketersediaan);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Meja created");