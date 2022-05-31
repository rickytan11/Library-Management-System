<?php
require 'php/function.php';
require 'php/fpdf.php';
$ViewPeminjam = new TableList();
$ViewPeminjam->CheckLoginSession();
$pdf = new FPDF();
if (isset($_POST["delete"])) {
    if ($_POST["status"] === 'belum') {
        $ViewPeminjam->DeletePeminjamPlus('tb_peminjam', $_POST["idPeminjam"], $_POST["namaBuku"]);
    } else if ($_POST["status"] === 'sudah') {
        $ViewPeminjam->DeletePeminjamMines('tb_peminjam', $_POST["idPeminjam"]);
    }
}
if (isset($_POST["dikembalikan"])) {
    $ViewPeminjam->MauKembalikanBuku('sudah', $_POST["idPeminjam"], $_POST["namaBuku"]);
}
if (isset($_POST["belum_dikembalikan"])) {
    $ViewPeminjam->BelumKembalikanBuku('belum', $_POST["idPeminjam"], $_POST["namaBuku"]);
}
if (isset($_POST["print"])) {
    $pdf->AddPage();
    $width_cell = array(7, 35, 25, 75, 30, 18); //total 190 biar bagus
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->SetFillColor(193, 229, 252);
    // Header Start
    $pdf->Cell($width_cell[0], 5, 'No', 1, 0, 'C', true);
    $pdf->Cell($width_cell[1], 5, 'Nama', 1, 0, 'C', true);
    $pdf->Cell($width_cell[2], 5, 'No.Telepon', 1, 0, 'C', true);
    $pdf->Cell($width_cell[3], 5, 'Buku', 1, 0, 'C', true);
    $pdf->Cell($width_cell[4], 5, 'Batas waktu', 1, 0, 'C', true);
    $pdf->Cell($width_cell[5], 5, 'status', 1, 1, 'C', true);
    //header end

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetFillColor(235, 236, 236);
    $fill = false; //alternate background colour

    //each record is 1 row
    $result_set = $ViewPeminjam->ListOrder('tb_peminjam', 'date', 'ASC');
    $i = 1;
    foreach ($result_set as $row) {
        $pdf->Cell($width_cell[0], 10, $i, 1, 0, 'C', $fill);
        $pdf->Cell($width_cell[1], 10, $row['nama_peminjam'], 1, 0, 'L', $fill);
        $pdf->Cell($width_cell[2], 10, $row['phoneNumber'], 1, 0, 'C', $fill);
        $pdf->Cell($width_cell[3], 10, $row['nama_buku'], 1, 0, 'L', $fill);
        $pdf->Cell($width_cell[4], 10, $row['date'], 1, 0, 'C', $fill);
        $pdf->Cell($width_cell[5], 10, $row['status'], 1, 1, 'C', $fill);
        $fill = !$fill; //to give alternate background colour
        $i++;
    }
    //end if record
    $pdf->Output();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazer Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">

</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title" style="font-size: 35px;">Menu</li>

                        <li class="sidebar-item">
                            <a href="dashboard.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item ">
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
                        <li class="sidebar-item active">
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
                <h3>Peminjam</h3>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header" style="font-size: 25px;">
                        Daftar Peminjam
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" method="POST" autocomplete="off" action="">
                            <div class="col-6 col-lg-5   col-md-10 mb-3">
                                <input type="text" id="namaPeminjam" class="form-control" name="searchTerm" placeholder="Search">
                                <div class="mt-3">
                                    <button type="submit" name="search" class="btn btn-secondary me-1 mb-1">Search</button>
                                    <button type="submit" name="semua" class="btn btn-secondary me-1 mb-1">All</button>
                                    <button type="submit" name="print" class="btn btn-secondary me-1 mb-1">Print</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Nomor Telepon</th>
                                    <th>Buku</th>
                                    <th>Batas Peminjaman (Hari)</th>
                                    <th>Keterangan Peminjam</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $dataPeminjam = $ViewPeminjam->List('tb_peminjam');
                                if (isset($_POST["search"])) {
                                    $dataPeminjam = $ViewPeminjam->ListSearchPeminjam('tb_peminjam', $_POST["searchTerm"], 'nama_peminjam', 'phoneNumber', 'nama_buku', 'status');
                                }
                                if (isset($_POST["semua"])) {
                                    $dataPeminjam = $ViewPeminjam->List('tb_peminjam');
                                }
                                foreach ($dataPeminjam as $d) {
                                ?>
                                    <tr>
                                        <td><?php echo $d['nama_peminjam']; ?></td>
                                        <td><?php echo $d['phoneNumber']; ?></td>
                                        <td><?php echo $d['nama_buku']; ?></td>
                                        <td><?php echo $d['date']; ?></td>
                                        <td><?php echo $d['keterangan_peminjam']; ?></td>
                                        <td><?php if ($d['date'] >= date('Y-m-d') or $d['status'] === 'sudah') {
                                                echo $d['status'];
                                            } else {
                                                echo 'telat';
                                            }
                                            ?></td>
                                        <td>
                                            <form method="POST" action="" autocomplete="off">
                                                <button style="padding: 0;border: none;background: none;" name="delete"><span class="badge bg-danger">Delete</span></button>
                                                <?php
                                                if ($d['status'] === 'sudah') {
                                                    echo  "<button style='padding: 0;border: none;background: none;' name='belum_dikembalikan'><span class='badge bg-primary'>Belum Dikembalikan</span></button>";
                                                } else if ($d['status'] === 'belum') {
                                                    echo  "<button style='padding: 0;border: none;background: none;' name='dikembalikan'><span class='badge bg-primary'>Dikembalikan</span></button>";
                                                }
                                                ?>
                                                <input type="hidden" name="idPeminjam" value="<?php echo $d['id']; ?>">
                                                <input type="hidden" name="namaBuku" value="<?php echo $d['nama_buku']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $d['status']; ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>

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
    <script src="dashboard.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>