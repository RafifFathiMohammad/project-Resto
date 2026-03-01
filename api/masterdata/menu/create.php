<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nama     = trim($data['nama'] ?? '-');
$id_kategori = $data['id_kategori'] ?? 0;
$Stok     = trim($data['Stok'] ?? 0);
$ketersediaan = trim($data['ketersediaan'] ?? 1);
$harga    = trim($data['harga'] ?? 0);

$sql = "INSERT INTO menu (nama, id_kategori, Stok, ketersediaan, harga)
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("siiii", $nama, $id_kategori, $Stok, $ketersediaan, $harga);
$stmt->execute();

jsonResponse(true, "Menu berhasil ditambahkan");