<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_pemesanan, catatan FROM pemesanan ORDER BY id_pemesanan ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Pemesanan list", $data);