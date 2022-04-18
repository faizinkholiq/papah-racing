<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_user = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM user WHERE id_user='$id_user' "));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-user'></i> Tambah User</h3>
    </div>
    <div class="col-4"><a href="main?url=user" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahuser" method="post">
        <input type="hidden" name="id_user" value="<?= $id_user; ?>">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" value="<?= $data['username']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
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
        <div class="form-group row">
            <label for="id_jabatan" class="col-sm-2 col-form-label">Jabatan</label>
            <div class="col-sm-10">
                <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                    <?php if ($data['id_jabatan'] == "1") { ?>
                        <option value="1">Owner</option>
                        <?php } else {
                        $query_jabatan = mysqli_query($con, "SELECT * FROM jabatan WHERE nama!='Administrator' && nama!='Owner'");
                        foreach ($query_jabatan as $qj) :
                        ?>
                            <option value="<?= $qj['id_jabatan']; ?>" <?php if ($data['id_jabatan'] == $qj['id_jabatan']) {
                                                                            echo "selected";
                                                                        } ?>><?= $qj['nama']; ?></option>
                    <?php endforeach;
                    } ?>
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