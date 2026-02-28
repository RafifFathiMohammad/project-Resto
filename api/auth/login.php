<?php

// CORS HEADERS (WAJIB untuk Ionic)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Wajib paling atas agar CORS header dikirim sebelum proses lain
require_once "../helpers/response.php"; 
require_once "../config/database.php";

// Ambil JSON input
$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    jsonResponse(false, "Email and password required", null, 400);
}

// TAMBAHKAN 'password' di SELECT agar data password ikut dikirim ke frontend
$sql = "SELECT id_user, nama, email, password, id_role 
        FROM user 
        WHERE email = ? AND password = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    jsonResponse(false, "Invalid email or password", null, 401);
}

$user = mysqli_fetch_assoc($result);

// Cek role admin
if ($user['id_role'] != 1) {
    jsonResponse(false, "Access denied. Admin only.", null, 403);
}

// Sekarang $user sudah mengandung field 'password' yang bisa dibaca
jsonResponse(true, "Login success", $user);

mysqli_stmt_close($stmt);
mysqli_close($conn);