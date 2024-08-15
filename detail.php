<?php
include "koneksi.php";

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data siswa berdasarkan ID
    $query = "SELECT * FROM tb_siswa WHERE id = $id";
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah hasilnya tidak null
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Ambil Nama dan NIS siswa
        $nama = $data['nama'];
        $nis = $data['nis'];

        // Query untuk mengambil semua data pinjaman berdasarkan nama siswa
        $queryLoans = "SELECT * FROM tb_siswa WHERE nama = '$nama'";
        $resultLoans = mysqli_query($koneksi, $queryLoans);
    } else {
        echo "Data tidak ditemukan!";
        exit;
    }
} else {
    echo "ID tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Siswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<section class="row">
    <div class="col-md-8 offset-md-2 align-self-center">
        <h1 class="text-center">Detail Siswa</h1>

        <!-- Menampilkan Nama dan NIS -->
        <div class="mb-4">
            <h3>Nama: <?php echo $nama; ?></h3>
            <h4>NIS: <?php echo $nis; ?></h4>
        </div>

        <!-- Menampilkan daftar laptop yang dipinjam -->
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Jenis Laptop</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($resultLoans && mysqli_num_rows($resultLoans) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($resultLoans)) { ?>
                    <tr>
                        <td><?php echo $row['laptop']; ?></td>
                        <td><?php echo $row['tanggal_peminjaman']; ?></td>
                        <td><?php echo $row['tanggal_pengembalian']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data peminjaman untuk siswa ini.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-primary">Kembali</a>
    </div>
</section>

</body>
</html>
`