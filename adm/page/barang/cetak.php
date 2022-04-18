<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
require "../../config/connect.php";
require "../../config/function.php";
session_start();
if (empty($_SESSION['id_user'])) {
    header('location:login');
}

$query = mysqli_query($con, "SELECT * FROM barang");
$data_toko = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko"));
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
    <div class="row">
        <div class="col-5">
            <div class="row">
                <div class="col-3">
                    <p>Tanggal</p>
                    <p>No HP</p>
                    <p>Alamat</p>
                </div>
                <div class="col-9">
                    <p>: <?= tgl(date('d-m-Y')); ?></p>
                    <p>: <?= $data_toko['kontak_toko']; ?></p>
                    <p>: <?= $data_toko['alamat_toko']; ?></p>
                </div>
            </div>

        </div>
        <div class="col-7 text-right">
            <img src="../../assets/img/<?= $data_toko['logo_header']; ?>" width="70%" alt="Knalpot Racing Speedshop">
        </div>
    </div>

    <table class="table table-striped table-bordered" style="width:100%">
        <thead class="text-center">
            <tr>
                <th class="align-middle" rowspan="2">No.</th>
                <th class="align-middle" rowspan="2">Barcode</th>
                <th class="align-middle" rowspan="2">Nama</th>
                <th class="align-middle" rowspan="2">Merk</th>
                <th class="align-middle" rowspan="2">Stok</th>
                <th colspan="6">Harga</th>
            </tr>
            <tr>
                <th>Modal</th>
                <th>Distributor</th>
                <th>Reseller</th>
                <th>Bengkel</th>
                <th>Admin</th>
                <th>HET</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($query as $data) : ?>
                <tr class="text-center">
                    <td><?= $no++; ?></td>
                    <td class="text-left"><?= $data['barcode']; ?></td>
                    <td class="text-left"><?= $data['nama']; ?></td>
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
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        window.print()
    </script>
</body>

</html>