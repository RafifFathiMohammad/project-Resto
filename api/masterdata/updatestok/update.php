<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_update = $data['id_update'] ?? 0;
$id_menu = trim($data['id_menu'] ?? 0);
$jumlah_porsi    = trim($data['jumlah_porsi'] ?? 0);
$tanggal_update = trim($data['tanggal_update'] ?? date('Y-m-d'));
$sql = "UPDATE update_stok_harian
        SET id_menu=?, id_kategori=?, jumlah_porsi=?, tanggal_update=?
        WHERE id_update=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("iisi", $id_menu, $jumlah_porsi, $tanggal_update, $id_update);
$stmt->execute();

jsonResponse(true, "Update Stok berhasil diupdate");