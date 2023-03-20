<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM seo WHERE id = 1"));

$get_barang = mysqli_query($con, "
    SELECT
        barang.id_barang,
        barang.barcode,
        barang.nama,
        COUNT(penjualan_det.id_barang) total_penjualan
    FROM barang
    LEFT JOIN penjualan_det ON penjualan_det.id_barang = barang.id_barang
    GROUP BY barang.id_barang
    ORDER BY COUNT(penjualan_det.id_barang) DESC
    LIMIT 5");

$get_pelanggan = mysqli_query($con, "
    SELECT
        pelanggan.id_pelanggan,
        pelanggan.nama,
        COUNT(penjualan.no_faktur) total_penjualan
    FROM pelanggan
    LEFT JOIN penjualan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    GROUP BY pelanggan.id_pelanggan
    ORDER BY COUNT(penjualan.no_faktur) DESC
    LIMIT 5
");

?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cog'></i> SEO</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>

<div class="wrapper mt-2">
    <div class="row" style="font-size: 1.3rem;">
        <div class="col-sm-2"><i class="fas fa-users mr-2"></i>Total Visitor</div>
        <div class="col-sm-10"><?= isset($data['visitor'])? $data['visitor'] : 0; ?></div>
    </div>
</div>

<div class="wrapper mt-2">
    <h4>Barang Paling Banyak Dibeli</h4>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Barcode</th>
                    <th>Barang</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($get_barang as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-left"><?= $data['barcode']; ?></td>
                        <td><?= $data['nama']; ?></td>
                        <td><?= $data['total_penjualan']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="wrapper mt-2">
    <h4>Pelanggan Paling Banyak melakukan Pembelian</h4>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($get_pelanggan as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-left"><?= $data['nama']; ?> : <?= $data['kontak']; ?></td>
                        <td class="text-center"><?= $data['total_penjualan']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>