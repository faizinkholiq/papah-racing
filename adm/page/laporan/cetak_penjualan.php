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
    $status = $_GET['status'];
    $id_pelanggan = $_GET['id'];
    if (empty($status) && empty($id_pelanggan)) {
        $arguments = "";
    } else if (!empty($status) && empty($id_pelanggan)) {
        $arguments = "status='$status' AND";
    } else if (empty($status) && !empty($id_pelanggan)) {
        $arguments = "id_pelanggan='$id_pelanggan' AND";
    } else if (!empty($status) && !empty($id_pelanggan)) {
        $arguments = "status='$status' AND id_pelanggan='$id_pelanggan' AND";
    }
    if (empty($id_pelanggan)) {
        $arguments2 = "";
    } else if (!empty($id_pelanggan)) {
        $arguments2 = "AND id_pelanggan='$id_pelanggan'";
    }
    $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_lunas = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan WHERE status='Lunas' $arguments2 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_hutang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM penjualan WHERE status='Hutang' $arguments2 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_pendapatan = $total_lunas + $total_hutang;
    $total_kekurangan = $total_transaksi - $total_pendapatan;

    $query = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2' GROUP BY penjualan.no_faktur ORDER BY tanggal DESC");
} else {
    $status = $_GET['status'];
    $id_pelanggan = $_GET['id'];
    if (empty($status) && empty($id_pelanggan)) {
        $arguments = "";
    } else if (!empty($status) && empty($id_pelanggan)) {
        $arguments = "WHERE status='$status'";
    } else if (empty($status) && !empty($id_pelanggan)) {
        $arguments = "WHERE id_pelanggan='$id_pelanggan'";
    } else if (!empty($status) && !empty($id_pelanggan)) {
        $arguments = "WHERE status='$status' AND id_pelanggan='$id_pelanggan'";
    }
    if (empty($id_pelanggan)) {
        $arguments2 = "";
    } else if (!empty($id_pelanggan)) {
        $arguments2 = "AND id_pelanggan='$id_pelanggan'";
    }
    $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan $arguments"))["total"];
    $total_lunas = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan WHERE status='Lunas'"))["total"];
    $total_hutang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM penjualan WHERE status='Hutang'"))["total"];
    $total_pendapatan = $total_lunas + $total_hutang;
    $total_kekurangan = $total_transaksi - $total_pendapatan;

    $query = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur $arguments GROUP BY penjualan.no_faktur ORDER BY tanggal DESC");
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
            <h4 class="text-center"><u>INVOICE</u></h4>
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
                <th>No PO</th>
                <th>Tanggal & Waktu</th>
                <th>Pelanggan</th>
                <th>Type</th>
                <th>Status</th>
                <th>Total Transaksi</th>
                <th>Total Bayar</th>
                <th>Total Kekurangan</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php $no = 1;
            foreach ($query as $data) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['no_faktur']; ?></td>
                    <td><?= tgl($data['tanggal']); ?>, <?= date("H:i", strtotime($data['tanggal'])); ?></td>
                    <?php
                    $data_pel = mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan='" . $data['id_pelanggan'] . "'");
                    foreach ($data_pel as $dp) {
                    ?>
                        <td><?= $dp['nama']; ?></td>
                    <?php } ?>
                    <td><?= ucwords($data['type']); ?></td>
                    <td><?= $data['status']; ?></td>
                    <td><?= rp($data['total_transaksi']); ?></td>
                    <?php if ($data['status'] == 'Lunas') { ?>
                        <td><?= rp($data['total_transaksi']); ?></td>
                    <?php } else { ?>
                        <td><?= rp($data['total_bayar']); ?></td>
                    <?php } ?>
                    <?php if ($data['status'] == 'Lunas') { ?>
                        <td><?= rp('0') ?></td>
                    <?php } else { ?>
                        <td><?= rp($data['total_transaksi'] - $data['total_bayar']); ?></td>
                    <?php } ?>
                </tr>
            <?php endforeach; ?>
            <tr class="font-weight-bolder">
                <td colspan="6" align="center">Subtotal</td>
                <td><?php if ($total_transaksi == 0) {
                        echo rp('0');
                    } else {
                        echo rp($total_transaksi);
                    } ?></td>
                <?php if ($status == 'Lunas') { ?>
                    <td><?= rp($total_lunas); ?></td>
                <?php } else if ($status == 'Hutang') { ?>
                    <td><?= rp($total_hutang); ?></td>
                <?php } else { ?>
                    <td><?= rp($total_pendapatan); ?></td>
                <?php } ?>
                <?php if ($status == 'Lunas') { ?>
                    <td><?= rp('0') ?></td>
                <?php } else if ($status == 'Hutang') { ?>
                    <td><?= rp($total_transaksi - $total_hutang); ?></td>
                <?php } else { ?>
                    <td><?= rp($total_kekurangan); ?></td>
                <?php } ?>
            </tr>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>

</html>