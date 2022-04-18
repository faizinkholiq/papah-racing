<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko WHERE id_toko='1' "));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cog'></i> Data Toko</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahdatatoko" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_toko" value="<?= $data['id_toko']; ?>">

        <div class="form-group row">
            <label for="nama_toko" class="col-sm-2 col-form-label">Nama Toko</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?= $data['nama_toko']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="ket_toko" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="ket_toko" name="ket_toko" value="<?= $data['ket_toko']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="alamat_toko" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="alamat_toko" name="alamat_toko" value="<?= $data['alamat_toko']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak_toko" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="kontak_toko" name="kontak_toko" value="<?= $data['kontak_toko']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="logo_title" class="col-sm-2 col-form-label">Logo Title</label>
            <div class="col-sm-8">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo_title" name="logo_title" accept=".png">
                    <label class="custom-file-label" for="logo_title">Pilih File...</label>
                </div>
            </div>
            <div class="col-sm-2">
                <img src="assets/img/<?= $data['logo_title']; ?>" alt="logo title bar" width="50px" height="50px">
            </div>
        </div>
        <div class="form-group row">
            <label for="logo_header" class="col-sm-2 col-form-label">Logo Header</label>
            <div class="col-sm-8">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo_header" name="logo_header" accept=".png">
                    <label class="custom-file-label" for="logo_header">Pilih File...</label>
                </div>
            </div>
            <div class="col-sm-2">
                <img src="assets/img/<?= $data['logo_header']; ?>" alt="logo header bar" width="180px" height="50px">
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>