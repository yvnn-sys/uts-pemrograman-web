
<?php
require "../koneksi.php";

$nim = $_POST['nim'];
$nama_lengkap = $_POST['nama_lengkap'];
$nilai_akhir = $_POST['nilai_akhir'];

if ($nilai_akhir >= 85) {
    $nilai_huruf = "A";
    $predikat = "Sangat Baik";
} elseif ($nilai_akhir >= 75) {
    $nilai_huruf = "B";
    $predikat = "Baik";
} elseif ($nilai_akhir >= 60) {
    $nilai_huruf = "C";
    $predikat = "Cukup";
} elseif ($nilai_akhir >= 50) {
    $nilai_huruf = "D";
    $predikat = "Kurang";
} else {
    $nilai_huruf = "E";
    $predikat = "Sangat Kurang";
}

// sql to update data
$sql = "UPDATE nilaimhs SET
        nama_mhs = '$nama_lengkap',
        nilai_akhir = '$nilai_akhir',
        nilai_huruf = '$nilai_huruf',
        predikat = '$predikat'
        WHERE nim = '$nim'";

if ($conn->query($sql) === TRUE) {
    echo "Data nilai berhasil diupdate";
    echo "<br><a href='bacanilaimhs.php'>Kembali ke Data</a>";
} else {
    echo "Error: " . $conn->error;
}

mysqli_close($conn);
?>