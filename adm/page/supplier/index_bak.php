<?php
$query = mysqli_query($con, "SELECT * FROM supplier WHERE id_supplier!='1' ORDER BY id_supplier DESC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-store'></i> Data Supplier</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-supplier" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
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
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-left"><?= $data['nama']; ?></td>
                        <td class="text-left"><?= $data['alamat']; ?></td>
                        <td class="text-right"><?= $data['kontak']; ?></td>
                        <td>
                            <!-- <a href="" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a> -->
                            <a href="main?url=ubah-supplier&this=<?= $data['id_supplier']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                            <a href="process/action?url=hapussupplier&this=<?= $data['id_supplier']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>