<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_meja, meja, jumlah_kursi, ketersediaan FROM meja ORDER BY id_meja ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Meja list", $data);