<?php
  include "koneksi.php";

  // Cek apakah ada ID yang dikirim melalui URL untuk update
  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data siswa berdasarkan ID
    $query = "SELECT * FROM tb_siswa WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    // Jika data tidak ditemukan, alihkan ke halaman utama
    if (!$data) {
      header("Location: index.php");
      exit;
    }
  }

  // Proses penambahan atau update data
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jenis_laptop = $_POST['jenis_laptop'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];

    if (isset($id)) {
      // Hapus data lama untuk kemudian diupdate
      $query = "DELETE FROM tb_siswa WHERE nis = '$nis'";
      mysqli_query($koneksi, $query);

      // Loop untuk memasukkan data baru setiap laptop
      for ($i = 0; $i < count($jenis_laptop); $i++) {
        $laptop = $jenis_laptop[$i];
        $tgl_peminjaman = $tanggal_peminjaman[$i];
        $tgl_pengembalian = $tanggal_pengembalian[$i];

        $query = "INSERT INTO tb_siswa (nis, nama, kelas, laptop, tanggal_peminjaman, tanggal_pengembalian) 
                  VALUES ('$nis', '$nama', '$kelas', '$laptop', '$tgl_peminjaman', '$tgl_pengembalian')";
        mysqli_query($koneksi, $query);
      }

      // Redirect ke halaman detail siswa setelah update
      header("Location: index.php?id=$id");
    } else {
      // Loop untuk memasukkan data baru setiap laptop
      for ($i = 0; $i < count($jenis_laptop); $i++) {
        $laptop = $jenis_laptop[$i];
        $tgl_peminjaman = $tanggal_peminjaman[$i];
        $tgl_pengembalian = $tanggal_pengembalian[$i];

        $query = "INSERT INTO tb_siswa (nis, nama, kelas, laptop, tanggal_peminjaman, tanggal_pengembalian) 
                  VALUES ('$nis', '$nama', '$kelas', '$laptop', '$tgl_peminjaman', '$tgl_pengembalian')";
        mysqli_query($koneksi, $query);
      }

      // Mendapatkan ID dari siswa yang baru ditambahkan
      $last_id = mysqli_insert_id($koneksi);

      // Redirect ke halaman detail siswa setelah penambahan
      header("Location: detail.php?id=$last_id");
    }
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo isset($id) ? 'Update' : 'Tambah'; ?> Siswa</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    .laptop-container {
      margin-bottom: 1rem;
    }
    .delete-btn {
      display: none; /* Hide delete button initially */
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>

  <section class="row">
    <div class="col-md-8 offset-md-2 align-self-center"> 
      <h1 class="text-center"><?php echo isset($id) ? 'Update' : 'Tambah'; ?> Siswa</h1>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="nis" class="form-label">NIS</label>
          <input type="text" class="form-control" id="nis" name="nis" value="<?php echo isset($data) ? htmlspecialchars($data['nis']) : ''; ?>" required>
        </div>
        <div class="mb-3">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" class="form-control" id="nama" name="nama" value="<?php echo isset($data) ? htmlspecialchars($data['nama']) : ''; ?>" required>
        </div>
        <div class="mb-3">
          <label for="kelas" class="form-label">Kelas</label>
          <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo isset($data) ? htmlspecialchars($data['kelas']) : ''; ?>" required>
        </div>

        <div id="laptopContainer">
          <div class="laptop-container" data-index="1">
            <label for="selectLaptop1" class="form-label">Laptop</label>
            <select class="form-control" id="selectLaptop1" name="jenis_laptop[]" required>
              <option value="">Pilih Laptop</option>
              <option value="ACER">ACER</option>
              <option value="LENOVO">LENOVO</option>
              <option value="ASUS">ASUS</option>
              <option value="DELL">DELL</option>
            </select>
            <label for="inputTanggalPeminjaman1" class="form-label">Tanggal Peminjaman</label>
            <input type="date" class="form-control" id="inputTanggalPeminjaman1" name="tanggal_peminjaman[]" required>
            <label for="inputTanggalPengembalian1" class="form-label">Tanggal Pengembalian</label>
            <input type="date" class="form-control" id="inputTanggalPengembalian1" name="tanggal_pengembalian[]" required>
            <button type="button" class="btn btn-danger delete-btn" data-index="1">Hapus Laptop</button>
          </div>
        </div>

        <button type="button" class="btn btn-secondary" id="addLaptop">Tambah Laptop</button>

        <button type="submit" class="btn btn-primary"><?php echo isset($id) ? 'Update' : 'Tambah'; ?></button>
      </form>

    </div>
  </section>

  <!-- JavaScript untuk menambahkan dan menghapus input laptop secara dinamis -->
  <script>
    let laptopCount = 1;

    function updateDeleteButtons() {
      const containers = document.querySelectorAll('.laptop-container');
      const showDelete = containers.length > 1;

      containers.forEach(container => {
        const deleteBtn = container.querySelector('.delete-btn');
        deleteBtn.style.display = showDelete ? 'inline-block' : 'none';
      });
    }

    document.getElementById('addLaptop').addEventListener('click', function() {
      laptopCount++;
      const laptopContainer = document.getElementById('laptopContainer');
      
      const newLaptopHTML = `
        <div class="laptop-container" data-index="${laptopCount}">
          <label for="selectLaptop${laptopCount}" class="form-label">Laptop</label>
          <select class="form-control" id="selectLaptop${laptopCount}" name="jenis_laptop[]" required>
            <option value="">Pilih Laptop</option>
            <option value="ACER">ACER</option>
            <option value="LENOVO">LENOVO</option>
            <option value="ASUS">ASUS</option>
            <option value="DELL">DELL</option>
          </select>
          <label for="inputTanggalPeminjaman${laptopCount}" class="form-label">Tanggal Peminjaman</label>
          <input type="date" class="form-control" id="inputTanggalPeminjaman${laptopCount}" name="tanggal_peminjaman[]" required>
          <label for="inputTanggalPengembalian${laptopCount}" class="form-label">Tanggal Pengembalian</label>
          <input type="date" class="form-control" id="inputTanggalPengembalian${laptopCount}" name="tanggal_pengembalian[]" required>
          <button type="button" class="btn btn-danger delete-btn" data-index="${laptopCount}">Hapus Laptop</button>
        </div>
      `;
      
      laptopContainer.insertAdjacentHTML('beforeend', newLaptopHTML);
      updateDeleteButtons(); // Update visibility of delete buttons
    });

    document.getElementById('laptopContainer').addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('delete-btn')) {
        const laptopContainer = e.target.closest('.laptop-container');
        laptopContainer.remove();
        updateDeleteButtons(); // Update visibility of delete buttons
      }
    });

    // Initialize delete button visibility
    updateDeleteButtons();
  </script>

</body>
</html>
