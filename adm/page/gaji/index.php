<?php 
$month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$year = date("Y");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-money-check-alt'></i> Data Gaji</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <select onchange="doFilter()" class="form-control" id="monthSelect" name="month">
                    <?php foreach ($month as $key => $value): ?>
                    <option <?= (($key + 1) == date('m'))? 'selected' : '' ?> value="<?= ($key + 1) ?>"><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group">
                <select onchange="doFilter()" class="form-control" id="yearSelect" name="year">
                    <?php for($i=$year-5; $i <= $year ; $i++): ?>
                    <option <?= ( $i == date('Y'))? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div><hr/>
    <div class="table-responsive mt-3">
        <table id="gajiTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th class="align-middle" rowspan="2" width="10">No.</th>
                    <th class="align-middle" rowspan="2">Nama</th>
                    <th colspan="6">Gaji</th>
                    <th class="align-middle" rowspan="2" width="100">Total</th>
                    <th class="align-middle" rowspan="2" width="120">Aksi</th>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">History Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <table style="width:100%" id="historyTable" class="table table-bordered table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th class="text-center" width="100">No. Faktur</th>
                            <th class="text-center" width="120">Tanggal</th>
                            <th class="text-center" width="100">Barcode</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center" width="100">Pelanggan</th>
                            <th class="text-center" width="150">Harga (Het)</th>
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

    let dt_params = {
        month: $('#monthSelect').val(),
        year: $('#yearSelect').val(),
    }

    let dt = $('#gajiTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=getgaji',
            type: "POST",
            data: {
                month: () => dt_params.month,
                year: () => dt_params.year
            },
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no", orderable: false },
            { data: "nama" },
            { 
                data: "pokok",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                }
            },
            { 
                data: "kehadiran",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                }
            },
            { 
                data: "prestasi",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                } 
            },
            { 
                data: "bonus",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                } 
            },
            { 
                data: "indisipliner", 
                render: function (data, type, row) {
                    return (row.process == 0)? (data != 0)? '-' + rupiah(data) : rupiah(data) : 0;
                }
            },
            { 
                data: "tunjangan_jabatan",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                } 
            },
            { 
                data: "total",
                render: function (data, type, row) {
                    return (row.process == 0)? rupiah(data) : 0
                }
            },
            { 
                class: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2) {
                        if(row.process == 1) {
                            return `
                                <button type="button" onclick='doEdit(${row.id_pelanggan})' class="mr-1 mt-2 btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                                <button type="button" onclick='showHistory(${row.id_pelanggan})' class="mr-1 mt-2 btn btn-sm btn-warning" style="width: 2rem;"><i class="fas fa-file-alt"></i></button>
                            `;
                        } else {
                            return `
                                <button type="button" onclick='doEdit(${row.id_pelanggan})' class="mr-1 mt-2 btn btn-sm btn-primary" style="width: 2rem;"><i class="fas fa-edit"></i></button>
                                <button type="button" onclick='showHistory(${row.id_pelanggan})' class="mr-1 mt-2 btn btn-sm btn-warning" style="width: 2rem;"><i class="fas fa-file-alt"></i></button>
                                <button type="button" onclick='processGaji(${row.id_pelanggan})' class="mr-1 mt-2 btn btn-sm btn-success" style="width: 2rem;"><i class="fas fa-hand-holding-usd"></i></button>
                            `;
                        }
                    }else if (sess_data["id_jabatan"] == 5) {
                        return `
                            <button type="button" onclick='showHistory(${row.id_pelanggan})' class="btn btn-sm btn-warning" style="width: 2rem;"><i class="fas fa-file-alt"></i></button>
                        `;
                    }else{
                        return '-';
                    }
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

        if (sess_data["id_jabatan"] != 1 && sess_data["id_jabatan"] != 2 && sss_data["id_jabatan"] != 5) {
            dt.columns([11]).visible(false);
        }

        dt.on( 'draw', function () {
          rewriteColNumbers()
        } );
    });

    function rewriteColNumbers() {
      $('#gajiTable tbody tr').each(function( index ) {
        let val = $('td', this ).first().text();
        if (val != "No data available in table") {
            $('td', this ).first().html(index + 1);
        }
      } );
    }

    function processGaji(id) {
        let ask = window.confirm("Anda yakin ingin memproses data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=processgaji&this="+id+"&page="+info.page+"&month="+dt_params.month+"&year="+dt_params.year
            window.open(url, "_self")
        }
    }

    function doEdit(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-gaji&this="+id+"&page="+info.page+"&month="+dt_params.month+"&year="+dt_params.year
        window.open(url, "_self")
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
                    id_user: id,
                    month: dt_params.month,
                    year: dt_params.year
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "no_faktur" },
                { data: "tanggal" },
                { data: "barcode" },
                { data: "nama" },
                { data: "pelanggan" },
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

    function resetFilter() {
        $('#monthSelect').val("");
        $('#yearSelect').val("");
        dt_params.month = "";
        dt_params.year = "";
        dt.ajax.reload();
    }

    function doFilter() {
        dt_params.month = $('#monthSelect').val();
        dt_params.year = $('#yearSelect').val();
        dt.ajax.reload();
    }

</script>