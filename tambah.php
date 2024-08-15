<?php
  include "koneksi.php";
  

  if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
  }

  // Proses saat tombol submit ditekan
  if (isset($_POST['daftar'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jenis_laptop = $_POST['jenis_laptop'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];

    // Loop untuk memasukkan data setiap laptop
    for ($i = 0; $i < count($jenis_laptop); $i++) {
      $laptop = $jenis_laptop[$i];
      $tgl_peminjaman = $tanggal_peminjaman[$i];

      // Menghitung tanggal pengembalian otomatis (1 minggu setelah peminjaman)
      $tgl_pengembalian = date('Y-m-d', strtotime($tgl_peminjaman. ' + 7 days'));

      $query = "INSERT INTO tb_siswa (nis, nama, kelas, laptop, tanggal_peminjaman, tanggal_pengembalian) 
                VALUES ('$nis', '$nama', '$kelas', '$laptop', '$tgl_peminjaman', '$tgl_pengembalian')";

      $result = mysqli_query($koneksi, $query);

      if (!$result) {
        echo "<script>alert('Data Gagal di tambahkan!')</script>";
        echo "Error: " . mysqli_error($koneksi);
        exit();
      }
    }

    header("Location: index.php");
    exit();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Halaman Tambah Data</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

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
      <h1 class="text-center mt-4">Form data siswa pinjam laptop</h1>
      <form method="POST">
        <div class="mb-3">
          <label for="inputNis" class="form-label">Nis</label>
          <input type="number" class="form-control" id="inputNis" name="nis" placeholder="Masukan Nis Siswa" required>
        </div>
        <div class="mb-3">
          <label for="selectKelas" class="form-label">Kelas</label>
          <select class="form-control" id="selectKelas" name="kelas" required>
            <option value="">Pilih Kelas</option>
            <option value="PPLG XII-6">PPLG XII-6</option>
            <option value="PPLG XXI-5">PPLG XII-5</option>
            <option value="PPLG XXI-4">PPLG XII-4</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="selectNama" class="form-label">Nama</label>
          <select class="form-control" id="selectNama" name="nama" required>
            <option value="">Pilih Nama</option>
            <option value="YANTO">YANTO</option>
            <option value="RUDI">RUDI</option>
            <option value="TAKA">TAKA</option>
            <option value="IBONG">IBONG</option>
          </select>
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
            <button type="button" class="btn btn-danger delete-btn" data-index="1">Hapus Laptop</button>
          </div>
        </div>

        <button type="button" class="btn btn-secondary" id="addLaptop">Tambah Laptop</button>

        <input name="daftar" type="submit" class="btn btn-primary" value="Tambah">
        <a href="index.php" type="button" class="btn btn-info text-white">Kembali</a>
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

    // Function to auto set return date 1 week after loan date
    document.getElementById('laptopContainer').addEventListener('change', function(e) {
      if (e.target && e.target.type === 'date') {
        const index = e.target.closest('.laptop-container').getAttribute('data-index');
        const loanDate = document.getElementById(`inputTanggalPeminjaman${index}`).value;
        const returnDateField = document.getElementById(`inputTanggalPengembalian${index}`);
        
        if (loanDate) {
          const loanDateObj = new Date(loanDate);
          loanDateObj.setDate(loanDateObj.getDate() + 7);
          const formattedReturnDate = loanDateObj.toISOString().split('T')[0];
          returnDateField.value = formattedReturnDate;
        }
      }
    });

    // Initialize delete button visibility
    updateDeleteButtons();
  </script>

</body>
</html>
