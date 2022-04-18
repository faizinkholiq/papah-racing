<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
require "../../config/connect.php";
require "../../config/function.php";
session_start();
if (empty($_SESSION['id_user'])) {
    header('location:login');
}
$data_toko = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko"));
if (isset($_GET['tgl1']) && isset($_GET['tgl2'])) {
    $tgl1 = $_GET['tgl1'];
    $tgl2 = $_GET['tgl2'];
    $query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
    $jumlah_pengeluaran = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(jumlah) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
} else {
    $jumlah_pengeluaran = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(jumlah) AS total FROM pengeluaran"))["total"];
    $query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <title>Knalpot Racing Speedshop</title>

</head>

<body>
    <div class="row font-weight-bolder mb-3">
        <div class="col-4">
            <img src="../../assets/img/<?= $data_toko['logo_header']; ?>" width="70%" alt="Knalpot Racing Speedshop">
            <h3><?= $data_toko['ket_toko']; ?></h3>
        </div>
        <div class="col-4">
            <h4 class="text-center"><u>PENGELUARAN</u></h4>
        </div>
        <div class="col-4">
            <p><?= $data_toko['alamat_toko']; ?><br>
                No Telp : <?= $data_toko['kontak_toko']; ?></p>
        </div>
    </div>
    <table class="table table-striped table-bordered" style="width:100%">
        <thead class="text-center">
            <tr>
                <th>No.</th>
                <th>Tanggal & Waktu</th>
                <th>Pengguna</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php $no = 1;
            foreach ($query as $data) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                    <td><?= $data['nama']; ?></td>
                    <td><?= $data['jenis']; ?></td>
                    <td><?= $data['keterangan']; ?></td>
                    <td><?= $data['jumlah']; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="font-weight-bolder">
                <td colspan="5" align="center">Subtotal</td>
                <td><?php if ($jumlah_pengeluaran == 0) {
                        echo rp('0');
                    } else {
                        echo rp($jumlah_pengeluaran);
                    } ?></td>
            </tr>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>

</html>