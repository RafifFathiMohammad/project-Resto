<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_menu     = trim($data['id_menu'] ?? 0);
$jumlah_porsi     = trim($data['jumlah_porsi'] ?? 0);
$tanggal_update = trim($data['tanggal_update'] ?? date('Y-m-d'));


$sql = "INSERT INTO update_stok_harian (id_menu, jumlah_porsi, tanggal_update)

        VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("iis", $id_menu, $jumlah_porsi, $tanggal_update);
$stmt->execute();

jsonResponse(true, "Update Stok berhasil ditambahkan");