<?php
require_once "../../config/database.php";
require_once "../../helpers/response.php";

$sql = "
  SELECT
    u.id_user,
    u.nama,
    u.email,
    u.password,
    u.id_role,
    r.pekerjaan AS role
  FROM user u
  LEFT JOIN role r ON u.id_role = r.id_role
  ORDER BY u.id_user ASC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    jsonResponse(false, mysqli_error($conn), null, 500);
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

jsonResponse(true, "User list", $data);