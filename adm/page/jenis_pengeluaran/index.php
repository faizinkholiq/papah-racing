<?php
$query = mysqli_query($con, "SELECT * FROM pengeluaran_type ORDER BY id_pengeluaran_type DESC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-basket'></i> Data Jenis Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-jenis-pengeluaran" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table id="jenisPengeluaranTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td><?= $data['jenis']; ?></td>
                        <td>
                            <a href="main?url=ubah-jenis-pengeluaran&this=<?= $data['id_pengeluaran_type']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                            <a href="process/action?url=hapusjenispengeluaran&this=<?= $data['id_pengeluaran_type']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    const sess_data = <?= json_encode($_SESSION) ?>;

    $(document).ready(function () {
        var dt = $('#jenisPengeluaranTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getjenispengeluaran',
                type: "POST"
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "row_no" },
                { data: "jenis" },
                { data: "aksi" },
            ],
            ordering: false
        });
    });
</script>