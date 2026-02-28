<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nama     = trim($data['nama'] ?? '-');
$email    = trim($data['email'] ?? '-');
$password = trim($data['password'] ?? '-');
$id_role  = $data['id_role'] ?? 0;

$sql = "INSERT INTO user (nama, email, password, id_role)
        VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
$stmt->bind_param("sssi", $nama, $email, $password, $id_role);
$stmt->execute();

jsonResponse(true, "User berhasil ditambahkan");