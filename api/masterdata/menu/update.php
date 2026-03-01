<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_menu = $data['id_menu'] ?? 0;
$nama    = trim($data['nama'] ?? 0);
$id_kategori = trim($data['id_kategori'] ?? 0);
$Stok    = trim($data['Stok'] ?? 0);
$ketersediaan = trim($data['ketersediaan'] ?? 1);
$harga   = trim($data['harga'] ?? 0);
$sql = "UPDATE menu
        SET nama=?, id_kategori=?, Stok=?, ketersediaan=?, harga=?
        WHERE id_menu=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("siiiii", $nama, $id_kategori, $Stok, $ketersediaan, $harga, $id_menu);
$stmt->execute();

jsonResponse(true, "Menu berhasil diupdate");