<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_pengeluaran = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pengeluaran WHERE id_pengeluaran='$id_pengeluaran' "));
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-bag'></i> Ubah Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="main?url=pengeluaran&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahpengeluaran&page=<?= $page ?>" method="post">
        <input type="hidden" name="id_pengeluaran" value="<?= $id_pengeluaran; ?>">
        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
        <div class="form-group row">
            <label for="id_pengeluaran_type" class="col-sm-2 col-form-label">Jenis</label>
            <div class="col-sm-10">
                <select class="form-control" id="id_pengeluaran_type" name="id_pengeluaran_type" required>
                    <?php
                    $query_jenis = mysqli_query($con, "SELECT * FROM pengeluaran_type");
                    foreach ($query_jenis as $qj) :
                    ?>
                        <option value="<?= $qj['id_pengeluaran_type']; ?>" <?php if ($data['id_pengeluaran_type'] == $qj['id_pengeluaran_type']) {
                                                                                echo "selected";
                                                                            } ?>><?= $qj['jenis']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
            <div class="col-sm-10">
                <input type="text" min="0" class="form-control uang" id="jumlah" name="jumlah" value="<?= $data['jumlah']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data['keterangan']; ?>" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>