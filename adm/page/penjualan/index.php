<?php
$admins = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan = 5");
echo ($_GET["admin"]);
$params = (!empty($_GET))? http_build_query($_GET) : "";
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cash-register'></i> Data Penjualan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
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
        <div style="font-size:1.3rem; margin-top: 1rem;">
            Status:
            <a href="main?url=penjualan&status=lunas" class="badge bg-success text-white">Lunas</a>
            <a href="main?url=penjualan&status=hutang" class="badge bg-danger text-white">Hutang</a>
        </div>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
                $query_type = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='" . $_SESSION['id_user'] . "'"));
                if (isset($query_type['id_user']) && $query_type['id_user'] != NULL):
            ?>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=<?= isset($query_type['type'])? $query_type['type'] : ''  ?>"><?= isset($query_type['type'])? ucfirst($query_type['type']) : '' ?></a>
            <?php else: ?>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=distributor">Distributor</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=reseller">Reseller</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=bengkel">Bengkel</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=admin">Admin</a>
                <a class="dropdown-item text-center" href="main?url=tambah-penjualan&type=het">HET</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table id="penjualanTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Tipe Pembayaran</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Keterangan</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<script>
    const sess_data = <?= json_encode($_SESSION) ?>;

    $(document).ready(function () {
        var dt = $('#penjualanTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getpenjualan',
                type: "POST",
                data: {
                    admin: <?= isset($_GET['admin'])? $_GET['admin'] : 0 ?>,
                    status: "<?= isset($_GET['status'])? $_GET['status'] : '' ?>",
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "no_faktur" },
                { data: "tanggal" },
                { data: "pelanggan" },
                { data: "type" },
                { data: "status", className: "text-center", },
                { data: "tipe_bayar", className: "text-center", },
                { data: "total_transaksi" },
                { data: "total_bayar" },
                { data: "persetujuan", className: "text-center", },
                { data: "user", className: "text-center", },
                { data: "aksi", className: "text-center", },
            ],
            ordering: false
        });
    });
</script>