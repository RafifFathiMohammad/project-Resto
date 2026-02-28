<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id   = $data['id_meja'] ?? '';
$meja = $data['meja'] ?? '';
$jumlah_kursi = $data['jumlah_kursi'] ?? '';
$ketersediaan = $data['ketersediaan'] ?? 1; // Default ke 1 (tersedia) jika tidak disediakan

if ($id === '' || $meja === '') {
    jsonResponse(false, "Invalid data", null, 400);
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE meja SET meja=?, jumlah_kursi=?, ketersediaan=? WHERE id_meja=?"
);
mysqli_stmt_bind_param($stmt, "ssii", $meja, $jumlah_kursi, $ketersediaan, $id);
mysqli_stmt_execute($stmt);

jsonResponse(true, "Meja updated");