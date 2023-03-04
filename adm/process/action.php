<?php date_default_timezone_set('Asia/Jakarta');
if (empty($_GET['url'])) {
	header('location:../notfound');
} else {
	require '../config/connect.php';
	require 'model.php';
	$nc = new con();
	$to = $_GET['url'];
	session_start();

	if ($to == 'login') {
		$nc->login($con, $_POST['username'], $_POST['password']);
	} else if ($to == 'logout') {
		$nc->logout();
	} else if ($to == 'getuser') {
		$nc->getuser($con);
	} else if ($to == 'tambahuser') {
		$nc->tambahuser($con, $_POST['username'], $_POST['password'], $_POST['nama'], $_POST['alamat'], $_POST['kontak'], $_POST['id_jabatan']);
	} else if ($to == 'ubahuser') {
		$nc->ubahuser($con, $_POST['id_user'], $_POST['username'], $_POST['nama'], $_POST['alamat'], $_POST['kontak'], $_POST['id_jabatan']);
	} else if ($to == 'resetpassworduser') {
		if ($_POST['password'] == $_POST['password2']) {
			$nc->resetpassworduser($con, $_POST['id_user'], $_POST['password'], $_POST['password2']);
		} else {
			echo "<script>alert('Maaf password yang anda masukkan tidak sama.'); window.location='../main?url=reset-password-user&this=" . $_POST['id_user'] . "';</script>";
		}
	} else if ($to == 'gantipassworduser') {
		$cek = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM user WHERE id_user='$_POST[id_user]' OR username='$_POST[username]' "));
		if (password_verify($_POST['password_lama'], $cek['password'])) {
			if ($_POST['password'] == $_POST['password2']) {
				$nc->gantipassworduser($con, $_POST['id_user'], $_POST['password'], $_POST['password2']);
			} else {
				echo "<script>alert('Maaf password yang anda masukkan tidak sama.'); window.location='../main?url=reset-password-user&this=" . $_POST['id_user'] . "';</script>";
			}
		}
		echo "<script>alert('Maaf password lama anda salah'); window.location='../main?url=ganti-password-user';</script>";
	} else if ($to == 'hapususer') {
		$id_user = $_GET['this'];
		$nc->hapususer($con, $id_user);
	} else if ($to == 'setaktifuser') {
		$id_user = $_GET['this'];
		$nc->setaktifuser($con, $id_user, $_GET["aktif"]);
	} else if ($to == 'getsupplier') {
		$nc->getsupplier($con);
	} else if ($to == 'tambahsupplier') {
		$nc->tambahsupplier($con, $_POST['nama'], $_POST['alamat'], $_POST['kontak']);
	} else if ($to == 'ubahsupplier') {
		$nc->ubahsupplier($con, $_POST['id_supplier'], $_POST['nama'], $_POST['alamat'], $_POST['kontak']);
	} else if ($to == 'hapussupplier') {
		$id_supplier = $_GET['this'];
		$nc->hapussupplier($con, $id_supplier);
	} else if ($to == 'getpelanggan') {
		$nc->getpelanggan($con);
	} else if ($to == 'gethistorypembelian') {
		$nc->gethistorypembelian($con);
	} else if ($to == 'tambahpelanggan') {
		$nc->tambahpelanggan($con, $_POST['nama'], $_POST['type'], $_POST['alamat'], $_POST['kontak']);
	} else if ($to == 'ubahpelanggan') {
		$nc->ubahpelanggan($con, $_POST['id_pelanggan'], $_POST['nama'], $_POST['type'], $_POST['alamat'], $_POST['kontak']);
	} else if ($to == 'hapuspelanggan') {
		$id_pelanggan = $_GET['this'];
		$nc->hapuspelanggan($con, $id_pelanggan);
	} else if ($to == 'getbarang') {
		$nc->getbarang($con);
	} else if ($to == 'tambahbarang') {
		$nc->tambahbarang($con, $_POST);
	} else if ($to == 'ubahbarang') {
		$nc->ubahbarang($con, $_POST);
	} else if ($to == 'hapusbarang') {
		$id_barang = $_GET['this'];
		$nc->softhapusbarang($con, $id_barang);
	} else if ($to == 'tambahbarangpembelian') {
		$barcode = str_replace(' ', '', strtoupper($_POST['barcode']));

		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		$cek_temp = mysqli_query($con, "SELECT * FROM pembelian_temp WHERE id_user='" . $_POST['id_user'] . "' AND id_barang='" . $query['id_barang'] . "'");
		if (mysqli_fetch_assoc($cek_temp)) {
			echo "<script>alert('Maaf barang sudah diinput.'); window.location='../main?url=tambah-pembelian';</script>";
			return false;
		}
		$nc->tambahbarangpembelian($con, $_POST['id_user'], $barcode, $_POST['qty']);
	} else if ($to == 'ubahbarangpembelian') {
		$id_user = $_POST['ubah_id_user'];
		$id_barang = $_POST['ubah_id_barang'];
		$barcode = $_POST['ubah_barcode'];
		$harga = $_POST['ubah_harga'];
		$qty = $_POST['ubah_qty'];
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		if ($query['stok'] == 0 || $qty > $query['stok']) {
			echo "<script>alert('Maaf stok tidak mencukupi.'); window.location='../main?url=tambah-pembelian';</script>";
		} else {
			$nc->ubahbarangpembelian($con, $id_user, $id_barang, $harga, $qty);
		}
	} else if ($to == 'hapusbarangpembelian') {
		$id_user = $_GET['user'];
		$id_barang = $_GET['this'];
		$nc->hapusbarangpembelian($con, $id_barang, $id_user);
	} else if ($to == 'tambahpembelian') {
		$nc->tambahpembelian($con, $_POST['id_supplier'], $_POST['id_user'], $_POST['total_transaksi'], $_POST['total_bayar'], $_POST['temp']);
	} else if ($to == 'cicilanpembelian') {
		$nc->cicilanpembelian($con, $_POST['id_user'], $_POST['no_po'], $_POST['bayar']);
	} else if ($to == 'hapuscicilanpembelian') {
		$no_po = $_GET['no_po'];
		$id_pembelian_debt = $_GET['this'];
		$nc->hapuscicilanpembelian($con, $no_po, $id_pembelian_debt);
	} else if ($to == 'getpembelian') {
		$nc->getpembelian($con);
	} else if ($to == 'hapuspembelian') {
		$no_po = $_GET['this'];
		$nc->hapuspembelian($con, $no_po);
	} else if ($to == 'tambahbarangpenjualan') {
		$id_barang = isset($_POST['id_barang'])? $_POST['id_barang'] : null ;
		$id_pelanggan = isset($_POST['id_pelanggan'])? $_POST['id_pelanggan'] : null ;
		$type = isset($_POST['type'])? $_POST['type'] : null ;
		$url = "../main?url=tambah-penjualan";
		
		if(!empty($id_pelanggan) && !empty($type)) {
			$url .= "&id_pelanggan=$id_pelanggan&type=$type";
		}

		if(empty($id_barang)){
			echo "<script>alert('Mohon pilih barang terlebih dahulu'); window.location='$url';</script>";
			return false;
		}

		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE id_barang=$id_barang"));
		$cek_temp = mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='" . $_POST['id_user'] . "' AND id_barang='" . $query['id_barang'] . "'");
		
		if (mysqli_fetch_assoc($cek_temp)) {
			echo "<script>alert('Maaf barang sudah diinput.'); window.location='$url';</script>";
			return false;
		}

		$nc->tambahbarangpenjualan($con, $url, $_POST['type'], $_POST['id_user'], $id_barang, $_POST['qty'], $_POST['diskon']);
	} else if ($to == 'ubahbarangpenjualan') {
		$id_user = $_POST['ubah_id_user'];
		$id_barang = $_POST['ubah_id_barang'];
		$barcode = $_POST['ubah_barcode'];
		$harga = $_POST['ubah_harga'];
		$diskon = $_POST['ubah_diskon'];
		$qty = $_POST['ubah_qty'];
		$type = strtolower($_POST['ubah_type']);
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		if ($query['stok'] == 0 || $qty > $query['stok']) {
			echo "<script>alert('Maaf stok tidak mencukupi.'); window.location='../main?url=tambah-penjualan&type=" . $type . "';</script>";
		} else if ($diskon > $query['modal']) {
			echo "<script>alert('Maaf diskon tidak bisa lebih dari harga modal nanti saya RUGI !!!.'); window.location='../main?url=tambah-penjualan&type=" . $type . "';</script>";
		} else {
			$nc->ubahbarangpenjualan($con, $id_user, $id_barang, $harga, $diskon, $qty, $type);
		}
	} else if ($to == 'hapusbarangpenjualan') {
		$id_user = $_GET['user'];
		$id_barang = $_GET['this'];
		$nc->hapusbarangpenjualan($con, $id_barang, $id_user);
	} else if ($to == 'getpenjualan') {
		$nc->getpenjualan($con);
	} else if ($to == 'tambahpenjualan') {
		$nc->tambahpenjualan($con, $_POST['id_pelanggan'], $_POST['id_user'], $_POST['total_transaksi'], $_POST['total_bayar'], $_POST['tipe_bayar']);
	} else if ($to == 'cicilanpenjualan') {
		$nc->cicilanpenjualan($con, $_POST['id_user'], $_POST['no_faktur'], $_POST['bayar']);
	} else if ($to == 'hapuscicilanpenjualan') {
		$no_faktur = $_GET['no_faktur'];
		$id_penjualan_debt = $_GET['this'];
		$nc->hapuscicilanpenjualan($con, $no_faktur, $id_penjualan_debt);
	} else if ($to == 'hapuspenjualan') {
		$no_faktur = $_GET['this'];
		$nc->hapuspenjualan($con, $no_faktur);
	} else if ($to == 'getjenispengeluaran') {
		$nc->getjenispengeluaran($con);
	} else if ($to == 'tambahjenispengeluaran') {
		$nc->tambahjenispengeluaran($con, $_POST['jenis']);
	} else if ($to == 'ubahjenispengeluaran') {
		$nc->ubahjenispengeluaran($con, $_POST['id_pengeluaran_type'], $_POST['jenis']);
	} else if ($to == 'hapusjenispengeluaran') {
		$id_pengeluaran_type = $_GET['this'];
		$nc->hapusjenispengeluaran($con, $id_pengeluaran_type);
	} else if ($to == 'getpengeluaran') {
		$nc->getpengeluaran($con);
	} else if ($to == 'tambahpengeluaran') {
		$nc->tambahpengeluaran($con, $_POST['id_pengeluaran_type'], str_replace(".", "", $_POST['jumlah']), $_POST['keterangan'], $_POST['id_user']);
	} else if ($to == 'ubahpengeluaran') {
		$nc->ubahpengeluaran($con, $_POST['id_pengeluaran'], $_POST['id_pengeluaran_type'], str_replace(".", "", $_POST['jumlah']), $_POST['keterangan'], $_POST['id_user']);
	} else if ($to == 'hapuspengeluaran') {
		$id_pengeluaran = $_GET['this'];
		$nc->hapuspengeluaran($con, $id_pengeluaran);
	} else if ($to == 'ubahdatatoko') {

		if ($_FILES['logo_title']['name'] != '') {
			// Upload logo title
			$tmp_name = $_FILES['logo_title']['tmp_name'];
			$name = $_FILES['logo_title']['name'];
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$loc_file = "../assets/img/";
			$new_name = 'logo.' . $extension;
			$query = mysqli_query($con, "UPDATE toko SET logo_title='$new_name'");
			move_uploaded_file($tmp_name, $loc_file . $new_name);
		}
		if ($_FILES['logo_header']['name'] != '') {
			// Upload logo title
			$tmp_name = $_FILES['logo_header']['tmp_name'];
			$name = $_FILES['logo_header']['name'];
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$loc_file = "../assets/img/";
			$new_name = 'logo-header.' . $extension;
			$query = mysqli_query($con, "UPDATE toko SET logo_header='$new_name'");
			move_uploaded_file($tmp_name, $loc_file . $new_name);
		}

		$nc->ubahdatatoko($con, $_POST['id_toko'], $_POST['nama_toko'], $_POST['ket_toko'], $_POST['alamat_toko'], $_POST['kontak_toko']);
	} else if ($to == 'approved') {
		$no_faktur = $_GET['this'];
		$nc->approved($con, $no_faktur);
	} else if ($to == 'tambah-socmed') {
		$nc->tambahsocmed($con, $_POST['tipe'], $_POST['keterangan'], $_POST['link']);
	} else if ($to == 'ubah-socmed') {
		$nc->ubahsocmed($con, $_POST['id'], $_POST['tipe'], $_POST['keterangan'], $_POST['link']);
	} else if ($to == 'hapus-socmed') {
		$id = $_GET['this'];
		$nc->hapussocmed($con, $id);
	} else if ($to == 'tambah-marketplace') {
		$nc->tambahmarketplace($con, $_POST['tipe'], $_POST['keterangan'], $_POST['link'], $_POST['order_no']);
	} else if ($to == 'ubah-marketplace') {
		$nc->ubahmarketplace($con, $_POST['id'], $_POST['tipe'], $_POST['keterangan'], $_POST['link'], $_POST['order_no']);
	} else if ($to == 'hapus-marketplace') {
		$id = $_GET['this'];
		$nc->hapusmarketplace($con, $id);
	} else if ($to == 'tambah-kontak') {
		$nc->tambahkontak($con, $_POST['keterangan'], $_POST['kontak'], $_POST["letak"], $_POST["aktif"], $_POST['order_no']);
	} else if ($to == 'ubah-kontak') {
		$nc->ubahkontak($con, $_POST['id'], $_POST['keterangan'], $_POST['kontak'], $_POST["letak"], $_POST["aktif"], $_POST['order_no']);
	} else if ($to == 'set-aktif') {
		$nc->setaktif($con, $_GET['id'], $_GET["aktif"]);
	} else if ($to == 'hapus-kontak') {
		$id = $_GET['this'];
		$nc->hapuskontak($con, $id);
	} else if ($to == 'upload-banner') {
		$nc->upload_banner($con);
	} else if ($to == 'update-order') {
		$nc->update_banner_order($con);
	} else if ($to == 'getmerk') {
		$nc->getmerk($con);
	} else if ($to == 'tambah-merk') {
		$nc->tambahmerk($con, $_POST['name']);
	} else if ($to == 'ubah-merk') {
		$nc->ubahmerk($con, $_POST['id'], $_POST['name']);
	} else if ($to == 'hapus-merk') {
		$id = $_GET['this'];
		$nc->hapusmerk($con, $id);
	} else if ($to == 'get-untung') {
		$data = $nc->getuntung($con);
		
		ob_end_clean();
		echo json_encode($data);
	} else if ($to == 'getlaporanharian') {
		$nc->getlaporanharian($con);
	} else if ($to == 'approveharian') {
		$nc->approveharian($con);
	} else if ($to == 'getgaji') {
		$nc->getgaji($con);
	} else if ($to == 'ubahgaji') {
		$nc->ubahgaji($con, $_POST['id_user'], $_POST['pokok'], $_POST['kehadiran'], $_POST['prestasi'], $_POST['bonus'], $_POST['indisipliner'], $_POST['tunjangan_jabatan']);
	} else if ($to == 'processgaji') {
		$id = $_GET['this'];
		$nc->processgaji($con, $id);
	} else if ($to == 'gethistorypenjualan') {
		$nc->gethistorypenjualan($con);
	} else if ($to == 'gethistorypembeliantemp') {
		$nc->gethistorypembeliantemp($con);
	} else if ($to == 'approvehistorypembelian') {
		$id_barang = $_GET['this'];
		$nc->approvehistorypembelian($con, $id_barang);
	} else if ($to == 'declinehistorypembelian') {
		$id_barang = $_GET['this'];
		$nc->declinehistorypembelian($con, $id_barang);
	} else if ($to == 'getlistbaranghistory') {
		$nc->getlistbaranghistory($con);
	} 
}
