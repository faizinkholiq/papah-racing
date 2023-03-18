<?php
$admins = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan = 5");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-handshake'></i> Tambah Pelanggan</h3>
    </div>
    <div class="col-4"><a href="main?url=pelanggan" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambahpelanggan" method="post">
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Pelanggan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Type</label>
            <div class="col-sm-10">
                <select class="form-control" id="type" name="type" required>
                    <option value="distributor">Distributor</option>
                    <option value="reseller">Reseller</option>
                    <option value="bengkel">Bengkel</option>
                    <option value="admin">Admin</option>
                    <option value="het">HET</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kontak" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="kontak" name="kontak" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Admin</label>
            <div class="col-sm-10">
                <select class="form-control" id="admin" name="admin">
                    <option value="">- Pilih salah satu-</option>
                    <?php foreach($admins as $item): ?>
                    <option value="<?= $item['id_user'] ?>"><?= $item['nama'] ?></option>
                    <?php endforeach; ?>
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