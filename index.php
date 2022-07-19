<?php
require 'adm/config/connect.php';
require 'adm/config/function.php';

date_default_timezone_set('Asia/Jakarta');

// Data Toko
$tokos = mysqli_query($con, "SELECT * FROM toko WHERE id_toko = '1' LIMIT 1");
foreach ($tokos as $to){
	$toko = $to['nama_toko'];
	$ket = $to['ket_toko'];
	$alamat = $to['alamat_toko'];
	$kontak = $to['kontak_toko'];
}

// Kontak
if (substr($kontak,0,2)=='08'){
	$phone = '628'.substr($kontak,2);
} else {
	$phone = $kontak;
}
$order = 'https://wa.me/'.$phone.'?text=';

// Banners
$banners = mysqli_query($con, "SELECT * FROM banner ORDER BY order_no ASC LIMIT 5");
// Merk
$merks = mysqli_query($con, "SELECT name merk FROM merk ORDER BY name ASC");

// URI
$requri = REQURI;
$xp = explode('/',$requri);

$count_uri = substr_count($requri,'/');
if (ENV === "Development") {
	$count_uri -= 1;
	$xp = arr_remove_empty($xp);
}

// Routing
if (empty($xp[1]) OR (!empty($_GET["kategori"]) || !empty($_GET["merk"]) || !empty($_GET["cari"]) || !empty($_GET["page"]))){
	// Page Info
	$title = $toko.' - '.$ket;
	
	if(!empty($_GET["kategori"])){
		$paging = true;
		$px = "kategori";
		
		// Filter
		$arr_kat = explode(",", $_GET["kategori"]);
		$src = sprintf("%s", join("'|'",$arr_kat));
		$src = str_replace(["(",")"], "", $src);
		$src = str_replace(" ", "|", $src);

		// Pagination
		$limit = 24;
		$page = isset($_GET['page'])?(int)$_GET['page'] : 1;
		$first_page = ($page>1) ? ($page * $limit) - $limit : 0;

		// All Data
		$all_data = mysqli_query($con,"SELECT * FROM barang WHERE deleted = 0 AND kategori REGEXP '$src'");
		$total_data = mysqli_num_rows($all_data);
		$total_page = ceil($total_data / $limit);
		
		if($total_data > 0) {
			// Limited Data
			$posts = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 AND kategori REGEXP '$src' ORDER BY rand() LIMIT $first_page, $limit");
	
			$nvurl = SITEURL."?kategori=".$_GET["kategori"];
			
			include('pages/home.php');
		}else{
			include('pages/404.php');
		}
	
	}else if(!empty($_GET["merk"])){
		$paging = false;
		$px = "merk";

		$src = $_GET["merk"];

		$posts = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 AND merk = '$src' ORDER BY created" );
		
		$nvurl = SITEURL."?merk=".$_GET["merk"];
		
		if(mysqli_num_rows($posts) > 0){
			include('pages/home.php');
		}else{
			include('pages/404.php');
		}
	
	}else if(!empty($_GET["cari"])){
		$paging = false;
		$px = "cari";
		
		$src = $_GET["cari"];
		$multi_src = str_replace(' ', '%', $src);
		
		$posts = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 AND nama LIKE '%$src%' OR nama LIKE '%$multi_src%' OR nama SOUNDS LIKE '$src' ORDER BY created");
		
		$nvurl = SITEURL."?cari=".$_GET["cari"];
	
		if(mysqli_num_rows($posts) > 0){
			include('pages/home.php');
		}else{
			include('pages/404.php');
		}

	}else{
		$paging = true;

		// Pagination
		$limit = 24;
		$page = isset($_GET['page'])?(int)$_GET['page'] : 1;
		$first_page = ($page>1) ? ($page * $limit) - $limit : 0;

		// All Data
		$all_data = mysqli_query($con,"SELECT * FROM barang");
		$total_data = mysqli_num_rows($all_data);
		$total_page = ceil($total_data / $limit);
		
		// Limited Data
		$posts = mysqli_query($con, "SELECT * FROM barang deleted = 0 ORDER BY created DESC LIMIT $first_page, $limit");

		$nvurl = SITEURL;

		include('pages/home.php');
	
	}
} elseif($xp[1]==='produk') {
	$px = "produk";
	$posts = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 AND id_barang = '".$xp[2]."' LIMIT 1");

	if(mysqli_num_rows($posts) > 0){
		$row = mysqli_fetch_assoc($posts);
		$random = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 AND nama SOUNDS LIKE '".$row['nama']."' OR kategori = '".$row['kategori']."' ORDER BY rand() LIMIT 8");
	
		include('pages/post.php');
	}else{
		include('pages/404.php');
	}
} elseif($xp[1]==='tentang') {
	$px = $xp[1];
	include('pages/tentang.php');
} elseif($xp[1]==='pelayanan') {
	$px = $xp[1];
	include('pages/pelayanan.php');
} elseif($xp[1]==='garansi') {
	$px = $xp[1];
	include('pages/garansi.php');
} elseif($xp[1]==='info' || strpos($xp[1], 'info') !== false ) {
	$px = 'info';
	include('pages/info.php');
} else {
	include('pages/404.php');		
}


function head(){
	global $title,$merks,$px,$px2,$banners,$p;

	echo '
	<!DOCTYPE html>
		<html lang="id">
			<head>
				<meta charset="utf-8" />
				<meta name="author" content="Themezhub" />
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>'.$title.'</title>
				<link href="'.SITEURL.'/css/styles.css?v='.date('Ymdhis').'" rel="stylesheet">'.
				'<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>'.
				'<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>'.
				'<style>.slider .slick-slide img{border-radius:3px;}.slider .slick-slide img{width:100%;}.slick-prev,.slick-next{width:50px;height:50px;z-index:1;}.slick-prev{left:5px;}.slick-next{right:5px;}.slick-prev:before,.slick-next:before{font-size:40px;text-shadow:0 0 10px rgba(0,0,0,0.5);}.slick-dots{bottom:15px;}.slick-dots li button:before{font-size:12px;color:#fff;text-shadow:0 0 10px rgba(0,0,0,0.5);opacity:1;}.slick-dots li.slick-active button:before{color:#dedede;}.slider:not(:hover) .slick-arrow,.slider:not(:hover) .slick-dots{opacity:0;}.slick-arrow,.slick-dots{transition:opacity 0.5s ease-out;}</style>'.
			'</head>';

	$now_src = isset($_GET["cari"])? $_GET["cari"] : '';
	$header_type = ($px == "cari" || $px == "kategori" || $px == "merk")? 'header-on-top' : '';
	echo '<body><div class="preloader"></div><div id="main-wrapper" style="width: 100%; position: absolute; overflow-x: hidden;">'.
	'<div class="header header-transparent dark-text ' . $header_type . '"><div class="container"><nav id="navigation" class="navigation navigation-landscape">'.
	'<div class="nav-header"><div class="nav-brand"><a href="'.SITEURL.'/"><img src="'.SITEURL.'/images/icons/logo.png" class="logo" alt="" /></a></div>'.
	'<div class="mobile_nav"><ul>'.
	'<li><div class="badge bg-danger my-btn btn-login"> <a href="#" data-toggle="modal" data-target="#login">LOGIN <i class="lni lni-user"></i></a></div></li> '.
	'</ul></div></div>'.
	'<div class="nav-menus-wrapper" style="transition-property: none;">'.
	'<form class="form m-0 p-0" method="GET" action="'.SITEURL.'/">
			<div class="input-group">
				<input type="text" class="form-control" name="cari" placeholder="Aku mau cari..." value="'.$now_src.'">
				<div class="my-input-group-append">
					<button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
				</div>
			</div>
	</form>'.
	'<ul class="nav-menu nav-menu-social align-to-right">'.
	'<li><div class="bg-danger btn-login my-btn"> <a href="#" data-toggle="modal" data-target="#login">LOGIN <i class="lni lni-user"></i></a></div></li> '.
	'</ul></div></nav></div></div>';
	
	if ($px == "cari" || $px == "kategori" || $px == "merk") {
		echo '<div class="clearfix" style="margin-top:6vh;"></div>';
	}else{
		echo '<div class="clearfix"></div>';
	}

	echo '
		<div class="little-container mobile-only" style="margin-bottom: 3.5rem!important;">
			<form class="form m-0 p-0" method="GET" action="'.SITEURL.'/">
					<div class="input-group">
						<input type="text" class="form-control" name="cari" placeholder="Aku mau cari..." value="'.$now_src.'" style="font-size:13px">
						<div class="my-input-group-append">
							<button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</div>
			</form>
		</div>
	';
	echo '<div class="bg-cover"><div class="container">'.'<div class="row align-items-center justify-content-center"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><div class="text-center sld">';
	
	if ($px != "cari" && $px != "kategori" && $px != "merk"){
		echo '<div class="slider">';
		foreach ($banners as $ban){
			echo '<div><a href="#"><img class="img-fluid" src="'.SITEURL.'/banner/'.$ban["photo"].'" alt="Image 1"></a></div>';
		}
		echo '</div>';
	}

	echo '</div></div></div></div>';
	
	if ($px != "info" && $px != "tentang" && $px != "pelayanan" && $px != "garansi") {
		echo '
		<div class="container little-container mb-1" style="padding:0;">
			<div class="row" style="
				height: 100%;
				width: 100%;
				margin: 0;
			">
				<div class="container-badge col-lg-3 little-4 text-center">
					<a href="'.SITEURL.'/info?active=pelayanan" class="my-badge-nav">
						<i class="fa fa-exclamation-circle mr-lg-2 mr-1"></i> <span>PERATURAN PELAYANAN</span>
					</a>
				</div>
				<div class="container-badge col-lg-3 little-4 text-center">
					<a href="'.SITEURL.'/info?active=garansi" class="my-badge-nav">
						<i class="fa fa-medal mr-lg-2 mr-1"></i> <span>GARANSI</span>
					</a>
				</div>
				<div class="container-badge col-lg-3 little-4 text-center">
					<a href="'.SITEURL.'/info?active=tentang" class="my-badge-nav">
						<i class="fa fa-users mr-lg-2 mr-1"></i> <span>TENTANG KAMI</span>
					</a>
				</div>
				<div class="container-badge col-lg-3 little-4 text-center">
					<a href="#!" class="my-badge-nav" data-toggle="modal" data-target="#joinus">
						<i class="fa fa-user-plus mr-lg-2 mr-1"></i> <span>JOIN US</span>
					</a>
				</div>
			</div>
		</div>
		';
	}
	
	if ($px != "cari" && $px != "kategori" && $px != "merk" && $px != "produk" && $px != "tentang" && $px != "pelayanan" && $px != "garansi" && $px != "info"){
		echo '<div class="middle"><div class="container"><div class="row align-items-center product-container">';
		$cats = array('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','KOPLING','PISTON', 'GEARBOX', 'MEMBRAN', 'INTAKE MANIPOL', 'BUSI', 'VARIASI', 'PAKING (GASKET)', 'BEARING', 'SPECIAL DISKON');
		$war = array('purple','red','blue','green','orange','yellow','dark-blue', 'danger','sky','dark-blue', 'purple','red','blue','green','orange', 'yellow', 'dark-blue', 'danger');
		$i = 0;
		foreach ($cats as $c){
			if (!empty($_GET['kategori'])) {
				$search = explode(",", $_GET['kategori']);

				if(!in_array(strtolower($c), $search)){
					$search[] = strtolower($c);
				}

			}else{
				$search = [strtolower($c)];
			}

			$select_cat = ($px === "kategori" && strtoupper($px2) === $c)? 'select-border' : '';
			echo '<div class="little-4 col-lg-2 col-md-4 cat"><div class="product_grid card '. $select_cat .'">'.
			'<div class="card-body p-0"><div class="shop_thumb position-relative"><a class="card-img-top d-block overflow-hidden" href="'.SITEURL.'?kategori='.implode(",", $search).'"><img class="card-img-top" src="'.SITEURL.'/images/kategori/'.strtolower(str_replace(' ','-',$c)).'.jpeg"></a></div></div>'.
			'<div class="badge bg-'.$war[$i].' py-2"><div class="text-white">'.$c.'</div></div>'.
			'</div></div>';
			$i++;
		}
		echo '</div></div></div>';
	} else {}
}

function foot(){
	global $px,$alamat,$kontak,$merks,$toko,$ket,$con;

	if($px != "produk" && $px !="tentang" && $px !="pelayanan" && $px !="garansi" && $px != "info"){
		$colors = ['purple','red','blue','green','orange','yellow','dark-blue','sky'];
	
		echo '<div class="container mb-4 container-merk">';
			foreach ($merks as $key => $ban){
				echo '<a class="my-badge-filter bg-'.$colors[$key%8].'" href="'.SITEURL.'?merk='.$ban["merk"].'">'.$ban["merk"].'</a>';
			}
		echo'</div>';
	}

	echo '<footer class="dark-footer skin-dark-footer style-2"><div class="footer-middle"><div class="container"><div class="row">'.
	'<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12"><div class="footer_widget">'.
	'<div class="mb-4">'.
	'<img class="mb-3" src="'.SITEURL.'/images/icons/logo.png" style="width: 217px;" alt="" />'.
	'<h5 style="color: white;font-weight: bold;text-decoration: underline;">Under Management:</h5>'.
	'<img src="'.SITEURL.'/images/knalpot-racing.png" style="width: 217px;" alt="" />'.
	'</div>'.
	'<div class="address mt-3">';

	$socmed = mysqli_query($con, "SELECT * FROM socmed ORDER BY id ASC");
	foreach ($socmed as $row){
		echo '<div class="address mt-3"><i class="lni lni-'.strtolower($row['tipe']).'"></i> <a href="'.$row['link'].'">'.$row['keterangan'].'</a></div>';
	}
	
	echo '</div>'.
	'</div></div>'.
	'<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">';
	echo '<div id="kontak" class="footer_widget"><h4 class="widget_title">KONTAK KAMI</h4><ul>';
	
	$kontak = mysqli_query($con, "SELECT * FROM kontak WHERE letak = 'footer' AND aktif = 1 ORDER BY id ASC");
	foreach ($kontak as $row){
		echo "<li>".$row['keterangan']." : ".$row['kontak']."</li>";
	}

	echo '</ul></div></div>';
	
	echo '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12"><div id="alamat" class="footer_widget"><h4 class="widget_title">ALAMAT</h4>'.
	'<div class="address mt-3">
		<iframe style="width: 100%;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.004731264543!2d106.91843581423603!3d-6.130064361809112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a218a0a0405e7%3A0x8562ade3b663c212!2sPapah%20Racing!5e0!3m2!1sen!2sid!4v1640485094031!5m2!1sen!2sid" 
			width="270" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
	</div>'.
	'<div class="address mt-3">'.str_replace("\n",'<br/>',$alamat).'</div>'.
	'</div></div></div></div></div>'.
	'</div>'.
	'<div class="footer-bottom"><div class="container"><div class="row align-items-center"><div class="col-lg-12 col-md-12 text-center"><p class="mb-0">© 2021 '.$toko.' - '.$ket.'.</p></div></div></div></div>'.'</footer>';
			
	/* ------------------------------- */
			
	echo '<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
		<div class="modal-dialog modal-xl login-pop-form" role="document">
			<div class="modal-content" id="loginmodal">
				<div class="modal-headers">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span class="ti-close"></span>
					</button>
				</div>
				<div class="modal-body p-5">
					<div class="text-center mb-4">
						<h2 class="m-0 ft-regular">Login</h2>
					</div>
					<form action="'.SITEURL.'/adm/process/action?url=login" method="post">			 
						<div class="form-group">
							<label>User Name</label>
							<input type="text" class="form-control" placeholder="Username*" name="username">
						</div>
						<div class="form-group">
							<label>Password</label>
							<div class="input-group">
								<input type="password" class="form-control" placeholder="Password*" name="password" id="password" data-toggle="password">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fa fa-eye"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-1">
									<input id="dd" class="checkbox-custom" name="dd" type="checkbox">
									<label for="dd" class="checkbox-custom-label">Remember Me</label>
								</div>
								<div class="eltio_k2">
									<a href="#">Lost Your Password?</a>
								</div>	 
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button>
						</div>
						<div class="form-group text-center mb-0">
							<p class="extra">Not a member?<a href="#et-register-wrap" class="text-dark"> Register</a></p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>';
	
	echo '
	<div class="modal fade" id="joinus" tabindex="-1" role="dialog" aria-labelledby="joinusmodal" aria-hidden="true">
		<div class="modal-dialog modal-xl joinus-pop-form" role="document">
			<div class="modal-content" id="joinusmodal">
				<div class="modal-headers">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span class="ti-close"></span>
					</button>
				</div>
				<div class="modal-body p-lg-4">
					<div class="text-center mb-4">
						<h2 class="m-0 ft-bold"><i class="fa fa-user-plus mr-2"></i> JOIN US</h2>
					</div>'.
					'<div class="form-group mb-4">'.
						'<h3>Pedagang</h3>'.
						'<p>Kami memberikan akses login untuk member pedagang mengetahui semua jumlah stok ditoko kita dan membaginya menjadi 3 harga yaitu distributor, 
							reseller dan HET (Harga Eceran Tertinggi). 
							Jadi member bisa membeli dan menjual dengan bisa mengetahui kapasitas keuntungan yang di dapat.</p>'.
						'<p><a href="https://wa.me/6281385595027?text='.urlencode('Saya join sebagai Pedagang').'" class="btn badge bg-success my-btn btn-join">JOIN</a></p>'.
						'<h3 style="margin-top:2rem">Penjual</h3>'.
						'<p>Anda produsen/importir/APM???</p>'.
						'<p>Kami menyiapkan Team untuk membantu anda menjual produk dengan cara yang lebih efisien karena kami sudah memiliki banyak agen distributor dan reseller dan kami juga memiliki admin yang sudah siap promosikan produk anda.</p>'.
						'<p><a href="https://wa.me/6281385595027?text='.urlencode('Saya join sebagai Penjual').'" class="btn badge bg-success my-btn btn-join">JOIN</a></p>'.
					'</div>'.
				'</div>
			</div>
		</div>
	</div>';
	
	echo '<div class="fab-collection">
		<div id="open24h-tooltip" class="tooltip bs-tooltip-top my-tooltip show" role="tooltip" style="display:none">
			<div class="arrow" style="left: 11rem;"></div>
			<div class="my-tooltip-inner">
				Kita buka service 24 jam .
				Silahkan order melalui whatsapp
				di bawah ini. Semua pengiriman
				dilakukan 3 kali dalam sehari.
				Sehingga memudahkan
				konsumen lebih cepat menerima
				barang
			</div>
		</div>
		<a id="open24h" href="#!">
			<img src="'.SITEURL.'/images/24h.png" />
		</a>
		<a id="what" title="Whatsapp" target="_blank" href="https://wa.me/6281329763463?text="><i class="lni lni-whatsapp"></i></a>
		<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
	</div>';
	echo '</div>';
	echo '<script src="'.SITEURL.'/js/jquery.min.js"></script>'.
	'<script src="'.SITEURL.'/js/popper.min.js"></script>'.
	'<script src="'.SITEURL.'/js/bootstrap.min.js"></script>'.
	'<script src="'.SITEURL.'/js/ion.rangeSlider.min.js"></script>'.
	'<script src="'.SITEURL.'/js/slick.js"></script>'.
	'<script src="'.SITEURL.'/js/slider-bg.js"></script>'.
	'<script src="'.SITEURL.'/js/lightbox.js"></script> '.
	'<script src="'.SITEURL.'/js/smoothproducts.js"></script>'.
	'<script src="'.SITEURL.'/js/snackbar.min.js"></script>'.
	'<script src="'.SITEURL.'/js/jQuery.style.switcher.js"></script>'.
	'<script src="'.SITEURL.'/js/custom.js"></script>'.
	'<script src="'.SITEURL.'/js/bootstrap-show-password.min.js"></script>';
	echo '<script>
			$(document).ready(function(){
				$(\'.slider\').slick({
					autoplay: true,
					autoplaySpeed: 2500,
					dots: true
				});

				$("#open24h").click(() => {
					$("#open24h-tooltip").fadeIn();
				});

				$("#open24h").mouseover(() => {
					$("#open24h-tooltip").fadeIn();
				});

				$("#open24h").mouseleave(() => {
					$("#open24h-tooltip").fadeOut();
				});

				$(".my-badge-filter").each(function(){
					var el= $(this);
					var textLength = el.html().length;
						if (textLength > 18) {
							el.css("font-size", "0.44rem");
						}
				});
			});

			function openSearch() { 
				document.getElementById("Search").
					style.display = "block"; 
			} 
			
			function closeSearch() { 
				document.getElementById("Search").
					style.display = "none";
			}
		</script>'.
	'</body></html>';
}
?>