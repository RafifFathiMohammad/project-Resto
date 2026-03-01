<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_detail      = $data['id_detail'] ?? 0;
$id_pemesanan   = $data['id_pemesanan'] ?? 0;
$id_menu        = $data['id_menu'] ?? 0;
$jumlah         = trim($data['jumlah'] ?? 0);
$subtotal       = trim($data['subtotal'] ?? 0);
$catatan        = trim($data['catatan'] ?? 0);

// Tambahkan baris ini sebelum query INSERT/UPDATE
$catatan = (!isset($data['catatan']) || trim($data['catatan']) === "") ? "-" : trim($data['catatan']);

$sql = "UPDATE detail_pemesanan
        SET id_menu=?, jumlah=?, subtotal=?, catatan=?
        WHERE id_detail=? AND id_pemesanan=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("iiiiis", $id_menu, $jumlah, $subtotal, $catatan, $id_detail, $id_pemesanan);
$stmt->execute();

jsonResponse(true, "Detail pemesanan berhasil diupdate");