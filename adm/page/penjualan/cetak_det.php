<?php
require "../../config/connect.php";
require "../../config/function.php";
$no_faktur = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN user ON penjualan.id_user=user.id_user WHERE penjualan.no_faktur='$no_faktur' "));
$data_toko = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko"));
$query_debt = mysqli_query($con, "SELECT * FROM penjualan_debt WHERE no_faktur='$no_faktur'");
$total_debt = mysqli_num_rows($query_debt);
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
    <div class="row font-weight-bolder">
        <div class="col-5">
            <div class="row">
                <div class="col-3">
                    <p>Nama Toko</p>
                    <p>No HP</p>
                    <p>Alamat</p>
                </div>
                <div class="col-9">
                    <p>: <?= $data_toko['nama_toko']; ?></p>
                    <p>: <?= $data_toko['kontak_toko']; ?></p>
                    <p>: <?= $data_toko['alamat_toko']; ?></p>
                </div>
            </div>

        </div>
        <div class="col-7 text-right">
            <img src="../../assets/img/<?= $data_toko['logo_header']; ?>" width="70%" alt="Knalpot Racing Speedshop">
        </div>
    </div>
    <hr>

    <div class="row font-weight-bolder">
        <div class="col-9 col-lg-3">
            <p>Nomor PO</p>
            <p>Nama Pelanggan</p>
            <p>Tanggal Transaksi</p>
        </div>
        <div class="col-3 col-lg-3">
            <p>: <?= $data['no_faktur']; ?></p>
            <?php
            $query_pelanggan = mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan='" . $data['id_pelanggan'] . "'");
            foreach ($query_pelanggan as $qs) :
            ?>
                <p>: <?= $qs['nama']; ?></p>
            <?php endforeach; ?>
            <p>: <?= tgl($data['tanggal']); ?>, <?= date("H:i", strtotime($data['tanggal'])); ?></p>
        </div>
        <div class="col-9 col-lg-3">
            <p>Cashier</p>
            <p>Status Pembayaran</p>
            <p>Tipe Penjualan</span></p>
        </div>
        <div class="col-3 col-lg-3">
            <p>: <?= $data['nama']; ?></p>
            <p>: <?= $data['status']; ?></p>
            <p>: <?= ucwords($data['type']); ?></p>
        </div>
    </div>
    <hr>
    <p class="font-weight-bolder mt-3">Detail Barang :</p>
    <table class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr class="text-center">
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $penjualan_det = mysqli_query($con, "SELECT * FROM penjualan_det INNER JOIN barang ON penjualan_det.id_barang=barang.id_barang WHERE penjualan_det.no_faktur='" . $data['no_faktur'] . "'");
            foreach ($penjualan_det as $dp) : ?>
                <tr class="text-center">
                    <td><?= $dp['nama']; ?></td>
                    <td><?= $dp['harga']; ?></td>
                    <td><?= $dp['diskon']; ?></td>
                    <td><?= $dp['qty']; ?></td>
                    <td><?= rp($dp['total_harga']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="text-center font-weight-bolder">
                <td colspan="4">Subtotal</td>
                <td><?= rp($data['total_transaksi']); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php if ($total_debt > 0) { ?>
        <p class="font-weight-bolder mt-3">Detail Pembayaran :</p>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Nama User</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($query_debt as $debt) : ?>
                        <tr class="text-center">
                            <?php
                            $tgl_debt = mysqli_query($con, "SELECT * FROM penjualan_debt WHERE id_penjualan_debt ='" . $debt['id_penjualan_debt'] . "'");
                            foreach ($tgl_debt as $td) {
                            ?>
                                <td><?= tgl($td['created']); ?>, <?= date("H:i", strtotime($td['created'])); ?></td>
                            <?php } ?>
                            <td class='text-center'><?= $debt['keterangan'] ?></td>
                            <?php
                            $user_debt = mysqli_query($con, "SELECT * FROM user WHERE id_user ='" . $debt['id_user'] . "'");
                            foreach ($user_debt as $ud) {
                            ?>
                                <td><?= $ud['nama']; ?></td>
                            <?php } ?>
                            <td><?= rp($debt['bayar']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="text-center font-weight-bolder">
                        <td colspan="3">Subtotal</td>
                        <td><?= rp($data['total_bayar']); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
    <div class="row font-weight-bolder">
        <div class="col-12 col-lg-6"></div>
        <div class="col-9 col-lg-3">
            <p><span>Total Transaksi</span></p>
            <p><span>Total Pembayaran</span></p>
            <?php if ($data['total_bayar'] < $data['total_transaksi']) { ?>
                <p><span>Kekurangan</span></p>
                <?php $hutang = $data['total_transaksi'] - $data['total_bayar'] ?>
            <?php } else { ?>
                <p><span>Total Kembalian</span></p>
                <?php $hutang = $data['total_bayar'] - $data['total_transaksi'] ?>
            <?php } ?>
        </div>
        <div class="col-3 col-lg-3">
            <p>: <?= rp($data['total_transaksi']); ?></p>
            <p>: <?= rp($data['total_bayar']); ?></p>
            <p>: <?= rp($hutang); ?></p>
        </div>
    </div>
    <script>
        window.print()
    </script>
</body>

</html>