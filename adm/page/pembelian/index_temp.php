<?php
$status = isset($_GET['status'])? $_GET['status'] : '';
$arr_params = ["url" => "history-barang"];

if(isset($_GET['status'])){
    $arr_params["status"] = $_GET["status"];
}

$params = (!empty($arr_params))? http_build_query($arr_params) : "";
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-history'></i> History Pembelian</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div style="width:100%; font-size:1.3rem; margin-top: 1rem;">
        Status:
        <a href="main?url=history-barang" class="badge bg-primary text-white ml-2">All Data</a>
        <?php 
            $arr_params = ["url" => "history-barang"];

            if(isset($_GET['status'])){
                $arr_params["status"] = $_GET["status"];
            }

            $arr_params["status"] = "Pending";
            $params = (!empty($arr_params))? http_build_query($arr_params) : "";   
        ?>
        <a href="main?<?=$params ?>" class="badge <?=($status == 'Pending')? 'bg-secondary' : 'bg-warning' ?> text-white">Pending</a>
        <?php 
            $arr_params["status"] = "Approved";
            $params = (!empty($arr_params))? http_build_query($arr_params) : "";   
        ?>
        <a href="main?<?=$params ?>" class="badge <?=($status == 'Approved')? 'bg-secondary' : 'bg-success' ?> text-white">Disetujui</a>
        <?php 
            $arr_params["status"] = "Decline";
            $params = (!empty($arr_params))? http_build_query($arr_params) : "";   
        ?>
        <a href="main?<?=$params ?>" class="badge <?=($status == 'Decline')? 'bg-secondary' : 'bg-danger' ?> text-white">Ditolak</a>
    </div>
    <div class="table-responsive mt-3">
        <table id="barangTable" class="table table-striped table-bordered " style="width:100%">
            <thead>
                <tr class="text-center">
                    <th class="align-middle" rowspan="2">No.</th>
                    <th class="align-middle" rowspan="2">Barcode</th>
                    <th class="align-middle" rowspan="2">Nama</th>
                    <th class="align-middle" rowspan="2">Merk</th>
                    <th class="align-middle" rowspan="2">Stok</th>
                    <th colspan="5">Harga</th>
                    <th class="align-middle" rowspan="2">Status</th>
                    <th class="align-middle" rowspan="2">Tipe</th>
                    <th class="align-middle" rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Distributor</th>
                    <th>Reseller</th>
                    <th>Bengkel</th>
                    <th>Admin</th>
                    <th>HET</th>
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

    let columnDefs = [];

    let dt = $('#barangTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getbarangtemp',
            type: "POST",
            data: {
                status: "<?= $status ?>",
            }, 
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no" },
            { data: "barcode" },
            { data: "nama" },
            { data: "merk" },
            { data: "stok" },
            { data: "distributor" },
            { data: "reseller" },
            { data: "bengkel" },
            { data: "admin" },
            { data: "het" },
            { 
                data: "status",
                render: function (data, type, row) {
                    let btn;

                    switch (data) {
                        case 'Pending':
                            btn = `<span class="badge bg-warning">Pending</span>`
                            break;
                        case 'Approved':
                            btn = `<span class="badge bg-success text-white">Disetujui</span>`
                            break;
                        case 'Decline':
                            btn = `<span class="badge bg-danger text-white">Ditolak</span>`
                            break;
                    }
                    return btn;
                }
            },
            { data: "type" },
            { 
                data: "", 
                class:"text-center",
                render: function (data, type, row) {
                    let btn = "-";

                    if(row.status == 'Pending'){
                        btn = `
                            <a href="#!" 
                            onclick="approveBarang(${row.id})" 
                            class="btn btn-success btn-sm" 
                            style="width:2rem;" 
                            data-toggle="tooltip" 
                            data-original-title="Setujui perubahan">
                                <i class="fas fa-check"></i>
                            </a>
			                <a href="#!" 
                            onclick="declineBarang(${row.id})" 
                            class="btn btn-danger btn-sm" 
                            style="width:2rem;" 
                            data-toggle="tooltip" 
                            data-original-title="Tolak perubahan">
                                <i class="fas fa-times"></i>
                            </a>`
                    }
                    
                    return btn;
                }
            },
        ],
        ordering: false
    });

    $(document).ready(()=>{
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2){
            dt.columns([12]).visible(true);
        }else{
            dt.columns([12]).visible(false);
        }


        $('#barangTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });

    function approveBarang(id) {
        let ask = window.confirm("Anda yakin ingin menyetujui perubahan data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=approvebarangtemp&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function declineBarang(id) {
        let ask = window.confirm("Anda yakin ingin menolak perubahan data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=declinebarangtemp&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>