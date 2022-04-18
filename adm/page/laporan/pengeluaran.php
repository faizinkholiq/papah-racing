<?php
if (isset($_POST['prosess'])) {
    $tgl1 = $_POST['tgl1'];
    $tgl2 = $_POST['tgl2'];
    $jumlah_pengeluaran = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(jumlah) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'"))["total"];

    $no = 1;
    $query_pengeluaran = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
    $hitung = mysqli_num_rows($query_pengeluaran);
} else if (isset($_POST['semua'])) {
    $jumlah_pengeluaran = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(jumlah) AS total FROM pengeluaran"))["total"];

    $no = 1;
    $query_pengeluaran = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user");
    $hitung = mysqli_num_rows($query_pengeluaran);
}
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-book'></i> Laporan Pengeluaran</h3>
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
                        <input type="date" class="form-control" id="tgl1" name="tgl1">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group row">
                    <label for="tgl2" class="col-sm-2 col-form-label">Sampai</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tgl2" name="tgl2">
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
                            <a href="page/laporan/cetak_pengeluaran.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                            <a href="page/laporan/excel_pengeluaran.php?tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
                    <?php }
                    endif ?>
                    <?php if (isset($_POST['semua'])) : ?>
                        <a href="page/laporan/cetak_pengeluaran.php?semua" target="_BLANK" class="btn btn-secondary"><i class="fa fa-print mr-2"></i>Cetak</a>
                        <a href="page/laporan/excel_pengeluaran.php?semua" target="_BLANK" class="btn btn-success"><i class="fa fa-file-excel mr-2"></i>Export Excel</a>
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
                                <th>Pengguna</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
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
                                    foreach ($query_pengeluaran as $qp) {
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= tgl($qp['tanggal']) . ", " . date("H:i", strtotime($qp['tanggal'])); ?></td>
                                            <td><?= $qp['nama']; ?></td>
                                            <td><?= $qp['jenis']; ?></td>
                                            <td><?= $qp['keterangan']; ?></td>
                                            <td><?= $qp['jumlah']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="font-weight-bolder">
                                        <td colspan="5" align="center">Subtotal</td>
                                        <td><?php if ($jumlah_pengeluaran == 0) {
                                                echo rp('0');
                                            } else {
                                                echo rp($jumlah_pengeluaran);
                                            } ?></td>
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