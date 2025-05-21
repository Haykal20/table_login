<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT m.* FROM mahasiswa m 
          JOIN users u ON m.nim = (
              SELECT nim FROM mahasiswa WHERE id = (
                  SELECT MAX(id) FROM mahasiswa WHERE nama = (
                      SELECT nama FROM users WHERE username = '$username' LIMIT 1
                  )
              )
          )
          WHERE u.username = '$username' LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$mahasiswa = mysqli_fetch_assoc($result);

if (!$mahasiswa) {
    die("Data mahasiswa tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Mahasiswa ILKOM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Sistem Mahasiswa</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <span class="nav-link">Halo, <?php echo $mahasiswa['nama']; ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="mb-4">Daftar Mahasiswa</h2>
    <table id="mahasiswaTable" class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>NIM</th>
          <th>Nama Mahasiswa</th>
          <th>Semester</th>
          <th>Mata Kuliah</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query_all = "SELECT * FROM mahasiswa";
        $result_all = mysqli_query($conn, $query_all);
        
        if (!$result_all) {
            die("Query error: " . mysqli_error($conn));
        }

        $no = 1;
        while ($row = mysqli_fetch_assoc($result_all)) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nim']}</td>
                    <td>{$row['nama']}</td>
                    <td>3</td>
                    <td>Ilkom</td>
                  </tr>";
            $no++;
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Script -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#mahasiswaTable').DataTable();
    });
  </script>
</body>
</html>