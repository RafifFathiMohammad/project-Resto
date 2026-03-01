<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id_detail'] ?? 0;

$sql = "DELETE FROM detail_pemesanan WHERE id_detail=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("i", $id);
$stmt->execute();

jsonResponse(true, "Detail pemesanan berhasil dihapus");