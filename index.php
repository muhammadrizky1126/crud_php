<!-- Panggil file koneksi, karena kita membutuhkannya -->
<?php
  include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Halaman Utama</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>

  <section class="row">
    <div class="col-md-8 offset-md-2 align-self-center"> 
      <h1 class="text-center">Daftar Pinjaman Siswa</h1>
      <a href="tambah.php" class="btn btn-primary mb-2">Tambah</a>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Nis</th>
            <th scope="col">Nama</th>
            <th scope="col">Kelas</th>
            <th scope="col">Jenis Laptop</th> <!-- Kolom untuk Jenis Laptop -->
            <th scope="col">Tanggal Peminjaman</th> <!-- Kolom untuk Tanggal Peminjaman -->
            <th scope="col">Tanggal Pengembalian</th> <!-- Kolom untuk Tanggal Pengembalian -->
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <?php
          $no = 1;
          $query = "SELECT * FROM tb_siswa"; // Pastikan tabel tb_siswa memiliki kolom tanggal_peminjaman dan tanggal_pengembalian
          $result = mysqli_query($koneksi, $query);
        ?>
        <tbody>
          <?php
            foreach ($result as $data) {
              echo "
                <tr>
                  <th scope='row'>". $no++ ."</th>
                  <td>". $data["nis"] ."</td>
                  <td>". $data["nama"] ."</td>
                  <td>". $data["kelas"] ."</td>
                  <td>". $data["laptop"] ."</td> <!-- Tampilkan Jenis Laptop -->
                  <td>". $data["tanggal_peminjaman"] ."</td> <!-- Tampilkan Tanggal Peminjaman -->
                  <td>". $data["tanggal_pengembalian"] ."</td> <!-- Tampilkan Tanggal Pengembalian -->
                  <td> 
                    <a href='update.php?id=".$data["id"]."' type='button' class='btn btn-success'>Update</a>
                    <a href='delete.php?id=".$data["id"]."' type='button' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus data?\")'>Delete</a>
                     <a href='detail.php?id=".$data["id"]."' type='button' class='btn btn-success'>Detail</a>
                  </td>
                </tr>  
              ";
            }
          ?>
        </tbody>  
      </table>
    </div>
  </section>

</body>
</html>
