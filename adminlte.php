<?php
//koneksi ke database
require_once __DIR__ . "/../koneksi.php";

//mengambil kata pencarian, filter, dan halaman
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$predikat = isset($_GET['predikat']) ? trim($_GET['predikat']) : "";
$halaman = isset($_GET['halaman']) ? max(1, (int) $_GET['halaman']) : 1;
$batas = 10;
$urutan = isset($_GET['urutan']) ? $_GET['urutan'] : 'ASC';

if ($urutan != 'ASC' && $urutan != 'DESC') {
  $urutan = 'ASC';
}

//daftar predikat dibuat tetap agar selalu tampil di filter
$daftarPredikat = [
    "Sangat Memuaskan",
    "Memuaskan",
    "Cukup Memuaskan",
    "Kurang Memuaskan",
    "Tidak Lulus"
];
if ($predikat !== "" && !in_array($predikat, $daftarPredikat, true)) {
    $predikat = "";
}

//menyusun kondisi pencarian dan filter
$kondisi = [];
$types = "";
$params = [];
if ($cari !== "") {
    $kondisi[] = "(nama_mhs LIKE ?)";
    $kataKunci = "%" . $cari . "%";
    $types .= "s";
    $params[] = $kataKunci;
}
if ($predikat !== "") {
    $kondisi[] = "predikat = ?";
    $types .= "s";
    $params[] = $predikat;
}
$sqlWhere = count($kondisi) > 0 ? " WHERE " . implode(" AND ", $kondisi) : "";

//menghitung seluruh data yang sesuai dengan pencarian/filter
$sqlJumlah = "SELECT COUNT(*) AS total FROM nilaimhs" . $sqlWhere;
$stmtJumlah = mysqli_prepare($conn, $sqlJumlah);
if ($types !== "") {
    mysqli_stmt_bind_param($stmtJumlah, $types, ...$params);
}
mysqli_stmt_execute($stmtJumlah);
$hasilJumlah = mysqli_stmt_get_result($stmtJumlah);
$total = (int) mysqli_fetch_assoc($hasilJumlah)['total'];
$totalHalaman = max(1, (int) ceil($total / $batas));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
}
$offset = ($halaman - 1) * $batas;

// mengambil data untuk halaman yang sedang dibuka
$sql = "SELECT * FROM nilaimhs" . $sqlWhere . " ORDER BY nilai_akhir " . $urutan . " LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $sql);
$typesData = $types . "ii";
$paramsData = $params;
$paramsData[] = $batas;
$paramsData[] = $offset;
mysqli_stmt_bind_param($stmt, $typesData, ...$paramsData);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jumlah = mysqli_num_rows($result);

function e($nilai) {
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}
function linkHalaman($tujuan, $cari, $predikat, $urutan) {
    return '?' . http_build_query(['cari' => $cari, 'predikat' => $predikat, 'urutan' => $urutan,'halaman' => $tujuan]);
}
$mulai = $total > 0 ? $offset + 1 : 0;
$sampai = $total > 0 ? $offset + $jumlah : 0;
?>
<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Nilai Mahasiswa</title>

  <!-- CSS AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <style>
    body {
      min-height: 100vh;
      background-color: #343a40;
    }
    .app-content-header {
      padding-top: 1rem;
      padding-bottom: 1rem;
    }
    .app-content-header {
      padding: .75rem 0 1rem;
    }
    .app-content > .container-fluid {
      padding-left: 1rem;
      padding-right: 1rem;
    }
    .card {
      border: 1px solid rgba(255,255,255,.14);
      border-radius: .55rem;
      box-shadow: 0 1px 4px rgba(0,0,0,.2);
      overflow: hidden;
    }
    .card-header {
      min-height: 74px;
      padding: 1rem 1.25rem;
      gap: 1rem;
    }
    .card-title {
      font-size: 1.15rem;
      white-space: nowrap;
    }
    .table th, .table td {
      vertical-align: middle;
      white-space: nowrap;
      padding: .8rem 1rem;
    }
    .table thead th {
      font-weight: 700;
      border-bottom-width: 1px;
    }
    .table-responsive {
      overflow-x: auto;
    }
    .card-header-tools {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: .5rem;
      flex-wrap: nowrap;
      margin-left: auto;
    }
    .search-form {
      width: 265px;
      margin: 0;
    }
    .search-form .input-group-text,
    .search-form .form-control,
    .card-header-tools > .btn,
    .filter-form .form-select {
      min-height: 40px;
      font-size: .95rem;
    }
    .search-form .input-group-text {
      padding: .45rem .7rem;
    }
    .search-form .form-control {
      min-width: 0;
      padding: .45rem .7rem;
    }
    .filter-form .form-select {
      width: 160px;
      padding: .45rem 2rem .45rem .7rem;
    }
    .card-header-tools > .btn {
      padding: .45rem .8rem;
      white-space: nowrap;
    }
    .card-footer {
      padding: .8rem 1.25rem;
      min-height: 58px;
      display: flex;
      align-items: center;
    }
    .table .btn-group .btn {
      min-width: 42px;
      min-height: 40px;
    }
    @media (max-width: 767px) {
      .card-header {
        align-items: flex-start !important;
        gap: .75rem;
        flex-direction: column;
      }
      .card-header-tools {
        width: 100%;
        margin-left: 0;
        flex-wrap: wrap;
        justify-content: flex-start;
      }
      .search-form {
        width: auto;
        flex: 1 1 180px;
        min-width: 150px;
      }
      .filter-form .form-select {
        width: 150px;
      }
    }
  </style>
</head>
<body class="bg-body-tertiary">
  <main class="app-main">
    <!-- Judul halaman -->
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Rekap Nilai Mahasiswa</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="adminlte.php">Beranda</a></li>
              <li class="breadcrumb-item active" aria-current="page">Rekap Nilai Mahasiswa</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel nilai mahasiswa -->
    <div class="app-content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h3 class="card-title mb-0">Data Nilai Mahasiswa</h3>
                <div class="card-header-tools">
                  <form method="get" action="adminlte.php" class="search-form">
                    <input type="hidden" name="predikat" value="<?php echo htmlspecialchars($predikat, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-search"></i></span>
                      <input type="search" name="cari" class="form-control" placeholder="Cari Nama..." aria-label="Cari data mahasiswa" value="<?php echo htmlspecialchars($cari, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                  </form>
                  <form method="get" action="adminlte.php" class="filter-form">
                    <input type="hidden" name="cari" value="<?php echo htmlspecialchars($cari, ENT_QUOTES, 'UTF-8'); ?>">
                    <select name="predikat" class="form-select form-select-sm" aria-label="Filter predikat" onchange="this.form.submit()">
                      <option value="">Semua Predikat</option>
                      <?php foreach ($daftarPredikat as $opsi) { ?>
                        <option value="<?php echo e($opsi); ?>" <?php echo ($predikat === $opsi) ? 'selected' : ''; ?>><?php echo e($opsi); ?></option>
                      <?php } ?>
                    </select>
                  </form>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Data Nilai
                  </button>
                </div>
              </div>

              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    <thead>
                      <tr>
                        <th scope="col" class="ps-4">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">
                            Nilai Akhir
                            <a href="?cari=<?php echo urlencode($cari); ?>&amp;predikat=<?php echo urlencode($predikat); ?>&amp;urutan=ASC">
                              ▲
                            </a>
                            <a href="?cari=<?php echo urlencode($cari); ?>&amp;predikat=<?php echo urlencode($predikat); ?>&amp;urutan=DESC">
                              ▼
                            </a>
                        </th>
                        <th scope="col">Nilai Huruf</th>
                        <th scope="col">Predikat</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="text-center pe-4">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($jumlah > 0) { ?>
                        <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                          <tr>
                            <td class="ps-4"><?php echo htmlspecialchars($data['nim']); ?></td>
                            <td>
                              <?php
                              //menampilkan foto mahasiswa
                              if (!empty($data['foto']) && file_exists("foto/" . $data['foto'])) {
                                echo '<img src="foto/' . htmlspecialchars($data['foto']) . '" width="40" height="40" style="object-fit: cover; border-radius: 50%; margin-right: 8px">';
                              }
                              ?>
                              <?php echo htmlspecialchars($data['nama_mhs']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($data['nilai_akhir']); ?></td>
                            <td><?php echo htmlspecialchars($data['nilai_huruf']); ?></td>
                            <td><?php echo htmlspecialchars($data['predikat']); ?></td>
                            <td><?php echo htmlspecialchars($data['preg_date']); ?></td>
                            <td class="text-center pe-4">
                              <div class="btn-group" role="group" aria-label="Aksi data">
                                <a href="edit_mhs.php?nim=<?php echo urlencode($data['nim']); ?>" class="btn btn-outline-secondary" title="Edit">
                                  <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger tombol-hapus" title="Hapus" data-nim="<?php echo e($data['nim']); ?>" data-nama="<?php echo e($data['nama_mhs']); ?>" data-bs-toggle="modal" data-bs-target="#modalHapus">
                                  <i class="bi bi-trash"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        <?php } ?>
                      <?php } else { ?>
                        <tr>
                          <td colspan="7" class="text-center py-4">Tidak ada nama mahasiswa yang sesuai.</td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer clearfix">
                <div class="float-start pt-1 fs-7 text-body-secondary">
                  Menampilkan <?php echo $mulai; ?> sampai <?php echo $sampai; ?> dari <?php echo $total; ?> data mahasiswa
                </div>
                <ul class="pagination pagination-sm m-0 float-end">
                  <li class="page-item <?php echo $halaman <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo e(linkHalaman(max(1, $halaman - 1), $cari, $predikat, $urutan)); ?>" aria-label="Sebelumnya">&laquo;</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalHalaman; $i++) { ?>
                    <li class="page-item <?php echo $i === $halaman ? 'active' : ''; ?>">
                      <a class="page-link" href="<?php echo e(linkHalaman($i, $cari, $predikat, $urutan)); ?>"><?php echo $i; ?></a>
                    </li>
                  <?php } ?>
                  <li class="page-item <?php echo $halaman >= $totalHalaman ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo e(linkHalaman(min($totalHalaman, $halaman + 1), $cari, $predikat, $urutan)); ?>" aria-label="Berikutnya">&raquo;</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal tambah data nilai -->
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="judulModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
      <form action="proses_mhs.php" method="post" enctype="multipart/form-data">
        <div class="modal-header"><h5 class="modal-title" id="judulModalTambah">Tambah Data Nilai</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body">
          <div class="mb-3"><label for="nim" class="form-label">NIM</label><input type="text" class="form-control" id="nim" name="nim" required></div>
          <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control"
                    id="nama_lengkap"
                    name="nama_lengkap"
                    pattern="[A-Za-z ]+"
                    title="Nama hanya boleh berisi huruf dan spasi"
                    required>
          </div>
          <div class="mb-3">
              <label for="foto" class="form-label">Foto Mahasiswa</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/jpeg, image/png, image/gif">
          </div>
          <div class="mb-3"><label for="nilai_akhir" class="form-label">Nilai Akhir</label><input type="number" class="form-control" id="nilai_akhir" name="nilai_akhir" min="0" max="100" step="0.01" required></div>
          <small class="text-body-secondary">Nilai huruf dan predikat ditentukan otomatis saat data disimpan.</small>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div></div>
  </div>

  <!-- Modal konfirmasi hapus data -->
  <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="judulModalHapus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="hapusmhs.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="judulModalHapus">Hapus Data Nilai</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p class="mb-2">Apakah kamu yakin ingin menghapus data nilai mahasiswa ini?</p>
            <p class="mb-0">Data yang sudah dihapus tidak dapat dikembalikan.</p>
            <p class="mt-3 mb-0">Mahasiswa: <strong id="namaMahasiswaHapus"></strong></p>
            <input type="hidden" name="nim" id="nimHapus" value="">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.tombol-hapus').forEach(function (tombol) {
      tombol.addEventListener('click', function () {
        document.getElementById('nimHapus').value = this.dataset.nim;
        document.getElementById('namaMahasiswaHapus').textContent = this.dataset.nama;
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.js"></script>
</body>
</html>