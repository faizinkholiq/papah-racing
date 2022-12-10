<?php
date_default_timezone_set('Asia/Jakarta');
// error_reporting(0);
require 'config/connect.php';
require 'config/function.php';
session_start();
if (empty($_SESSION['id_user'])) {
    header('location:login');
}
$data_toko = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko"));
$data_jabatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM jabatan WHERE id_jabatan='" . $_SESSION['id_jabatan'] . "'"));
$id_user = $_SESSION['id_user'];
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.css" />
    <link rel="stylesheet" href="assets/datatables/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo strtotime(date('Y-m-d H:i:s')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/datatables/jquery.dataTables.min.js"></script>

    <link rel="icon" href="assets/img/<?= $data_toko['logo_title']; ?>">
    <title><?= $data_toko['nama_toko']; ?></title>
</head>

<body>
<?php if ($_SESSION['id_jabatan'] == '8'||$_SESSION['id_jabatan'] == '7'||$_SESSION['id_jabatan'] == '6'||$_SESSION['id_jabatan'] == '4') {} else { ?>
    <input type="checkbox" id="check" checked="checked">
    <div class="sidebar bg-dark">
        <!-- <header>Main Menu</header> -->
            <ul>
                <li><a href="index.php"><i class='fas fa-tachometer-alt mr-2'></i>Menu Utama</a></li>
                <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '3') { ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed text-truncate" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><i class="fas fa-folder-open mr-2"></i>Data Master</a>
                        <div class="collapse" id="submenu1" aria-expanded="false">
                            <ul class="flex-column nav bg-secondary">
                                <li class="nav-item"><a class="nav-link" href="main?url=pelanggan"><span class="ml-2"><i class='fas fa-handshake mr-2'></i>Data Pelanggan</span></a></li>
                                <li class="nav-item"><a class="nav-link" href="main?url=barang"><span class="ml-2"><i class='fas fa-box mr-2'></i>Data Barang</span></a></li>
                                <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
                                    <li class="nav-item"><a class="nav-link" href="main?url=supplier"><span class="ml-2"><i class='fas fa-store mr-2'></i>Data Supplier</span></a></li>
                                    <li class="nav-item"><a class="nav-link" href="main?url=user"><span class="ml-2"><i class='fas fa-user mr-2'></i>Data User</span></a></li>
                                    <li class="nav-item"><a class="nav-link" href="main?url=merk"><span class="ml-2"><i class='fas fa-bookmark mr-2'></i>Data Merk</span></a></li>
                                <?php } ?>
                                <li class="nav-item"><a class="nav-link" href="main?url=jenis-pengeluaran"><span class="ml-2"><i class='fas fa-shopping-basket mr-2'></i>Jenis Pengeluaran</span></a></li>
                                <li class="nav-item"><a class="nav-link" href="main?url=banner"><span class="ml-2"><i class='fas fa-images mr-2'></i>Banner</span></a></li>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '5') { ?>
                    <li><a href="main?url=pembelian"><i class='fas fa-shopping-cart mr-2'></i>Pembelian</a></li>
                <?php } ?>
                <li><a href="main?url=penjualan"><i class='fas fa-cash-register mr-2'></i>Penjualan</a></li>
                <li><a href="main?url=pengeluaran"><i class='fas fa-shopping-bag mr-2'></i>Pengeluaran</a></li>
                <li class="nav-item">
                    <a class="nav-link collapsed text-truncate" href="#submenu2" data-toggle="collapse" data-target="#submenu2"><i class="fas fa-book mr-2"></i>Laporan</a>
                    <div class="collapse" id="submenu2" aria-expanded="false">
                        <ul class="flex-column nav bg-secondary">
                            <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
                                <li class="nav-item"><a class="nav-link" href="main?url=laporan-pembelian"><span class="ml-2"><i class='fas fa-file-invoice mr-2'></i>Pembelian</span></a></li>
                            <?php } ?>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-penjualan"><span class="ml-2"><i class='fas fa-file-invoice-dollar mr-2'></i>Penjualan</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-pengeluaran"><span class="ml-2"><i class='fas fa-file-upload mr-2'></i>Pengeluaran</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-barang-keluar"><span class="ml-2"><i class='fas fa-box mr-2'></i>Barang Keluar</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-barang-masuk"><span class="ml-2"><i class='fas fa-box-open mr-2'></i>Barang Masuk</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-keuntungan"><span class="ml-2"><i class='fas fa-file-invoice-dollar mr-2'></i>Keuntungan</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=laporan-harian"><span class="ml-2"><i class='fas fa-calendar-alt mr-2'></i>Laporan Harian</span></a></li>
                        </ul>
                    </div>
                </li>
                <?php // if ($_SESSION['id_jabatan'] == '1') { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed text-truncate" href="#submenu3" data-toggle="collapse" data-target="#submenu3"><i class="fas fa-cog mr-2"></i>Pengaturan</a>
                    <div class="collapse" id="submenu3" aria-expanded="false">
                        <ul class="flex-column nav bg-secondary">
                            <li class="nav-item"><a class="nav-link" href="main?url=data-toko"><span class="ml-2"><i class='fas fa-store mr-2'></i>Data Toko</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=socmed"><span class="ml-2"><i class='fas fa-share-alt mr-2'></i>Social Media</span></a></li>
                            <li class="nav-item"><a class="nav-link" href="main?url=kontak"><span class="ml-2"><i class='fas fa-phone mr-2'></i>Kontak</span></a></li>
                        </ul>
                    </div>
                </li>
                <!-- <li><a href="main?url=data-toko"><i class='fas fa-cog mr-2'></i>Pengaturan</a></li> -->
                <?php // } ?>
            </ul>
     
    </div>
<?php } ?>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand font-weight-bolder" href="index.php"><small><?= $data_toko['nama_toko']; ?></small></a>
        <label for="check">
            <div class="menu-toggle">
                <i class='fas fa-bars'></i>
            </div>
        </label>
        <!-- <div class="dropdown ml-auto">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <span class="text-white font-weight-bolder"><i class="fas fa-cubes"></i>3</span>
            </a>
            <div class="dropdown-menu" style="height: 50vh; width: 400px; overflow:scroll;">
                <?php
                $cekstok = mysqli_query($con, "SELECT * FROM barang WHERE stok < 5");
                foreach ($cekstok as $cs) {
                    if ($cs['stok'] <= 2) {
                ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong> Sisa Stok Barang <strong><?= $cs['nama']; ?></strong> Sangat Minimal !!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong> Sisa Stok Barang <strong><?= $cs['nama']; ?></strong> Sudah Hampir Habis !!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                <?php }
                } ?>
            </div>
        </div> -->
        <div class="dropdown ml-auto">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="assets/img/user/no-photo.png" width="25px" class="rounded-circle mr-2" alt="Foto Profil"> -->
                <span class="text-white font-weight-bolder"><?= $_SESSION['nama'] ?> <small>(<?= $data_jabatan['nama'] ?>)</small></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="main?url=ganti-password-user"><i class='fas fa-key'></i> Ganti Password</a>
                <!-- <div class="dropdown-divider"></div> -->
                <a class="dropdown-item" href="process/action?url=logout"><i class='fas fa-sign-out-alt'></i> Logout</a>
            </div>
        </div>
        <!-- <?= date("Y-m-d h:i:s"); ?> -->
    </nav>
		
<?php 
echo '<section><div class="content">';
if ($_SESSION['id_jabatan'] == '8'||$_SESSION['id_jabatan'] == '7'||$_SESSION['id_jabatan'] == '6') {
	if (empty($_GET['url'])) {
		require 'page/dashboard.php';
	} else {
		$act = $_GET['url'];
		if ($act == 'reset-password-user') {
			require 'page/user/reset_password.php';
		} elseif ($act == 'ganti-password-user') {
			require 'page/user/ganti_password.php';
		} else if ($act == 'ubah-barang') {
			require 'page/barang/ubah.php';
		} else {
			header("location: main");
		}
	}
} elseif ($_SESSION['id_jabatan'] == '4'){
	if (empty($_GET['url'])) {
		require 'page/dashboard.php';
	} else {
		$act = $_GET['url'];
		if ($act == 'reset-password-user') {
			require 'page/user/reset_password.php';
		} elseif ($act == 'ganti-password-user') {
			require 'page/user/ganti_password.php';
		} else if ($act == 'ubah-barang') {
			require 'page/barang/ubah.php';
		} else if ($act == 'upload-barang') {
			require 'page/barang/upload.php';
		} else {
			header("location: main");
		}
	}
} else {
	if (empty($_GET['url'])) {
		require 'page/dashboard.php';
	} else {
		$act = $_GET['url'];
        // print_r($act);exit;
		if ($act == 'supplier') {
			require 'page/supplier/index.php';
		} else if ($act == 'tambah-supplier') {
			require 'page/supplier/tambah.php';
		} else if ($act == 'ubah-supplier') {
			require 'page/supplier/ubah.php';
		} else if ($act == 'pelanggan') {
			require 'page/pelanggan/index.php';
		} else if ($act == 'tambah-pelanggan') {
			require 'page/pelanggan/tambah.php';
		} else if ($act == 'ubah-pelanggan') {
			require 'page/pelanggan/ubah.php';
		} else if ($act == 'barang') {
			require 'page/barang/index.php';
		} else if ($act == 'tambah-barang') {
			require 'page/barang/tambah.php';
		} else if ($act == 'ubah-barang') {
			require 'page/barang/ubah.php';
		} else if ($act == 'upload-barang') {
			require 'page/barang/upload.php';
		} else if ($act == 'hapus-barang') {
			require 'page/barang/hapus.php';
		} else if ($act == 'user') {
			require 'page/user/index.php';
		} else if ($act == 'tambah-user') {
			require 'page/user/tambah.php';
		} else if ($act == 'ubah-user') {
			require 'page/user/ubah.php';
		} else if ($act == 'reset-password-user') {
			require 'page/user/reset_password.php';
		} else if ($act == 'ganti-password-user') {
			require 'page/user/ganti_password.php';
		} else if ($act == 'pembelian') {
			require 'page/pembelian/index.php';
		} else if ($act == 'tambah-pembelian') {
			require 'page/pembelian/tambah.php';
		} else if ($act == 'lihat-pembelian') {
			require 'page/pembelian/lihat.php';
		} else if ($act == 'cicilan-pembelian') {
			require 'page/pembelian/cicilan.php';
		} else if ($act == 'penjualan') {
			require 'page/penjualan/index.php';
		} else if ($act == 'tambah-penjualan') {
			require 'page/penjualan/tambah.php';
		} else if ($act == 'lihat-penjualan') {
			require 'page/penjualan/lihat.php';
		} else if ($act == 'cicilan-penjualan') {
			require 'page/penjualan/cicilan.php';
		} else if ($act == 'laporan-pembelian') {
			require 'page/laporan/pembelian.php';
		} else if ($act == 'laporan-penjualan') {
			require 'page/laporan/penjualan.php';
		} else if ($act == 'laporan-pengeluaran') {
			require 'page/laporan/pengeluaran.php';
		} else if ($act == 'laporan-barang-keluar') {
			require 'page/laporan/barang_keluar.php';
		} else if ($act == 'laporan-barang-masuk') {
			require 'page/laporan/barang_masuk.php';
		} else if ($act == 'laporan-keuntungan') {
			require 'page/laporan/keuntungan.php';
		} else if ($act == 'laporan-harian') {
			require 'page/laporan/harian.php';
		} else if ($act == 'jenis-pengeluaran') {
			require 'page/jenis_pengeluaran/index.php';
		} else if ($act == 'tambah-jenis-pengeluaran') {
			require 'page/jenis_pengeluaran/tambah.php';
		} else if ($act == 'ubah-jenis-pengeluaran') {
			require 'page/jenis_pengeluaran/ubah.php';
		} else if ($act == 'pengeluaran') {
			require 'page/pengeluaran/index.php';
		} else if ($act == 'tambah-pengeluaran') {
			require 'page/pengeluaran/tambah.php';
		} else if ($act == 'ubah-pengeluaran') {
			require 'page/pengeluaran/ubah.php';
		} else if ($act == 'data-toko') {
			require 'page/data_toko.php';
		} else if ($act == 'socmed') {
			require 'page/socmed/index.php';
		} else if ($act == 'ubah-socmed') {
			require 'page/socmed/ubah.php';
		} else if ($act == 'tambah-socmed') {
			require 'page/socmed/tambah.php';
		} else if ($act == 'kontak') {
			require 'page/kontak/index.php';
		} else if ($act == 'ubah-kontak') {
			require 'page/kontak/ubah.php';
		} else if ($act == 'tambah-kontak') {
			require 'page/kontak/tambah.php';
		} else if ($act == 'merk') {
			require 'page/merk/index.php';
		} else if ($act == 'ubah-merk') {
			require 'page/merk/ubah.php';
		} else if ($act == 'tambah-merk') {
			require 'page/merk/tambah.php';
		} else if ($act == 'banner') {
			require 'page/banner/index.php';
		} else {
			header("location: main");
		}
	}
}
echo '</div></section>';
?>
        
        <!-- <footer class="copyright">
            <div class="row">
                <div class="col-6"> -->
        <!-- <p>Copyright © 2021. All Rights Reserved</p> -->
        <!-- <p>Web Developer By <a href="#">NDRE Production</a> </p>
                </div>
                <div class="col-6 col-md-4 text-right">
                    <?= date("Y-m-d h:i:s"); ?>
                </div>
            </div>
        </footer> -->
    
<?php ?>
    <script src="assets/js/jquery.mask.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="assets/js/chart.min.js"></script> -->
    <script src="assets/js/script.js?20220518"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();
        });
    </script>
</body>
</html>