<?php
class con
{
	function login($con, $username, $password)
	{	
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM user WHERE username='$username' "));
		// Cek Username
		if ($query != NULL) {
			if (password_verify($password, $query['password'])) {
				$log = false;
				if (!empty($user["last_login"])){
					$now = time();
					$datediff = $now - strtotime($user["last_login"]);
					$diffday = round($datediff / (60 * 60 * 24));
		
					if ($diffday > 30) {
						$log = false;
					} else {
						$log = true;
					}
				}else{
					$log = true;
				}

				if ($log) {
					if($query["aktif"] == 1){
						session_start();
						$_SESSION['id_user'] = $query['id_user'];
						$_SESSION['username'] = $query['username'];
						$_SESSION['nama'] = $query['nama'];
						$_SESSION['id_jabatan'] = $query['id_jabatan'];
						$id_user = $_SESSION['id_user'];
						$date = date("Y-m-d h:i:s");
						$query1 = mysqli_query($con, "UPDATE user SET last_login='$date' WHERE id_user='$id_user' ");
						header('location:../main');
					}else{
						echo "<script type='text/javascript'>alert('Akun anda tidak aktif, silahkan hubungi admin!');window.location='../login.php';</script>";
					}
				}else{
					if($query["aktif"] == 1) {
						$query = mysqli_query($con, "UPDATE user SET aktif = 0 WHERE id_user='".$query['id_user']."' ");
					}

					echo "<script type='text/javascript'>alert('Akun anda tidak aktif, silahkan hubungi admin!');window.location='../login.php';</script>";
				}

			} else {
				echo "<script type='text/javascript'>alert('Password Salah!!!');window.location='../login.php';</script>";
			}
		} else {
			echo "<script type='text/javascript'>alert('Username Tidak Terdaftar');window.location='../login.php';</script>";
		}
	}

	function logout()
	{
		session_start();
		unset($_SESSION['id_user']);
		unset($_SESSION['username']);
		unset($_SESSION['nama']);
		unset($_SESSION['id_jabatan']);
		session_destroy();
		header('location:../login');
	}

	function tambahuser($con, $username, $password, $nama, $alamat, $kontak, $id_jabatan)
	{
		$username = $username = htmlspecialchars(str_replace(' ', '', strtolower($username)));
		$password = password_hash($password, PASSWORD_DEFAULT);
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO user SET username='$username',password='$password',nama='$nama',alamat='$alamat',kontak='$kontak',id_jabatan='$id_jabatan' ");
		header('location:../main?url=user');
	}

	function ubahuser($con, $id_user, $username, $nama, $alamat, $kontak, $id_jabatan)
	{
		$username = $username = htmlspecialchars(str_replace(' ', '', strtolower($username)));
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "UPDATE user SET  username='$username',nama='$nama',alamat='$alamat',kontak='$kontak',id_jabatan='$id_jabatan' WHERE id_user='$id_user' ");
		header('location:../main?url=user');
	}

	function resetpassworduser($con, $id_user, $password, $password2)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = mysqli_query($con, "UPDATE user SET  password='$password' WHERE id_user='$id_user' ");
		header('location:../main?url=user');
	}

	function gantipassworduser($con, $id_user, $password, $password2)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = mysqli_query($con, "UPDATE user SET  password='$password' WHERE id_user='$id_user' ");
		header('location:../main');
	}

	function hapususer($con, $id_user)
	{
		$query = mysqli_query($con, "DELETE FROM user WHERE id_user='$id_user' ");
		header('location:../main?url=user');
	}

	function setaktifuser($con, $id_user, $aktif)
	{
		$user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM user WHERE id_user='$id_user'"));

		if($aktif == 1){
			if (!empty($user["last_login"])){
				$now = time();
				$datediff = $now - strtotime($user["last_login"]);
				$diffday = round($datediff / (60 * 60 * 24));
	
				if ($diffday > 30) {
					$user["last_login"] = date("Y-m-d H:i:s");
				} 
			}
		}
		$query = mysqli_query($con, "UPDATE user SET aktif = $aktif, last_login = '".$user['last_login']."' WHERE id_user='$id_user' ");
		header('location:../main?url=user');
	}

	function tambahsupplier($con, $nama, $alamat, $kontak)
	{
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO supplier SET nama='$nama',alamat='$alamat',kontak='$kontak' ");
		header('location:../main?url=supplier');
	}

	function ubahsupplier($con, $id_supplier, $nama, $alamat, $kontak)
	{
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE supplier SET nama='$nama',alamat='$alamat',kontak='$kontak',updated='$updated' WHERE id_supplier='$id_supplier' ");
		header('location:../main?url=supplier');
	}

	function hapussupplier($con, $id_supplier)
	{
		$query = mysqli_query($con, "DELETE FROM supplier WHERE id_supplier='$id_supplier' ");
		header('location:../main?url=supplier');
	}

	function tambahpelanggan($con, $nama, $type, $alamat, $kontak)
	{
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO pelanggan SET nama='$nama',type='$type',alamat='$alamat',kontak='$kontak' ");
		header('location:../main?url=pelanggan');
	}

	function ubahpelanggan($con, $id_pelanggan, $nama, $type, $alamat, $kontak)
	{
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE pelanggan SET nama='$nama',type='$type',alamat='$alamat',kontak='$kontak',updated='$updated' WHERE id_pelanggan='$id_pelanggan' ");
		header('location:../main?url=pelanggan');
	}

	function hapuspelanggan($con, $id_pelanggan)
	{
		$query = mysqli_query($con, "DELETE FROM pelanggan WHERE id_pelanggan='$id_pelanggan' ");
		header('location:../main?url=pelanggan');
	}

	function tambahbarang($con, $post)
	{
		// Kode Barcode Otomatis
		// $query = mysqli_query($con, "SELECT max(barcode) as kodeTerbesar FROM barang");
		// $data = mysqli_fetch_array($query);
		// $barcode = $data['kodeTerbesar'];
		// $urutan = (int) substr($barcode, 6, 3);
		// $urutan++;
		// $huruf = "BRG";
		// $barcode = $huruf . sprintf("%06s", $urutan);

		$barcode = htmlspecialchars(str_replace(' ', '', strtoupper($post['barcode'])));
		$nama = htmlspecialchars(ucwords($post['nama']));
		$merk = htmlspecialchars(strtoupper($post['merk']));
		$stok = $post['stok'];
		$modal = str_replace('.', '', $post['modal']);
		$distributor = str_replace('.', '', $post['distributor']);
		$reseller = str_replace('.', '', $post['reseller']);
		$bengkel = str_replace('.', '', $post['bengkel']);
		$admin = str_replace('.', '', $post['admin']);
		$het = str_replace('.', '', $post['het']);
		$kondisi = htmlspecialchars(strtoupper($post['kondisi']));
		$kualitas = htmlspecialchars(strtoupper($post['kualitas']));
		$kategori = join(',', $post['kategori']);
		$tipe_pelanggan = $post['tipe_pelanggan'];
		$tambahan = str_replace("'", "''", htmlspecialchars(strtoupper($post['tambahan'])));
		$berat = $post['berat'];
		$deskripsi = str_replace("'", "''", $post['deskripsi']);

		$query = mysqli_query($con, "INSERT INTO barang SET barcode='$barcode',nama='$nama',merk='$merk',stok='$stok',modal='$modal',distributor='$distributor',reseller='$reseller',bengkel='$bengkel',admin='$admin',het='$het',kondisi='$kondisi',kualitas='$kualitas',kategori='$kategori',tipe_pelanggan='$tipe_pelanggan',tambahan='$tambahan', deskripsi='$deskripsi', berat=$berat ");

		// Upload
		$id_barang = mysqli_insert_id($con);
		
		$f = $_FILES;
		if(!empty($f)){
			$path = str_replace('/adm/process','/p/'.trim($id_barang),dirname(__FILE__));
			if(!file_exists($path)){
				mkdir($path);
			}

			$jum = count($f['gambar']['name']);
			for ($i = 0; $i < $jum; $i++) {
				$nama_file = $f['gambar']['name'][$i];
				$ukuran_file = $f['gambar']['size'][$i];
				$tipe_file = $f['gambar']['type'][$i];
				$tmp_file = $f['gambar']['tmp_name'][$i];
				if($ukuran_file <= 4000000){  
					if(move_uploaded_file($tmp_file, $path.'/'.$nama_file)){
					} else {       
						echo "Maaf, Terjadi kesalahan.";
						echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";      
					}
				} else {  
					echo "Maaf, Ukuran gambar yang diupload tidak boleh lebih dari 4MB";    
					echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";  
				}
			}

			// Update selected
			if (isset($post["selected_barang"])) {
				$selected_barang = basename($post["selected_barang"]);
				mysqli_query($con,"REPLACE INTO foto_barang (id_barang, name) VALUES ($id_barang, '$selected_barang')");
			}

			header('location:../main?url=barang');
		}else{
			header('location:../main?url=barang');
		}
	}

	function ubahbarang($con, $post)
	{
		session_start();
		$id_barang = $post['id_barang'];
		$barcode = !empty($post['barcode'])? htmlspecialchars(str_replace(' ', '', strtoupper($post['barcode']))) : null;
		$nama = !empty($post['nama'])? htmlspecialchars(ucwords(addslashes($post['nama']))) : null;
		$merk = !empty($post['merk'])? htmlspecialchars(strtoupper($post['merk'])) : null;
		$stok = !empty($post['stok'])? $post['stok'] : null;
		$modal = !empty($post['modal'])? str_replace('.', '', $post['modal']) : null;
		$distributor = !empty($post['distributor'])? str_replace('.', '', $post['distributor']) : null;
		$reseller = !empty($post['reseller'])? str_replace('.', '', $post['reseller']) : null;
		$bengkel = !empty($post['bengkel'])? str_replace('.', '', $post['bengkel']) : null;
		$admin = !empty($post['admin'])? str_replace('.', '', $post['admin']) : null;
		$het = !empty($post['het'])? str_replace('.', '', $post['het']) : null;
		$kondisi = !empty($post['kondisi'])? htmlspecialchars(strtoupper($post['kondisi'])) : null;
		$kualitas = !empty($post['kualitas'])? htmlspecialchars(strtoupper($post['kualitas'])) : null;
		$kategori = !empty($post['kategori'])? join(',', $post['kategori']) : null;
		$tipe_pelanggan = !empty($post['tipe_pelanggan'])? $post['tipe_pelanggan'] : null;
		$tambahan = !empty($post['tambahan'])? htmlspecialchars(strtoupper(addslashes($post['tambahan']))) : null;
		$deskripsi = !empty($post['deskripsi'])? addslashes($post['deskripsi']) : null;
		$berat = !empty($post['berat'])? $post['berat'] : null;
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE barang SET  
			barcode = '$barcode', 
			nama = '$nama', 
			merk = '$merk', 
			stok = '$stok', 
			modal = '$modal',
			distributor = '$distributor', 
			reseller = '$reseller', 
			bengkel = '$bengkel', 
			admin = '$admin', 
			het = '$het',
			kondisi = '$kondisi',
			kualitas = '$kualitas',
			kategori = '$kategori',
			tipe_pelanggan = '$tipe_pelanggan',
			tambahan = '$tambahan',
			deskripsi = '$deskripsi',
			updated = '$updated',
			berat = '$berat' 
			WHERE id_barang = '$id_barang' ");

		// Hapus barang
		if(!empty($post['hapus_barang'])) {
			$hapus_barang = explode(',', $post['hapus_barang']);
			if(count($hapus_barang) > 0){
				foreach($hapus_barang as $item) {
					$filename = basename($item);
					$path = str_replace('/adm/process','/p/'.trim($id_barang),dirname(__FILE__));
					$file = $path.'/'.$filename;

					if(file_exists($file)){
						$x = unlink($file);
					}
				}
			}
		}

		// Upload barang
		$f = $_FILES;
		if(!empty($f)){
			$path = str_replace('/adm/process','/p/'.trim($id_barang),dirname(__FILE__));
			if(!file_exists($path)){
				mkdir($path);
			}

			$jum = count($f['gambar']['name']);
			for ($i = 0; $i < $jum; $i++) {
				$nama_file = $f['gambar']['name'][$i];
				$ukuran_file = $f['gambar']['size'][$i];
				$tipe_file = $f['gambar']['type'][$i];
				$tmp_file = $f['gambar']['tmp_name'][$i];
				if($ukuran_file <= 4000000){  
					move_uploaded_file($tmp_file, $path.'/'.$nama_file);
				} else {  
					echo "Maaf, Ukuran gambar yang diupload tidak boleh lebih dari 4MB";    
					echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";  
				}
			}

		}

		// Update selected
		if (isset($post["selected_barang"])) {
			$selected_barang = basename($post["selected_barang"]);
			mysqli_query($con,"REPLACE INTO foto_barang (id_barang, name) VALUES ($id_barang, '$selected_barang')");
		}

		if($_SESSION['id_jabatan'] == "4"){
			header('location:../main');
		}else{
			header('location:../main?url=barang');
		}
	}

	function hapusbarang($con, $id_barang)
	{	
		$query = mysqli_query($con, "DELETE FROM barang WHERE id_barang='$id_barang' ");
		$path = str_replace('/adm/process','/p/'.trim($id_barang),dirname(__FILE__));
		if(file_exists($path)){
			$this->rrmdir($path);
		}
		header('location:../main?url=barang');
	}

	function rrmdir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir);
			foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
				rrmdir($dir. DIRECTORY_SEPARATOR .$object);
				else
				unlink($dir. DIRECTORY_SEPARATOR .$object); 
			} 
			}
			rmdir($dir); 
		} 
	}

	function tambahbarangpembelian($con, $id_user, $barcode, $qty)
	{
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		if ($barcode != $query['barcode']) {
			echo "<script>alert('Maaf barang tidak ditemukan mungkin kode barcode salah'); window.location='../main?url=tambah-pembelian';</script>";
		} else {
			$id_barang = $query['id_barang'];
			$harga = $query['modal'];
			$total_harga = $harga * $qty;
			$query = mysqli_query($con, "INSERT INTO pembelian_temp SET id_barang='$id_barang',qty='$qty',total_harga='$total_harga',id_user='$id_user' ");
			header('location:../main?url=tambah-pembelian');
		}
	}

	function ubahbarangpembelian($con, $id_user, $id_barang, $harga, $qty)
	{
		$harga = str_replace('.', '', $harga);
		$total_harga = $harga * $qty;
		$query = mysqli_query($con, "UPDATE pembelian_temp SET qty='$qty',total_harga='$total_harga' WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-pembelian');
	}

	function hapusbarangpembelian($con, $id_barang, $id_user)
	{
		$query = mysqli_query($con, "DELETE FROM pembelian_temp WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-pembelian');
	}

	function tambahpembelian($con, $id_supplier, $id_user, $total_transaksi, $total_bayar)
	{
		// Kode Otomatis Barcode
		$today = date('ymd');
		$char = 'PO' . $today;
		$qpo = mysqli_query($con, "SELECT max(no_po) as max_po FROM pembelian WHERE no_po LIKE '{$char}%' ORDER BY no_po DESC LIMIT 1");
		$data = mysqli_fetch_assoc($qpo);
		$getId = $data['max_po'];
		$no = substr($getId, -3, 3);
		$no = (int) $no;
		$no += 1;
		$no_po = $char . sprintf("%03s", $no);
		$tanggal = date("Y-m-d h:i:s");
		$total_transaksi = str_replace('.', '', $total_transaksi);
		$total_bayar = str_replace('.', '', $total_bayar);

		if ($total_bayar < $total_transaksi) {
			$status = "Hutang";
		} else {
			$status = "Lunas";
		}

		$query = mysqli_query($con, "INSERT INTO pembelian SET no_po='$no_po',id_supplier='$id_supplier',tanggal='$tanggal',status='$status',total_transaksi='$total_transaksi',total_bayar='$total_bayar',id_user='$id_user' ");
		// memasukkan history pembayaran jika hutang
		if ($total_bayar < $total_transaksi) {
			$query = mysqli_query($con, "INSERT INTO pembelian_debt SET no_po='$no_po',bayar='$total_bayar',keterangan='DP',id_user='$id_user' ");
		}
		// memasukkan pembelian temp ke detail pembelian berdasarkan user id
		$pembelian_temp = mysqli_query($con, "SELECT * FROM pembelian_temp WHERE id_user='$id_user'");
		foreach ($pembelian_temp as $pt) {
			$id_barang = $pt['id_barang'];
			$qty = $pt['qty'];
			$total_harga = $pt['total_harga'];

			$insert_pembelian_temp = mysqli_query($con, "INSERT INTO pembelian_det SET no_po='$no_po',id_barang='$id_barang',qty='$qty',total_harga='$total_harga' ");
			$stock_in = mysqli_query($con, "UPDATE barang SET stok=stok+$qty WHERE id_barang='$id_barang'");
		}
		$del_pembelian_temp = mysqli_query($con, "DELETE FROM pembelian_temp WHERE id_user='$id_user' ");
		header('location:../main?url=lihat-pembelian&this=' . $no_po . '');
	}

	function cicilanpembelian($con, $id_user, $no_po, $bayar)
	{
		$query = mysqli_query($con, "INSERT INTO pembelian_debt SET no_po='$no_po',bayar='$bayar',keterangan='Cicilan',id_user='$id_user' ");
		$update_pembayaran = mysqli_query($con, "UPDATE pembelian SET total_bayar=total_bayar+$bayar WHERE no_po='$no_po'");
		$query_pembelian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian WHERE no_po='$no_po'"));
		if ($query_pembelian['total_bayar'] >= $query_pembelian['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE pembelian SET status='Lunas' WHERE no_po='$no_po'");
		}
		header('location:../main?url=lihat-pembelian&this=' . $no_po . '');
	}

	function hapuscicilanpembelian($con, $no_po, $id_pembelian_debt)
	{
		$pembelian_det = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian_debt WHERE id_pembelian_debt='$id_pembelian_debt'"));
		$bayar = $pembelian_det['bayar'];
		$update_pembayaran = mysqli_query($con, "UPDATE pembelian SET total_bayar=total_bayar-$bayar WHERE no_po='$no_po'");
		$query_pembelian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian WHERE no_po='$no_po'"));
		if ($query_pembelian['total_bayar'] < $query_pembelian['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE pembelian SET status='Hutang' WHERE no_po='$no_po'");
		}
		$query = mysqli_query($con, "DELETE FROM pembelian_debt WHERE id_pembelian_debt='$id_pembelian_debt' ");
		header('location:../main?url=lihat-pembelian&this=' . $no_po . '');
	}

	function hapuspembelian($con, $no_po)
	{
		$pembelian_det = mysqli_query($con, "SELECT * FROM pembelian_det WHERE no_po='$no_po'");
		foreach ($pembelian_det as $pd) {
			$id_barang = $pd['id_barang'];
			$qty = $pd['qty'];
			$stock_out = mysqli_query($con, "UPDATE barang SET stok=stok-$qty WHERE id_barang='$id_barang'");
		}
		$query = mysqli_query($con, "DELETE FROM pembelian WHERE no_po='$no_po' ");
		header('location:../main?url=pembelian');
	}

	function tambahbarangpenjualan($con, $type, $id_user, $barcode, $qty, $diskon)
	{
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		if ($barcode != $query['barcode']) {
			echo "<script>alert('Maaf barang tidak ditemukan mungkin kode barcode salah.'); window.location='../main?url=tambah-penjualan&type=" . $type . "';</script>";
		} else if ($query['stok'] == 0 || $qty > $query['stok']) {
			echo "<script>alert('Maaf stok tidak mencukupi.'); window.location='../main?url=tambah-penjualan&type=" . $type . "';</script>";
		} else if ($diskon > $query['modal']) {
			echo "<script>alert('Maaf diskon tidak bisa lebih dari harga modal nanti saya RUGI !!!.'); window.location='../main?url=tambah-penjualan&type=" . $type . "';</script>";
		} else {
			$id_barang = $query['id_barang'];
			if ($type == 'distributor') {
				$harga = $query['distributor'];
			} else if ($type == 'reseller') {
				$harga = $query['reseller'];
			} else if ($type == 'bengkel') {
				$harga = $query['bengkel'];
			} else if ($type == 'admin') {
				$harga = $query['admin'];
			} else {
				$harga = $query['het'];
			}
			$total_harga = ($harga - $diskon) * $qty;
			$query = mysqli_query($con, "INSERT INTO penjualan_temp SET id_barang='$id_barang',qty='$qty',diskon='$diskon',harga='$harga',type='$type',total_harga='$total_harga',id_user='$id_user' ");
			header('location:../main?url=tambah-penjualan&type=' . $type . '');
		}
	}

	function ubahbarangpenjualan($con, $id_user, $id_barang, $harga, $diskon, $qty, $type)
	{
		$harga = str_replace('.', '', $harga);
		$diskon = str_replace('.', '', $diskon);
		$total_harga = ($harga - $diskon) * $qty;
		$query = mysqli_query($con, "UPDATE penjualan_temp SET qty='$qty',diskon='$diskon',total_harga='$total_harga' WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-penjualan&type=' . $type . '');
	}

	function hapusbarangpenjualan($con, $id_barang, $id_user)
	{
		$query = mysqli_query($con, "DELETE FROM penjualan_temp WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		$query_type = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='$id_user'"));
		if ($query_type['id_user'] != NULL) {
			header('location:../main?url=tambah-penjualan&type=' . $query_type['type'] . '');
		} else {
			header('location:../main?url=penjualan');
		}
	}

	function tambahpenjualan($con, $id_pelanggan, $id_user, $total_transaksi, $total_bayar)
	{
		// Kode Otomatis Barcode
		$today = date('ymd');
		$char = 'NO' . $today;
		$qno = mysqli_query($con, "SELECT max(no_faktur) as max_faktur FROM penjualan WHERE no_faktur LIKE '{$char}%' ORDER BY no_faktur DESC LIMIT 1");
		$data = mysqli_fetch_assoc($qno);
		$getId = $data['max_faktur'];
		$no = substr($getId, -3, 3);
		$no = (int) $no;
		$no += 1;
		$no_faktur = $char . sprintf("%03s", $no);
		$tanggal = date("Y-m-d h:i:s");
		$total_transaksi = str_replace('.', '', $total_transaksi);
		$total_bayar = str_replace('.', '', $total_bayar);

		if ($total_bayar < $total_transaksi) {
			$status = "Hutang";
		} else {
			$status = "Lunas";
		}

		$query = mysqli_query($con, "INSERT INTO penjualan SET no_faktur='$no_faktur',id_pelanggan='$id_pelanggan',tanggal='$tanggal',status='$status',total_transaksi='$total_transaksi',total_bayar='$total_bayar',id_user='$id_user' ");
		// memasukkan history pembayaran jika hutang
		if ($total_bayar < $total_transaksi) {
			$query = mysqli_query($con, "INSERT INTO penjualan_debt SET no_faktur='$no_faktur',bayar='$total_bayar',keterangan='DP',id_user='$id_user' ");
		}
		// memasukkan penjualan temp ke detail penjualan berdasarkan user id
		$penjualan_temp = mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='$id_user'");
		foreach ($penjualan_temp as $pt) {
			$id_barang = $pt['id_barang'];
			$harga = $pt['harga'];
			$qty = $pt['qty'];
			$diskon = $pt['diskon'];
			$type = $pt['type'];
			$total_harga = $pt['total_harga'];

			$insert_penjualan_temp = mysqli_query($con, "INSERT INTO penjualan_det SET no_faktur='$no_faktur',id_barang='$id_barang',harga='$harga',qty='$qty',diskon='$diskon',type='$type',total_harga='$total_harga' ");
			$stock_out = mysqli_query($con, "UPDATE barang SET stok=stok-$qty WHERE id_barang='$id_barang'");
		}
		$del_penjualan_temp = mysqli_query($con, "DELETE FROM penjualan_temp WHERE id_user='$id_user' ");
		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '');
	}

	function cicilanpenjualan($con, $id_user, $no_faktur, $bayar)
	{
		$query = mysqli_query($con, "INSERT INTO penjualan_debt SET no_faktur='$no_faktur',bayar='$bayar',keterangan='Cicilan',id_user='$id_user' ");
		$update_pembayaran = mysqli_query($con, "UPDATE penjualan SET total_bayar=total_bayar+$bayar WHERE no_faktur='$no_faktur'");
		$query_penjualan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan WHERE no_faktur='$no_faktur'"));
		if ($query_penjualan['total_bayar'] >= $query_penjualan['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE penjualan SET status='Lunas' WHERE no_faktur='$no_faktur'");
		}
		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '');
	}

	function hapuscicilanpenjualan($con, $no_faktur, $id_penjualan_debt)
	{
		$penjualan_det = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_debt WHERE id_penjualan_debt='$id_penjualan_debt'"));
		$bayar = $penjualan_det['bayar'];
		$update_pembayaran = mysqli_query($con, "UPDATE penjualan SET total_bayar=total_bayar-$bayar WHERE no_faktur='$no_faktur'");
		$query_penjualan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan WHERE no_faktur='$no_faktur'"));
		if ($query_penjualan['total_bayar'] < $query_penjualan['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE penjualan SET status='Hutang' WHERE no_faktur='$no_faktur'");
		}
		$query = mysqli_query($con, "DELETE FROM penjualan_debt WHERE id_penjualan_debt='$id_penjualan_debt' ");
		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '');
	}

	function hapuspenjualan($con, $no_faktur)
	{
		// $penjualan_det = mysqli_query($con, "SELECT * FROM penjualan_det WHERE no_faktur='$no_faktur'");
		// disable temporary
		// foreach ($penjualan_det as $pd) {
			// $id_barang = $pd['id_barang'];
			// $qty = $pd['qty'];
			// $stock_in = mysqli_query($con, "UPDATE barang SET stok=stok+$qty WHERE id_barang='$id_barang'");
		// }
		$query = mysqli_query($con, "DELETE FROM penjualan WHERE no_faktur='$no_faktur' ");
		header('location:../main?url=penjualan');
	}

	function tambahjenispengeluaran($con, $jenis)
	{
		$jenis = htmlspecialchars(strtoupper($jenis));
		$query = mysqli_query($con, "INSERT INTO pengeluaran_type SET jenis='$jenis' ");
		header('location:../main?url=jenis-pengeluaran');
	}

	function ubahjenispengeluaran($con, $id_pengeluaran_type, $jenis)
	{
		$jenis = htmlspecialchars(strtoupper($jenis));
		$query = mysqli_query($con, "UPDATE pengeluaran_type SET jenis='$jenis' WHERE id_pengeluaran_type='$id_pengeluaran_type' ");
		header('location:../main?url=jenis-pengeluaran');
	}

	function hapusjenispengeluaran($con, $id_pengeluaran_type)
	{
		$query = mysqli_query($con, "DELETE FROM pengeluaran_type WHERE id_pengeluaran_type='$id_pengeluaran_type' ");
		header('location:../main?url=pengeluaran');
	}

	function tambahpengeluaran($con, $id_pengeluaran_type, $jumlah, $keterangan, $id_user)
	{
		$keterangan = htmlspecialchars(ucwords($keterangan));
		$query = mysqli_query($con, "INSERT INTO pengeluaran SET id_pengeluaran_type='$id_pengeluaran_type',jumlah='$jumlah',keterangan='$keterangan',id_user='$id_user' ");
		header('location:../main?url=pengeluaran');
	}

	function ubahpengeluaran($con, $id_pengeluaran,  $id_pengeluaran_type, $jumlah, $keterangan, $id_user)
	{
		$keterangan = htmlspecialchars(ucwords($keterangan));
		var_dump($id_pengeluaran,  $id_pengeluaran_type, $jumlah, $keterangan);
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE pengeluaran SET id_pengeluaran_type='$id_pengeluaran_type',jumlah='$jumlah',keterangan='$keterangan',id_user='$id_user',updated='$updated' WHERE id_pengeluaran='$id_pengeluaran' ");
		header('location:../main?url=pengeluaran');
	}

	function hapuspengeluaran($con, $id_pengeluaran)
	{
		$query = mysqli_query($con, "DELETE FROM pengeluaran WHERE id_pengeluaran='$id_pengeluaran' ");
		header('location:../main?url=pengeluaran');
	}

	function ubahdatatoko($con, $id_toko, $nama_toko, $ket_toko, $alamat_toko, $kontak_toko)
	{
		$nama_toko = htmlspecialchars(ucwords($nama_toko));
		$ket_toko = htmlspecialchars(ucwords($ket_toko));
		$alamat_toko = htmlspecialchars(ucwords($alamat_toko));
		$query = mysqli_query($con, "UPDATE toko SET nama_toko='$nama_toko',ket_toko='$ket_toko',alamat_toko='$alamat_toko',kontak_toko='$kontak_toko' WHERE id_toko='$id_toko' ");
		header('location:../main');
	}

	function approved($con, $no_faktur)
	{
		$query = mysqli_query($con, "UPDATE penjualan SET persetujuan='Approved' WHERE no_faktur='$no_faktur' ");
		header('location:../main?url=penjualan');
	}

	function tambahsocmed($con, $tipe, $keterangan, $link)
	{
		$query = mysqli_query($con, "INSERT INTO socmed SET keterangan='$keterangan',tipe='$tipe',link='$link' ");
		header('location:../main?url=socmed');
	}

	function ubahsocmed($con, $id, $tipe, $keterangan, $link)
	{
		$query = mysqli_query($con, "UPDATE socmed SET keterangan='$keterangan',tipe='$tipe',link='$link' WHERE id='$id' ");

		header('location:../main?url=socmed');
	}

	function hapussocmed($con, $id)
	{
		$query = mysqli_query($con, "DELETE FROM socmed WHERE id='$id' ");
		header('location:../main?url=socmed');
	}

	function tambahkontak($con, $keterangan, $kontak, $letak, $aktif)
	{
		$query = mysqli_query($con, "INSERT INTO kontak SET keterangan='$keterangan', kontak='$kontak', letak='$letak', aktif='$aktif' ");
		header('location:../main?url=kontak');
	}

	function ubahkontak($con, $id, $keterangan, $kontak, $letak, $aktif)
	{
		$query = mysqli_query($con, "UPDATE kontak SET keterangan='$keterangan', kontak='$kontak', letak='$letak', aktif='$aktif' WHERE id='$id' ");
		header('location:../main?url=kontak');
	}

	function setaktif($con, $id, $aktif)
	{
		$query = mysqli_query($con, "UPDATE kontak SET aktif='$aktif' WHERE id='$id' ");
		header('location:../main?url=kontak');
	}

	function hapuskontak($con, $id)
	{
		$query = mysqli_query($con, "DELETE FROM kontak WHERE id='$id' ");
		header('location:../main?url=kontak');
	}

	function tambahmerk($con, $merk)
	{
		$query = mysqli_query($con, "INSERT INTO merk SET name='$merk' ");
		header('location:../main?url=merk');
	}

	function ubahmerk($con, $id, $merk)
	{
		$query = mysqli_query($con, "UPDATE merk SET name='$merk' WHERE id='$id' ");
		header('location:../main?url=merk');
	}

	function hapusmerk($con, $id)
	{
		$query = mysqli_query($con, "DELETE FROM merk WHERE id='$id' ");
		header('location:../main?url=merk');
	}


	function upload_banner($con)
	{
		$err = "";
		if (!empty($_FILES['files'])){
			$path = str_replace('/adm/process','/banner/',dirname(__FILE__));
			if(!file_exists($path)){
				mkdir($path);
			}

			$n = 0;

			$prepareName = [];
			foreach($_FILES["files"]["name"] as $val){
				$fileName = $_FILES["files"]["name"][$n];
				$fileSize = $_FILES["files"]["size"][$n];
				$fileTemp = $_FILES['files']['tmp_name'][$n];
				$fileType = $_FILES['files']['type'][$n];

				if($fileSize <= 4000000) {
					if(move_uploaded_file($fileTemp, $path.$fileName)){
						$err = "";
						$prepareName[] = $fileName;
					}
				}else{
					$err = "Ukuran gambar terlalu besar"; 
					break;
				}

				$n++;
			}

			if (empty($err)) {
				if(!empty($prepareName)){
					$count  = 1;
					$cn_banner = mysqli_num_rows(mysqli_query($con, "SELECT * FROM banner"));
					if($cn_banner > 0) {
						$count = $cn_banner;
					}
					foreach($prepareName as $name){
						mysqli_query($con, "INSERT INTO banner SET photo = '$name', order_no = $count");
						$count++;
					}
				}
			}
		}else{
			$err = "Gambar yang dilampirkan tidak tersedia";
		}

		ob_end_clean();
		if (!empty($err)){
			echo json_encode([
				"success" => 0,
				"message" => null,
				"err" => $err,
			]);
		}else{
			echo json_encode([
				"success" => 1,
				"message" => "Sukses upload",
				"err" => null,
			]);
		}
	}

	function update_banner_order($con)
	{
		$idArray = explode(",", $_POST["ids"]);
		$count = 1;

		foreach($idArray as $id){
			$query = mysqli_query($con, "UPDATE banner SET order_no = '$count' WHERE id = '$id'");
			$count++; 
		}

		ob_end_clean();
		echo json_encode([
			"success" => 1,
			"message" => "Success update"
		]);
	}

	function getuntung($con) 
	{
		$query = mysqli_query($con, "
			SELECT
				penjualan.no_faktur,
				penjualan.tanggal,
				pelanggan.nama pelanggan,
				pelanggan.type,
				barang.nama barang,
				penjualan_det.qty jumlah,
				barang.modal harga_modal,
				CASE 
					WHEN pelanggan.type = 'distributor' THEN barang.distributor
					WHEN pelanggan.type = 'reseller' THEN barang.reseller
					WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
					WHEN pelanggan.type = 'admin' THEN barang.admin
					WHEN pelanggan.type = 'het' THEN barang.het
				END harga_transaksi,
				penjualan_det.qty * barang.modal total_harga_modal,
				penjualan_det.qty * (
					CASE 
						WHEN pelanggan.type = 'distributor' THEN barang.distributor
						WHEN pelanggan.type = 'reseller' THEN barang.reseller
						WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
						WHEN pelanggan.type = 'admin' THEN barang.admin
						WHEN pelanggan.type = 'het' THEN barang.het
					END
				) total_harga_transaksi,
				sum_penjualan.modal total_modal,
				sum_penjualan.transaksi total_transaksi,
				sum_penjualan.transaksi - sum_penjualan.modal laba,
				user.nama oleh
			FROM penjualan
			JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur
			JOIN barang ON penjualan_det.id_barang = barang.id_barang
			LEFT JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			LEFT JOIN (
				SELECT 
					penjualan.no_faktur,
					SUM(penjualan_det.qty * barang.modal) modal,
					SUM(penjualan_det.qty * (
						CASE 
							WHEN pelanggan.type = 'distributor' THEN barang.distributor
							WHEN pelanggan.type = 'reseller' THEN barang.reseller
							WHEN pelanggan.type = 'bengkel' THEN barang.bengkel
							WHEN pelanggan.type = 'admin' THEN barang.admin
							WHEN pelanggan.type = 'het' THEN barang.het
						END
					)) transaksi
				FROM penjualan
				JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur
				JOIN barang ON penjualan_det.id_barang = barang.id_barang
				LEFT JOIN pelanggan ON penjualan.id_pelanggan = pelanggan.id_pelanggan
				GROUP BY penjualan.no_faktur
			) sum_penjualan ON penjualan.no_faktur = sum_penjualan.no_faktur
			GROUP BY penjualan.no_faktur, penjualan_det.id_barang
			ORDER BY penjualan.tanggal DESC, penjualan_det.id_barang
		");
		
		while($row = $query->fetch_assoc())
		{
			$rows[] = $row;
		}
		
		return [
			"data" => $rows
		];
	}
}
