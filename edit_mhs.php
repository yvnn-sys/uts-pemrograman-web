<?php
require "../koneksi.php";

// mengambil nim dari halaman sebelumnya
if (!isset($_GET['nim'])) {
    exit("NIM tidak ditemukan. Silakan pilih data dari halaman utama.");
}

$nim = $_GET['nim'];

// mengambil data berdasarkan nim
$sql = "SELECT * FROM nilaimhs WHERE nim = '$nim'";
$result = $conn->query($sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $nim = $row['nim'];
    $nama_lengkap = $row['nama_mhs'];
    $nilai_akhir = $row['nilai_akhir'];
} else {
    exit("Data mahasiswa tidak ditemukan. Silakan kembali ke halaman utama.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Nilai Mahasiswa</title>
</head>
<body>

<h2>Form Edit Nilai Mahasiswa</h2>

<form action="updatemhs.php" method="post">
    <label>NIM:</label><br>
    <input type="text" name="nim" value="<?php echo $nim; ?>" readonly><br><br>

    <label>Nama Mahasiswa:</label><br>
    <input type="text" name="nama_lengkap" value="<?php echo $nama_lengkap; ?>" required><br><br>

    <label>Nilai Akhir:</label><br>
    <input type="number" name="nilai_akhir" min="0" max="100" step="0.01"
           value="<?php echo $nilai_akhir; ?>" required><br><br>

    <input type="submit" value="Update">
    <input type="button" value="Cancel" onclick="history.back()">
</form>

</body>
</html>

<?php
mysqli_close($conn);
?>