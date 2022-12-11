<?php
$admins = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan = 5");
$admin = isset($_GET['admin'])? $_GET['admin'] : '0';
$status = isset($_GET['status'])? $_GET['status'] : '';
$arr_params = ["url" => "penjualan"];
if(isset($_GET['admin'])){
    $arr_params["admin"] = $_GET["admin"];
}

if(isset($_GET['status'])){
    $arr_params["status"] = $_GET["status"];
}

$params = (!empty($arr_params))? http_build_query($arr_params) : "";
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cash-register'></i> Data Penjualan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="dropdown">
        <a href="main?url=tambah-penjualan" class="btn btn-primary" id="dropdownMenuButton">
            <span class="text-white"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</span>
        </a>
        <div style="font-size:1.3rem; margin-top: 1rem;">
            <a href="main?url=penjualan" class="badge bg-primary text-white">All Data</a>
            <?php 
            foreach($admins as $item):
            $arr_params["admin"] = $item["id_user"];
            $params = (!empty($arr_params))? http_build_query($arr_params) : "";
            ?>
            <a href="main?<?=$params ?>" class="badge <?=($admin == $item["id_user"])? 'bg-secondary text-white' : 'bg-info text-white' ?> "><?=$item["nama"] ?></a>
            <?php endforeach; ?>
        </div>
        <div style="font-size:1.3rem; margin-top: 1rem;">
            Status:
            <?php 
                $arr_params["status"] = "lunas";
                $params = (!empty($arr_params))? http_build_query($arr_params) : "";   
            ?>
            <a href="main?<?=$params ?>" class="badge <?=($status == 'lunas')? 'bg-secondary' : 'bg-success' ?> text-white">Lunas</a>
            <?php 
                $arr_params["status"] = "hutang";
                $params = (!empty($arr_params))? http_build_query($arr_params) : "";   
            ?>
            <a href="main?<?=$params ?>" class="badge <?=($status == 'hutang')? 'bg-secondary' : 'bg-danger' ?> text-white">Hutang</a>
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
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Transfer / Cash</th>
                    <th>Keterangan</th>
                    <th>Dibuat Oleh</th>
                    <th width="" >Aksi</th>
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
                    admin: <?= $admin ?>,
                    status: "<?= $status ?>",
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
                { data: "total_transaksi" },
                { data: "total_bayar" },
                { data: "tipe_bayar", className: "text-center", },
                { data: "persetujuan", className: "text-center", },
                { data: "user", className: "text-center", },
                { data: "aksi", className: "text-center", },
            ],
            ordering: false
        });
    });
</script>