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

$where = "1=1";
$tgl1 = isset($_GET['tgl1']) && !isset($_GET["semua"]) ? $_GET['tgl1'] : "";
$tgl2 = isset($_GET['tgl2']) && !isset($_GET["semua"]) ? $_GET['tgl2'] : "";

if (isset($_GET["tgl1"]) && isset($_GET["tgl2"])){
    $where = "DATE_FORMAT(penjualan.tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'";
}

$query = mysqli_query($con, "
    SELECT
        penjualan.no_faktur,
        penjualan.tanggal,
        pelanggan.nama pelanggan,
        pelanggan.type,
        barang.nama barang,
        penjualan_det.qty jumlah,
        barang.modal harga_modal,
        CASE 
            WHEN pelanggan.type = 'distributor' THEN barang.distributor
            WHEN pelanggan.type = 'reseller' THEN barang.reseller
            WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
            WHEN pelanggan.type = 'admin' THEN barang.admin
            WHEN pelanggan.type = 'het' THEN barang.het
        END harga_transaksi,
        penjualan_det.qty * barang.modal total_harga_modal,
        penjualan_det.qty * (
            CASE 
                WHEN pelanggan.type = 'distributor' THEN barang.distributor
                WHEN pelanggan.type = 'reseller' THEN barang.reseller
                WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
                WHEN pelanggan.type = 'admin' THEN barang.admin
                WHEN pelanggan.type = 'het' THEN barang.het
            END
        ) total_harga_transaksi,
        sum_penjualan.modal total_modal,
        sum_penjualan.transaksi total_transaksi,
        sum_penjualan.transaksi - sum_penjualan.modal laba,
        user.nama oleh,
        sum_penjualan.rowspan,
        barang.deleted
    FROM penjualan
    JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur
    JOIN barang ON penjualan_det.id_barang = barang.id_barang
    LEFT JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    LEFT JOIN user ON user.id_user = penjualan.id_user
    LEFT JOIN (
        SELECT 
            penjualan.no_faktur,
            SUM(penjualan_det.qty * barang.modal) modal,
            SUM(penjualan_det.qty * (
                CASE 
                    WHEN pelanggan.type = 'distributor' THEN barang.distributor
                    WHEN pelanggan.type = 'reseller' THEN barang.reseller
                    WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
                    WHEN pelanggan.type = 'admin' THEN barang.admin
                    WHEN pelanggan.type = 'het' THEN barang.het
                END
            )) transaksi,
            COUNT(penjualan_det.id_barang) rowspan
        FROM penjualan
        JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur
        JOIN barang ON penjualan_det.id_barang = barang.id_barang
        LEFT JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
        GROUP BY penjualan.no_faktur
    ) sum_penjualan ON penjualan.no_faktur = sum_penjualan.no_faktur
    WHERE ".$where."
    GROUP BY penjualan.no_faktur, penjualan_det.id_barang
    ORDER BY penjualan.tanggal DESC, penjualan_det.id_barang
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $data_toko['nama_toko']; ?></title>
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
</head>

<body>
    <?php
    $filename = "Keuntungan" . date('d-m-Y') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . "");
    ?>

    <center>
        <h1><?= $data_toko['nama_toko']; ?><br>Pengeluaran</h1>
    </center>
    <table border="1">
        <thead class="text-center">
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Type</th>
                <th>Nama Barang</th>
                <th>Total Beli</th>
                <th>Harga Modal</th>
                <th>Harga Transaksi</th>
                <th>Total Harga Modal</th>
                <th>Total Harga Transaksi</th>
                <th>Total Modal</th>
                <th>Total Transaksi</th>
                <th>Laba</th>
                <th>Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php
            $no = 1;
            $before_date = 0;
            foreach ($query as $key => $data) :
                if ($data["tanggal"] == $before_date ) $data['rowspan'] = 0;
            ?>
                <tr class="text-center">
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal']));?> </td><?php else: ''; endif; ?>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['pelanggan'];?> </td><?php else: ''; endif; ?>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['type'];?> </td><?php else: ''; endif; ?>
                    <td <?= ($data['deleted'] == 1)? 'class="text-left text-danger" title="Barang telah dihapus"' : 'class="text-left"' ?>><?= $data['barang']; ?></td>
                    <td><?= $data['jumlah']; ?></td>
                    <td><?= $data['harga_modal']; ?></td>
                    <td><?= $data['harga_transaksi']; ?></td>
                    <td><?= $data['total_harga_modal']; ?></td>
                    <td><?= $data['total_harga_transaksi']; ?></td>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['total_modal'];?> </td><?php else: ''; endif; ?>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['total_transaksi'];?> </td><?php else: ''; endif; ?>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['laba'];?> </td><?php else: ''; endif; ?>
                    <?php if($data['rowspan'] > 0): ?> <td rowspan='<?=$data['rowspan']?>'> <?=$data['oleh'];?> </td><?php else: ''; endif; ?>
                </tr>
            <?php $before_date = $data["tanggal"]; endforeach; ?>
        </tbody>
    </table>
</body>

</html>