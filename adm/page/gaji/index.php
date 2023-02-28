<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-money-check-alt'></i> Data Gaji</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="table-responsive mt-3">
        <table id="gajiTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th class="align-middle" rowspan="2" width="10">No.</th>
                    <th class="align-middle" rowspan="2">Nama</th>
                    <th class="align-middle" rowspan="2">Username</th>
                    <th class="align-middle" rowspan="2">Jabatan</th>
                    <th colspan="6">Gaji</th>
                    <th class="align-middle" rowspan="2" width="100">Total</th>
                    <th class="align-middle" rowspan="2" width="60">Aksi</th>
                </tr>
                <tr>
                    <th width="100" class="text-center align-middle">Pokok</th>
                    <th width="100" class="text-center align-middle">Kehadiran</th>
                    <th width="100" class="text-center align-middle">Prestasi</th>
                    <th width="100" class="text-center align-middle">Bonus</th>
                    <th width="100" class="text-center align-middle">Indisipliner</th>
                    <th width="100" class="text-center align-middle">Tunjangan<br/>Jabatan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal History -->
<div id="historyModal" class="modal" tabindex="-2" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">History Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <table style="width:100%" id="historyTable" class="table table-bordered table-striped table-hover text-center mt-3">
                    <thead>
                        <tr>
                            <th>No. Faktur</th>
                            <th>Barcode</th>
                            <th>Nama</th>
                            <th>Harga (Het)</th>
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

    let dt = $('#gajiTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getgaji',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no" },
            { data: "nama" },
            { data: "username" },
            { data: "jabatan" },
            { 
                data: "pokok",
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { 
                data: "kehadiran",
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { 
                data: "prestasi",
                render: function (data, type, row) {
                    return rupiah(data)
                } 
            },
            { 
                data: "bonus",
                render: function (data, type, row) {
                    return rupiah(data)
                } 
            },
            { 
                data: "indisipliner", 
                render: function (data, type, row) {
                    return (data != 0)? '-' + rupiah(data) : rupiah(data);
                }
            },
            { 
                data: "tunjangan_jabatan",
                render: function (data, type, row) {
                    return rupiah(data)
                } 
            },
            { 
                data: "total",
                render: function (data, type, row) {
                    return rupiah(data)
                }
            },
            { 
                class: "text-center",
                render: function (data, type, row) {
                    if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2) {
                        return `
                            <button type="button" onclick='doEdit(${row.id_user})' class="btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                            <button type="button" onclick='showHistory(${row.id_user})' class="btn btn-sm btn-warning" style="width: 2rem;"><i class="fas fa-file-alt"></i></button>
                        `;
                    }else if (sess_data["id_jabatan"] == 5) {
                        return `
                            <button type="button" onclick='showHistory(${row.id_user})' class="btn btn-sm btn-warning" style="width: 2rem;"><i class="fas fa-file-alt"></i></button>
                        `;
                    }else{
                        return '-';
                    }
                }
            },
        ],
        ordering: false
    });

    $(document).ready(function () {
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#gajiTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });

        if (sess_data["id_jabatan"] != 1 && sess_data["id_jabatan"] != 2 && sess_data["id_jabatan"] != 5) {
            dt.columns([11]).visible(false);
        }
    });

    function doEdit(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-gaji&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function doDelete(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapusgaji&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function showHistory(id) {
        $('#historyModal').modal('show');

        $('#historyTable').DataTable().clear().destroy();
        $('#historyTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=gethistorypenjualan',
                type: "POST",
                data: {
                    id_user: id
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "no_faktur" },
                { data: "barcode" },
                { data: "nama" },
                { 
                    data: "het",
                    render: function (data, type, row) {
                        return rupiah(data)
                    }
                },
            ],
            ordering: false
        });
    }

</script>