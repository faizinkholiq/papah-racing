<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_user = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "
    SELECT 
        user.id_user,
        user.nama,
        user.username,
        jabatan.nama jabatan,
        COALESCE(gaji.pokok, 0) pokok,
        COALESCE(gaji.kehadiran, 0) kehadiran,
        COALESCE(gaji.prestasi, 0) prestasi,
        COALESCE((penjualan.total_het * 2) / 100, 0) bonus,
        COALESCE(gaji.indisipliner, 0) indisipliner,
        COALESCE(gaji.jabatan, 0) tunjangan_jabatan
    FROM user
    LEFT JOIN gaji ON gaji.id_user = user.id_user
    LEFT JOIN jabatan ON jabatan.id_jabatan = user.id_jabatan
    LEFT JOIN (
        SELECT
            penjualan.id_user,
            penjualan.tanggal,
            SUM(barang.het) total_het
        FROM penjualan
        LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
        LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
        WHERE penjualan.persetujuan = 'Approved'
            AND YEAR(penjualan.tanggal) = YEAR(NOW())
            AND MONTH(penjualan.tanggal) = MONTH(NOW())
        GROUP BY penjualan.id_user
    ) penjualan ON penjualan.id_user = user.id_user 
    WHERE user.id_user='$id_user' 
    GROUP BY user.id_user
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
        <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Anggota</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nama" value="<?= $data['nama']; ?>" required disabled>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jabatan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="jabatan" value="<?= $data['jabatan']; ?>" required disabled>
            </div>
        </div><br/>
        <hr/>
        <h3><i class="fa fa-dollar-sign"></i> Data Gaji</h3><br/>
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
                <input type="text" class="form-control" name="bonus" value="<?= (float)$data['bonus']; ?>" required>
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