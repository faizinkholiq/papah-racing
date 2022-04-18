<?php
if (isset($_POST['prosess'])) {
    $tgl1 = $_POST['tgl1'];
    $tgl2 = $_POST['tgl2'];
    $type = $_POST['type'];
    if (empty($type)) {
        $arguments = "";
    } else if (!empty($type)) {
        $arguments = "type='$type' AND";
    }
    $total_qty_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_harga_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $total_total_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];
    $no = 1;
    $query_penjualan = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN barang ON penjualan_det.id_barang=barang.id_barang WHERE $arguments DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
    $hitung = mysqli_num_rows($query_penjualan);
} else if (isset($_POST['semua'])) {
    $type = $_POST['type'];
    if (empty($type)) {
        $arguments = "";
    } else if (!empty($type)) {
        $arguments = "WHERE type='$type'";
    }
    $total_qty_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur $arguments"))["total"];
    $total_harga_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur $arguments"))["total"];
    $total_total_barang =  mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur $arguments"))["total"];

    $no = 1;
    $query_penjualan = mysqli_query($con, "SELECT * FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur JOIN barang ON penjualan_det.id_barang=barang.id_barang $arguments");
    $hitung = mysqli_num_rows($query_penjualan);
}
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-box'></i> Laporan Barang Keluar</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form method="POST">
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group row">
                    <label for="tgl1" class="col-sm-3 col-form-label">Dari</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tgl1" name="tgl1">
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group row">
                    <label for="tgl2" class="col-sm-3 col-form-label">Sampai</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tgl2" name="tgl2">
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="type" name="type">
                            <option value="">Semua</option>
                            <option value="distributor">Distributor</option>
                            <option value="reseller">Reseller</option>
                            <option value="bengkel">Bengkel</option>
                            <option value="admin">Admin</option>
                            <option value="het">HET</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-center">
                <div class="form-group">
                    <button id="formbtn" name="prosess" class="btn btn-info"><i class="fas fa-calendar-day mr-2"></i>Filter Data</button>
                    <button class="btn btn-primary" name="semua"><i class="fas fa-calendar-week mr-2"></i>Semua Data</button>
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
                            <a href="page/laporan/cetak_barang_keluar.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&type=<?= $_POST['type']; ?>" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                            <a href="page/laporan/excel_barang_keluar.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&type=<?= $_POST['type']; ?>" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                    <?php }
                    endif ?>
                    <?php if (isset($_POST['semua'])) : ?>
                        <a href="page/laporan/cetak_barang_keluar.php?semua" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                        <a href="page/laporan/excel_barang_keluar.php?semua" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
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
                                <th>Tanggal & Waktu</th>
                                <th>Nama Barang</th>
                                <th>Type Harga</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
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
                                    foreach ($query_penjualan as $qp) {
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= tgl($qp['tanggal']) . ", " . date("H:i", strtotime($qp['tanggal'])); ?></td>
                                            <td class="text-left"><?= $qp['nama']; ?></td>
                                            <td><?= ucwords($qp['type']); ?></td>
                                            <td class="text-left"><?= rp($qp['harga']); ?></td>
                                            <td><?= $qp['qty']; ?></td>
                                            <td class="text-left"><?= rp($qp['total_harga']); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="font-weight-bolder">
                                        <td colspan="4" align="center">Subtotal</td>
                                        <td class="text-left">
                                            <?php if ($total_harga_barang == 0) {
                                                echo rp('0');
                                            } else {
                                                echo rp($total_harga_barang);
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($total_qty_barang == 0) {
                                                echo '0';
                                            } else {
                                                echo $total_qty_barang;
                                            } ?>
                                        </td>
                                        <td class="text-left">
                                            <?php if ($total_total_barang == 0) {
                                                echo rp('0');
                                            } else {
                                                echo rp($total_total_barang);
                                            } ?>
                                        </td>
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