<?php
require 'function.php';
$connection = new Connection;
$searchTerm = $_GET['term'];

$select = mysqli_query($connection->conn, "SELECT * FROM tb_siswa WHERE nama LIKE '%" . $searchTerm . "%'");
$select1 = mysqli_query($connection->conn, "SELECT * FROM tb_siswa WHERE phoneNumber LIKE '%" . $searchTerm . "%'");
while ($row = mysqli_fetch_array($select)) {
    $data[] = $row['nama'];
}
while ($row = mysqli_fetch_array($select1)) {
    $data[] = $row['phoneNumber'];
}
//return json data
echo json_encode($data);
