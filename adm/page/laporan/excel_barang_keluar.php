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
} else {
    $query = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN barang ON penjualan_det.id_barang=barang.id_barang");
    $total_qty_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur"))["total"];
    $total_harga_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur"))["total"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= $data_toko['nama_toko']; ?></title>
</head>

<body>
    <style type="text/css">
        body {
            font-family: sans-serif;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #3c3c3c;
            padding: 3px 8px;

        }

        a {
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
    </style>

    <?php
    $filename = "Barang Keluar_" . date('d-m-Y') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . "");
    ?>

    <center>
        <h1><?= $data_toko['nama_toko']; ?><br>BARANG KELUAR</h1>
    </center>

    <table border="1">
        <thead>
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
        <tbody>
            <?php
            $no = 1;
            foreach ($query as $data) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                    <td class="text-left"><?= $data['nama']; ?></td>
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
    </table>
</body>

</html>