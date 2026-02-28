<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_pengalaman, type FROM pengalaman ORDER BY id_pengalaman ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Pengalaman list", $data);