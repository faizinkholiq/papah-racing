<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-bookmark'></i> Tambah Merk</h3>
    </div>
    <div class="col-4"><a href="main?url=merk" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambah-merk" method="post">
        <div class="form-group row">
            <label for="alamat" class="col-sm-2 col-form-label">MErk</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="merk" name="name" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>
</div>