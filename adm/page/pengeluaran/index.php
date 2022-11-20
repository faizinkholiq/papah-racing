<?php
if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
	$query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user ORDER BY tanggal DESC");
} else {
	$query = mysqli_query($con, "SELECT * FROM pengeluaran JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type=pengeluaran_type.id_pengeluaran_type JOIN user ON pengeluaran.id_user=user.id_user WHERE user.id_user='" . $_SESSION['id_user'] . "' ORDER BY tanggal DESC");
}

?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-bag'></i> Data Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-pengeluaran" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table id="pengeluaranTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Pengguna</th>
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
        var dt = $('#pengeluaranTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getpengeluaran',
                type: "POST",
                data: {
                    admin: <?= isset($_GET['admin'])? $_GET['admin'] : 0 ?>
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "row_no" },
                { data: "tanggal" },
                { data: "jenis" },
                { data: "jumlah" },
                { data: "keterangan" },
                { data: "user" },
                { data: "aksi", className: "text-center", },
            ],
            ordering: false
        });
    });
</script>