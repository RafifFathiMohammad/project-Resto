<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$data = json_decode(file_get_contents("php://input"), true);

// 1. Ambil data (Pastikan nama key JSON sama dengan yang dikirim Vue)
$id_struk      = (int)($data['id_struk'] ?? 0);
$id_meja       = isset($data['id_meja']) ? (int)$data['id_meja'] : null; // Gunakan null jika kosong
$id_pemesanan  = (int)($data['id_pemesanan'] ?? 0);
$id_pengalaman = (int)($data['id_pengalaman'] ?? 0); // Sesuaikan dengan Vue
$id_pegawai    = (int)($data['id_pegawai'] ?? 0);
$id_payment    = (int)($data['id_payment'] ?? 0);
$total         = (int)($data['total'] ?? 0);
$tanggal       = $data['tanggal'] ?? date("Y-m-d");
$status        = (int)($data['status'] ?? 0);

// 2. Query Update (8 Kolom + 1 WHERE)
$sql = "UPDATE struk_pembayaran SET 
        id_meja=?, id_pemesanan=?, id_pengalaman=?, id_pegawai=?, 
        id_payment=?, total=?, tanggal=?, status=? 
        WHERE id_struk=?";

$stmt = mysqli_prepare($conn, $sql);

// 3. Bind Param (Urutan: 6 integer, 1 string tanggal, 1 integer status, 1 integer id_struk)
// String format: "iiiiissii" -> 9 karakter sesuai tanda tanya
$stmt->bind_param("iiiiissii", 
    $id_meja, 
    $id_pemesanan, 
    $id_pengalaman, 
    $id_pegawai, 
    $id_payment, 
    $total, 
    $tanggal, 
    $status, 
    $id_struk
);

if (mysqli_stmt_execute($stmt)) {
    jsonResponse(true, "Data berhasil diperbarui");
} else {
    jsonResponse(false, "Gagal update: " . mysqli_error($conn));
}