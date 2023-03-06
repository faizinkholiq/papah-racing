<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$no_po = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian JOIN pembelian_det ON pembelian.no_po=pembelian_det.no_po JOIN user ON pembelian.id_user=user.id_user WHERE pembelian.no_po='$no_po'"));
$query_debt = mysqli_query($con, "SELECT * FROM pembelian_debt WHERE no_po='$no_po'");
$total_debt = mysqli_num_rows($query_debt);
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-cart'></i> Detail Pembelian</h3>
    </div>
    <div class="col-4"><a href="main?url=pembelian&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="row">
        <div class="col-6 col-lg-3">
            <p><span class="font-weight-bolder">Nomor PO</span></p>
            <p><span class="font-weight-bolder">Nama Supplier</span></p>
            <p><span class="font-weight-bolder">Tanggal Transaksi</span></p>
        </div>
        <div class="col-6 col-lg-3">
            <p>: <?= $data['no_po']; ?></p>
            <?php
            $query_supplier = mysqli_query($con, "SELECT * FROM supplier WHERE id_supplier='" . $data['id_supplier'] . "'");
            foreach ($query_supplier as $qs) :
            ?>
                <p>: <?= $qs['nama']; ?></p>
            <?php endforeach; ?>
            <p>: <?= tgl($data['tanggal']); ?>, <?= date("H:i", strtotime($data['tanggal'])); ?></p>
        </div>
        <div class="col-6 col-lg-3">
            <p><span class="font-weight-bolder">Cashier</span></p>
            <p><span class="font-weight-bolder">Status Pembayaran</span></p>
        </div>
        <div class="col-6 col-lg-3">
            <p>: <?= $data['nama']; ?></p>
            <p>: <?= $data['status']; ?></p>
        </div>
    </div>
    <hr>
    <p class="font-weight-bolder mt-3">Detail Barang :</p>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pembelian_det = mysqli_query($con, "SELECT * FROM pembelian_det INNER JOIN barang ON pembelian_det.id_barang=barang.id_barang WHERE pembelian_det.no_po='" . $data['no_po'] . "'");
                foreach ($pembelian_det as $dp) : ?>
                    <tr class="text-center">
                        <td><?= $dp['nama']; ?></td>
                        <td><?= $dp['modal']; ?></td>
                        <td><?= $dp['qty']; ?></td>
                        <td><?= rp($dp['total_harga']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <!-- <tfoot>
                <tr class="text-center font-weight-bolder">
                    <td colspan="3">Subtotal</td>
                    <td><?= rp($data['total_transaksi']); ?></td>
                </tr>
            </tfoot> -->
        </table>
    </div>
    <?php if ($total_debt > 0) { ?>
        <p class="font-weight-bolder mt-3">Detail Pembayaran :</p>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Nama User</th>
                        <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($query_debt as $debt) : ?>
                        <tr class="text-center">
                            <?php
                            $tgl_debt = mysqli_query($con, "SELECT * FROM pembelian_debt WHERE id_pembelian_debt ='" . $debt['id_pembelian_debt'] . "'");
                            foreach ($tgl_debt as $td) {
                            ?>
                                <td><?= tgl($td['created']); ?>, <?= date("H:i", strtotime($td['created'])); ?></td>
                            <?php } ?>
                            <?php
                            if ($debt['keterangan'] == 'DP') {
                                echo "<td class='text-center'><span class='badge badge-success'>" . $debt['keterangan'] . "</span></td>";
                            } else {
                                echo "<td class='text-center'><span class='badge badge-info'>" . $debt['keterangan'] . "</span></td>";
                            } ?>
                            <td><?= rp($debt['bayar']); ?></td>
                            <?php
                            $user_debt = mysqli_query($con, "SELECT * FROM user WHERE id_user ='" . $debt['id_user'] . "'");
                            foreach ($user_debt as $ud) {
                            ?>
                                <td><?= $ud['nama']; ?></td>
                            <?php } ?>
                            <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                                <td>
                                    <?php if ($debt['keterangan'] != 'DP') { ?>
                                        <a href="process/action?url=hapuscicilanpembelian&no_po=<?= $no_po ?>&this=<?= $debt['id_pembelian_debt']; ?>&page=<?= $page ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <div class="row font-weight-bolder">
        <div class="col-12 col-lg-6"></div>
        <div class="col-6 col-lg-3">
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
        <div class="col-6 col-lg-3">
            <p>: <?= rp($data['total_transaksi']); ?></p>
            <p>: <?= rp($data['total_bayar']); ?></p>
            <p>: <?= rp($hutang); ?></p>
        </div>
        <div class="col-12 text-center mt-3">
            <a href="page/pembelian/cetak_det.php?this=<?= $data['no_po']; ?>" target="_blank" class="btn btn-primary"><i class='fas fa-print mr-2'></i>Cetak</a>
        </div>
    </div>

</div>