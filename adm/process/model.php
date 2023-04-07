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

	function getuser($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["user.nama", "user.username", "user.alamat", "user.kontak", "jabatan.nama", "user.last_login"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		
		$orderBy = "user.id_jabatan ASC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$btn_aksi = "CONCAT(
			'<a title=\"Set Aktif\" 
				href=\"#!\" onclick=\"setAktif(', user.id_user, ',', IF(aktif = 1, 0, 1), ')\" 
				class=\"btn ', IF(aktif = 1, 'btn-secondary', 'btn-info') , ' btn-sm\">
				<i class=\"fas ', IF(aktif = 1, 'fa-eye-slash',  'fa-eye') , '\"></i>
			</a>
			<a title=\"Ubah User\" href=\"#!\" onclick=\"editUser(', user.id_user, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
			<a title=\"Reset Password\" href=\"#!\" onclick=\"resetPassword(', user.id_user, ')\"  class=\"btn btn-warning btn-sm\"><i class=\"fas fa-key\"></i></a>
			<a title=\"Hapus User\" href=\"#!\"  onclick=\"hapusUser(', user.id_user, ')\"  class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";
		
		$badge_aktif = "IF(aktif = 1, '<span class=\"badge badge-success\">Aktif</span>', '<span class=\"badge badge-secondary\">Not Aktif</span>')";

		$badge_jabatan = "
			CASE 
				WHEN jabatan.nama = 'Owner' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-success\">', jabatan.nama, '</span></td>')
				WHEN jabatan.nama = 'Manager' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-warning\">', jabatan.nama, '</span></td>')
				WHEN jabatan.nama = 'Admin' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-info\">', jabatan.nama, '</span></td>')
				WHEN jabatan.nama = 'Marketer' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-danger\">', jabatan.nama, '</span></td>')
				WHEN jabatan.nama = 'Reseller' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-primary\">', jabatan.nama, '</span></td>')
				WHEN jabatan.nama = 'Distributor' THEN CONCAT('<td class=\"text-center\"><span class=\"badge badge-secondary\">', jabatan.nama, '</span></td>')
				ELSE CONCAT('<td class=\"text-center\"><span class=\"badge badge-dark\">', jabatan.nama, '</span></td>')
			END 
		";

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY user.id_jabatan ASC) AS row_no,
				user.id_user,
				user.nama,
				COUNT(this_month.no_faktur) bulan_ini,
				COUNT(prev_month.no_faktur) bulan_lalu,
				user.username,
				user.alamat,
				user.kontak,
				$badge_jabatan jabatan,
				$badge_aktif status,
				user.last_login,
				$btn_aksi aksi
			FROM user
			LEFT JOIN jabatan ON jabatan.id_jabatan = user.id_jabatan
			LEFT JOIN penjualan this_month ON this_month.id_user = user.id_user 
				AND YEAR(this_month.tanggal) = YEAR(CURRENT_DATE())
				AND MONTH(this_month.tanggal) = MONTH(CURRENT_DATE())
			LEFT JOIN penjualan prev_month ON prev_month.id_user = user.id_user 
				AND YEAR(prev_month.tanggal) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
				AND MONTH(prev_month.tanggal) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
			WHERE user.id_jabatan!='1'
			$whereFilter
			GROUP BY user.id_user
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			// $row["last_login"] = $this->time_ago(substr($row['last_login'],0,-3));
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT user.id_user
			FROM user 
			LEFT JOIN jabatan ON jabatan.id_jabatan = user.id_jabatan
			WHERE user.id_jabatan!='1'
			$whereFilter
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function time_ago($timestamp){
		date_default_timezone_set('Asia/Jakarta');  
		$time_ago = strtotime($timestamp);  
		$current_time = time();  
		$time_difference = $current_time - $time_ago;  
		$seconds = $time_difference;  
		$minutes      = round($seconds / 60 );        // value 60 is seconds  
		$hours        = round($seconds / 3600);       //value 3600 is 60 minutes * 60 sec  
		$days         = round($seconds / 86400);      //86400 = 24 * 60 * 60;  
		$weeks        = round($seconds / 604800);     // 7*24*60*60;  
		$months       = round($seconds / 2629440);    //((365+365+365+365+366)/5/12)*24*60*60  
		$years        = round($seconds / 31553280);   //(365+365+365+365+366)/5 * 24 * 60 * 60  
		
		if($seconds <= 60) {  
			return "Just Now";  
		} else if($minutes <=60) {  
			if($minutes==1){  
				return "one minute ago";  
			}else {  
				return "$minutes minutes ago";  
			}  
		} else if($hours <=24) {  
			if($hours==1) {  
				return "an hour ago";  
			} else {  
				return "$hours hrs ago";  
			}  
		}else if($days <= 7) {  
			if($days==1) {  
				return "yesterday";  
			}else {  
				return "$days days ago";  
			}  
		}else if($weeks <= 4.3) {  //4.3 == 52/12
			if($weeks==1){  
				return "a week ago";  
			}else {  
				return "$weeks weeks ago";  
			}  
		} else if($months <=12){  
			if($months==1){  
				return "a month ago";  
			}else{  
				return "$months months ago";  
			}  
		}else {  
			if($years==1){  
				return "one year ago";  
			}else {  
				return "$years years ago";  
			}  
		}  
	} 

	function tambahuser($con, $username, $password, $nama, $alamat, $kontak, $id_jabatan)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$username = $username = htmlspecialchars(str_replace(' ', '', strtolower($username)));
		$password = password_hash($password, PASSWORD_DEFAULT);
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO user SET username='$username',password='$password',nama='$nama',alamat='$alamat',kontak='$kontak',id_jabatan='$id_jabatan' ");

		header('location:../main?url=user&page='.$page);
	}

	function ubahuser($con, $id_user, $username, $nama, $alamat, $kontak, $id_jabatan)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$username = $username = htmlspecialchars(str_replace(' ', '', strtolower($username)));
		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "UPDATE user SET  username='$username',nama='$nama',alamat='$alamat',kontak='$kontak',id_jabatan='$id_jabatan' WHERE id_user='$id_user' ");
		
		header('location:../main?url=user&page='.$page);
	}

	function resetpassworduser($con, $id_user, $password, $password2)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = mysqli_query($con, "UPDATE user SET  password='$password' WHERE id_user='$id_user' ");
		
		header('location:../main?url=user&page='.$page);
	}

	function gantipassworduser($con, $id_user, $password, $password2)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = mysqli_query($con, "UPDATE user SET  password='$password' WHERE id_user='$id_user' ");
		header('location:../main');
	}

	function hapususer($con, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM user WHERE id_user='$id_user' ");
		header('location:../main?url=user&page='.$page);
	}

	function setaktifuser($con, $id_user, $aktif)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;
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
		
		header('location:../main?url=user&page='.$page);
	}

	function getsupplier($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["nama", "alamat", "kontak"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "id_supplier DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$btn_aksi = "CONCAT(
			'<a href=\"#!\" onclick=\"editSupplier(', id_supplier, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
			<a href=\"#!\" onclick=\"hapusSupplier(', id_supplier, ')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY id_supplier DESC) AS row_no,
				id_supplier,
				nama,
				alamat,
				kontak,
				$btn_aksi aksi
			FROM supplier
			WHERE id_supplier!='1'
			$whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
			
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT * 
			FROM supplier 
			WHERE id_supplier!='1' $whereFilter
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function tambahsupplier($con, $nama, $alamat, $kontak)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO supplier SET nama='$nama',alamat='$alamat',kontak='$kontak' ");

		header('location:../main?url=supplier&page='.$page);
	}

	function ubahsupplier($con, $id_supplier, $nama, $alamat, $kontak)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE supplier SET nama='$nama',alamat='$alamat',kontak='$kontak',updated='$updated' WHERE id_supplier='$id_supplier' ");
		
		header('location:../main?url=supplier&page='.$page);
	}

	function hapussupplier($con, $id_supplier)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM supplier WHERE id_supplier='$id_supplier' ");
		
		header('location:../main?url=supplier&page='.$page);
	}

	function getpelanggan($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["pelanggan.nama", "type", "pelanggan.alamat", "pelanggan.kontak", "admin.nama"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "pelanggan.id_pelanggan DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];
		$btn_aksi = "CONCAT(
			'<a href=\"#!\" onclick=\"historyPelanggan(', pelanggan.id_pelanggan, ')\" class=\"btn btn-warning btn-sm\" data-toggle=\"tooltip\" data-original-title=\"History Pembelian\"><i class=\"fas fa-history\"></i></a> ',
			'<a href=\"#!\" onclick=\"editPelanggan(', pelanggan.id_pelanggan, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a> ',
			'<a href=\#!\" onclick=\"hapusPelanggan(', pelanggan.id_pelanggan, ')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";

		$result = mysqli_query($con, "
			SELECT 
				pelanggan.id_pelanggan,
				CONCAT(UCASE(LEFT(type, 1)), LCASE(SUBSTRING(type, 2))) type,
				pelanggan.nama,
				COUNT(this_month.no_faktur) bulan_ini,
				COUNT(prev_month.no_faktur) bulan_lalu,
				pelanggan.alamat,
				pelanggan.kontak,
				admin.nama admin,
				$btn_aksi aksi,
				pelanggan.created,
				pelanggan.updated,
				ROW_NUMBER() OVER(ORDER BY pelanggan.id_pelanggan DESC) AS row_no
			FROM pelanggan 
			LEFT JOIN penjualan this_month ON this_month.id_pelanggan = pelanggan.id_pelanggan 
				AND YEAR(this_month.tanggal) = YEAR(CURRENT_DATE())
				AND MONTH(this_month.tanggal) = MONTH(CURRENT_DATE())
			LEFT JOIN penjualan prev_month ON prev_month.id_pelanggan = pelanggan.id_pelanggan 
				AND YEAR(prev_month.tanggal) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
				AND MONTH(prev_month.tanggal) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
			LEFT JOIN user admin ON admin.id_user = pelanggan.admin
			WHERE pelanggan.id_pelanggan!='1' 
			AND pelanggan.id_pelanggan!='2' 
			$whereFilter
			GROUP BY pelanggan.id_pelanggan
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT pelanggan.id_pelanggan
			FROM pelanggan 
			LEFT JOIN user admin ON admin.id_user = pelanggan.admin
			WHERE id_pelanggan!='1' 
			AND id_pelanggan!='2' 
			$whereFilter
			ORDER BY id_pelanggan DESC
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function tambahpelanggan($con, $nama, $type, $alamat, $kontak, $admin = null)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$query = mysqli_query($con, "INSERT INTO pelanggan SET nama='$nama',type='$type',alamat='$alamat',kontak='$kontak',admin=$admin ");
		
		header('location:../main?url=pelanggan&page='.$page);
	}

	function ubahpelanggan($con, $id_pelanggan, $nama, $type, $alamat, $kontak, $admin)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$nama = htmlspecialchars(ucwords($nama));
		$alamat = htmlspecialchars(ucwords($alamat));
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE pelanggan SET nama='$nama',type='$type',alamat='$alamat',kontak='$kontak',updated='$updated',admin=$admin WHERE id_pelanggan='$id_pelanggan' ");

		header('location:../main?url=pelanggan&page='.$page);
	}

	function hapuspelanggan($con, $id_pelanggan)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM pelanggan WHERE id_pelanggan='$id_pelanggan' ");
		header('location:../main?url=pelanggan&page='.$page);
	}

	function getbarang($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["barcode", "nama", "merk", "stok", "modal", "distributor", "reseller", "bengkel", "admin", "het"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "created DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		if($_SESSION["id_jabatan"] == 1 || $_SESSION["id_jabatan"] == 2){
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"editBarang(', id_barang, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
				<a href=\"#!\" onclick=\"hapusBarang(', id_barang, ')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
			)";
		}else{
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"editBarang(', id_barang, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>'
			)";
		}

		$btn_gambar = "CONCAT('<a href=\"main?url=ubah-barang&this=', id_barang, '\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-photo-video\"></i></a>')";
		$btn_pilih = "CONCAT('<button id=\"pilihbarang\" class=\"btn btn-sm btn-info\" data-id=\"', id_barang, '\" data-barcode=\"', barcode, '\" data-nama=\"', nama, '\" data-stok=\"', stok, '\">Pilih</button>')";

		$result = mysqli_query($con, "
			SELECT  
				ROW_NUMBER() OVER(ORDER BY created DESC) AS row_no,
				barcode,
				nama,
				merk,
				COALESCE(barang.stok, 0) - COALESCE(history_pembelian.qty, 0) stok,
				CONCAT('RP', FORMAT(modal, 0, 'id_ID')) modal,
				CONCAT('RP', FORMAT(distributor, 0, 'id_ID')) distributor,
				CONCAT('RP', FORMAT(reseller, 0, 'id_ID')) reseller,
				CONCAT('RP', FORMAT(bengkel, 0, 'id_ID')) bengkel,
				CONCAT('RP', FORMAT(admin, 0, 'id_ID')) admin,
				CONCAT('RP', FORMAT(het, 0, 'id_ID')) het,
				$btn_aksi aksi,
				$btn_gambar gambar,
				$btn_pilih aksi_pilih
			FROM barang 
			LEFT JOIN (
				SELECT
					pembelian_det.id_barang id,
					SUM(pembelian_det.qty) qty
				FROM pembelian_det
				JOIN pembelian ON pembelian.no_po = pembelian_det.no_po
				WHERE pembelian.temp = 1
				GROUP BY pembelian.no_po, pembelian_det.id_barang
			) history_pembelian ON barang.id_barang = history_pembelian.id
			WHERE deleted = 0 
			$whereFilter
			GROUP BY barang.id_barang
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 $whereFilter");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
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
		$created = date("Y-m-d H:i:s");

		$query = mysqli_query($con, "
			INSERT INTO barang SET 
				barcode='$barcode',
				nama='$nama',
				merk='$merk',
				stok='$stok',
				modal='$modal',
				distributor='$distributor',
				reseller='$reseller',
				bengkel='$bengkel',
				admin='$admin',
				het='$het',
				kondisi='$kondisi',
				kualitas='$kualitas',
				kategori='$kategori',
				tipe_pelanggan='$tipe_pelanggan',
				tambahan='$tambahan', 
				deskripsi='$deskripsi', 
				berat=$berat,
				created='$created'
		");

		// Upload
		$id_barang = mysqli_insert_id($con);
		
		$f = $_FILES;
		if(!empty($f)){
			$path = str_replace(['/adm/process', 'adm\process'],'/p/'.trim($id_barang),dirname(__FILE__));
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
			$selected_barang = isset($post["selected_barang"]) && !empty($post["selected_barang"])? $post["selected_barang"] :  $f['gambar']['name'][0];
			if ($selected_barang) 
			{
				mysqli_query($con,"REPLACE INTO foto_barang (id_barang, name) VALUES ($id_barang, '$selected_barang')");
			}

			header('location:../main?url=barang');
		}else{
			header('location:../main?url=barang');
		}
	}

	function ubahbarang($con, $post)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;

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
					$path = str_replace(['/adm/process', 'adm\process'],'/p/'.trim($id_barang),dirname(__FILE__));
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
			$path = str_replace(['/adm/process', 'adm\process'],'/p/'.trim($id_barang),dirname(__FILE__));
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
					echo "<br><a href='main?url=ubah-barang&this=".$id_barang."&page=".$page."'>Kembali Ke Form</a><br>";  
				}
			}

		}

		// Update selected
		if (isset($post["selected_barang"]) && !empty($post["selected_barang"])) {
			$selected_barang = basename($post["selected_barang"]);
			mysqli_query($con,"REPLACE INTO foto_barang (id_barang, name) VALUES ($id_barang, '$selected_barang')");
		}

		if($_SESSION['id_jabatan'] == "4"){
			header('location:../main');
		}else{
			header('location:../main?url=barang&page='.$page);
		}
	}

	function softhapusbarang($con, $id_barang)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "UPDATE barang SET deleted = 1 WHERE id_barang='$id_barang' ");
		$path = str_replace(['/adm/process', 'adm\process'],'/p/'.trim($id_barang),dirname(__FILE__));
		if(file_exists($path)){
			$this->rrmdir($path);
		}
		header('location:../main?url=barang&page='.$page);
	}

	function hapusbarang($con, $id_barang)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$query = mysqli_query($con, "DELETE FROM barang WHERE id_barang='$id_barang' ");
		$path = str_replace(['/adm/process', 'adm\process'],'/p/'.trim($id_barang),dirname(__FILE__));
		if(file_exists($path)){
			$this->rrmdir($path);
		}

		header('location:../main?url=barang&page='.$page);
	}

	function rrmdir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir);
			foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
				rmdir($dir. DIRECTORY_SEPARATOR .$object);
				else
				unlink($dir. DIRECTORY_SEPARATOR .$object); 
			} 
			}
			rmdir($dir); 
		} 
	}

	function tambahbarangpembelian($con, $id_user, $barcode, $qty)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE barcode='$barcode'"));
		if ($barcode != $query['barcode']) {
			echo "<script>alert('Maaf barang tidak ditemukan mungkin kode barcode salah'); window.location='../main?url=tambah-pembelian&page=".$page."';</script>";
		} else {
			$id_barang = $query['id_barang'];
			$harga = $query['modal'];
			$total_harga = $harga * $qty;
			$query = mysqli_query($con, "INSERT INTO pembelian_temp SET id_barang='$id_barang',qty='$qty',total_harga='$total_harga',id_user='$id_user' ");
			header('location:../main?url=tambah-pembelian&page='.$page);
		}
	}

	function ubahbarangpembelian($con, $id_user, $id_barang, $harga, $qty)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$harga = str_replace('.', '', $harga);
		$total_harga = $harga * $qty;
		$query = mysqli_query($con, "UPDATE pembelian_temp SET qty='$qty',total_harga='$total_harga' WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-pembelian&page='.$page);
	}

	function hapusbarangpembelian($con, $id_barang, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM pembelian_temp WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-pembelian&page='.$page);
	}

	function tambahpembelian($con, $id_supplier, $id_user, $total_transaksi, $total_bayar, $temp)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

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

		$query = mysqli_query($con, "INSERT INTO pembelian SET no_po='$no_po',id_supplier='$id_supplier',tanggal='$tanggal',status='$status',total_transaksi='$total_transaksi',total_bayar='$total_bayar',id_user='$id_user',temp='$temp',temp_status='Pending' ");
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
		header('location:../main?url=lihat-pembelian&this=' . $no_po . '&page='.$page);
	}

	function cicilanpembelian($con, $id_user, $no_po, $bayar)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO pembelian_debt SET no_po='$no_po',bayar='$bayar',keterangan='Cicilan',id_user='$id_user' ");
		$update_pembayaran = mysqli_query($con, "UPDATE pembelian SET total_bayar=total_bayar+$bayar WHERE no_po='$no_po'");
		$query_pembelian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian WHERE no_po='$no_po'"));
		if ($query_pembelian['total_bayar'] >= $query_pembelian['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE pembelian SET status='Lunas' WHERE no_po='$no_po'");
		}

		header('location:../main?url=lihat-pembelian&this=' . $no_po . '&page='.$page);
	}

	function hapuscicilanpembelian($con, $no_po, $id_pembelian_debt)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$pembelian_det = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian_debt WHERE id_pembelian_debt='$id_pembelian_debt'"));
		$bayar = $pembelian_det['bayar'];
		$update_pembayaran = mysqli_query($con, "UPDATE pembelian SET total_bayar=total_bayar-$bayar WHERE no_po='$no_po'");
		$query_pembelian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian WHERE no_po='$no_po'"));
		if ($query_pembelian['total_bayar'] < $query_pembelian['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE pembelian SET status='Hutang' WHERE no_po='$no_po'");
		}
		$query = mysqli_query($con, "DELETE FROM pembelian_debt WHERE id_pembelian_debt='$id_pembelian_debt' ");
		
		header('location:../main?url=lihat-pembelian&this=' . $no_po . '&page='.$page);
	}

	function getpembelian($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["pembelian.no_po", "supplier.nama", "DATE_FORMAT(pembelian.tanggal, '%e %M %Y')", "supplier.nama", "pembelian.status", "user.nama"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src) ";
		}

		$orderBy = "pembelian.tanggal DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '5'){
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"lihatPembelian(\'', pembelian.no_po, '\')\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-eye\"></i></a>
				<a href=\"#!\" onclick=\"cetakPembelian(\'', pembelian.no_po, '\')\" class=\"btn btn-secondary btn-sm\"><i class=\"fas fa-print\"></i></a> ',
				IF(pembelian.status = 'Hutang', CONCAT('<a href=\"#!\" onclick=\"cicilanPembelian(\'', pembelian.no_po, '\')\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-hand-holding-usd\"></i></a> '), ''),
				'<a href=\"#!\" onclick=\"hapusPembelian(\'', pembelian.no_po, '\')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
			)";
		}else{
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"lihatPembelian(\'', pembelian.no_po, '\')\"  class=\"btn btn-info btn-sm\"><i class=\"fas fa-eye\"></i></a>
				<a href=\"#!\" onclick=\"cetakPembelian(\'', pembelian.no_po, '\')\" class=\"btn btn-secondary btn-sm\"><i class=\"fas fa-print\"></i></a>'
			)";
		}

		$badge_status = "IF(pembelian.status = 'Lunas', CONCAT('<span class=\"badge badge-success\">', pembelian.status, '</span>'), CONCAT('<span class=\"badge badge-danger\">', pembelian.status, '</span>'))";

		if ($_SESSION['id_jabatan'] != "1" && $_SESSION['id_jabatan'] != "2" && $_SESSION['id_jabatan'] != "5") {
			$whereFilter .=  "AND pembelian.id_user=" . $_SESSION['id_user'];
		}

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY pembelian.tanggal DESC) AS row_no,
				pembelian.no_po,
				pembelian.id_supplier,
				supplier.nama supplier,
				DATE_FORMAT(pembelian.tanggal, '%e %M %Y') tanggal,
				$badge_status status,
				CONCAT('Rp', FORMAT(pembelian.total_transaksi, 0,'id_ID')) total_transaksi,
				CONCAT('Rp', FORMAT(IF(pembelian.status = 'Lunas', pembelian.total_transaksi, pembelian.total_bayar), 0,'id_ID')) total_bayar,
				pembelian.id_user,
				user.nama user,
				pembelian.updated,
				$btn_aksi aksi
			FROM pembelian
			LEFT JOIN supplier ON supplier.id_supplier = pembelian.id_supplier
			LEFT JOIN user ON user.id_user = pembelian.id_user
			WHERE pembelian.temp = 0 $whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT pembelian.no_po
			FROM pembelian 
			LEFT JOIN supplier ON supplier.id_supplier = pembelian.id_supplier
			LEFT JOIN user ON user.id_user = pembelian.id_user
			WHERE pembelian.temp = 0 $whereFilter");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}


	function hapuspembelian($con, $no_po)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$pembelian_det = mysqli_query($con, "SELECT * FROM pembelian_det WHERE no_po='$no_po'");
		foreach ($pembelian_det as $pd) {
			$id_barang = $pd['id_barang'];
			$qty = $pd['qty'];
			$stock_out = mysqli_query($con, "UPDATE barang SET stok=stok-$qty WHERE id_barang='$id_barang'");
		}
		$query = mysqli_query($con, "DELETE FROM pembelian WHERE no_po='$no_po' ");
		header('location:../main?url=pembelian&page='.$page);
	}

	function tambahbarangpenjualan($con, $url, $type, $id_user, $id_barang, $qty, $diskon)
	{
		$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM barang WHERE id_barang=$id_barang"));
		if ($id_barang != $query['id_barang']) {
			echo "<script>alert('Maaf barang tidak ditemukan mungkin kode barcode salah.'); window.location='$url';</script>";
		} else if ($query['stok'] == 0 || $qty > $query['stok']) {
			echo "<script>alert('Maaf stok tidak mencukupi.'); window.location='$url';</script>";
		} else if ($diskon > $query['modal']) {
			echo "<script>alert('Maaf diskon tidak bisa lebih dari harga modal nanti saya RUGI !!!.'); window.location='$url';</script>";
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
			header("location:$url");
		}
	}

	function ubahbarangpenjualan($con, $id_user, $id_barang, $harga, $diskon, $qty, $type)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$harga = str_replace('.', '', $harga);
		$diskon = str_replace('.', '', $diskon);
		$total_harga = ($harga - $diskon) * $qty;
		$query = mysqli_query($con, "UPDATE penjualan_temp SET qty='$qty',diskon='$diskon',total_harga='$total_harga' WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		header('location:../main?url=tambah-penjualan&type=' . $type . '&page='.$page);
	}

	function hapusbarangpenjualan($con, $id_barang, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query_type = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='$id_user'"));
		$query = mysqli_query($con, "DELETE FROM penjualan_temp WHERE id_barang='$id_barang' AND id_user='$id_user' ");
		if ($query_type['id_user'] != NULL) {
			header('location:../main?url=tambah-penjualan&type=' . $query_type['type'] . '&page='.$page);
		} else {
			header('location:../main?url=penjualan');
		}
	}

	function getpenjualan($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["penjualan.no_faktur", "DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i')",  "pelanggan.nama", "pelanggan.type", "penjualan.status", "penjualan.persetujuan", "user.nama", "penjualan.tipe_bayar"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter1 = "";
		$whereFilter2 = "";
		if(!empty($q_src)){
			$whereFilter1 = "AND ($q_src) ";
			$whereFilter2 = "AND ($q_src) ";
		}

		$orderBy = "real_tanggal DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2'){
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"lihatPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-eye\"></i></a>
				<a href=\"#!\" onclick=\"cetakPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-secondary btn-sm\"><i class=\"fas fa-print\"></i></a> ',
				IF(penjualan.persetujuan = 'Pending', CONCAT('<a href=\"#!\" onclick=\"approvePenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-check\"></i></a> '), ''),
				IF(penjualan.status = 'Hutang', CONCAT('<a href=\"#!\" onclick=\"cicilanPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-hand-holding-usd\"></i></a> '), ''),
				'<a href=\"#!\" onclick=\"hapusPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
			)";
		}else{
			$btn_aksi = "CONCAT(
				'<a href=\"#!\" onclick=\"lihatPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-eye\"></i></a>
				<a href=\"#!\" onclick=\"cetakPenjualan(\'', penjualan.no_faktur, '\')\" class=\"btn btn-secondary btn-sm\"><i class=\"fas fa-print\"></i></a>'
			)";
		}

		$badge_status = "IF(penjualan.status = 'Lunas', CONCAT('<span class=\"badge badge-success\">', penjualan.status, '</span>'), CONCAT('<span class=\"badge badge-danger\">', penjualan.status, '</span>'))";
		$badge_approve = "IF(penjualan.persetujuan = 'Approved', CONCAT('<span class=\"badge badge-primary\">', penjualan.persetujuan, '</span>'), CONCAT('<span class=\"badge badge-warning\">', penjualan.persetujuan, '</span>'))";

		if ($_SESSION['id_jabatan'] != "1" || $_SESSION['id_jabatan'] != "2") {
			// $whereFilter1 .=  "AND DATEDIFF(NOW(), tanggal) <= 90 AND status = 'Lunas' AND persetujuan = 'Approved' ";
			$whereFilter1 .=  "AND status = 'Lunas' AND persetujuan = 'Approved' ";
			$whereFilter2 .=  "AND CONCAT(status,persetujuan) != CONCAT('Lunas','Approved') ";
		}else{
			// $whereFilter1 .=  "AND penjualan.id_user='" . $_SESSION['id_user'] . "' AND DATEDIFF(NOW(), penjualan.tanggal) <= 90 AND penjualan.status = 'Lunas' AND penjualan.persetujuan = 'Approved'";
			$whereFilter1 .=  "AND penjualan.id_user='" . $_SESSION['id_user'] . "' AND penjualan.status = 'Lunas' AND penjualan.persetujuan = 'Approved' ";
			$whereFilter2 .=  "AND CONCAT(penjualan.status, penjualan.persetujuan) != CONCAT('Lunas','Approved') ";
		}
		
		if (isset($_POST["admin"]) && !empty($_POST["admin"])) {
			$whereFilter1 .= "AND penjualan.id_user = ".$_POST["admin"]." ";
			$whereFilter2 .= "AND penjualan.id_user = ".$_POST["admin"]." ";
		}

		if (isset($_POST["status"]) && !empty($_POST["status"])) {
			$whereFilter1 .= "AND penjualan.status = '".$_POST["status"]."' ";
			$whereFilter2 .= "AND penjualan.status = '".$_POST["status"]."' ";
		}

		$result = mysqli_query($con, "
			SELECT 
				penjualan.no_faktur,
				penjualan.id_pelanggan,
				pelanggan.nama pelanggan,
				CONCAT(UCASE(LEFT(pelanggan.type, 1)), SUBSTRING(pelanggan.type, 2)) type,
				DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i') tanggal,
				penjualan.tanggal real_tanggal,
				$badge_status status,
				CASE 
					WHEN penjualan.tipe_bayar = 'Cash' THEN 'C'
					WHEN penjualan.tipe_bayar = 'Transfer' THEN 'T'
					ELSE ''
				END tipe_bayar,
				CONCAT('Rp', FORMAT(penjualan.total_transaksi, 0,'id_ID')) total_transaksi,
				CONCAT('Rp', FORMAT(IF(penjualan.status = 'Lunas', penjualan.total_transaksi, penjualan.total_bayar), 0,'id_ID')) total_bayar,
				$badge_approve persetujuan,
				penjualan.id_user,
				user.nama user,
				penjualan.updated,
				$btn_aksi aksi
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE 1=1 $whereFilter1
			UNION ALL
			SELECT
				penjualan.no_faktur,
				penjualan.id_pelanggan,
				pelanggan.nama pelanggan,
				CONCAT(UCASE(LEFT(pelanggan.type, 1)), SUBSTRING(pelanggan.type, 2)) type,
				DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i') tanggal,
				penjualan.tanggal real_tanggal,
				$badge_status status,
				CASE 
					WHEN penjualan.tipe_bayar = 'Cash' THEN 'C'
					WHEN penjualan.tipe_bayar = 'Transfer' THEN 'T'
					ELSE ''
				END tipe_bayar,
				CONCAT('Rp', FORMAT(penjualan.total_transaksi, 0,'id_ID')) total_transaksi,
				CONCAT('Rp', FORMAT(IF(penjualan.status = 'Lunas', penjualan.total_transaksi, penjualan.total_bayar), 0,'id_ID')) total_bayar,
				$badge_approve persetujuan,
				penjualan.id_user,
				user.nama user,
				penjualan.updated,
				$btn_aksi aksi
			FROM penjualan
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE 1=1 $whereFilter2
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT penjualan.no_faktur
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE 1=1 $whereFilter1
			UNION ALL
			SELECT penjualan.no_faktur
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE 1=1 $whereFilter2");
			
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);

		// Get total hutang
		$result_hutang = mysqli_query($con, "
			SELECT CONCAT('Rp', FORMAT(SUM(total_transaksi) - SUM(total_bayar), 0,'id_ID')) total_hutang 
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE penjualan.status = 'Hutang' $whereFilter2");
		
		$data_hutang = mysqli_fetch_assoc($result_hutang);
		$data["total_hutang"] = isset($data_hutang["total_hutang"]) && !empty($data_hutang["total_hutang"]) ? $data_hutang["total_hutang"] : 0 ;

		echo json_encode($data);
	}

	function tambahpenjualan($con, $id_pelanggan, $id_user, $total_transaksi, $total_bayar, $tipe_bayar)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

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

		$query = mysqli_query($con, "INSERT INTO penjualan SET no_faktur='$no_faktur',id_pelanggan='$id_pelanggan',tanggal='$tanggal',status='$status',total_transaksi='$total_transaksi',total_bayar='$total_bayar',id_user='$id_user', tipe_bayar='$tipe_bayar' ");
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
		
		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '&page='.$page);
	}

	function cicilanpenjualan($con, $id_user, $no_faktur, $bayar)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO penjualan_debt SET no_faktur='$no_faktur',bayar='$bayar',keterangan='Cicilan',id_user='$id_user' ");
		$update_pembayaran = mysqli_query($con, "UPDATE penjualan SET total_bayar=total_bayar+$bayar WHERE no_faktur='$no_faktur'");
		$query_penjualan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan WHERE no_faktur='$no_faktur'"));
		if ($query_penjualan['total_bayar'] >= $query_penjualan['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE penjualan SET status='Lunas' WHERE no_faktur='$no_faktur'");
		}

		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '&page='.$page);
	}

	function hapuscicilanpenjualan($con, $no_faktur, $id_penjualan_debt)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$penjualan_det = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_debt WHERE id_penjualan_debt='$id_penjualan_debt'"));
		$bayar = $penjualan_det['bayar'];
		$update_pembayaran = mysqli_query($con, "UPDATE penjualan SET total_bayar=total_bayar-$bayar WHERE no_faktur='$no_faktur'");
		$query_penjualan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan WHERE no_faktur='$no_faktur'"));
		if ($query_penjualan['total_bayar'] < $query_penjualan['total_transaksi']) {
			$update_status = mysqli_query($con, "UPDATE penjualan SET status='Hutang' WHERE no_faktur='$no_faktur'");
		}
		$query = mysqli_query($con, "DELETE FROM penjualan_debt WHERE id_penjualan_debt='$id_penjualan_debt' ");

		header('location:../main?url=lihat-penjualan&this=' . $no_faktur . '&page='.$page);
	}

	function hapuspenjualan($con, $no_faktur)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		$penjualan_det = mysqli_query($con, "SELECT * FROM penjualan_det WHERE no_faktur='$no_faktur'");
		// disable temporary
		foreach ($penjualan_det as $pd) {
			$id_barang = $pd['id_barang'];
			$qty = $pd['qty'];
			$stock_in = mysqli_query($con, "UPDATE barang SET stok=stok+$qty WHERE id_barang='$id_barang'");
		}
		$query = mysqli_query($con, "DELETE FROM penjualan WHERE no_faktur='$no_faktur' ");
		header('location:../main?url=penjualan&page='.$page);
	}

	function getjenispengeluaran($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["jenis"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "id_pengeluaran_type DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];
		$btn_aksi = "CONCAT(
			'<a href=\"#!\" onclick=\"editJenisPengeluaran(', id_pengeluaran_type, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
			<a href=\"#!\" onclick=\"hapusJenisPengeluaran(', id_pengeluaran_type, ')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY id_pengeluaran_type DESC) AS row_no,
				id_pengeluaran_type,
				jenis,
				$btn_aksi aksi
			FROM pengeluaran_type
			WHERE 1=1 $whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "SELECT * FROM pengeluaran_type WHERE 1=1 $whereFilter");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function tambahjenispengeluaran($con, $jenis)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$jenis = htmlspecialchars(strtoupper($jenis));
		$query = mysqli_query($con, "INSERT INTO pengeluaran_type SET jenis='$jenis' ");
		header('location:../main?url=jenis-pengeluaran&page='.$page);
	}

	function ubahjenispengeluaran($con, $id_pengeluaran_type, $jenis)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$jenis = htmlspecialchars(strtoupper($jenis));
		$query = mysqli_query($con, "UPDATE pengeluaran_type SET jenis='$jenis' WHERE id_pengeluaran_type='$id_pengeluaran_type' ");
		header('location:../main?url=jenis-pengeluaran&page='.$page);
	}

	function hapusjenispengeluaran($con, $id_pengeluaran_type)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM pengeluaran_type WHERE id_pengeluaran_type='$id_pengeluaran_type' ");
		header('location:../main?url=jenis-pengeluaran&page='.$page);
	}

	function getpengeluaran($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["DATE_FORMAT(pengeluaran.tanggal, '%e %M %Y, %H:%i')", "pengeluaran_type.jenis", "pengeluaran.keterangan", "user.nama"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src) ";
		}

		if ($_SESSION['id_jabatan'] != "1" && $_SESSION['id_jabatan'] != "2") {
			$whereFilter .= "AND user.id_user='" . $_SESSION['id_user'] . "' ";
		}

		$orderBy = "pengeluaran.tanggal DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];
		$btn_aksi = "CONCAT(
			'<a href=\"#!\" onclick=\"editPengeluaran(', pengeluaran.id_pengeluaran, ')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
			<a href=\"#!\" onclick=\"hapusPengeluaran(', pengeluaran.id_pengeluaran, ')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\" onclick=\"return confirm(`Anda yakin ingin hapus data ini?`)\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY pengeluaran.tanggal DESC) AS row_no,
				pengeluaran.id_pengeluaran,
				DATE_FORMAT(pengeluaran.tanggal, '%e %M %Y, %H:%i') tanggal,
				pengeluaran_type.jenis,
				CONCAT('Rp', FORMAT(pengeluaran.jumlah, 0,'id_ID')) jumlah,
				pengeluaran.keterangan,
				user.nama user,
				$btn_aksi aksi
			FROM pengeluaran
			JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type = pengeluaran_type.id_pengeluaran_type 
			JOIN user ON pengeluaran.id_user = user.id_user
			WHERE 1=1 $whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT pengeluaran.id_pengeluaran 
			FROM pengeluaran 
			JOIN pengeluaran_type ON pengeluaran.id_pengeluaran_type = pengeluaran_type.id_pengeluaran_type 
			JOIN user ON pengeluaran.id_user = user.id_user
			WHERE 1=1 $whereFilter");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function tambahpengeluaran($con, $id_pengeluaran_type, $jumlah, $keterangan, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$keterangan = htmlspecialchars(ucwords($keterangan));
		$query = mysqli_query($con, "INSERT INTO pengeluaran SET id_pengeluaran_type='$id_pengeluaran_type',jumlah='$jumlah',keterangan='$keterangan',id_user='$id_user' ");
		header('location:../main?url=pengeluaran&page='.$page);
	}

	function ubahpengeluaran($con, $id_pengeluaran,  $id_pengeluaran_type, $jumlah, $keterangan, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$keterangan = htmlspecialchars(ucwords($keterangan));
		var_dump($id_pengeluaran,  $id_pengeluaran_type, $jumlah, $keterangan);
		$updated = date("Y-m-d h:i:s");
		$query = mysqli_query($con, "UPDATE pengeluaran SET id_pengeluaran_type='$id_pengeluaran_type',jumlah='$jumlah',keterangan='$keterangan',id_user='$id_user',updated='$updated' WHERE id_pengeluaran='$id_pengeluaran' ");
		header('location:../main?url=pengeluaran&page='.$page);
	}

	function hapuspengeluaran($con, $id_pengeluaran)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM pengeluaran WHERE id_pengeluaran='$id_pengeluaran' ");
		header('location:../main?url=pengeluaran&page='.$page);
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
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$date = date('Y-m-d H:i:s');
		$query = mysqli_query($con, "UPDATE penjualan SET persetujuan='Approved', updated='$date' WHERE no_faktur='$no_faktur' ");
		header('location:../main?url=penjualan&page='.$page);
	}

	function tambahsocmed($con, $tipe, $keterangan, $link)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO socmed SET keterangan='$keterangan',tipe='$tipe',link='$link' ");
		header('location:../main?url=socmed&page='.$page);
	}

	function ubahsocmed($con, $id, $tipe, $keterangan, $link)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$query = mysqli_query($con, "UPDATE socmed SET keterangan='$keterangan',tipe='$tipe',link='$link' WHERE id='$id' ");
		header('location:../main?url=socmed&page='.$page);
	}

	function hapussocmed($con, $id)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM socmed WHERE id='$id' ");
		header('location:../main?url=socmed&page='.$page);
	}

	function tambahmarketplace($con, $tipe, $keterangan, $link, $order = 1)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO market_place SET keterangan='$keterangan',tipe='$tipe',link='$link',order_no='$order' ");
		header('location:../main?url=marketplace&page='.$page);
	}

	function ubahmarketplace($con, $id, $tipe, $keterangan, $link, $order = 1)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$query = mysqli_query($con, "UPDATE market_place SET keterangan='$keterangan',tipe='$tipe',link='$link',order_no='$order' WHERE id='$id' ");
		header('location:../main?url=marketplace&page='.$page);
	}

	function hapusmarketplace($con, $id)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM market_place WHERE id='$id' ");
		header('location:../main?url=marketplace&page='.$page);
	}

	function tambahkontak($con, $keterangan, $kontak, $letak, $aktif, $order = 1)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO kontak SET keterangan='$keterangan', kontak='$kontak', letak='$letak', aktif='$aktif', order_no='$order' ");
		header('location:../main?url=kontak&page='.$page);
	}

	function ubahkontak($con, $id, $keterangan, $kontak, $letak, $aktif, $order = 1)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "UPDATE kontak SET keterangan='$keterangan', kontak='$kontak', letak='$letak', aktif='$aktif', order_no='$order' WHERE id='$id' ");
		header('location:../main?url=kontak&page='.$page);
	}

	function setaktif($con, $id, $aktif)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "UPDATE kontak SET aktif='$aktif' WHERE id='$id' ");
		header('location:../main?url=kontak&page='.$page);
	}

	function hapuskontak($con, $id)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM kontak WHERE id='$id' ");
		header('location:../main?url=kontak&page='.$page);
	}

	function getmerk($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["name"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "name ASC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];
		$btn_aksi = "CONCAT(
			'<a href=\"#!\" onclick=\"editMerk(', id,')\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-edit\"></i></a>
			<a href=\"#!\" onclick=\"hapusMerk(', id,')\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" data-original-title=\"Hapus\"><i class=\"fas fa-trash-alt\"></i></a>'
		)";

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY name ASC) AS row_no,
				id,
				name,
				$btn_aksi aksi
			FROM merk
			WHERE 1=1 $whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "SELECT * FROM merk WHERE 1=1 $whereFilter");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function tambahmerk($con, $merk)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "INSERT INTO merk SET name='$merk' ");
		
		header('location:../main?url=merk&page='.$page);
	}

	function ubahmerk($con, $id, $merk)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$query = mysqli_query($con, "UPDATE merk SET name='$merk' WHERE id='$id' ");
		
		header('location:../main?url=merk&page='.$page);
	}

	function hapusmerk($con, $id)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "DELETE FROM merk WHERE id='$id' ");
		
		header('location:../main?url=merk&page='.$page);
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

	function getlaporanharian($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["penjualan.no_faktur", "DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i')",  "pelanggan.nama", "pelanggan.type", "penjualan.status", "penjualan.persetujuan", "user.nama", "penjualan.tipe_bayar"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src) ";
		}

		$orderBy = "penjualan.tanggal DESC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$badge_status = "IF(penjualan.status = 'Lunas', CONCAT('<span class=\"badge badge-success\">', penjualan.status, '</span>'), CONCAT('<span class=\"badge badge-danger\">', penjualan.status, '</span>'))";
		$badge_approve = "IF(penjualan.persetujuan = 'Approved', CONCAT('<span class=\"badge badge-primary\">', penjualan.persetujuan, '</span>'), CONCAT('<span class=\"badge badge-warning\">', penjualan.persetujuan, '</span>'))";

		$result = mysqli_query($con, "
			SELECT 
				penjualan.no_faktur,
				penjualan.id_pelanggan,
				pelanggan.nama pelanggan,
				CONCAT(UCASE(LEFT(pelanggan.type, 1)), SUBSTRING(pelanggan.type, 2)) type,
				DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i') tanggal,
				penjualan.tanggal real_tanggal,
				$badge_status status,
				CASE 
					WHEN penjualan.tipe_bayar = 'Cash' THEN 'C'
					WHEN penjualan.tipe_bayar = 'Transfer' THEN 'T'
					ELSE ''
				END tipe_bayar,
				CONCAT('Rp', FORMAT(penjualan.total_transaksi, 0,'id_ID')) total_transaksi,
				CONCAT('Rp', FORMAT(IF(penjualan.status = 'Lunas', penjualan.total_transaksi, penjualan.total_bayar), 0,'id_ID')) total_bayar,
				$badge_approve persetujuan,
				penjualan.id_user,
				user.nama user,
				penjualan.updated
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE (penjualan.daily != true OR penjualan.daily IS NULL) $whereFilter
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT penjualan.no_faktur
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE (penjualan.daily != true OR penjualan.daily IS NULL) $whereFilter");
			
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);

		// Get total transaksi
		$result_transaksi = mysqli_query($con, "
			SELECT 
				CONCAT('Rp', FORMAT(SUM(CASE WHEN tipe_bayar = 'Cash' THEN total_transaksi ELSE 0 END), 0,'id_ID')) total_cash, 
				CONCAT('Rp', FORMAT(SUM(CASE WHEN tipe_bayar = 'Transfer' THEN total_transaksi ELSE 0 END), 0,'id_ID')) total_transfer, 
				CONCAT('Rp', FORMAT(SUM(CASE WHEN tipe_bayar = 'MarketPlace' THEN total_transaksi ELSE 0 END), 0,'id_ID')) total_marketplace 
			FROM penjualan 
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			LEFT JOIN user ON user.id_user = penjualan.id_user
			WHERE (penjualan.daily != true OR penjualan.daily IS NULL) $whereFilter");
		
		$data_transaksi = mysqli_fetch_assoc($result_transaksi);
		$data["summary"]["cash"] = isset($data_transaksi["total_cash"]) && !empty($data_transaksi["total_cash"]) ? $data_transaksi["total_cash"] : 0 ;
		$data["summary"]["transfer"] = isset($data_transaksi["total_transfer"]) && !empty($data_transaksi["total_transfer"]) ? $data_transaksi["total_transfer"] : 0 ;
		$data["summary"]["marketplace"] = isset($data_transaksi["total_marketplace"]) && !empty($data_transaksi["total_marketplace"]) ? $data_transaksi["total_marketplace"] : 0 ;
		
		echo json_encode($data);
	}

	function approveharian($con)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		$query = mysqli_query($con, "UPDATE `penjualan` SET daily = true WHERE (daily != true OR daily IS NULL)");
		header('location:../main?url=laporan-harian&page='.$page);
	}

	function gethistorypembelian($con)
	{	
		$id_pelanggan = $_POST['id_pelanggan'];
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = [
				"penjualan.tanggal", 
				"barang.nama",
				"penjualan_det.qty",
				"penjualan_det.total_harga"
			];

			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "penjualan.tanggal DESC, penjualan_det.id_barang ASC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$result = mysqli_query($con, "
			SELECT 
				penjualan.tanggal,
				barang.nama,
				penjualan_det.qty,
				penjualan_det.total_harga
			FROM penjualan_det
			JOIN penjualan ON penjualan.no_faktur = penjualan_det.no_faktur
			LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
			WHERE 
				id_pelanggan = $id_pelanggan
				$whereFilter
			GROUP BY 
				penjualan.no_faktur, 
				penjualan_det.id_barang
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT 
				penjualan.tanggal,
				barang.nama,
				penjualan_det.qty,
				penjualan_det.total_harga
			FROM penjualan_det
			JOIN penjualan ON penjualan.no_faktur = penjualan_det.no_faktur
			LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
			WHERE 
				id_pelanggan = $id_pelanggan
				$whereFilter
			GROUP BY 
				penjualan.no_faktur, 
				penjualan_det.id_barang
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function getgaji($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["user.nama", "user.username", "jabatan.nama", "gaji.pokok", "gaji.kehadiran", "gaji.prestasi", "gaji.bonus", "gaji.indisipliner", "gaji.jabatan"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$orderBy = "user.id_user ASC";
		if(!empty($_POST["order"])){
			$orderBy = $_POST["columns"][$_POST["order"][0]["column"]]["data"]." ".$_POST["order"][0]["dir"];
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY user.id_user ASC) AS row_no,
				user.id_user,
				user.nama,
				user.username,
				jabatan.nama jabatan,
				COALESCE(gaji.pokok, 0) pokok,
				COALESCE(gaji.kehadiran, 0) kehadiran,
				COALESCE(gaji.prestasi, 0) prestasi,
				COALESCE((penjualan.total_het * 2) / 100, 0) bonus,
				COALESCE(gaji.indisipliner, 0) indisipliner,
				COALESCE(gaji.jabatan, 0) tunjangan_jabatan,
				COALESCE(
					COALESCE(gaji.pokok, 0) + 
					COALESCE(gaji.kehadiran, 0) +
					COALESCE(gaji.prestasi, 0) +
					COALESCE((penjualan.total_het * 2) / 100, 0) -
					COALESCE(gaji.indisipliner, 0) +
					COALESCE(gaji.jabatan, 0)
				, 0) total
			FROM user
			LEFT JOIN gaji ON gaji.id_user = user.id_user
			LEFT JOIN jabatan ON jabatan.id_jabatan = user.id_jabatan
			LEFT JOIN (
            	SELECT
                	penjualan.id_user,
                	penjualan.tanggal,
                	SUM(barang.het) total_het
                FROM penjualan
                LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
            	LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
				WHERE penjualan.persetujuan = 'Approved'
					AND YEAR(penjualan.tanggal) = YEAR(NOW())
                	AND MONTH(penjualan.tanggal) = MONTH(NOW())
                GROUP BY penjualan.id_user
            ) penjualan ON penjualan.id_user = user.id_user 
            WHERE user.id_jabatan = 5 $whereFilter
			GROUP BY user.id_user
			ORDER BY $orderBy
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT user.id_user 
			FROM user 
			LEFT JOIN gaji ON gaji.id_user = user.id_user
			LEFT JOIN jabatan ON jabatan.id_jabatan = user.id_jabatan
			LEFT JOIN (
            	SELECT
                	penjualan.id_user,
                	penjualan.tanggal,
                	SUM(barang.het) total_het
                FROM penjualan
                LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
            	LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
				WHERE penjualan.persetujuan = 'Approved'
					AND YEAR(penjualan.tanggal) = YEAR(NOW())
                	AND MONTH(penjualan.tanggal) = MONTH(NOW())
                GROUP BY penjualan.id_user
            ) penjualan ON penjualan.id_user = user.id_user 
			WHERE user.id_jabatan = 5 $whereFilter
			GROUP BY user.id_user
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function ubahgaji($con, $id_user, $pokok, $kehadiran, $prestasi, $bonus, $indisipliner, $tunjangan_jabatan)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$detail = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM gaji WHERE id_user='$id_user' "));

		if($detail) {
			mysqli_query($con, "UPDATE gaji SET pokok='$pokok', kehadiran='$kehadiran', prestasi='$prestasi', bonus='$bonus', indisipliner='$indisipliner', jabatan='$tunjangan_jabatan' WHERE id_user='$id_user' ");
		}else{
			mysqli_query($con, "INSERT INTO gaji SET id_user='$id_user', pokok='$pokok', kehadiran='$kehadiran', prestasi='$prestasi', bonus='$bonus', indisipliner='$indisipliner', jabatan='$tunjangan_jabatan'");
		}
		
		header('location:../main?url=gaji&page='.$page);
	}

	function gethistorypenjualan($con)
	{	
		$user = $_POST["id_user"];
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["penjualan.no_faktur", "barang.barcode", "barang.nama", "barang.het", "pelanggan.nama", "DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i')"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$result = mysqli_query($con, "
			SELECT 
				ROW_NUMBER() OVER(ORDER BY penjualan.tanggal DESC, penjualan_det.id_barang ASC) AS row_no,
				penjualan.no_faktur,
				barang.barcode,
				DATE_FORMAT(penjualan.tanggal, '%e %M %Y, %H:%i') tanggal,
                pelanggan.nama pelanggan,
				barang.nama,
				barang.het
			FROM penjualan
			LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
			LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			WHERE penjualan.id_user = $user
				AND penjualan.persetujuan = 'Approved'
				AND YEAR(penjualan.tanggal) = YEAR(NOW())
				AND MONTH(penjualan.tanggal) = MONTH(NOW())
            	$whereFilter
			GROUP BY penjualan.no_faktur, penjualan_det.id_barang
			ORDER BY penjualan.tanggal DESC, penjualan_det.id_barang ASC
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT 
				penjualan.no_faktur,
				penjualan_det.id_barang
			FROM penjualan
			LEFT JOIN penjualan_det ON penjualan_det.no_faktur = penjualan.no_faktur
			LEFT JOIN barang ON barang.id_barang = penjualan_det.id_barang
			LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
			WHERE penjualan.id_user = $user
				AND penjualan.persetujuan = 'Approved'
				AND YEAR(penjualan.tanggal) = YEAR(NOW())
				AND MONTH(penjualan.tanggal) = MONTH(NOW())
            	$whereFilter
			GROUP BY penjualan.no_faktur, penjualan_det.id_barang");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function processgaji($con, $id_user)
	{
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		$detail = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM gaji WHERE id_user='$id_user' "));

		if($detail) {
			mysqli_query($con, "UPDATE gaji SET pokok='0', kehadiran='0', prestasi='0', bonus='0', indisipliner='0', jabatan='0' WHERE id_user='$id_user' ");
		}else{
			mysqli_query($con, "INSERT INTO gaji SET id_user='$id_user', pokok='0', kehadiran='0', prestasi='0', bonus='0', indisipliner='0', jabatan='0'");
		}
		
		header('location:../main?url=gaji&page='.$page);
	}

	function gethistorypembeliantemp($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = [
				"pembelian.no_po", 
				"supplier.nama", 
				"DATE_FORMAT(pembelian.tanggal, '%e %M %Y')", 
				"supplier.nama", 
				"pembelian.status", 
				"user.nama"
			];

			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		if ($_SESSION['id_jabatan'] == 5) {
			$whereFilter .= " AND pembelian.id_user = ".$_SESSION['id_user'];
		}

		if (isset($_POST["status"]) && !empty($_POST["status"])) {
			$whereFilter .= " AND pembelian.temp_status = '".$_POST["status"]."' ";
		}

		$badge_status = "IF(pembelian.status = 'Lunas', CONCAT('<span class=\"badge badge-success\">', pembelian.status, '</span>'), CONCAT('<span class=\"badge badge-danger\">', pembelian.status, '</span>'))";
		
		$result = mysqli_query($con, "
			SELECT  
				ROW_NUMBER() OVER(ORDER BY pembelian.tanggal DESC) AS row_no,
				pembelian.no_po,
				pembelian.id_supplier,
				supplier.nama supplier,
				DATE_FORMAT(pembelian.tanggal, '%e %M %Y') tanggal,
				$badge_status status,
				CONCAT('Rp', FORMAT(pembelian.total_transaksi, 0,'id_ID')) total_transaksi,
				CONCAT('Rp', FORMAT(IF(pembelian.status = 'Lunas', pembelian.total_transaksi, pembelian.total_bayar), 0,'id_ID')) total_bayar,
				pembelian.id_user,
				user.nama user,
				pembelian.updated,
				pembelian.temp_status,
				pembelian.temp_reason
			FROM pembelian
			LEFT JOIN supplier ON supplier.id_supplier = pembelian.id_supplier
			LEFT JOIN user ON user.id_user = pembelian.id_user
			WHERE (pembelian.temp = 1 OR pembelian.temp_status = 'Approved') $whereFilter
			ORDER BY pembelian.tanggal DESC
			LIMIT $limit OFFSET $offset
		");

		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}
		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT pembelian.no_po
			FROM pembelian 
			LEFT JOIN supplier ON supplier.id_supplier = pembelian.id_supplier
			LEFT JOIN user ON user.id_user = pembelian.id_user
			WHERE (pembelian.temp = 1 OR pembelian.temp_status = 'Approved') $whereFilter
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function approvehistorypembelian($con, $id)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;
		
		mysqli_query($con, "UPDATE pembelian SET temp='0', temp_status='Approved' WHERE no_po = '$id'");

		header('location:../main?url=history-pembelian&page='.$page);
	}

	function declinehistorypembelian($con, $id)
	{	
		$page = isset($_GET['page'])? $_GET['page'] : 0;

		mysqli_query($con, "UPDATE pembelian SET temp_status='Decline' WHERE no_po = '$id'");

		header('location:../main?url=history-pembelian&page='.$page);
	}

	function getlistbaranghistory($con)
	{	
		$search = $_POST["search"];
		$no_po = $_POST["no_po"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = [];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		// if ($_SESSION['id_jabatan'] == 5) {
		// 	$whereFilter .= " AND pembelian.id_user = ".$_SESSION['id_user'];
		// }

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$result = mysqli_query($con, "
			SELECT
				barang.barcode,
				barang.nama,
				barang.modal harga,
				pembelian_det.qty,
				pembelian_det.total_harga total
			FROM pembelian_det 
			JOIN pembelian ON pembelian.no_po = pembelian_det.no_po
			JOIN barang ON pembelian_det.id_barang=barang.id_barang 
			WHERE pembelian.no_po = '$no_po' $whereFilter
			GROUP BY pembelian_det.no_po, pembelian_det.id_barang
			ORDER BY pembelian_det.id_barang ASC
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT
				barang.barcode,
				barang.nama,
				barang.modal harga,
				pembelian_det.qty,
				pembelian_det.total_harga total
			FROM pembelian_det 
			JOIN pembelian ON pembelian.no_po = pembelian_det.no_po
			JOIN barang ON pembelian_det.id_barang=barang.id_barang 
			WHERE pembelian.no_po = '$no_po' $whereFilter
			GROUP BY pembelian_det.no_po, pembelian_det.id_barang
			ORDER BY pembelian_det.id_barang ASC
			LIMIT $limit OFFSET $offset
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function getlocation($con)
	{	
		//if latitude and longitude are submitted
		if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
			//send request and receive json data by latitude and longitude
			$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
			$json = @file_get_contents($url);
			$data = json_decode($json);
			$status = $data->status;
			
			//if request status is successful
			if($status == "OK"){
				//get address from json data
				$location = $data->results[0]->formatted_address;
			}else{
				$location =  '';
			}
			
			//return address to ajax 
			echo $location;
		}
	}

	function getmaxbarang($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["barang.id_barang", "barang.barcode", "barang.nama"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];
		
		if(!empty($_POST["from"]) && !empty($_POST["to"])){	
			$whereFilter .= " AND penjualan.tanggal BETWEEN '".$_POST["from"]."' AND '".$_POST["to"]."'";
		}

		$result = mysqli_query($con, "
			SELECT
				barang.id_barang,
				barang.barcode,
				barang.nama,
				COUNT(penjualan.id_barang) total_penjualan
			FROM barang
			LEFT JOIN (
				SELECT penjualan_det.*, penjualan.tanggal
				FROM penjualan_det
				JOIN penjualan ON penjualan.no_faktur = penjualan_det.no_faktur
			) penjualan ON penjualan.id_barang = barang.id_barang
			WHERE 1=1 $whereFilter
			GROUP BY barang.id_barang
			ORDER BY COUNT(penjualan.id_barang) DESC, barang.id_barang
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT
				barang.id_barang
			FROM barang
			LEFT JOIN (
				SELECT penjualan_det.*, penjualan.tanggal
				FROM penjualan_det
				JOIN penjualan ON penjualan.no_faktur = penjualan_det.no_faktur
			) penjualan ON penjualan.id_barang = barang.id_barang
            WHERE 1=1 $whereFilter
			GROUP BY barang.id_barang
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}

	function getmaxpelanggan($con)
	{	
		$search = $_POST["search"];
		
		$q_src = "";
		if(!empty($search["value"])){
			$col = ["pelanggan.id_pelanggan", "pelanggan.nama"];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			foreach($col as $key => $val){
				if($key == 0) {
					$q_src .= "(";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}else{
					$q_src .= " OR (";
					foreach($src_arr as $k => $v){
						if($k == 0) {
							$q_src .= "$val LIKE '%$v%'"; 
						}else{
							$q_src .= " AND $val LIKE '%$v%'";
						}
					}
					$q_src .= ")";
				}
			}
		}

		$whereFilter = "";
		if(!empty($q_src)){
			$whereFilter = "AND ($q_src)";
		}

		$limit = $_POST["length"];
		$offset = $_POST["start"];

		$whereJoin = "";
		if(!empty($_POST["from"]) && !empty($_POST["to"])){	
			$whereJoin .= " AND penjualan.tanggal BETWEEN '".$_POST["from"]."' AND '".$_POST["to"]."'";
		}

		$result = mysqli_query($con, "
			SELECT
				pelanggan.id_pelanggan,
				pelanggan.nama,
				COUNT(penjualan.no_faktur) total_penjualan
			FROM pelanggan
			LEFT JOIN penjualan ON penjualan.id_pelanggan = pelanggan.id_pelanggan $whereJoin
			WHERE 1=1 $whereFilter
            GROUP BY pelanggan.id_pelanggan
    		ORDER BY COUNT(penjualan.no_faktur) DESC, pelanggan.id_pelanggan
			LIMIT $limit OFFSET $offset
		");
		
		$data["data"] = [];
		while($row = mysqli_fetch_assoc($result)){
			$data["data"][] = $row;
		}

		$data["draw"] = intval($_POST["draw"]);

		$result_all = mysqli_query($con, "
			SELECT
				pelanggan.id_pelanggan
			FROM pelanggan
			LEFT JOIN penjualan ON penjualan.id_pelanggan = pelanggan.id_pelanggan $whereJoin
			WHERE 1=1 $whereFilter
            GROUP BY pelanggan.id_pelanggan
		");
		$data["recordsTotal"] = mysqli_num_rows($result_all);
		$data["recordsFiltered"] = mysqli_num_rows($result_all);
		
		echo json_encode($data);
	}
}
