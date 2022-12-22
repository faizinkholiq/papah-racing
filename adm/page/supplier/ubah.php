<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_supplier = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM supplier WHERE id_supplier='$id_supplier' "));
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-store'></i> Ubah Supplier</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahsupplier&page=<?= $page ?>" method="post">
        <input type="hidden" name="id_supplier" value="<?= $id_supplier; ?>">
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Supplier</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $data['alamat']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="kontak" name="kontak" value="<?= $data['kontak']; ?>" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>