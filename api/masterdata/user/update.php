<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_user = $data['id_user'] ?? 0;
$nama    = trim($data['nama'] ?? '-');
$email   = trim($data['email'] ?? '-');
$id_role = $data['id_role'] ?? 0;

$sql = "UPDATE user
        SET nama=?, email=?, id_role=?
        WHERE id_user=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("ssii", $nama, $email, $id_role, $id_user);
$stmt->execute();

jsonResponse(true, "User berhasil diupdate");