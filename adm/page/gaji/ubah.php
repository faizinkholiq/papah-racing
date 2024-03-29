<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_pelanggan = $_GET['this'];
$months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$year_now = date("Y");

$month = $_GET["month"];
$year = $_GET["year"];

$data = mysqli_fetch_array(mysqli_query($con, "
    SELECT 
        pelanggan.id_pelanggan,
        pelanggan.nama,
        COALESCE(gaji.pokok, 0) pokok,
        COALESCE(gaji.kehadiran, 0) kehadiran,
        COALESCE(gaji.prestasi, 0) prestasi,
        CASE 
            WHEN penjualan.total_het > 100000000 THEN COALESCE((penjualan.total_het * 10) / 100, 0)
            WHEN penjualan.total_het > 50000000 THEN COALESCE((penjualan.total_het * 5) / 100, 0)
            ELSE COALESCE((penjualan.total_het * 2) / 100, 0)
        END bonus,
        COALESCE(gaji.indisipliner, 0) indisipliner,
        COALESCE(gaji.jabatan, 0) tunjangan_jabatan
    FROM pelanggan
    LEFT JOIN gaji ON gaji.id_user = pelanggan.id_pelanggan AND gaji.month = $month AND gaji.year = $year
    LEFT JOIN (
        SELECT 
            penjualan.id_pelanggan,
            penjualan.tanggal,
            SUM(barang.het) total_het
        FROM penjualan
        LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
        LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
        WHERE penjualan.persetujuan = 'Approved'
            AND YEAR(penjualan.tanggal) = $year
            AND MONTH(penjualan.tanggal) = $month
        GROUP BY penjualan.id_pelanggan
    ) penjualan ON penjualan.id_pelanggan = pelanggan.id_pelanggan 
    WHERE pelanggan.id_pelanggan='$id_pelanggan' 
    GROUP BY pelanggan.id_pelanggan
"));
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-money-check-alt'></i> Ubah Gaji</h3>
    </div>
    <div class="col-4"><a href="main?url=gaji&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahgaji&page=<?= $page ?>" method="post">
        <input type="hidden" name="id_user" value="<?= $data['id_pelanggan']; ?>">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Anggota</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama" value="<?= $data['nama']; ?>" required disabled>
            </div>
        </div>
        <hr/>
        <h3><i class="fa fa-dollar-sign"></i> Data Gaji</h3><br/>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Bulan / Tahun</label>
            <div class="col-sm-10 row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <select class="form-control" id="monthSelect" name="month">
                            <?php foreach ($months as $key => $value): ?>
                            <option <?= (($key + 1) == $month)? 'selected' : '' ?> value="<?= ($key + 1) ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <select class="form-control" id="yearSelect" name="year">
                            <?php for($i=$year_now-5; $i <= $year_now ; $i++): ?>
                            <option <?= ( $i == $year)? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pokok</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pokok" value="<?= (float)$data['pokok']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kehadiran</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="kehadiran" value="<?= (float)$data['kehadiran']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Prestasi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="prestasi" value="<?= (float)$data['prestasi']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Bonus</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="bonus" value="<?= (float)$data['bonus']; ?>" required readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Indisipliner</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="indisipliner" value="<?= (float)$data['indisipliner']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tunjangan Jabatan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="tunjangan_jabatan" value="<?= (float)$data['tunjangan_jabatan']; ?>" required>
            </div>
        </div>
        <br/>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>