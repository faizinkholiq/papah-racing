<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kontak WHERE id='$id' "));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-phone'></i> Ubah Kontak</h3>
    </div>
    <div class="col-4"><a href="main?url=kontak" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubah-kontak" method="post">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-group row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data['keterangan']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="kontak" name="kontak" value="<?= $data['kontak']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak" class="col-sm-2 col-form-label">Letak</label>
            <div class="col-sm-10">
                <select class="form-control" id="letak" name="letak">
                    <option value=""></option>
                    <option <?=($data['letak'] == 'footer')? 'selected' : '' ?> value="footer">Footer</option>
                    <option <?=($data['letak'] == 'order')? 'selected' : '' ?> value="order">Order</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
                <select class="form-control" id="letak" name="letak">
                    <option <?=($data['aktif'] == true)? 'selected' : '' ?> value="1">Aktif</option>
                    <option <?=($data['aktif'] == false)? 'selected' : '' ?> value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>