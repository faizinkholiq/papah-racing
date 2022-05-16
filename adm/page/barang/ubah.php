<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_barang = $_GET['this'];
$path = str_replace('/adm/page/barang','/p/'.trim($id_barang),dirname(__FILE__));

$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE id_barang='$id_barang' "));
$selected_brg = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM foto_barang WHERE id_barang='$id_barang' "));
if ($_SESSION['id_jabatan'] == '1'||$_SESSION['id_jabatan'] == '2'||$_SESSION['id_jabatan'] == '3') {
	echo '<!--';
	print_r($data);
	echo '--!>';
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-box'></i> Ubah Barang</h3>
    </div>
    <div class="col-4"><a href="main?url=barang" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=ubahbarang" enctype="multipart/form-data" method="post">
        <input type="hidden" name="id_barang" value="<?= $id_barang; ?>">
        <div class="form-group row">
            <label for="barcode" class="col-sm-2 col-form-label">Barcode</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="barcode" name="barcode" value="<?= $data['barcode']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="merk" class="col-sm-2 col-form-label">Merk</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="merk" name="merk" value="<?= $data['merk']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="stok" class="col-sm-2 col-form-label">Stok</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="stok" name="stok" value="<?= $data['stok']; ?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="kondisi" class="col-sm-2 col-form-label">Kondisi</label>
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
            <label for="kualitas" class="col-sm-2 col-form-label">Kualitas</label>
            <div class="col-sm-10">
                <select class="form-control" id="kualitas" name="kualitas">
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
            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
                <select class="form-control selectpicker" id="kategori" name="kategori[]" multiple data-live-search="true" required>
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
            <label for="berat" class="col-sm-2 col-form-label">Berat (Kg)</label>
            <div class="col-sm-10">
                <input type="number" min="0" class="form-control" id="berat" name="berat" value="<?= $data['berat']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Tipe Pelanggan</label>
            <div class="col-sm-10">
                <select class="form-control" id="tipe_pelanggan" name="tipe_pelanggan">
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
                <input type="text" class="form-control" id="tambahan" name="tambahan" value="<?= $data['tambahan']; ?>" placeholder="Semisal ukuran / warna dengan pemisah coma">
            </div>
        </div>
        <div class="form-group row">
            <label for="modal" class="col-sm-2 col-form-label">Harga Modal</label>
            <div class="col-sm-10">
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control uang" id="modal" name="modal" value="<?= $data['modal']; ?>" autocomplete="off" required>
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
                    <input type="text" class="form-control uang" id="distributor" name="distributor" value="<?= $data['distributor']; ?>" autocomplete="off" required>
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
                    <input type="text" class="form-control uang" id="reseller" name="reseller" value="<?= $data['reseller']; ?>" autocomplete="off" required>
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
                    <input type="text" class="form-control uang" id="bengkel" name="bengkel" value="<?= $data['bengkel']; ?>" autocomplete="off" required>
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
                    <input type="text" class="form-control uang" id="admin" name="admin" value="<?= $data['admin']; ?>" autocomplete="off" required>
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
                    <input type="text" class="form-control uang" id="het" name="het" value="<?= $data['het']; ?>" autocomplete="off" required>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="het" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= $data['deskripsi']; ?></textarea>
            </div>
        </div>
        <div class="card bg-light mb-3">
            <div class="card-header font-weight-bolder">Upload Gambar Maximum 3, Ukuran file Maximum 4 Mb</div><br>
            <input type="hidden" id="hapus_barang" name="hapus_barang" />
            <?php $val_selected_brg = !empty($selected_brg['name'])? str_replace('/adm/page/barang','/p/'.trim($id_barang),dirname(__FILE__)).'/'.$selected_brg['name'] : '' ; ?>
            <input type="hidden" id="selected_barang" name="selected_barang" value="<?=$val_selected_brg?>"/>
            <div class="card-body" style="text-align: center">
                <?php
                    if (file_exists($path)):
                    $gl = glob($path.'/*');        
                ?>
                <input id="imgInp" type="file" name="gambar[]" accept="image/*" multiple>
                <div class="col-lg-12 mt-4 img-container">
                <?php foreach ($gl as $l): ?>
                        <div onclick="selectImage('<?= $l ?>')" class="i6" data-id="<?= $l ?>">
                            <div class="overlay" style="display:<?=($selected_brg['name'] == basename($l))? '' : 'none' ?>"><i class="fa fa-check"></i></div>
                            <img src="<?= SITEURL.'/p/'.trim($id_barang).'/'.basename($l) ?>">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" 
                                onclick="removeImage('<?= $l ?>', false, event)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                <?php endforeach; ?>
                </div><br>
                <?php endif; ?>
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
    var deleted_photos = [];
    
    class _DataTransfer {
      constructor() {
        return new ClipboardEvent("").clipboardData || new DataTransfer();
      }
    }
    
    function doPreview(fileList) {
        // $('.img-container').html('')
        if (fileList) {
            for (let file of fileList) {
             	var reader = new FileReader();
            
              reader.onload = function (e) {
                  $('.img-container').append(`
                    <div class="i6" onclick="selectImage('${file.name}')" data-id="${file.name}">
                        <div class="overlay" style="display:none"><i class="fa fa-check"></i></div>
                        <img src="${e.target.result}">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" 
                            onclick="removeImage('${file.name}', true, event)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                  `)
              }

              reader.readAsDataURL(file);
            }
        }
    }
    
    function removeImage(name, file = true, event) {
    	event.stopPropagation();

        if($('#selected_barang').val() != name) {
            photos = photos.filter((item) => item.name !== name)
            
            if (photos.length < 3) {
                $('#imgInp').show()
            }else{
                $('#imgInp').hide()
            }

            if (file) {
                updateValue()
                $(`div[data-id='${name}']`).remove()
            }else{
                deleted_photos.push(name)
                $('#hapus_barang').val(deleted_photos)
                $(`div[data-id='${name}']`).remove()
            }
        }else{
            alert("Tidak bisa menghapus barang yang sedang ditampilkan");
        }
    }

    function selectImage(name) {
        $('#selected_barang').val(name)
        $('.i6 .overlay').hide()
        $(`div[data-id='${name}'] .overlay`).fadeIn()
    }
    
    function updateValue() {
    	const dt = new _DataTransfer();
        for (let file of photos) {
            if (!file.saved){
                dt.items.add(file)  
            }
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

        if(photos.length > 3) {
            photos = photos.slice(0,3)
        }

        updateValue()
        doPreview($("#imgInp")[0].files)
        
    });

    $(document).ready(function() {
        kategori = "<?php echo $data['kategori']; ?>"
        arrKategori = kategori.split(',');
        $('#kategori').val(arrKategori);
        $('select').selectpicker("refresh");
        
        let gl = <?= json_encode(glob($path.'/*')) ?>;
        photos = gl.map((item) => { return {'name': item, 'saved': true} } )

        if (photos.length < 3) {
            $('#imgInp').show()
        }else{
            $('#imgInp').hide()
        }
    });
</script>
<?php
} else {}

if ($_SESSION['id_jabatan'] == '8'||$_SESSION['id_jabatan'] == '7'||$_SESSION['id_jabatan'] == '6'||$_SESSION['id_jabatan'] == '5'){
	echo '<div class="row"><div class="col-md-12 mb-2"><div class="card bg-light mb-3"><div class="card-header font-weight-bolder">Gambar '.$data['nama'].'</div><div class="card-body">';
	if (file_exists($path)){
		$gl = glob($path.'/*');
		// print_r($gl);
		if (count($gl)>0){
			foreach ($gl as $l){
				echo '<div class="i6"><img src="https://'.str_replace('admin.','',$_SERVER['SERVER_NAME']).'/p/'.trim($id_barang).'/'.basename($l).'"></div>';
			}
		} else {
			echo 'Gambar Belum Ada';
		}
	}
	echo '</div></div><a href="main?url=barang" class="btn btn-danger float-right"><i class="fas fa-times-circle mr-2"></i>Back</a></div></div>';
}
?>