<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-store'></i> Data Supplier</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-supplier" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table id="supplierTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
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

    let dt = $('#supplierTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getsupplier',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no" },
            { data: "nama" },
            { data: "alamat" },
            { data: "kontak" },
            { data: "aksi", class:"text-center" },
        ],
        ordering: false
    });

    $(document).ready(function () {
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#supplierTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });
</script>