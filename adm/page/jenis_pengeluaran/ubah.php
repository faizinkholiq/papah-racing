<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_pengeluaran_type = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pengeluaran_type WHERE id_pengeluaran_type='$id_pengeluaran_type' "));
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-basket'></i> Ubah Jenis Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="main?url=jenis-pengeluaran&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahjenispengeluaran&page=<?= $page ?>" method="post">
        <input type="hidden" name="id_pengeluaran_type" value="<?= $id_pengeluaran_type; ?>">
        <div class="form-group row">
            <label for="jenis" class="col-sm-2 col-form-label">Jenis Pengeluaran</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="jenis" name="jenis" value="<?= $data['jenis']; ?>" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>