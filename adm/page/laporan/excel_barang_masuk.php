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
    $filename = "Pengeluaran_" . date('d-m-Y') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . "");
    ?>

    <center>
        <h1><?= $data_toko['nama_toko']; ?><br>Pengeluaran</h1>
    </center>

    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal & Waktu</th>
                <th>Pengguna</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($query as $data) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['tanggal']; ?></td>
                    <td <?= ($data['deleted'] == 1)? 'class="text-left text-danger" title="Barang telah dihapus"' : 'class="text-left"' ?>><?= $data['nama']; ?></td>
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
    </table>
</body>

</html>