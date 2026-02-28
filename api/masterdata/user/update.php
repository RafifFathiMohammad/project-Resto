<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_user = $data['id_user'] ?? 0;
$nama    = trim($data['nama'] ?? '-');
$email   = trim($data['email'] ?? '-');
$password = trim($data['password'] ?? '-');
$id_role = $data['id_role'] ?? 0;

$sql = "UPDATE user
        SET nama=?, email=?, password=?, id_role=?
        WHERE id_user=?";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("ssssi", $nama, $email, $password, $id_role, $id_user);
$stmt->execute();

jsonResponse(true, "User berhasil diupdate");