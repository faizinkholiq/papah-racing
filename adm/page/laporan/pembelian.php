<?php
if (isset($_POST['prosess'])) {
    $tgl1 = $_POST['tgl1'];
    $tgl2 = $_POST['tgl2'];
    $status = $_POST['status'];
    $id_supplier = $_POST['id_supplier'];
    if (empty($status) && empty($id_supplier)) {
        $arguments = "";
    } else if (!empty($status) && empty($id_supplier)) {
        $arguments = "status='$status' AND";
    } else if (empty($status) && !empty($id_supplier)) {
        $arguments = "id_supplier='$id_supplier' AND";
    } else if (!empty($status) && !empty($id_supplier)) {
        $arguments = "status='$status' AND id_supplier='$id_supplier' AND";
    }
    if (empty($id_supplier)) {
        $arguments2 = "";
    } else if (!empty($id_supplier)) {
        $arguments2 = "AND id_supplier='$id_supplier'";
    }
    $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_lunas = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian WHERE status='Lunas' $arguments2 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_hutang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM pembelian WHERE status='Hutang' $arguments2 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_pendapatan = $total_lunas + $total_hutang;
    $total_kekurangan = $total_transaksi - $total_pendapatan;

    $no = 1;
    $query_pembelian = mysqli_query($con, "SELECT * FROM pembelian WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2' ORDER BY tanggal DESC");
    $hitung = mysqli_num_rows($query_pembelian);
} else if (isset($_POST['semua'])) {
    $status = $_POST['status'];
    $id_supplier = $_POST['id_supplier'];
    if (empty($status) && empty($id_supplier)) {
        $arguments = "";
    } else if (!empty($status) && empty($id_supplier)) {
        $arguments = "WHERE status='$status'";
    } else if (empty($status) && !empty($id_supplier)) {
        $arguments = "WHERE id_supplier='$id_supplier'";
    } else if (!empty($status) && !empty($id_supplier)) {
        $arguments = "WHERE status='$status' AND id_supplier='$id_supplier'";
    }
    if (empty($id_supplier)) {
        $arguments2 = "";
    } else if (!empty($id_supplier)) {
        $arguments2 = "AND id_supplier='$id_supplier'";
    }
    $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian $arguments"))["total"];
    $total_lunas = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian WHERE status='Lunas' $arguments2"))["total"];
    $total_hutang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM pembelian WHERE status='Hutang' $arguments2"))["total"];
    $total_pendapatan = $total_lunas + $total_hutang;
    $total_kekurangan = $total_transaksi - $total_pendapatan;

    $no = 1;
    $query_pembelian = mysqli_query($con, "SELECT * FROM pembelian $arguments ORDER BY tanggal DESC");
    $hitung = mysqli_num_rows($query_pembelian);
}
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-book'></i> Laporan Pembelian</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form method="POST">
        <div class="row">
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="tgl1">Dari</label>
                    <input type="date" class="form-control" id="tgl1" name="tgl1">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="tgl2">Sampai</label>
                    <input type="date" class="form-control" id="tgl2" name="tgl2">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Semua</option>
                        <option value="Hutang">Hutang</option>
                        <option value="Lunas">Lunas</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="id_supplier">Supplier</label>
                    <select class="form-control" id="id_supplier" name="id_supplier">
                        <option value="">Semua</option>
                        <?php
                        $data_supplier = mysqli_query($con, "SELECT * FROM supplier");
                        foreach ($data_supplier as $dp) {
                        ?>
                            <option value="<?= $dp['id_supplier']; ?>"><?= $dp['nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="form-group">
                    <button id="formbtn" name="prosess" class="btn btn-info mt-3"><i class="fas fa-calendar-day mr-2"></i>Filter Data</button>
                    <button class="btn btn-primary mt-3" name="semua"><i class="fas fa-calendar-week mr-2"></i>Semua Data</button>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" align="center">
                    <?php
                    if (isset($_POST['prosess'])) :
                        if ($_POST['tgl1'] == NULL && $_POST['tgl2'] == NULL) {
                            echo '<button class="btn btn-secondary" disabled><i class="fa fa-print mr-2"></i>Cetak</button>
                            <button class="btn btn-success" disabled><i class="fa fa-file-excel mr-2"></i>Export Excel</button>';
                        } else {
                    ?>
                            <a href="page/laporan/cetak_pembelian.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&status=<?= $_POST['status']; ?>&id=<?= $_POST['id_supplier']; ?>" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                            <a href="page/laporan/excel_pembelian.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&status=<?= $_POST['status']; ?>&id=<?= $_POST['id_supplier']; ?>" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                    <?php }
                    endif ?>
                    <?php if (isset($_POST['semua'])) : ?>
                        <a href="page/laporan/cetak_pembelian.php?semua&status=<?= $_POST['status']; ?>&id=<?= $_POST['id_supplier']; ?>" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                        <a href="page/laporan/excel_pembelian.php?semua&status=<?= $_POST['status']; ?>&id=<?= $_POST['id_supplier']; ?>" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                    <?php endif ?>
                    <?php if (!isset($_POST['prosess']) && !isset($_POST['semua'])) : ?>
                        <button class="btn btn-secondary" disabled><i class="fa fa-print mr-2"></i>Cetak</button>
                        <button class="btn btn-success" disabled><i class="fa fa-file-excel mr-2"></i>Export Excel</button>
                    <?php endif ?>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr class="active">
                                <th>No.</th>
                                <th>No PO</th>
                                <th>Tanggal & Waktu</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Total Transaksi</th>
                                <th>Total Bayar</th>
                                <th>Total Kekurangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!isset($_POST['prosess']) && !isset($_POST['semua'])) { ?>
                                <tr>
                                    <td colspan="8" align="center">Pilih Opsi Tampil</td>
                                </tr>
                            <?php } else { ?>
                                <?php
                                if ($hitung < 1) {
                                    echo "<tr><td colspan='8' align='center'>Data Kosong</td></tr>";
                                } else {
                                    foreach ($query_pembelian as $qp) {
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $qp['no_po']; ?></td>
                                            <td><?= tgl($qp['tanggal']); ?>, <?= date("H:i", strtotime($qp['tanggal'])); ?></td>
                                            <?php
                                            $data_sup = mysqli_query($con, "SELECT * FROM supplier WHERE id_supplier='" . $qp['id_supplier'] . "'");
                                            foreach ($data_sup as $ds) {
                                            ?>
                                                <td><?= $ds['nama']; ?></td>
                                            <?php } ?>
                                            <td><?= $qp['status']; ?></td>
                                            <td><?= rp($qp['total_transaksi']); ?></td>
                                            <?php if ($qp['status'] == "Lunas") { ?>
                                                <td><?= rp($qp['total_transaksi']); ?></td>
                                            <?php } else { ?>
                                                <td><?= rp($qp['total_bayar']); ?></td>
                                            <?php } ?>
                                            <?php if ($qp['status'] == "Lunas") { ?>
                                                <td><?= rp('0') ?></td>
                                            <?php } else { ?>
                                                <td><?= rp($qp['total_transaksi'] - $qp['total_bayar']); ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="font-weight-bolder">
                                        <td colspan="5" align="center">Subtotal</td>
                                        <td><?php if ($total_transaksi == 0) {
                                                echo rp('0');
                                            } else {
                                                echo rp($total_transaksi);
                                            } ?></td>
                                        <?php if ($status == 'Lunas') { ?>
                                            <td><?= rp($total_lunas); ?></td>
                                        <?php } else if ($status == 'Hutang') { ?>
                                            <td><?= rp($total_hutang); ?></td>
                                        <?php } else { ?>
                                            <td><?= rp($total_pendapatan); ?></td>
                                        <?php } ?>
                                        <?php if ($status == 'Lunas') { ?>
                                            <td><?= rp('0') ?></td>
                                        <?php } else if ($status == 'Hutang') { ?>
                                            <td><?= rp($total_transaksi - $total_hutang); ?></td>
                                        <?php } else { ?>
                                            <td><?= rp($total_kekurangan); ?></td>
                                        <?php } ?>
                                    </tr>
                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>