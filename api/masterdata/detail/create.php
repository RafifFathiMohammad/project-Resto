<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_pemesanan   = $data['id_pemesanan'] ?? 0;
$id_menu        = $data['id_menu'] ?? 0;
$jumlah         = $data['jumlah'] ?? 0;
$subtotal       = $data['subtotal'] ?? 0;
// LOGIKA: Jika catatan kosong atau null, isi dengan "-"
$catatan        = (!isset($data['catatan']) || trim($data['catatan']) === "") ? "-" : trim($data['catatan']);

$sql = "INSERT INTO detail_pemesanan (id_Pemesanaan, id_menu, jumlah, subtotal, catatan) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("iiiis", $id_pemesanan, $id_menu, $jumlah, $subtotal, $catatan);

if (mysqli_stmt_execute($stmt)) {
    jsonResponse(true, "Detail pemesanan berhasil ditambahkan");
} else {
    jsonResponse(false, "Gagal menambahkan detail");
}