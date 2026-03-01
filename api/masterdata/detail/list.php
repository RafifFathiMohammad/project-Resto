<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "
  SELECT
    d.id_detail,
    d.id_Pemesanaan,
    p.id_Pemesanan AS Pemesanan,
    d.id_menu,
    m.nama AS nama_menu,
    d.jumlah,
    d.subtotal,
    d.catatan
  FROM detail_pemesanan d
  LEFT JOIN pemesanan p ON d.id_Pemesanaan = p.id_Pemesanan
  LEFT JOIN menu m ON d.id_menu = m.id_menu
  ORDER BY d.id_detail ASC
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