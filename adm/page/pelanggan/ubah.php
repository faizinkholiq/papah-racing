<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_pelanggan = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan='$id_pelanggan' "));
$page = isset($_GET['page'])? $_GET['page'] : 0;
$admins = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan = 5");
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-handshake'></i> Ubah Pelanggan</h3>
    </div>
    <div class="col-4"><a href="main?url=pelanggan&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahpelanggan&page=<?= $page ?>" method="post">
        <input type="hidden" name="id_pelanggan" value="<?= $id_pelanggan; ?>">
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Pelanggan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Type</label>
            <div class="col-sm-10">
                <select class="form-control" id="type" name="type" required>
                    <option value="distributor" <?php if ($data['type'] == 'distributor') {
                                                    echo "selected";
                                                } ?>>Distributor</option>
                    <option value="reseller" <?php if ($data['type'] == 'reseller') {
                                                    echo "selected";
                                                } ?>>Reseller</option>
                    <option value="bengkel" <?php if ($data['type'] == 'bengkel') {
                                                echo "selected";
                                            } ?>>Bengkel</option>
                    <option value="admin" <?php if ($data['type'] == 'admin') {
                                                echo "selected";
                                            } ?>>Admin</option>
                    <option value="het" <?php if ($data['type'] == 'het') {
                                            echo "selected";
                                        } ?>>Het</option>
                </select>
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
            <label for="type" class="col-sm-2 col-form-label">Admin</label>
            <div class="col-sm-10">
                <select class="form-control" id="admin" name="admin">
                    <option value="">- Pilih salah satu-</option>
                    <?php foreach($admins as $item): ?>
                    <option <?= ($data['admin'] == $item['id_user'])? 'selected' : '' ?> value="<?= $item['id_user'] ?>"><?= $item['nama'] ?></option>
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