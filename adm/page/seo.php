<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko WHERE id_toko='1' "));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cog'></i> SEO</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahdatatoko" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_toko" value="<?= $data['id_toko']; ?>">

        <div class="form-group row">
            <label for="nama_toko" class="col-sm-2 col-form-label">Total Visitor</label>
            <div class="col-sm-10">
            </div>
        </div>
        <div class="form-group row">
            <label for="ket_toko" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
            </div>
        </div>
        <div class="form-group row">
            <label for="alamat_toko" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak_toko" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>