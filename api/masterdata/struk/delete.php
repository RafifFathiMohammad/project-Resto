<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id_struk'] ?? 0;

$sql = "DELETE FROM struk_pembayaran WHERE id_struk=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if (mysqli_error($conn)) {
    jsonResponse(false, "Gagal menghapus struk");
}
jsonResponse(true, "struk berhasil dihapus");