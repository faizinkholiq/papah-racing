<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-phone'></i> Tambah Kontak</h3>
    </div>
    <div class="col-4"><a href="main?url=kontak" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambah-kontak" method="post">
        <div class="form-group row">
            <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="keterangan" name="keterangan" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="alamat" class="col-sm-2 col-form-label">Kontak</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="kontak" name="kontak" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>