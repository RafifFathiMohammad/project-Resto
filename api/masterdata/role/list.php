<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "SELECT id_role, pekerjaan FROM role ORDER BY id_role ASC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "Role list", $data);