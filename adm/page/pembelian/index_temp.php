<?php
$status = isset($_GET['status'])? $_GET['status'] : '';
$arr_params = ["url" => "history-pembelian"];

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
        <a href="main?url=history-pembelian" class="badge bg-primary text-white ml-2">All Data</a>
        <?php 
            $arr_params = ["url" => "history-pembelian"];

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
    <div class="table-responsive mt-3" style="min-height: 50vh;">
        <table id="barangTable" class="table table-striped table-bordered " style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Status Transaksi</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th>Dibuat Oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal History -->
<div id="listModal" class="modal" tabindex="-2" role="dialog" aria-labelledby="listModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">List Barang Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <table style="width:100%" id="listTable" class="table table-bordered table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th class="text-center" width="100">Barcode</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center" width="100">Harga</th>
                            <th class="text-center" width="100">Quantity</th>
                            <th class="text-center" width="100">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const sess_data = <?= json_encode($_SESSION) ?>;
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;

    const rupiah = (number)=>{
        return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
        }).format(number);
    }

    let columnDefs = [];

    let dt = $('#barangTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=gethistorypembeliantemp',
            type: "POST",
            data: {
                status: "<?= $status ?>",
            }, 
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "no_po" },
            { data: "tanggal" },
            { data: "supplier" },
            { data: "status", className: "text-center", },
            { data: "total_transaksi" },
            { data: "total_bayar" },
            { data: "user", className: "text-center", },
            { data: "temp_status", className: "text-center", },
            { 
                data: "", 
                class:"text-center",
                render: function (data, type, row) {
                    let btn = "-";

                    if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2){
                        if(row.temp_status == 'Pending'){
                            btn = `
                                <a href="#!" 
                                onclick="showBarang('${row.no_po}')" 
                                class="btn btn-primary btn-sm mr-4" 
                                style="width:2rem;" 
                                data-toggle="tooltip" 
                                data-original-title="List Barang">
                                    <i class="fas fa-list"></i>
                                </a>
                                <a href="#!" 
                                onclick="approveBarang('${row.no_po}')" 
                                class="btn btn-success btn-sm" 
                                style="width:2rem;" 
                                data-toggle="tooltip" 
                                data-original-title="Setujui perubahan">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="#!" 
                                onclick="declineBarang('${row.no_po}')" 
                                class="btn btn-danger btn-sm" 
                                style="width:2rem;" 
                                data-toggle="tooltip" 
                                data-original-title="Tolak perubahan">
                                    <i class="fas fa-times"></i>
                                </a>`
                        }
                    }else{
                        btn = ` 
                            <a href="#!" 
                            onclick="showBarang('${row.no_po}')" 
                            class="btn btn-primary btn-sm" 
                            style="width:2rem;" 
                            data-toggle="tooltip" 
                            data-original-title="List Barang">
                                <i class="fas fa-list"></i>
                            </a>`
                    }
                    
                    return btn;
                }
            },
        ],
        ordering: true,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    $(document).ready(()=>{
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        if (sess_data["id_jabatan"] == 5){
            dt.columns([6]).visible(false);
        }


        $('#barangTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });

        dt.on( 'draw', function () {
          rewriteColNumbers()
        } );

    });

    function rewriteColNumbers() {
      $('#merkTable tbody tr').each(function( index ) {
        $('td', this ).first().html(index + 1);
      } );
    }

    function approveBarang(id) {
        let ask = window.confirm("Anda yakin ingin menyetujui perubahan data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=approvehistorypembelian&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function declineBarang(id) {
        let ask = window.confirm("Anda yakin ingin menolak perubahan data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=declinehistorypembelian&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function showBarang(id) {
        $('#listModal').modal('show');
        
        $('#listTable').DataTable().clear().destroy();
        $('#listTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getlistbaranghistory',
                type: "POST",
                data: {
                    no_po: id,
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "barcode" },
                { data: "nama" },
                { 
                    data: "harga",
                    render: function (data, type, row) {
                        return rupiah(data)
                    }
                },
                { data: "qty" },
                { 
                    data: "total",
                    render: function (data, type, row) {
                        return rupiah(data)
                    }
                },
            ],
            ordering: false
        });
    }

</script>