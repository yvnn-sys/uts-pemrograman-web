<?php
require_once __DIR__ . "/../koneksi.php";

// Penghapusan hanya dilakukan setelah formulir konfirmasi dikirim.
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["nim"]) || trim($_POST["nim"]) === "") {
    exit("Permintaan penghapusan tidak valid.");
}

$nim = trim($_POST["nim"]);
$sql = "DELETE FROM nilaimhs WHERE nim = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Gagal menyiapkan penghapusan data.");
}

mysqli_stmt_bind_param($stmt, "s", $nim);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: adminlte.php?status=hapus_berhasil");
        exit;
    }
    header("Location: adminlte.php?status=data_tidak_ditemukan");
    exit;
}

echo "Data nilai gagal dihapus.";
mysqli_stmt_close($stmt);
mysqli_close($conn);