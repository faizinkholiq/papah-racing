<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-basket'></i> Tambah Jenis Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="main?url=jenis-pengeluaran" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambahjenispengeluaran" method="post">
        <div class="form-group row">
            <label for="jenis" class="col-sm-2 col-form-label">Jenis Pengeluaran</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="jenis" name="jenis" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>