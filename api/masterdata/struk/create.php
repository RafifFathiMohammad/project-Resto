<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

// Ambil data JSON dari Vue
$data = json_decode(file_get_contents("php://input"), true);

// Proteksi data: Paksa menjadi Integer jika itu ID atau Angka
// Ambil data dan pastikan jika 0 atau kosong, diubah jadi NULL
$id_meja = (!isset($data['id_meja']) || $data['id_meja'] == 0) ? null : (int)$data['id_meja'];
$id_pemesanan  = (int)($data['id_pemesanan'] ?? 0);
$id_pengalaman = (int)($data['id_pengalaman'] ?? 0);
$id_pegawai    = (int)($data['id_pegawai'] ?? 0);
$id_payment    = (int)($data['id_payment'] ?? 0);
$total         = (int)($data['total'] ?? 0);
$tanggal       = $data['tanggal'] ?? date("Y-m-d");
$status        = (int)($data['status'] ?? 0); 

$sql = "INSERT INTO struk_pembayaran (id_meja, id_pemesanan, id_pengalaman, id_pegawai, id_payment, total, tanggal, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

// Karena id_meja bisa NULL, kita harus menangani bind_param dengan hati-hati
// Cara paling aman adalah mengirimkan NULL ke database
$stmt->bind_param("iiiiissi", $id_meja, $id_pemesanan, $id_pengalaman, $id_pegawai, $id_payment, $total, $tanggal, $status);

if (mysqli_stmt_execute($stmt)) {
    jsonResponse(true, "Data struk berhasil masuk!");
} else {
    // Ini akan memunculkan error asli MySQL jika masih gagal
    jsonResponse(false, "MySQL Error: " . mysqli_error($conn));
}