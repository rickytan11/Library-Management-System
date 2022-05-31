<?php
require 'php/function.php';
require 'php/fpdf.php';
$DaftarMember = new TableList();
$DaftarMember->CheckLoginSession();
$pdf = new FPDF();
if (isset($_POST["submit"])) {
    $result = $DaftarMember->addMember($_POST["name"], $_POST["email"], $_POST["phoneNumber"], $_POST["address"]);
    $DaftarMember->CheckSukses($result, 'Member berhasil ditambah', 'Member sudah pernah mendaftar', 'Email sudah pernah terdaftar mohon menggunakan email yg belum terdaftar', 'ada error');
}
if (isset($_POST["delete"])) {
    $DaftarMember->Delete('tb_siswa', $_POST['idSiswa']);
}
if (isset($_POST["update"])) {
    $result = $DaftarMember->UpdateMember($_POST["id"], $_POST["name"], $_POST["email"], $_POST["phoneNumber"], $_POST["address"]);
    $DaftarMember->CheckSukses($result, 'Member berhasil diupdate',  'Nama atau No telepon ada duplikat', 'Email ada Duplikat', 'ada error');
}
if (isset($_POST["print"])) {
    $pdf->AddPage();
    $width_cell = array(7, 35, 58, 25, 65);
    $pdf->SetFont('Arial', 'B', 11);

    $pdf->SetFillColor(193, 229, 252);
    // Header Start
    $pdf->Cell($width_cell[0], 5, 'No', 1, 0, 'C', true);
    $pdf->Cell($width_cell[1], 5, 'Nama', 1, 0, 'C', true);
    $pdf->Cell($width_cell[2], 5, 'Email', 1, 0, 'C', true);
    $pdf->Cell($width_cell[3], 5, 'No.Telepon', 1, 0, 'C', true);
    $pdf->Cell($width_cell[4], 5, 'Address', 1, 1, 'C', true);
    //header end

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetFillColor(235, 236, 236);
    $fill = false; //alternate background colour

    //each record is 1 row
    $result_set = $DaftarMember->ListOrder('tb_siswa', 'nama', 'ASC');
    $i = 1;
    foreach ($result_set as $row) {
        $pdf->Cell($width_cell[0], 10, $i, 1, 0, 'C', $fill);
        $pdf->Cell($width_cell[1], 10, $row['nama'], 1, 0, 'L', $fill);
        $pdf->Cell($width_cell[2], 10, $row['email'], 1, 0, 'L', $fill);
        $pdf->Cell($width_cell[3], 10, $row['phoneNumber'], 1, 0, 'C', $fill);
        $pdf->Cell($width_cell[4], 10, $row['address'], 1, 1, 'L', $fill);
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
    <script type="text/javascript" src="assets/jquery/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="assets/jquery/jquery-ui.js"></script>
    <link rel="stylesheet" href="assets/jquery/jquery-ui.css">
    <script src="assets/js/button.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#namaSiswa").autocomplete({
                source: 'php/autocompleteDbSiswa.php',
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
                            <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title" style="font-size: 35px; ">Menu</li>

                        <li class="sidebar-item">
                            <a href="dashboard.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item active">
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
                <h3>Member</h3>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header" style="font-size: 25px;">
                        Daftar Member
                    </div>


                    <div class="card-body">
                        <div class="col-6 col-lg-5   col-md-5 mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Tambah Member</button>
                        </div>
                        <form class="form form-horizontal" method="POST" autocomplete="off" action="">
                            <div class="col-6 col-lg-5   col-md-10 mb-3">
                                <input type="text" id="namaSiswa" class="form-control" name="searchTerm" placeholder="Search">
                                <div class="mt-3">
                                <button type="submit" name="search" class="btn btn-secondary me-1 mb-1">Search</button>
                                <button type="submit" name="semua" class="btn btn-secondary me-1 mb-1">All</button>
                                <button type="submit" name="print" class="btn btn-secondary me-1 mb-1">Print</button>
                                </div>
                            </div>
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Member</h5>
                                    </div>
                                    <div class="card-content">

                                        <div class="card-body">
                                            <form class="form form-horizontal" method="POST" autocomplete="off" action="">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" id="first-name" class="form-control" name="name" autocomplete="off" placeholder="Name" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="email" id="email-id" class="form-control" name="email" autocomplete="off" placeholder="Email" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Phone Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="number" id="contact-info" class="form-control" name="phoneNumber" autocomplete="off" placeholder="Phone Number" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group">
                                                            <input type="text" id="Address" class="form-control" name="address" placeholder="Address" autocomplete="off" required>
                                                        </div>
                                                        <div class="col-sm-12 d-flex justify-content-end">
                                                            <button type="submit" name="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->

                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $dataMember = $DaftarMember->List('tb_siswa');

                                if (isset($_POST["search"])) {
                                    $dataMember = $DaftarMember->ListSearch('tb_siswa', $_POST["searchTerm"], 'nama', 'phoneNumber');
                                }
                                if (isset($_POST["semua"])) {
                                    $dataMember = $DaftarMember->List('tb_siswa');
                                }

                                foreach ($dataMember as $d) {
                                ?>
                                    <tr>
                                        <td><?php echo $d['nama']; ?></td>
                                        <td><?php echo $d['email']; ?></td>
                                        <td><?php echo $d['phoneNumber']; ?></td>
                                        <td><?php echo $d['address']; ?></td>
                                        <td>
                                            <button style="border: 0;"><span class="badge bg-success" data-bs-toggle="modal" data-bs-target="#exampleModalCenter<?php echo $d['id']; ?>">Edit</span></button>
                                            <form method="POST" autocomplete="off" action="">
                                                <button style="border: 0;" type="submit" name="delete"><span class="badge bg-danger">Delete</span></button>
                                                <input type="hidden" name="idSiswa" value="<?php echo $d['id']; ?>">
                                            </form>
                                        </td>
                                    </tr>
                                    <!--Modal-->
                                    <div class="modal fade" id="exampleModalCenter<?php echo $d['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Buku</h5>
                                                </div>
                                                <div class="card-content">

                                                    <div class="card-body">
                                                        <form class="form form-horizontal" action="" method="POST">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Name</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" id="first-name" class="form-control" name="name" value="<?php echo $d['nama']; ?>" autocomplete="off" placeholder="Name" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Email</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="email" id="email-id" class="form-control" name="email" value="<?php echo $d['email']; ?>" autocomplete="off" placeholder="Email" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Phone Number</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="number" id="contact-info" class="form-control" name="phoneNumber" value="<?php echo $d['phoneNumber']; ?>" autocomplete="off" placeholder="Phone Number" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Address</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" id="Address" class="form-control" name="address" value="<?php echo $d['address']; ?>" autocomplete="off" placeholder="Address" required>
                                                                        <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                                                                    </div>
                                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                                        <button type="submit" name="update" class="btn btn-primary me-1 mb-1">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Modal-->
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