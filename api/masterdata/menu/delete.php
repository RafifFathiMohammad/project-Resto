<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id_menu'] ?? 0;

$sql = "DELETE FROM menu WHERE id_menu=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("i", $id);
$stmt->execute();

jsonResponse(true, "Menu berhasil dihapus");