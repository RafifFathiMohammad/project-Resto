<?php
require_once "../config/database.php";
require_once "../helpers/response.php";

// Handle OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ambil JSON input
$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    jsonResponse(false, "Email and password required", null, 400);
}

// Prepared Statement
$sql = "SELECT id_user, nama, email, id_role 
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

// Login sukses
jsonResponse(true, "Login success", $user);

mysqli_stmt_close($stmt);
mysqli_close($conn);