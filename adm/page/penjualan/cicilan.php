<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$no_faktur = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan WHERE no_faktur='$no_faktur' "));
$kekurangan = $data['total_transaksi'] - $data['total_bayar'];
$page = isset($_GET['page'])? $_GET['page'] : 0;
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cash-register'></i> Cicilan Penjualan</h3>
    </div>
    <div class="col-4"><a href="main?url=penjualan&page=<?= $page ?>" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=cicilanpenjualan&page=<?= $page ?>" method="post">
        <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'] ?>">
        <div class="form-group row">
            <label for="no_faktur" class="col-sm-2 col-form-label">No PO</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="no_faktur" name="no_faktur" value="<?= $no_faktur; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="total_transaksi" class="col-sm-2 col-form-label">Total Transaksi</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="total_transaksi" name="total_transaksi" value="<?= $data['total_transaksi']; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="total_bayar" class="col-sm-2 col-form-label">Yang Sudah Dibayar</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="total_bayar" name="total_bayar" value="<?= $data['total_bayar']; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="kekurangan" class="col-sm-2 col-form-label">Kekurangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="kekurangan" name="kekurangan" value="<?= $kekurangan; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="bayar" class="col-sm-2 col-form-label">Bayar Cicilan</label>
            <div class="col-sm-10">
                <input type="number" min="1" class="form-control" id="bayar" name="bayar" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-money-bill-alt mr-2'></i>Bayar</button>
            </div>
        </div>

    </form>
</div>