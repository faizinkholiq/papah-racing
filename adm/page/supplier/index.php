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

    $(document).ready(function () {
        var dt = $('#supplierTable').DataTable({
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
                { data: "aksi" },
            ],
            ordering: false
        });
    });
</script>