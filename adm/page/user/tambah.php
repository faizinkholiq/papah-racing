<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class="fas fa-user"></i> Tambah User</h3>
    </div>
    <div class="col-4"><a href="main?url=user" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambahuser" method="post">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" minlength="6" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" required>
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
            <label for="id_jabatan" class="col-sm-2 col-form-label">Jabatan</label>
            <div class="col-sm-10">
                <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM jabatan WHERE nama!='Administrator' AND nama!='Owner'");
                    foreach ($query as $data) :
                    ?>
                        <option value="<?= $data['id_jabatan']; ?>"><?= $data['nama']; ?></option>
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