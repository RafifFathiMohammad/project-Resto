<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "
  SELECT
    u.id_update,
    u.id_menu,
    m.nama AS nama,
    u.jumlah_porsi,
    u.tanggal_update
  FROM update_stok_harian u
  LEFT JOIN menu m ON u.id_menu = m.id_menu
  ORDER BY u.id_update ASC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    jsonResponse(false, mysqli_error($conn), null, 500);
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Update Stok list", $data);