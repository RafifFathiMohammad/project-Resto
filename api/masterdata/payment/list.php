<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_payment, type FROM payment ORDER BY id_payment ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Payment list", $data);