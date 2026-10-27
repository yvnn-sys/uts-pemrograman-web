<?php
require "../koneksi.php";

$nim = $_POST['nim'];
$nama_mhs = $_POST['nama_mhs'];
$nilai_akhir = $_POST['nilai_akhir'];
$nilai_huruf = $_POST['nilai_huruf'];
$predikat = $_POST['predikat'];

//sql to insert data
$sql = "INSERT INTO  (nim, nama_mhs, nilai_akhir, nilai_huruf, predikat, preg_date) VALUES 
('$nim', '$nama_mhs', '$nilai_akhir', '$nilai_huruf', '$predikat', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

mysqli_close($conn);
?>