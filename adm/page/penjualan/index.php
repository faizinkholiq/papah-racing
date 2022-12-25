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
    <div style="width:100%; display:flex;">
        <div style="flex-grow: 1;">
            <a href="main?url=tambah-penjualan" class="btn btn-primary">
                <span class="text-white"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</span>
            </a>
        </div>
        <span class="font-weight-bold" style="font-size: 1.3rem; margin-right: 1rem;">
            Total Hutang: <span class="text-danger" style="font-size: 1.5rem;" id="total_hutang"></span>
        </span>
    </div>
    <div style="width:100%; font-size:1.3rem; margin-top: 1rem;">
        <a href="main?url=penjualan" class="badge bg-primary text-white">All Data</a>
        <?php 
        foreach($admins as $item):
        $arr_params["admin"] = $item["id_user"];
        $params = (!empty($arr_params))? http_build_query($arr_params) : "";
        ?>
        <a href="main?<?=$params ?>" class="badge <?=($admin == $item["id_user"])? 'bg-secondary text-white' : 'bg-info text-white' ?> "><?=$item["nama"] ?></a>
        <?php endforeach; ?>
    </div>
    <div style="width:100%; font-size:1.3rem; margin-top: 1rem;">
        Status:
        <?php 
            $arr_params = ["url" => "penjualan"];
            if(isset($_GET['admin'])){
                $arr_params["admin"] = $_GET["admin"];
            }
            
            if(isset($_GET['status'])){
                $arr_params["status"] = $_GET["status"];
            }

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
                    <th width="10">Tipe Bayar</th>
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
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;
    
    let dt = $('#penjualanTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getpenjualan',
            type: "POST",
            data: {
                admin: <?= $admin ?>,
                status: "<?= $status ?>",
            }, 
        },
        initComplete: function( settings, json){
            $('#total_hutang').text(json.total_hutang)
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

    $(document).ready(function () {
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#penjualanTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });

    function lihatPenjualan(id) {
        const info = dt.page.info();
        const url = "main?url=lihat-penjualan&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function cetakPenjualan(id) {
        const info = dt.page.info();
        const url = "page/penjualan/cetak_det.php?this="+id+"&page="+info.page
        window.open(url, "_blank")
    }

    function approvePenjualan(id) {
        const info = dt.page.info();
        const url = "process/action?url=approved&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function cicilanPenjualan(id) {
        const info = dt.page.info();
        const url = "main?url=cicilan-penjualan&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusPenjualan(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapuspenjualan&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>