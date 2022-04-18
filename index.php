<?php

define('N',$_SERVER['SERVER_NAME']);
define('A',$_SERVER['SERVER_ADDR']);
define('ROOT',dirname(__FILE__));
define('PHOTO',ROOT.'/p');
define('SITEURL','https://'.N);
define('TEMA',SITEURL.'/tema');

$_fb = '';
$_yt = '';
$_tw = '';
$_in = '';
$_pi = '';

date_default_timezone_set('Asia/Jakarta');
$dbhost = 'localhost';
$dbuser = 'u1039423_andre';
$dbpass = 'Papahracing2000';
$dbname = 'u1039423_papahracing';
$db_fc = "mysql:dbname=$dbname;host=$dbhost";
try {
    $db = new PDO($db_fc, $dbuser, $dbpass);
} catch (PDOException $e) {
    echo $e->getMessage(), ' in your website. <strong>Connection Failed</strong>';
    die();
}
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$tokos = mysqli_query($con, "SELECT * FROM toko WHERE id_toko = '1' LIMIT 1");
foreach ($tokos as $to){
	$toko = $to['nama_toko'];
	$ket = $to['ket_toko'];
	$alamat = $to['alamat_toko'];
	$kontak = $to['kontak_toko'];
}

$mereks = mysqli_query($con, "SELECT merk FROM barang ORDER BY stok DESC");
$merks = array();
foreach ($mereks as $m){
	$merks[] = trim($m['merk']);
}
$merks = array_unique($merks);

if (substr($kontak,0,2)=='08'){
	$phone = '628'.substr($kontak,2);
} else {
	$phone = $kontak;
} 
$order = 'https://wa.me/'.$phone.'?text=';

/* 
$now = date('Y-m-d h:i:s');
$start = date('Y-m').'-01 00:00:00';
echo $start;
$qq = mysqli_query($con, "SELECT * FROM penjualan WHERE tanggal > '".$start."' ORDER BY id_pelanggan DESC LIMIT 20");
$arr = array();
foreach ($qq as $q){
 $arr[$q['id_pelanggan']] += $q['total_bayar'];  
 echo $q['id_pelanggan'].' : '.$q['total_bayar'].'<br/>';
}
print_r($arr);
*/
if (isset($_GET['sort'])){
	$s = $_GET['sort'];
	header("Location: ".SITEURL.'/sort/'.$s.'/');
	exit;
} elseif (isset($_GET['merk'])){
	$s = $_GET['merk'];
	header("Location: ".SITEURL.'/merk/'.$s.'/');
	exit;
} else {}

$pp = 24;
$requri = $_SERVER['REQUEST_URI'];
$xp = explode('/',$requri);

$count = mysqli_query($con, "SELECT * FROM barang WHERE stok > 0 AND het > 0");
$total = mysqli_num_rows($count);
$per = ceil($total/$pp);
$bc = '';
$ttl = $toko.' - '.$ket;
$px = '';
if (substr_count($requri,'/')==1){
	if (empty($xp[1])){
		$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY created DESC LIMIT ".$pp);
		$title = $toko.' - '.$ket;
		$page = 1;
		$nvurl = SITEURL;
		include('tema/home.php');
	} elseif (substr($xp[1],0,3)=='?q='){
		header("Location: ".SITEURL.'/cari/'.str_replace('?q=','',$xp[1].'/'));
		exit;
	} else {
		include('tema/404.php');		
	}
} elseif (substr_count($requri,'/')==3||substr_count($requri,'/')==5){
	$px = $xp[1];
	$p = $xp[2];
	if ($px!=='sort'){
		$nvurl = SITEURL.'/'.$px.'/'.$p;
	}
	if (isset($xp[5])){
		if (empty($xp[5])){
			$p3 = $xp[3];$p4 = $xp[4];$p5 = $xp[5];
		} else {
			include('tema/404.php');
		}
	} else {
		$p3 = '';$p4 = '';$p5 = '';
	}
	if ($p3=='page'){
		$l = intval($p4);
	} else {
		$l = 1;
	}
	if (empty($p3)||$p3=='page'){
		if ($px=='produk'){
			$posts = mysqli_query($con, "SELECT * FROM barang WHERE id_barang = '".$p."' LIMIT 1");
			$random = mysqli_query($con, "SELECT * FROM barang ORDER BY created DESC LIMIT 8");
			include('tema/post.php');
		} elseif ($px=='page'){
			$s = (($p-1)*$pp)+1;
			if(is_numeric($p)&&strlen($p)==3){
				$p = intval($p);
				$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY created DESC LIMIT ".$s.",".$pp);
				// $total = mysqli_num_rows($posts);
				// $per = ceil($total/$pp);	
				$page = $p;
				$ttl = 'Page '.$p;
				$nvurl = SITEURL;
				$title = 'Page : '.$p.' - '.$ket;
				include('tema/home.php');
			} else {
				include('tema/404.php');
			}
		} elseif ($px=='merk'){
			$total = mysqli_num_rows(mysqli_query($con, "SELECT * FROM barang WHERE merk = '".$p."'"));
			$posts = mysqli_query($con, "SELECT * FROM barang WHERE merk = '".$p."' LIMIT ".$l.",".$pp );
			$per = ceil($total/$pp);	
			$ttl = 'Page '.$l;
			$bc = '<li class="breadcrumb-item"><a href="'.SITEURL.'/merk/'.$b.'/">'.ucwords($p).'</a></li>';
			if ($total>0){
				$title = 'Brand : '.ucwords($p).' ['.$total.']';
				include('tema/home.php');
			} else {
				include('tema/404.php');
			}
		} elseif ($px=='kategori'){
			$total = mysqli_num_rows(mysqli_query($con, "SELECT * FROM barang WHERE kategori = '".$p."'"));
			$posts = mysqli_query($con, "SELECT * FROM barang WHERE kategori = '".$p."' LIMIT ".$l.",".$pp );
			$per = ceil($total/$pp);	
			if ($total>0){
				$bc = '<li class="breadcrumb-item"><a href="'.SITEURL.'/kategori/'.$p.'/">'.ucwords($p).'</a></li>';
				$ttl = 'Page '.$l;
				$title = 'Kategori : '.ucwords($p).' ['.$total.']';
				include('tema/home.php');
			} else {
				include('tema/404.php');
			}
		} elseif ($px=='cari'){
			// $posts = mysqli_query($con, "SELECT * FROM barang WHERE nama LIKE '%".$p."%'");
			$posts = mysqli_query($con, "SELECT * FROM barang WHERE nama IN (".str_replace(' ',',',trim($p)).")");
			$total = mysqli_num_rows($posts);
			$per = ceil($total/$pp);
			$bc = '';
			$ttl = ucwords($p);
			$title = 'Search : '.ucwords($p).' ('.$total.')';
			if ($total>0){
				include('tema/home.php');
			} else {
				include('tema/404.php');
			}
		} elseif ($px=='sort'&&empty($p3)){
			if ($p=='harga-asc'){
				$ttl = 'Harga Terendah';
				$title = 'Harga Terendah - '.$toko;
				$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY het != 0 ASC LIMIT ".$pp);
				include('tema/home.php');
			} elseif ($p=='harga-desc'){
				$ttl = 'Harga Tertinggi';
				$title = 'Harga Tertinggi - '.$toko;
				$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY het DESC LIMIT ".$pp);
				include('tema/home.php');
			} elseif ($p=='stok-asc'){
				$ttl = 'Stok Paling Sedikit';
				$title = 'Stok Paling Sedikit - '.$toko;
				$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY stok ASC LIMIT ".$pp);
				include('tema/home.php');
			} elseif ($p=='stok-desc'){
				$ttl = 'Stok Paling Banyak';
				$title = 'Stok Paling Banyak - '.$toko;
				$posts = mysqli_query($con, "SELECT * FROM barang ORDER BY stok DESC LIMIT ".$pp);
				include('tema/home.php');
			} else {			
				include('tema/404.php');
			}
		} else {
			include('tema/404.php');		
		}
	} else {
		include('tema/404.php');		
	}
} else {
	include('tema/404.php');
}

function rp($str){
	$jum = strlen($str);
	$jumtitik = ceil($jum / 3);
	$balik = strrev($str);
	$awal = 0;
	$akhir = 3;
	for ($x = 0; $x < $jumtitik; $x++) {
		$a[$x] = substr($balik, $awal, $akhir) . ".";
		$awal += 3;
	}
	$hasil = implode($a);
	$hasilakhir = strrev($hasil);
	$hasilakhir = substr($hasilakhir, 1, $jum + $jumtitik);
	return "Rp. " . $hasilakhir . "";
}

function head(){
	global $title,$merks,$px;
	echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8" /><meta name="author" content="Themezhub" /><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.$title.'</title><link href="'.TEMA.'/styles.css?v='.date('Ymdhis').'" rel="stylesheet">'.
	'<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>'.
	'<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>'.
	'<style>.slider .slick-slide img{border-radius:3px;}.slider .slick-slide img{width:100%;}.slick-prev,.slick-next{width:50px;height:50px;z-index:1;}.slick-prev{left:5px;}.slick-next{right:5px;}.slick-prev:before,.slick-next:before{font-size:40px;text-shadow:0 0 10px rgba(0,0,0,0.5);}.slick-dots{bottom:15px;}.slick-dots li button:before{font-size:12px;color:#fff;text-shadow:0 0 10px rgba(0,0,0,0.5);opacity:1;}.slick-dots li.slick-active button:before{color:#dedede;}.slider:not(:hover) .slick-arrow,.slider:not(:hover) .slick-dots{opacity:0;}.slick-arrow,.slick-dots{transition:opacity 0.5s ease-out;}</style>'.
	'</head>';



	echo '<body><div class="preloader"></div><div id="main-wrapper">'.
	'<div class="header header-transparent dark-text"><div class="container"><nav id="navigation" class="navigation navigation-landscape">'.
	'<div class="nav-header"><a class="nav-brand" href="'.SITEURL.'/"><img src="'.SITEURL.'/images/icons/logo.png" class="logo" alt="" /></a>'.
	// '<div class="nav-toggle"></div>'.
	'<form method="GET" action="https://papahracing.com/" class="scl form m-0 p-0"><div class="form-group"><input type="text" class="form-control" name="q" placeholder="Product Keyword.."></div></form>'.
	'<div class="mobile_nav"><ul>'.
	// '<li><a href="#" onclick="openSearch()"><i class="lni lni-search-alt"></i></a></li>'.
	'<li><div class="join"><a href="#" data-toggle="modal" data-target="#joinus">JOIN US</a></div></li>'.
	'<li><div class="loin"> <a href="#" data-toggle="modal" data-target="#login"> LOGIN</a></div></li> '.
	'</ul></div></div>'.
	'<div class="nav-menus-wrapper" style="transition-property: none;">'.
	'<form class="form m-0 p-0" method="GET" action="'.SITEURL.'/"><div class="form-group"><input type="text" class="form-control" name="q" placeholder="Product Keyword.." /></div></form>'.
	/* '<ul class="nav-menu">'.
	'<li><a href="'.SITEURL.'/">HOME</a></li>'.
	'<li><a href="#joinus">JOIN US</a></li>'.
	'<li><a href="#alamat">ALAMAT</a></li>'.
	'<li><a href="#kontak">KONTAK</a></li>'.
	'</ul>'. */
	'<ul class="nav-menu nav-menu-social align-to-right">'.
	// '<li><a href="#" onclick="openSearch()"><i class="lni lni-search-alt"></i></a></li>'.
	'<li><div class="badge bg-success login"> <a href="#" data-toggle="modal" data-target="#joinus">JOIN US <i class="lni lni-users"></i></a></div></li>'.
	'<li><div class="badge bg-danger login"> <a href="#" data-toggle="modal" data-target="#login">LOGIN <i class="lni lni-user"></i></a></div></li> '.
	'</ul></div></nav></div></div>';
		
	echo '<div class="clearfix"></div>';
	// style="background:url('.SITEURL.'/images/ls.jpg) no-repeat;"	
	echo '<div class="bg-cover"><div class="container">'.'<div class="row align-items-center justify-content-center"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><div class="text-center sld">';
	echo '<div class="slider">';
	$ims = range(1,5);
	foreach ($ims as $s){
		echo '<div><a href="#"><img src="'.SITEURL.'/tema/i/s'.$s.'.jpeg" alt="Image 1"></a></div>';
	}
	echo '</div>';
	/* '<h1 class="ft-medium mb-3">'.$title.'</h1><ul class="shop_categories_list m-0 p-0">';
	$i = 1;
	foreach ($merks as $m){
		if ($i>9){} else {
			echo '<li><a href="'.SITEURL.'/merk/'.strtolower($m).'/">'.$m.'</a></li>';
		} $i++;
	}
	echo '</ul></div>'; */
	echo '</div></div></div></div>';
	// echo '<section class="py-2 br-bottom br-top"><div class="container"><div class="row align-items-center justify-content-between"><div class="col-xl-3 col-lg-4 col-md-5 col-sm-12"><nav aria-label="breadcrumb"><h2 class="off_title">KATEGORI</h2></nav></div></div></div>';
	
	if (empty($px)){
		echo '<div class="middle"><div class="container"><div class="row align-items-center">';
		$cats = array('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','KOPLING','PISTON');
		$war = array('purple','red','blue','green','orange','yellow','dark-blue','danger','sky','dark-blue');
		$i = 0;
		foreach ($cats as $c){
			echo '<div class="col-lg-3 col-md-4 cat"><div class="product_grid card">'.
			'<div class="card-body p-0"><div class="shop_thumb position-relative"><a class="card-img-top d-block overflow-hidden" href="'.SITEURL.'/kategori/'.strtolower(str_replace(' ','-',$c)).'/"><img class="card-img-top" src="'.TEMA.'/i/'.strtolower(str_replace(' ','-',$c)).'.jpeg"></a></div></div>'.
			'<div class="badge bg-'.$war[$i].' py-2"><div class="text-white">'.$c.'</div></div>'.
			'</div></div>';
			$i++;
		}
		echo '</div></div></div>';
	} else {}
}

function foot(){
	global $alamat,$kontak,$merks,$toko,$ket;
	// echo '<section class="px-0 py-3 br-top"><div class="container"><div class="row"><div class="col-xl-3 col-lg-3 col-md-6 col-sm-6"><div class="d-flex align-items-center justify-content-start py-2"><div class="d_ico"><i class="fas fa-shopping-basket"></i></div><div class="d_capt"><h5 class="mb-0">Free Shipping</h5><span class="text-muted">Capped at $10 per order</span></div></div></div><div class="col-xl-3 col-lg-3 col-md-6 col-sm-6"><div class="d-flex align-items-center justify-content-start py-2"><div class="d_ico"><i class="far fa-credit-card"></i></div><div class="d_capt"><h5 class="mb-0">Secure Payments</h5><span class="text-muted">Up to 6 months installments</span></div></div></div><div class="col-xl-3 col-lg-3 col-md-6 col-sm-6"><div class="d-flex align-items-center justify-content-start py-2"><div class="d_ico"><i class="fas fa-shield-alt"></i></div><div class="d_capt"><h5 class="mb-0">15-Days Returns</h5><span class="text-muted">Shop with fully confidence</span></div></div></div><div class="col-xl-3 col-lg-3 col-md-6 col-sm-6"><div class="d-flex align-items-center justify-content-start py-2"><div class="d_ico"><i class="fas fa-headphones-alt"></i></div><div class="d_capt"><h5 class="mb-0">24x7 Fully Support</h5><span class="text-muted">Get friendly support</span></div></div></div></div></div></section>';
			
	echo '<footer class="dark-footer skin-dark-footer style-2"><div class="footer-middle"><div class="container"><div class="row">'.
	'<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12"><div class="footer_widget"><img src="'.SITEURL.'/images/icons/logo.png" class="img-footer small mb-2" alt="" />'.
	// '<div class="address mt-3">'.str_replace("\n",'<br/>',$alamat).'</div>'.
	// '<div class="address mt-3"><i class="lni lni-whatsapp"></i> '.$kontak.'</div>'.
	'<div class="address mt-3">'.
	'<div class="address mt-3"><i class="lni lni-instagram"></i> <a href="https://www.instagram.com/papahracingspeedshop/">Papah Racing Speedshop</a></div>'.
	'<div class="address mt-3"><i class="lni lni-instagram-filled"></i> <a href="https://www.instagram.com/knalpot_racing.com_speedshop/">Knalpot Racing Speedshop</a></div>'.
	'<div class="address mt-3"><i class="lni lni-youtube"></i> <a href="https://www.youtube.com/channel/UCOKpjvUiNM-em4j7-_4XxlA/">Knalpot Racing Speedshop</a></div>'.
	// '<ul class="list-inline"><li class="list-inline-item"><a href="'.$_fb.'" rel="nofollow"><i class="lni lni-facebook-filled"></i></a></li><li class="list-inline-item"><a href="'.$_tw.'" rel="nofollow"><i class="lni lni-twitter-filled"></i></a></li><li class="list-inline-item"><a href="'.$_yt.'" rel="nofollow"><i class="lni lni-youtube"></i></a></li><li class="list-inline-item"><a href="'.$_in.'" rel="nofollow"><i class="lni lni-instagram-filled"></i></a></li><li class="list-inline-item"><a href="'.$_pi.'" rel="nofollow"><i class="lni lni-pinterest-filled"></i></a></li></ul>'.
	'</div>'.
	'</div></div>'.
	'<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">';
	/* echo '<div class="footer_widget brand"><h4 class="widget_title">Brand</h4>';
	$i = 1;shuffle($merks);
	foreach ($merks as $m){
	if ($i>32){} else {
	echo '<a href="'.SITEURL.'/merk/'.strtolower($m).'/">'.$m.'</a> ';
	} $i++;
	}
	echo '</div></div>'; */
	echo '<div id="kontak" class="footer_widget"><h4 class="widget_title">KONTAK KAMI</h4><ul>';
	echo '<li>Order via website : 081329763463</li>'.
	'<li>Order via instagram : 082124118766</li>'.
	'<li>Info expedisi partai : 081385595070</li>'.
	'<li>Info resi dan pengiriman : 081385595013 / 081385595085</li>'.
	'<li>Manager : 081385595027</li>'.
	'<li>Founder : 087877481465</li>';
	echo '</ul></div></div>';
	
	echo '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12"><div id="alamat" class="footer_widget"><h4 class="widget_title">ALAMAT</h4>'.
	'<div class="address mt-3"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.004731264543!2d106.91843581423603!3d-6.130064361809112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a218a0a0405e7%3A0x8562ade3b663c212!2sPapah%20Racing!5e0!3m2!1sen!2sid!4v1640485094031!5m2!1sen!2sid" width="270" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>'.
	'<div class="address mt-3">'.str_replace("\n",'<br/>',$alamat).'</div>'.
	'</div></div></div></div></div>'.
	'</div>'.
	'<div class="footer-bottom"><div class="container"><div class="row align-items-center"><div class="col-lg-12 col-md-12 text-center"><p class="mb-0">© 2021 '.$toko.' - '.$ket.'.</p></div></div></div></div>'.'</footer>';
			
	/* ------------------------------- */
			
	echo '<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true"><div class="modal-dialog modal-xl login-pop-form" role="document"><div class="modal-content" id="loginmodal"><div class="modal-headers"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"></span></button></div><div class="modal-body p-5"><div class="text-center mb-4"><h2 class="m-0 ft-regular">Login</h2></div><form action="https://admin.papahracing.com/process/action?url=login" method="post">			 <div class="form-group"><label>User Name</label><input type="text" class="form-control" placeholder="Username*" name="username"></div><div class="form-group"><label>Password</label><input type="password" class="form-control" placeholder="Password*" name="password"></div><div class="form-group"><div class="d-flex align-items-center justify-content-between"><div class="flex-1"><input id="dd" class="checkbox-custom" name="dd" type="checkbox"><label for="dd" class="checkbox-custom-label">Remember Me</label></div><div class="eltio_k2"><a href="#">Lost Your Password?</a></div>	 </div></div><div class="form-group"><button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button></div><div class="form-group text-center mb-0"><p class="extra">Not a member?<a href="#et-register-wrap" class="text-dark"> Register</a></p></div></form></div></div></div></div>';
	
	echo '<div class="modal fade" id="joinus" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true"><div class="modal-dialog modal-xl login-pop-form" role="document"><div class="modal-content" id="loginmodal"><div class="modal-headers"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"></span></button></div><div class="modal-body p-5"><div class="text-center mb-4"><h2 class="m-0 ft-regular">JOIN US</h2></div>'.
	'<div class="form-group">'.
	'<h3>Pedagang</h3>'.
	'<p>Kami memberikan akses login untuk member pedagang mengetahui semua jumlah stok ditoko kita dan membaginya menjadi 3 harga yaitu distributor, reseller dan HET (Harga Eceran Tertinggi). Jadi member bisa membeli dan menjual dengan bisa mengetahui kapasitas keuntungan yang di dapat.</p>'.
	'<p><a href="https://wa.me/6281385595027?text='.urlencode('Saya join sebagai Pedagang').'" class="badge bg-success login">JOIN</a></p>'.
	'<h3>Penjual</h3>'.
	'<p>Anda produsen/importir/APM???</p>'.
	'<p>Kami menyiapkan Team untuk membantu anda menjual produk dengan cara yang lebih efisien karena kami sudah memiliki banyak agen distributor dan reseller dan kami juga memiliki admin yang sudah siap promosikan produk anda.</p>'.
	'<p><a href="https://wa.me/6281385595027?text='.urlencode('Saya join sebagai Penjual').'" class="badge bg-success login">JOIN</a></p>'.
	'</div>'.
	'</div></div></div></div>';
	
	/* echo '<div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;" id="Search">'.
	'<div class="rightMenu-scroll"><div class="d-flex align-items-center justify-content-between slide-head py-3 px-3"><h4 class="cart_heading fs-md ft-medium mb-0">Search Products</h4><button onclick="closeSearch()" class="close_slide"><i class="ti-close"></i></button></div>'.
	'<div class="cart_action px-3 py-4"><form class="form m-0 p-0" method="GET" action="'.SITEURL.'/"><div class="form-group"><input type="text" class="form-control" name="q" placeholder="Product Keyword.." /></div><div class="form-group mb-0"><input type="submit" class="btn d-block full-width btn-dark">Search Product</div></form></div>'.
	'</div></div>'; */
	
	echo '<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>';
	echo '<a id="what" class="top-scroll" title="Back to top" target="_blank" href="https://wa.me/6281329763463?text="><i class="lni lni-whatsapp"></i></a>';
	echo '</div>';
	echo '<script src="'.TEMA.'/jquery.min.js"></script>'.
	'<script src="'.TEMA.'/popper.min.js"></script>'.
	'<script src="'.TEMA.'/bootstrap.min.js"></script>'.
	'<script src="'.TEMA.'/ion.rangeSlider.min.js"></script>'.
	'<script src="'.TEMA.'/slick.js"></script>'.
	'<script src="'.TEMA.'/slider-bg.js"></script>'.
	'<script src="'.TEMA.'/lightbox.js"></script> '.
	'<script src="'.TEMA.'/smoothproducts.js"></script>'.
	'<script src="'.TEMA.'/snackbar.min.js"></script>'.
	'<script src="'.TEMA.'/jQuery.style.switcher.js"></script>'.
	'<script src="'.TEMA.'/custom.js"></script>';
	echo '<script>$(document).ready(function(){$(\'.slider\').slick({autoplay: true,autoplaySpeed: 2500,dots: true});});</script>';
	echo '<script> function openSearch() { document.getElementById("Search").style.display = "block"; } function closeSearch() { document.getElementById("Search").style.display = "none";}</script>'.
	'</body></html>';
}
/*
/page-1/
/brand-honda/
/search-knalpot/
/product-1/
/
*/
?>