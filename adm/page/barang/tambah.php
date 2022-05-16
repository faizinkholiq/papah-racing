<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-box'></i> Tambah Barang</h3>
    </div>
    <div class="col-4"><a href="main?url=barang" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=tambahbarang" enctype="multipart/form-data"  method="post">
        <div class="form-group row">
            <label for="barcode" class="col-sm-2 col-form-label">Barcode</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="barcode" name="barcode" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="merk" class="col-sm-2 col-form-label">Merk</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="merk" name="merk" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stok" class="col-sm-2 col-form-label">Stok</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="stok" name="stok" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Kondisi</label>
            <div class="col-sm-10">
                <select class="form-control" id="kondisi" name="kondisi" required>
									<?php
									$konds = array('','BARU','BEKAS');
									foreach ($konds as $k){
										if ($k==$data['kondisi']){
											echo '<option value="'.$k.'" selected>'.ucwords($k).'</option>';
										} else {
											echo '<option value="'.$k.'">'.ucwords($k).'</option>';
										}
									}
									?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Kualitas</label>
            <div class="col-sm-10">
                <select class="form-control" id="kualitas" name="kualitas" required>
                  <?php
									$quals = array('','ORIGINAL');
									foreach ($quals as $q){
										if ($q==$data['kualitas']){
											echo '<option value="'.$q.'" selected>'.ucwords($q).'</option>';
										} else {
											echo '<option value="'.$q.'">'.ucwords($q).'</option>';
										}
									}
									?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
                <select class="form-control" id="kategori" name="kategori[]" multiple data-live-search="true" required>
                  <?php
									$quals = array(null, 'MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','KOPLING','PISTON', 'GEARBOX', 'MEMBRAN', 'INTAKE MANIPOL', 'BUSI', 'VARIASI', 'PAKING (GASKET)', 'BEARING', 'SPECIAL DISKON');
									foreach ($quals as $q){
										if ($q==$data['kategori']){
											echo '<option value="'.$q.'" selected>'.ucwords($q).'</option>';
										} else {
											echo '<option value="'.$q.'">'.ucwords($q).'</option>';
										}
									}
									?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Tipe Pelanggan</label>
            <div class="col-sm-10">
                <select class="form-control" id="tipe_pelanggan" name="tipe_pelanggan" required>
                  <?php
									$quals = array('','DISTRIBUTOR');
									foreach ($quals as $q){
										if ($q==$data['tipe_pelanggan']){
											echo '<option value="'.$q.'" selected>'.ucwords($q).'</option>';
										} else {
											echo '<option value="'.$q.'">'.ucwords($q).'</option>';
										}
									}
									?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="tambahan" class="col-sm-2 col-form-label">Keterangan Tambahan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="tambahan" name="tambahan" placeholder="Semisal ukuran / warna dengan pemisah coma">
            </div>
        </div>
        <div class="form-group row">
            <label for="modal" class="col-sm-2 col-form-label">Harga Modal</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" name="modal" class="form-control uang" id="modal" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="distributor" class="col-sm-2 col-form-label">Harga Distributor</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="distributor" name="distributor" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="reseller" class="col-sm-2 col-form-label">Harga Reseller</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="reseller" name="reseller" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="bengkel" class="col-sm-2 col-form-label">Harga Bengkel</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="bengkel" name="bengkel" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="admin" class="col-sm-2 col-form-label">Harga Admin</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="admin" name="admin" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="het" class="col-sm-2 col-form-label">Harga HET</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="het" name="het" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="het" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
            </div>
        </div>
        <div class="card bg-light mb-3">
            <div class="card-header font-weight-bolder">Upload Gambar Maximum 3, Ukuran file Maximum 4 Mb</div><br>
            <div class="card-body" style="text-align: center">
                <input id="imgInp" type="file" name="gambar[]" accept="image/*" multiple>
                <div class="col-lg-12 mt-4 img-container">
                </div><br>        
            </div>
        </div><br>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>

<script>
    var photos = [];
    
    class _DataTransfer {
      constructor() {
        return new ClipboardEvent("").clipboardData || new DataTransfer();
      }
    }
    
    function doPreview(fileList) {
        $('.img-container').html('')
        if (fileList) {
            for (let file of fileList) {
             	var reader = new FileReader();
            
              reader.onload = function (e) {
                  $('.img-container').append(`
                    <div class="i6">
                        <img src="${e.target.result}">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" 
                            onclick="removeImage('${file.name}')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                  `)
              }

              reader.readAsDataURL(file);
            }
        }
    }
    
    function removeImage(name) {
    	photos = photos.filter((item) => item.name !== name)
        updateValue()
        doPreview($("#imgInp")[0].files)
    }
    
    function updateValue() {
    	const dt = new _DataTransfer();
        for (let file of photos) {
            dt.items.add(file)  
        }
        $("#imgInp")[0].files = dt.files 
    }
    
    $("#imgInp").change(function(){
        let arrImage = Object.values($(this)[0].files)
        
        if (photos.length > 0) {
              var ids = new Set(photos.map(d => d.name));
            photos = [...photos, ...arrImage.filter(d => !ids.has(d.name))];
        }else{
            photos = arrImage;
        } 

        updateValue()
        doPreview($("#imgInp")[0].files)
        
    });
</script>