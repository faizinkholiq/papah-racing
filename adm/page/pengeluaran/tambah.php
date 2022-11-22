<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-bag'></i> Tambah Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="main?url=pengeluaran" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambahpengeluaran" method="post">
        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
        <div class="form-group row">
            <label for="id_pengeluaran_type" class="col-sm-2 col-form-label">Jenis</label>
            <div class="col-sm-10">
                <select class="form-control" id="id_pengeluaran_type" name="id_pengeluaran_type" required>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM pengeluaran_type");
                    foreach ($query as $data) :
                    ?>
                        <option value="<?= $data['id_pengeluaran_type']; ?>"><?= $data['jenis']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah (Rp)</label>
            <div class="col-sm-10">
                <input type="text" min="0" class="form-control uang" id="jumlah" name="jumlah" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>