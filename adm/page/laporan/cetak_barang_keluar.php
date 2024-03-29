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
    $type = $_GET['type'];
    if (empty($type)) {
        $arguments = "";
    } else if (!empty($type)) {
        $arguments = "type='$type' AND";
    }
    $query = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN barang ON penjualan_det.id_barang=barang.id_barang WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
    $total_qty_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_harga_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_total_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
} else {
    $query = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN barang ON penjualan_det.id_barang=barang.id_barang");
    $total_qty_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur"))["total"];
    $total_harga_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur"))["total"];
    $total_total_barang =  mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur $arguments"))["total"];
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
            <h4 class="text-center"><u>BARANG KELUAR</u></h4>
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
                <th>Nama Barang</th>
                <th>Type Harga</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php $no = 1;
            foreach ($query as $data) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                    <td <?= ($data['deleted'] == 1)? 'class="text-left text-danger" title="Barang telah dihapus"' : 'class="text-left"' ?>><?= $data['nama']; ?></td>
                    <td><?= ucwords($data['type']); ?></td>
                    <td class="text-left"><?= rp($data['harga']); ?></td>
                    <td><?= $data['qty']; ?></td>
                    <td class="text-left"><?= rp($data['total_harga']); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="font-weight-bolder">
                <td colspan="4" align="center">Subtotal</td>
                <td class="text-left">
                    <?php if ($total_harga_barang == 0) {
                        echo rp('0');
                    } else {
                        echo rp($total_harga_barang);
                    } ?>
                </td>
                <td>
                    <?php if ($total_qty_barang == 0) {
                        echo '0';
                    } else {
                        echo $total_qty_barang;
                    } ?>
                </td>
                <td class="text-left">
                    <?php if ($total_total_barang == 0) {
                        echo rp('0');
                    } else {
                        echo rp($total_total_barang);
                    } ?>
                </td>
            </tr>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>

</html>