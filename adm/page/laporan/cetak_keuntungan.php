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
        sum_penjualan.rowspan
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
            <h4 class="text-center"><u>KEUNTUNGAN</u></h4>
        </div>
        <div class="col-4">
            <p><?= $data_toko['alamat_toko']; ?><br>
                No Telp : <?= $data_toko['kontak_toko']; ?></p>
        </div>
    </div>
    <table class="table table-striped table-bordered" style="width:100%">
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
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['pelanggan']; ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['type']; ?></td>
                    <td><?= $data['barang']; ?></td>
                    <td><?= $data['jumlah']; ?></td>
                    <td><?= $data['harga_modal']; ?></td>
                    <td><?= $data['harga_transaksi']; ?></td>
                    <td><?= $data['total_harga_modal']; ?></td>
                    <td><?= $data['total_harga_transaksi']; ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['total_modal']; ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['total_transaksi']; ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['laba']; ?></td>
                    <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['oleh']; ?></td>
                </tr>
            <?php $before_date = $data["tanggal"]; endforeach; ?>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>

</html>