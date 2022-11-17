<?php
$query = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan!='1' ORDER BY id_jabatan ASC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class="fas fa-user"></i> Data User</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
        <a href="main?url=tambah-user" class="btn btn-primary"><i class="fas fa-plus-circle mr-2"></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table id="userTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Bulan Ini</th>
                    <th>Bulan Lalu</th>
                    <th>Username</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Terakhir Login</th>
                    <th width="15%">Aksi</th>
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
        var dt = $('#userTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getuser',
                type: "POST"
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "row_no" },
                { data: "nama" },
                { data: "bulan_ini", visible: false, },
                { data: "bulan_lalu", visible: false, },
                { data: "username" },
                { data: "alamat" },
                { data: "kontak" },
                { data: "jabatan", className: "text-center", },
                { data: "status", className: "text-center", },
                { data: "last_login", visible: false, className: "text-center", },
                { data: "aksi", visible: false, className: "text-center", },
            ],
            ordering: false
        });

        if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2){
            dt.columns([2,3,9,10]).visible(true);
        }
    });
</script>