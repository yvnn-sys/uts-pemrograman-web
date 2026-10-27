<?php
require "../koneksi.php";

$sql = "SELECT * FROM nilaimhs";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Rekap Nilai Mahasiswa</h3>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<tr>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Nilai Akhir</th>
            <th>Nilai Huruf</th>
            <th>Predikat</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['nim'] . "</td>
                <td>" . $row['nama_mhs'] . "</td>
                <td>" . $row['nilai_akhir'] . "</td>
                <td>" . $row['nilai_huruf'] . "</td>
                <td>" . $row['predikat'] . "</td>
                <td>" . $row['preg_date'] . "</td>
                <td>
                    <a class='btn btn-warning btn-sm' href='edit_mhs.php?nim=" . urlencode($row['nim']) . "'>Edit</a>
                    <a class='btn btn-danger btn-sm' href='hapusmhs.php?nim=" . urlencode($row['nim']) . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Delete</a>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "Data nilai belum tersedia.";
}

echo "<br><a href='form_mhs.html' class='btn btn-primary btn-sm'>Tambah Nilai</a>";

mysqli_close($conn);
?>