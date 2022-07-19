<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
require "../../config/connect.php";
require "../../config/function.php";
session_start();
if (empty($_SESSION['id_user'])) {
    header('location:login');
}
$query = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 ORDER BY created DESC");
$data_toko = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko"));
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
    $filename = "Data Barang_" . date('d-m-Y') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename . "");
    ?>

    <center>
        <h1><?= $data_toko['nama_toko']; ?></h1>
    </center>

    <table border="1">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Barcode</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Merk</th>
                <th rowspan="2">Stok</th>
                <th colspan="6">Harga</th>
            </tr>
            <tr class="text-center">
                <th>Modal</th>
                <th>Distributor</th>
                <th>Reseller</th>
                <th>Bengkel</th>
                <th>Admin</th>
                <th>HET</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($query as $data) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $data['barcode']; ?></td>
                    <td><?= $data['nama']; ?></td>
                    <td><?= $data['merk']; ?></td>
                    <td><?= $data['stok']; ?></td>
                    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                        <td><?= $data['modal']; ?></td>
                    <?php } else { ?>
                        <td><?= '-'; ?></td>
                    <?php } ?>
                    <td><?= $data['distributor']; ?></td>
                    <td><?= $data['reseller']; ?></td>
                    <td><?= $data['bengkel']; ?></td>
                    <td><?= $data['admin']; ?></td>
                    <td><?= $data['het']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>