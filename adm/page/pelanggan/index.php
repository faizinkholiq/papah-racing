<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-handshake'></i> Data Pelanggan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2" || $_SESSION["id_jabatan"] == "5") { ?>
        <a href="main?url=tambah-pelanggan" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table id="pelangganTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Nama</th>
                    <th>Bulan Ini</th>
                    <th>Bulan Lalu</th>
                    <th>Type</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Admin</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal History Pembelian -->
<div id="modalHistoryPembelian" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">History Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <div class="table-responsive mt-3">
                    <table id="historyPembelianTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const sess_data = <?= json_encode($_SESSION) ?>;
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;

    let dt = $('#pelangganTable').DataTable({
        dom: "zBflrtip",
        ajax: {
            url: 'process/action?url=getpelanggan',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no", orderable: false },
            { data: "nama"},
            { data: "bulan_ini", visible: false, class:"text-center", },
            { data: "bulan_lalu", visible: false, class:"text-center", },
            { data: "type", },
            { data: "alamat", },
            { data: "kontak", },
            { data: "admin", },
            { data: "aksi", visible: false, class:"text-center", orderable:false },
        ],
        ordering: true,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    $(document).ready(function () {
        if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2 || sess_data["id_jabatan"] == 5){
            dt.columns([1,2,8]).visible(true);
        }

        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#pelangganTable').on( 'page.dt', function () {
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
      $('#pelangganTable tbody tr').each(function( index ) {
        $('td', this ).first().html(index + 1);
      } );
    }

    function editPelanggan(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-pelanggan&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusPelanggan(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapuspelanggan&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function historyPelanggan(id) {
        $('#modalHistoryPembelian').modal('show');

        $('#historyPembelianTable').DataTable().clear().destroy();
        $('#historyPembelianTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=gethistorypembelian',
                type: "POST",
                data: {
                    id_pelanggan: id,
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "tanggal" },
                { data: "nama" },
                { data: "qty" },
                { data: "total_harga" },
            ],
            ordering: false
        });

    }

</script>