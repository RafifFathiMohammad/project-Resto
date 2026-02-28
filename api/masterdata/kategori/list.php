<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_kategori, kategori FROM kategori ORDER BY id_kategori ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Kategori list", $data);