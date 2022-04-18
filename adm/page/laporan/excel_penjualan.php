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
    $filename = "Invoice_" . date('d-m-Y') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . "");
    ?>

    <center>
        <h1><?= $data_toko['nama_toko']; ?><br>Invoice</h1>
    </center>

    <table border="1">
        <thead>
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
        <tbody>
            <?php
            $no = 1;
            foreach ($query as $data) :
            ?>
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
                    <td><?= $data['total_transaksi']; ?></td>
                    <?php if ($data['status'] == 'Lunas') { ?>
                        <td><?= $data['total_transaksi']; ?></td>
                    <?php } else { ?>
                        <td><?= $data['total_bayar']; ?></td>
                    <?php } ?>
                    <?php if ($data['status'] == 'Lunas') { ?>
                        <td>0</td>
                    <?php } else { ?>
                        <td><?= $data['total_transaksi'] - $data['total_bayar']; ?></td>
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
                    <td><?= $total_lunas; ?></td>
                <?php } else if ($status == 'Hutang') { ?>
                    <td><?= $total_hutang; ?></td>
                <?php } else { ?>
                    <td><?= $total_pendapatan; ?></td>
                <?php } ?>
                <?php if ($status == 'Lunas') { ?>
                    <td>0</td>
                <?php } else if ($status == 'Hutang') { ?>
                    <td><?= $total_transaksi - $total_hutang; ?></td>
                <?php } else { ?>
                    <td><?= $total_kekurangan; ?></td>
                <?php } ?>
            </tr>
    </table>
</body>

</html>