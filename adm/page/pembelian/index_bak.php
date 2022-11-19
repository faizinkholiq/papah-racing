<?php
if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
	$query = mysqli_query($con, "SELECT * FROM pembelian ORDER BY tanggal DESC");
} else {
	$query = mysqli_query($con, "SELECT * FROM pembelian WHERE id_user='" . $_SESSION['id_user'] . "' ORDER BY tanggal DESC");
}
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-cart'></i> Data Pembelian</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
        <a href="main?url=tambah-pembelian" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $data['no_po']; ?></td>
                        <td><?= tgl($data['tanggal']); ?></td>
                        <?php
                        $query_supplier = mysqli_query($con, "SELECT * FROM supplier WHERE id_supplier='" . $data['id_supplier'] . "'");
                        foreach ($query_supplier as $qs) {
                        ?>
                            <td class="text-left"><?= $qs['nama']; ?></td>
                        <?php } ?>
                        <?php
                        if ($data['status'] == 'Lunas') {
                            echo "<td class='text-center'><span class='badge badge-success'>" . $data['status'] . "</span></td>";
                        } else {
                            echo "<td class='text-center'><span class='badge badge-danger'>" . $data['status'] . "</span></td>";
                        }
                        ?>
                        <td class="text-left"><?= rp($data['total_transaksi']); ?></td>
                        <?php if ($data['status'] == 'Lunas') { ?>
                            <td class="text-left"><?= rp($data['total_transaksi']); ?></td>
                        <?php } else { ?>
                            <td class="text-left"><?= rp($data['total_bayar']); ?></td>
                        <?php } ?>
                        <?php
                        $query_user = mysqli_query($con, "SELECT * FROM user WHERE id_user='" . $data['id_user'] . "'");
                        foreach ($query_user as $qu) :
                        ?>
                            <td class="text-left"><?= $qu['nama']; ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="main?url=lihat-pembelian&this=<?= $data['no_po']; ?>" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a>
                            <a href="page/pembelian/cetak_det.php?this=<?= $data['no_po']; ?>" target="_blank" class="btn btn-secondary btn-sm"><i class='fas fa-print'></i></a>
                            <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
                                <?php if ($data['status'] == 'Hutang') { ?>
                                    <a href="main?url=cicilan-pembelian&this=<?= $data['no_po']; ?>" class="btn btn-success btn-sm"><i class='fas fa-hand-holding-usd'></i></a>
                                <?php } ?>
                                <a href="process/action?url=hapuspembelian&this=<?= $data['no_po']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>