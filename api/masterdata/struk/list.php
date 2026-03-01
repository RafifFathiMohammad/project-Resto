<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "
  SELECT
    s.id_struk,
    s.id_meja,
    m.meja AS meja,
    s.id_pemesanan,
    ps.id_pemesanan AS pemesanan,
    s.id_pengalaman,
    pl.type AS pengalaman,
    s.id_pegawai,
    u.nama AS pegawai,
    s.id_payment,
    p.type AS payment,
    s.total,
    s.tanggal,
    s.status
  FROM struk_pembayaran s
  LEFT JOIN meja m ON s.id_meja = m.id_meja
  LEFT JOIN pemesanan ps ON s.id_pemesanan = ps.id_pemesanan
  LEFT JOIN Pengalaman pl ON s.id_pengalaman = pl.id_pengalaman
  LEFT JOIN user u ON s.id_pegawai = u.id_user
  LEFT JOIN payment p ON s.id_payment = p.id_payment
  ORDER BY s.id_struk ASC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    jsonResponse(false, mysqli_error($conn), null, 500);
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}


jsonResponse(true, "Detail list", $data);