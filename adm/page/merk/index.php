<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-bookmark'></i> Data Merk</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-merk" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table id="merkTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Merk</th>
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
        var dt = $('#merkTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getmerk',
                type: "POST"
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "row_no" },
                { data: "name" },
                { data: "aksi" },
            ],
            ordering: false
        });
    });
</script>