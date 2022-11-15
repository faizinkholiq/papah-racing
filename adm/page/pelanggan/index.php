<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-handshake'></i> Data Pelanggan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
        <a href="main?url=tambah-pelanggan" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table id="pelangganTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Bulan Ini</th>
                    <th>Bulan Lalu</th>
                    <th>Type</th>
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
        var dt = $('#pelangganTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getpelanggan',
                type: "GET"
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "row_no" },
                { data: "nama" },
                { data: "bulan_ini" },
                { data: "bulan_lalu" },
                { data: "type" },
                { data: "alamat" },
                { data: "kontak" },
                { data: "aksi" },
            ],
            ordering: false
        });

        if (sess_data["id_jabatan"] != 1 && sess_data["id_jabatan"] != 2){
            dt.columns([2,3,7]).visible(false);
        }
    });
</script>