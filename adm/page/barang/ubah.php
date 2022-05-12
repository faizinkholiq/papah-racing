<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_barang = $_GET['this'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE id_barang='$id_barang' "));
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
    <form action="process/action?url=ubahbarang" method="post">
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
            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
                <select class="form-control selectpicker" id="kategori" name="kategori[]" multiple data-live-search="true" required>
                  <?php
									$quals = array(null, 'MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','KOPLING','PISTON', 'GEARBOX', 'MEMBRAN', 'INTAKE MANIPOL', 'BUSI', 'VARIASI', 'PAKING (GASKET)', 'SPECIAL DISKON');
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
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>
    </form>

</div>
<script>
    $(document).ready(function() {
        kategori = "<?php echo $data['kategori']; ?>"
        arrKategori = kategori.split(',');
        $('#kategori').val(arrKategori);
        $('select').selectpicker("refresh");
    });
</script>
<?php
} else {}

$path = str_replace('/adm/page/barang','/p/'.trim($id_barang),dirname(__FILE__));
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
} else {
	echo '<br/><div class="row"><div class="col-4"></div><div class="col-md-12 mb-2"><div class="card bg-light mb-3"><div class="card-header font-weight-bolder">Upload Gambar Maximum 3, Ukuran file Maximum 4 Mb</div><div class="card-body">';
	$uform = '<br/><center><form method="post" enctype="multipart/form-data" action="main?url=upload-barang&this='.$id_barang.'">
	<input type="file" name="gambar[]" accept="image/*" multiple>
	<input type="hidden" value="'.$data['nama'].'" name="nama">
	<input type="submit" class="btn btn-success mb-2" value="Upload">
	</form></center><br/>';
	if (file_exists($path)){
		$gl = glob($path.'/*');
		// print_r($gl);
		if (count($gl)>2){
		} else {
			echo $uform;
		}
		foreach ($gl as $l){
			echo '<div class="i6"><img src="'.SITEURL.'/p/'.trim($id_barang).'/'.basename($l).'"><a href="main?url=hapus-barang&this='.$id_barang.'&img='.basename($l).'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm(\'Anda yakin ingin hapus data ini?\')"><i class="fas fa-trash-alt"></i></a></div>';
		}
	} else {
		echo $uform;
	}
	echo '</div></div><a href="main?url=barang" class="btn btn-danger float-right"><i class="fas fa-times-circle mr-2"></i>Back</a></div></div>';
}
echo '<style>.i6{position:relative;float:left;border:1px solid #eee;margin:0 10px 10px 0;width:300px;height:300px;overflow:hidden;}.i6 img{width:100%;height:auto;}.i6 a{position:absolute;top:10px;right:10px;}</style>';
?>