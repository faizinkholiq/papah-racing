<?php
$where = "";
if (isset($_GET["admin"])) {
    $where = "AND penjualan.id_user = ".$_GET["admin"];
}

if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
	$query = mysqli_query($con, "
        SELECT * 
        FROM penjualan 
        WHERE DATEDIFF(NOW(), tanggal) <= 90 
            AND status = 'Lunas' AND persetujuan = 'Approved' 
        UNION ALL
        SELECT * FROM penjualan
        WHERE CONCAT(status,persetujuan) != CONCAT('Lunas','Approved') $where
        ORDER BY tanggal DESC");
} else {
	$query = mysqli_query($con, "
        SELECT * 
        FROM penjualan 
        WHERE id_user='" . $_SESSION['id_user'] . "' 
            AND DATEDIFF(NOW(), tanggal) <= 90 
            AND status = 'Lunas' AND persetujuan = 'Approved'
        UNION ALL
        SELECT * FROM penjualan
        WHERE CONCAT(status,persetujuan) != CONCAT('Lunas','Approved') $where
        ORDER BY tanggal DESC");
}

$admins = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan = 5");

?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cash-register'></i> Data Penjualan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <!-- <a href="main?url=tambah-penjualan" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a> -->
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="text-white pl-2"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</span>
        </button>
        <div style="font-size:1.3rem; margin-top: 1rem;">
            <a href="main?url=penjualan" class="badge bg-primary text-white">All Data</a>
            <?php foreach($admins as $item): ?>
            <a href="main?url=penjualan&admin=<?=$item["id_user"] ?>" class="badge <?=(isset($_GET["admin"]) && $_GET["admin"] == $item["id_user"])? 'bg-secondary text-white' : 'bg-info text-white' ?> "><?=$item["nama"] ?></a>
            <?php endforeach; ?>
        </div>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
            $query_type = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='" . $_SESSION['id_user'] . "'"));
            if ($query_type['id_user'] != NULL) {
            ?>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=<?= $query_type['type']; ?>"><?= ucfirst($query_type['type']); ?></a>
            <?php } else { ?>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=distributor">Distributor</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=reseller">Reseller</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=bengkel">Bengkel</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=admin">Admin</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=het">HET</a>
            <?php } ?>
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Keterangan</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $data['no_faktur']; ?></td>
                        <td><?= tgl($data['tanggal']); ?></td>
                        <?php
                        $query_pelanggan = mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan='" . $data['id_pelanggan'] . "'");
                        foreach ($query_pelanggan as $qp) {
                        ?>
                            <td class="text-left"><?= $qp['nama']; ?></td>
                            <td><?= ucwords($qp['type']); ?></td>
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
                        if ($data['persetujuan'] == 'Approved') {
                            echo "<td class='text-center'><span class='badge badge-primary'>" . $data['persetujuan'] . "</span></td>";
                        } else {
                            echo "<td class='text-center'><span class='badge badge-warning'>" . $data['persetujuan'] . "</span></td>";
                        }
                        ?>
                        <?php
                        $query_user = mysqli_query($con, "SELECT * FROM user WHERE id_user='" . $data['id_user'] . "'");
                        foreach ($query_user as $qu) :
                        ?>
                            <td class="text-left"><?= $qu['nama']; ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="main?url=lihat-penjualan&this=<?= $data['no_faktur']; ?>" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a>
                            <a href="page/penjualan/cetak_det.php?this=<?= $data['no_faktur']; ?>" target="_blank" class="btn btn-secondary btn-sm"><i class='fas fa-print'></i></a>
                            <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
                                <?php if ($data['persetujuan'] == 'Pending') { ?>
                                    <a href="process/action?url=approved&this=<?= $data['no_faktur']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-check'></i></a>
                                <?php } ?>
                                <?php if ($data['status'] == 'Hutang') { ?>
                                    <a href="main?url=cicilan-penjualan&this=<?= $data['no_faktur']; ?>" class="btn btn-success btn-sm"><i class='fas fa-hand-holding-usd'></i></a>
                                <?php } ?>
                                <a href="process/action?url=hapuspenjualan&this=<?= $data['no_faktur']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>