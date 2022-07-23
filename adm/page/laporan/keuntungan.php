<?php
    $where = "1=1";
    $tgl1 = isset($_POST['tgl1']) && !isset($_POST["semua"]) ? $_POST['tgl1'] : "";
    $tgl2 = isset($_POST['tgl2']) && !isset($_POST["semua"]) ? $_POST['tgl2'] : "";
    $total_laba = 0;

    if (isset($_POST["prosess"])){
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
            sum_penjualan.rowspan,
            barang.deleted
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
    
    $before_date = null;
    if (isset($_POST['prosess']) || isset($_POST['semua'])){
        foreach ($query as $key => $data) {
            if ($data["tanggal"] != $before_date ) {
                $total_laba += $data["laba"];
            }

            $before_date = $data["tanggal"];
        }
    }

?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class="fas fa-file-invoice-dollar"></i> Laporan Keuntungan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form method="POST">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group row">
                    <label for="tgl1" class="col-sm-2 col-form-label">Dari</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl1" name="tgl1" value="<?=$tgl1?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group row">
                    <label for="tgl2" class="col-sm-2 col-form-label">Sampai</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl2" name="tgl2" value="<?=$tgl2?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="form-group">
                    <button id="formbtn" name="prosess" class="btn btn-info"><i class="fas fa-calendar-day mr-2"></i>Filter Data</button>
                    <button class="btn btn-primary" name="semua"><i class="fas fa-calendar-week mr-2"></i>Semua Data</button>
                </div>
            </div>
        </div>
    </form>
    <div class="col-md-12">
        <div class="card" style="max-height: 75vh;">
            <div class="card-header" align="center" style="display:flex">
                <div style="width: 20%; font-weight:bold; color: red; font-size: 1.2rem">Total Laba: <?=rp($total_laba)?></div>
                <div style="margin-left: 2vw;">
                <?php
                if (isset($_POST['prosess'])) :
                    if ($_POST['tgl1'] == NULL && $_POST['tgl2'] == NULL) {
                        echo '<button class="btn btn-secondary" disabled><i class="fa fa-print mr-2"></i>Cetak</button>
                        <button class="btn btn-success" disabled><i class="fa fa-file-excel mr-2"></i>Export Excel</button>';
                    } else {
                ?>
                        <a href="page/laporan/cetak_keuntungan.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                        <a href="page/laporan/excel_keuntungan.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                <?php }
                endif ?>
                <?php if (isset($_POST['semua'])) : ?>
                    <a href="page/laporan/cetak_keuntungan.php?semua" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                    <a href="page/laporan/excel_keuntungan.php?semua" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                <?php endif ?>
                <?php if (!isset($_POST['prosess']) && !isset($_POST['semua'])) : ?>
                    <button class="btn btn-secondary" disabled><i class="fa fa-print mr-2"></i>Cetak</button>
                    <button class="btn btn-success" disabled><i class="fa fa-file-excel mr-2"></i>Export Excel</button>
                <?php endif ?>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="tb-untung">
                    <thead>
                        <tr class="text-center">
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
                    <tbody>
                        <?php 
                        if (!isset($_POST['prosess']) && !isset($_POST['semua'])): ?>
                                <tr>
                                    <td colspan="13" align="center">Pilih Opsi Tampil</td>
                                </tr>
                        <?php else :
                        $no = 1;
                        $before_date = 0;
                        foreach ($query as $key => $data) :
                            if ($data["tanggal"] == $before_date ) $data['rowspan'] = 0;
                        ?>
                            <tr class="text-center">
                                <td class="text-left" <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                                <td class="text-left" <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['pelanggan']; ?></td>
                                <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['type']; ?></td>
                                <td <?= ($data['deleted'] == 1)? 'class="text-left text-danger" title="Barang telah dihapus"' : 'class="text-left"' ?>><?= $data['barang']; ?></td>
                                <td><?= $data['jumlah']; ?></td>
                                <td><?= rp($data['harga_modal']); ?></td>
                                <td><?= rp($data['harga_transaksi']); ?></td>
                                <td><?= rp($data['total_harga_modal']); ?></td>
                                <td><?= rp($data['total_harga_transaksi']); ?></td>
                                <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= rp($data['total_modal']); ?></td>
                                <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= rp($data['total_transaksi']); ?></td>
                                <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= rp($data['laba']); ?></td>
                                <td <?= ($data['rowspan'] > 0)? "rowspan='".$data['rowspan']."'" : "style='display:none;'"; ?>><?= $data['oleh']; ?></td>
                            </tr>
                        <?php $before_date = $data["tanggal"]; endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
</div>