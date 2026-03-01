<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "
  SELECT
    m.id_menu,
    m.nama,
    m.id_kategori,
    k.kategori AS kategori,
    m.Stok,
    m.ketersediaan,
    m.harga
  FROM menu m
  LEFT JOIN kategori k ON m.id_kategori = k.id_kategori
  ORDER BY m.id_menu ASC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    jsonResponse(false, mysqli_error($conn), null, 500);
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Menu list", $data);