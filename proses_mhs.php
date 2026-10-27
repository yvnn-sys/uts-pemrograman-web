<?php
require "../koneksi.php";

$nim = $_POST['nim'];
$nama_lengkap = $_POST['nama_lengkap'];
$nilai_akhir = $_POST['nilai_akhir'];

//validasi nama mahasiswa
if (!preg_match("/^[a-zA-Z ]+$/", $nama_lengkap)) {
    echo "Nama mahasiswa hanya boleh berisi huruf dan spasi.";
    echo "<br><a href='adminlte.php'>Kembali</a>";
} else {
//menentukan nilai huruf dan predikat
if ($nilai_akhir >= 81) {
    $nilai_huruf = "A";
    $predikat = "Sangat Memuaskan";
} elseif ($nilai_akhir >= 71) {
    $nilai_huruf = "B";
    $predikat = "Memuaskan";
} elseif ($nilai_akhir >= 61) {
    $nilai_huruf = "C";
    $predikat = "Cukup Memuaskan";
} elseif ($nilai_akhir >= 41) {
    $nilai_huruf = "D";
    $predikat = "Kurang Memuaskan";
} else {
    $nilai_huruf = "E";
    $predikat = "Tidak Lulus";
}

//upload foto mahasiswa
$foto = "";
$validasi = true;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] != 4) {
    $namafile = $_FILES['foto']['name'];
    $ukuran = $_FILES['foto']['size'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ekstensi = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ekstensi, $ekstensi_valid)) {
        echo "Format foto harus JPG, JPEG, PNG, atau GIF.";
        $validasi = false;
    } elseif ($ukuran > 3000000) {
        echo "Ukuran foto maksimal 3 MB.";
        $validasi = false;
    } elseif (getimagesize($tmp) === false) {
        echo "File yang diupload harus berupa gambar.";
        $validasi = false;
    } else {
        //membuat nama file baru agar tidak sama
        $foto = uniqid() . "." . $ekstensi;

        if (!move_uploaded_file($tmp, "foto/" . $foto)) {
            echo "Foto gagal diupload.";
            $validasi = false;
        }
    }
}

//menyimpan data mahasiswa
if ($validasi == true) {
    $sql = "INSERT INTO nilaimhs
            (nim, nama_mhs, nilai_akhir, nilai_huruf, predikat, preg_date, foto)
            VALUES
            ('$nim', '$nama_lengkap', '$nilai_akhir', '$nilai_huruf', '$predikat', CURRENT_TIMESTAMP(), '$foto')";

    if ($conn->query($sql) === TRUE) {
        echo "Data nilai berhasil disimpan";
        echo "<br><a href='adminlte.php'>Lihat Data</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
}

mysqli_close($conn);
?>