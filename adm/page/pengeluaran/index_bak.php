<?php
if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
	$query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user ORDER BY tanggal DESC");
} else {
	$query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user WHERE user.id_user='" . $_SESSION['id_user'] . "' ORDER BY tanggal DESC");
}

?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-bag'></i> Data Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-pengeluaran" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td><?= tgl($data['tanggal']) . ", " . date("H:i", strtotime($data['tanggal'])); ?></td>
                        <td><?= $data['jenis']; ?></td>
                        <td><?= rp($data['jumlah']); ?></td>
                        <td><?= $data['keterangan']; ?></td>
                        <td><?= $data['nama']; ?></td>
                        <td>
                            <!-- <a href="" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a> -->
                            <a href="main?url=ubah-pengeluaran&this=<?= $data['id_pengeluaran']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                            <a href="process/action?url=hapuspengeluaran&this=<?= $data['id_pengeluaran']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>