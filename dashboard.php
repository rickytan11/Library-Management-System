<?php
require 'php/function.php';

$dashboard = new Dashboard();
$dashboard->CheckLoginSession();
$viewBukuBaru = new TableList();

if (isset($_POST["submit"])) {
    foreach ($_POST["namaBuku"] as $row => $value) {
        $buku = $_POST["namaBuku"][$row];
        $result = $viewBukuBaru->addPeminjam($_POST["namaPeminjam"], $_POST["phoneNumber"], $buku, $_POST["date"], $_POST["keteranganPeminjam"]);
    }
    $viewBukuBaru->CheckSukses($result, 'Berhasil Dipinjamkan', 'Buku tidak ada', 'Nama Peminjam atau No telpon anda salah atau tidak terdaftar', 'Stocknya lagi kosong');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazer Admin Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
    <script type="text/javascript" src="assets/jquery/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="assets/jquery/jquery-ui.js"></script>
    <link rel="stylesheet" href="assets/jquery/jquery-ui.css">
    <script src="assets/js/button.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#1").autocomplete({
                source: 'php/autocompleteDbBuku.php'
            });
        });
        $(function() {
            $("#first-name-vertical").autocomplete({
                source: 'php/autocompleteDbSiswa.php'
            });
        });
        $(function() {
            $("#notelpon").autocomplete({
                source: 'php/autocompleteDbSiswa.php'
            });
        });
    </script>
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <h3>Selamat Datang,</h3>
                            <h3><?php $dashboard->CallName("nama"); ?></h3>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title" style="font-size: 40px;">Menu</li>

                        <li class="sidebar-item active ">
                            <a href="dashboard.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="daftar-member.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Daftar Member</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="daftar-buku.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Daftar Buku</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="daftar-peminjam.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Daftar Peminjam</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="logout.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <svg class="bi" width="1em" height="1em" fill="currentColor">
                                                    <use xlink:href="assets/vendors/bootstrap-icons/bootstrap-icons.svg#book-fill" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Judul Buku</h6>
                                            <h6 class="font-extrabold mb-0"><?php echo $dashboard->ViewAngka('nama', 'tb_buku') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="iconly-boldProfile"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Total Member</h6>
                                            <h6 class="font-extrabold mb-0"><?php echo $dashboard->ViewAngka('nama', 'tb_siswa') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="iconly-boldAdd-User"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Buku Dipinjam</h6>
                                            <h6 class="font-extrabold mb-0"><?php echo $dashboard->ViewAngkaWhere('status', 'tb_peminjam', 'belum') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Pengembalian</h6>
                                            <h6 class="font-extrabold mb-0"><?php echo $dashboard->ViewAngkaWhere('status', 'tb_peminjam', 'sudah') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tambah Peminjam</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form form-vertical" method="POST" action="" autocomplete="off">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="first-name-vertical">Member</label>
                                                        <input type="text" id="first-name-vertical" class="form-control" name="namaPeminjam" placeholder="Nama Member" autocomplete="">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">No Telepon</label>
                                                        <input type="number" id="notelpon" class="form-control" name="phoneNumber" placeholder="No Telepon" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email-id-vertical">Buku</label>
                                                        
                                                        <ul style="float:right">
                                                        <button style="width: 50px; font-size: 12px;" type="button" onclick="add()" class="btn btn-primary center-block">Add</button>
                                                        <button style="width: 100px; font-size: 12px;" type="button" onclick="remove()" class="btn btn-primary center-block">Remove</button>
                                                        </ul>
                                                        <input type="text" id="1" class="form-control" name="namaBuku[]" placeholder="Judul Buku" autocomplete="">
                                                        <div id="new_chq"></div>
                                                        <input type="hidden" value="1" id="total_chq">

                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="email-id-vertical">Batas Peminjaman (Hari)</label>
                                                        <input type="date" id="email-id-vertical" class="form-control" name="date" placeholder="" value="<?php echo date('Y-m-d') ?>" autocomplete="">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password-vertical">Keterangan Peminjam</label>
                                                        <input type="text" id="password-vertical" class="form-control" name="keteranganPeminjam" placeholder="Keterangan Peminjam">
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" name="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">New Books</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <!-- Table with no outer spacing -->
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-lg">
                                            <thead>
                                                <tr>
                                                    <th>Judul</th>
                                                    <th>Pengarang</th>
                                                    <th>Lokasi Buku</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dataBuku = $viewBukuBaru->ListLimited('tb_buku', 5);
                                                foreach ($dataBuku as $d) {
                                                ?>
                                                    <tr>
                                                        <td class="text-bold-500"><?php echo $d['nama']; ?></td>
                                                        <td><?php echo $d['pengarang']; ?></td>
                                                        <td class="text-bold-500"><?php echo $d['lokasiBuku']; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2022 &copy; Deadline</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with by Team Deadline</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>