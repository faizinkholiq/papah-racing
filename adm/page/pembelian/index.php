<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-cart'></i> Data Pembelian</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
        <a href="main?url=tambah-pembelian" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table id="pembelianTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
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
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;

    let dt = $('#pembelianTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getpembelian',
            type: "POST"
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
            { data: "aksi", className: "text-center", },
        ],
        ordering: false
    });

    $(document).ready(function () {
        // if(sess_data["id_jabatan"] == 5){
        //     dt.columns([7]).visible(false);
        // }

        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#merkTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });

    function lihatPembelian(id) {
        const info = dt.page.info();
        const url = "main?url=lihat-pembelian&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function cetakPembelian(id) {
        const info = dt.page.info();
        const url = "page/pembelian/cetak_det.php?this="+id+"&page="+info.page
        window.open(url, "_blank")
    }

    function cicilanPembelian(id) {
        const info = dt.page.info();
        const url = "main?url=cicilan-pembelian&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusPembelian(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapuspembelian&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>