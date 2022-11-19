<?php
$query = mysqli_query($con, "SELECT * FROM merk ORDER BY name ASC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-bookmark'></i> Data Merk</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-merk" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Merk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-left"><?= $data['name']; ?></td>
                        <td>
                            <a href="main?url=ubah-merk&this=<?= $data['id']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                            <a href="process/action?url=hapus-merk&this=<?= $data['id']; ?>" 
                                class="btn btn-danger btn-sm" 
                                data-toggle="tooltip" 
                                data-original-title="Hapus" 
                                onclick="return confirm('Anda yakin ingin hapus data ini?')">
                                <i class='fas fa-trash-alt'></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>