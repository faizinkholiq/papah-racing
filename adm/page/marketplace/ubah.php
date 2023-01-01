<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM market_place WHERE id='$id' "));
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-share-alt'></i> Tambah Social Media</h3>
    </div>
    <div class="col-4"><a href="main?url=marketplace&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubah-marketplace&page=<?= $page ?>" method="post">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <div class="form-group row">
            <label for="tipe" class="col-sm-2 col-form-label">Tipe</label>
            <div class="col-sm-10">
                <select class="form-control" id="tipe" name="tipe" required>
                    <option value="Blibli" <?= ($data['tipe'] == 'Blibli')? 'selected' : '' ?>>Blibli</option>
                    <option value="Bukalapak" <?= ($data['tipe'] == 'Bukalapak')? 'selected' : '' ?>>Bukalapak</option>
                    <option value="Lazada" <?= ($data['tipe'] == 'Lazada')? 'selected' : '' ?>>Lazada</option>
                    <option value="Shopee" <?= ($data['tipe'] == 'Shopee')? 'selected' : '' ?>>Shopee</option>
                    <option value="Tokopedia" <?= ($data['tipe'] == 'Tokopedia')? 'selected' : '' ?>>Tokopedia</option>
				</select>
            </div>
        </div>
        <div class="form-group row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="keterangan" name="keterangan" required value="<?=$data['keterangan']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="link" class="col-sm-2 col-form-label">Link</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="link" name="link" required value="<?=$data['link']?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="order_no" class="col-sm-2 col-form-label">Order No.</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="order_no" name="order_no" value="<?=$data['order_no']?>">
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>