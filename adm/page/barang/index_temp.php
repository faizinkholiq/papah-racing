<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-history'></i> History Perubahan Barang</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
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
            type: "POST"
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
            { data: "status" },
            { data: "type" },
            { data: "aksi", class:"text-center" },
        ],
        ordering: false
    });

    $(document).ready(()=>{
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#barangTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });

    function editBarang(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-barang&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusBarang(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapusbarang&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>