<?php
head();
$i6 = '';
$i9 = '';
$im = '';
foreach ($posts as $r){
	$id = $r['id_barang'];
	$ptitle = $r['nama'];
	$plink = SITEURL.'/produk/'.$id.'/';
	$barcode = $r['barcode'];
	$merk = $r['merk'];
	$kondisi = $r['kondisi'];
	$kualitas = $r['kualitas'];
	$kategori = $r['kategori'];
	$tambahan = $r['tambahan'];
	$deskripsi = !empty($r['deskripsi'])? $r['deskripsi'] : '-';
	$stok = $r['stok'];
	$harga = $r['het'];
	$time = $r['updated'];
	$gi = glob(PHOTO.'/'.$id.'/*');
	$selected_brg = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM foto_barang WHERE id_barang='$id'"));
	$kontaks = mysqli_query($con, "SELECT * FROM kontak WHERE letak = 'order' AND aktif = 1 ORDER BY id ASC");
	if (empty($gi)){} else {
		$i = 1;
		foreach ($gi as $im){
			if(!empty($selected_brg["name"]) && $selected_brg["name"] == basename($im)) {
					$im = SITEURL.'/p/'.$id.'/'.$selected_brg["name"];
					$i9 = '<a href="'.$im.'"><img style="width:100%; object-fit:cover;" onerror="this.onerror=null;this.src=\''.SITEURL.'/images/load.gif\'" src="'.$im.'" alt=""></a>'.$i9;
			}else{
				$im = SITEURL.'/p/'.$id.'/'.basename($im);
				$i9 .= '<a href="'.$im.'"><img style="width:100%; object-fit:cover;" onerror="this.onerror=null;this.src=\''.SITEURL.'/images/load.gif\'" src="'.$im.'" alt=""></a>';
			}
			if ($i>1){
			} else {
				$i6 .= $im;
			}
			$i++;
		}
	}
}
	
echo '<section class="py-2 br-bottom br-top"><div class="container"><div class="row align-items-center justify-content-between">';

echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><nav aria-label="breadcrumb">'.
	'<ol class="breadcrumb">'. 
		'<li class="breadcrumb-item">
			<a href="'.SITEURL.'/">Home</a>
		</li>'.
		'<li class="breadcrumb-item active" aria-current="page">'.$ptitle.'</li>'.
	'</ol>'.
'</nav></div>';

echo '</div>'.
'</div></section>';

if (empty($kondisi)){$kondisi='BARU';}
if ($kualitas!=='ORIGINAL'){$kua1 = '';$kua2 = '';} else {
	$kua2 = '<p><span class="label">Kualitas</span> : '.$kualitas.'</p>';
	$kua1 = '';
}
if (empty($tambahan)){$tambahan='-';}

echo '<section class="middle"><div class="container">'.
'<div class="row">'.
'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12"><div class="sp-loading"><img onerror="this.onerror=null;this.src=\''.SITEURL.'/images/load.gif\'" src="'.$i6.'" alt=""><br>LOADING IMAGES</div><div class="sp-wrap">'.$i9.'</div></div>'.
'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12"><div class="prd_details">';
echo '<div class="prt_02 mb-4"><h1 class="ft-bold mb-1">'.$ptitle.'</h1><div class="text-left"><div class="elis_rty">'.
'<span class="ft-bold theme-cl mr-2" style="font-size: 1.5rem;">'.rp($harga).',00</span>'.
'</div></div></div>'.
'<div class="prt_03 mb-4" style="font-size: 1.2rem;">';
echo 
'<p><span class="label">Merk</span> : <a href="'.SITEURL.'/merk/'.$merk.'/">'.$merk.'</a></p>'.
'<p><span class="label">Stok</span> : '.$stok.'</p>'.
'<p><span class="label">Kondisi</span> : '.$kondisi.'</p>'.
$kua2.
'<p><span class="label">Keterangan</span> : '.$tambahan.'</p>'.
'<p><span class="label">Deskripsi</span> : '.$deskripsi.'</p>'.
'</div>'.
'<div class="prt_05 mb-4 mt-2"><div class="form-row mb-7">'.
'<div class="col-12 col-lg">
	<div class="row" style="gap:1rem;">
		<a href="#!" data-toggle="modal" data-target="#order-modal" style="font-size:1.2rem;" class="col-lg-8 col-sm-11 text-white btn custom-height bg-success mb-2 my-btn"><i class="lni lni-shopping-basket mr-2"></i>Pesan Sekarang</a>
		<a href="'.SITEURL.'" style="font-size:1.2rem;" class="col-lg-3 col-sm-11 text-white btn custom-height bg-danger mb-2 my-btn">Kembali</a>
	</div>
</div>'.
'</div></div>'.
'<div class="prt_06" style="font-size:1.2rem;"><p class="mb-0 d-flex align-items-center">'.
'<span class="mr-4">Share:</span>'.
'<a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="https://www.facebook.com/share.php?u='.$plink.'&p='.urlencode($ptitle).'" target="_blank"><i class="fab fa-facebook-f position-absolute"></i></a>'.
'<a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="http://instagram.com/sharer.php?u='.$plink.'&media='. $im .'&description='.urlencode($ptitle).'" target="_blank"><i class="fab fa-instagram position-absolute"></i></a>'.
'<a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="http://telegram.com/sharer.php?u='.$plink.'&media='. $im .'&description='.urlencode($ptitle).'" target="_blank"><i class="fab fa-telegram position-absolute"></i></a>'.
'<a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="http://whatsapp.com/sharer.php?u='.$plink.'&media='. $im .'&description='.urlencode($ptitle).'" target="_blank"><i class="fab fa-whatsapp position-absolute"></i></a>'.
'</p></div>'.
'</div></div></div></div></section>';

$ordermodal = '<div class="modal fade lg-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="ordermodal" aria-hidden="true">
	<div class="modal-dialog modal-xl login-pop-form" role="document">
		<div class="modal-content" id="ordermodal" style="padding:1rem">
			<div class="modal-headers"> 
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="ti-close"></span> </button> 
			</div>'.
			'<div class="modal-body">
				<div class="quick_view_wrap">
					<div class="quick_view_thmb">
						<div class="quick_view_slide">'.$i9.'</div>
					</div>'.
					'<div class="quick_view_capt">
						<div class="prd_details">';

if (!empty($kategori)){
	$ordermodal .= '<div class="prt_01 mb-1"><span class="text-light bg-info rounded px-2 py-1"><a href="'.SITEURL.'/kategori/'.strtolower($kategori).'/">'.$kategori.'</a></span></div>';
}

if(empty($kualitas) || $kualitas == ''){
	$kualitas = '-';
}

$ordermodal .= '<div class="prt_02 mb-2">
		<h2 class="ft-bold mb-1">'.$ptitle.'</h2>
	<div class="text-left">
		<div class="elis_rty">'.
			'<span style="font-size:1.2rem" class="ft-bold theme-cl mr-2 text-red">'.rp($harga).',00</span>'.
			'</div></div></div>'.
			'<div class="prt_03 mb-3 mt-3 row" style="font-size:1rem">'.
			'<div class="col-lg-6">'.
			'<p><span class="label" style="width:5.5rem">Merk</span> : '.$merk.'</p>'.
			'<p><span class="label" style="width:5.5rem">Stok</span> : '.$stok.'</p>'.
			'<p><span class="label" style="width:5.5rem">Kondisi</span> : '.$kondisi.'</p>'.
			'</div>'.
			'<div class="col-lg-6">'.
				'<p><span class="label" style="width:5.5rem">Kualitas</span> : '.$kualitas.'</p>'.
				'<p><span class="label" style="width:5.5rem">Keterangan</span> : '.$tambahan.'</p>'.
				'<p><span class="label" style="width:5.5rem">Deskripsi</span> : '.$deskripsi.'</p>'.
			'</div>'.
		'</div>
	</div>
	<div class="mt-2" style="font-size:1rem">
	Silahkan melakukan pemesanan dengan menghubungi salah satu kontak dibawah ini :
		<div class="mt-3" style="display: flex; gap: 1rem; flex-direction: column;">';
			
		foreach($kontaks as $item) {
			$ordermodal .= '<a href="https://wa.me/'.$item["kontak"].'?text='.urlencode('Saya order '.$ptitle).'%0a'.urlencode($plink).'" target="_blank">
				<div style="display:flex">
					<div class="mini-wa mr-1" style="color: white; background:#46df1b;">
						<i class="lni lni-whatsapp"></i>
					</div> '.$item["kontak"].'
				</div>
			</a>';
		}

		$ordermodal .= '</div>
	</div>
</div>
</div></div></div></div></div></div>';

echo '<section class="middle pt-0"><div class="container">'.
'<div class="row justify-content-center"> <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"> 
	<h3 class="mb-4">Produk Terkait</h3>
	</div> </div>'.
'<div class="row"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><div class="slide_items">';
$all = '';
foreach ($random as $pos){
	$id = $pos['id_barang'];
	$purl = SITEURL.'/produk/'.$id.'/';
	$ptitle = $pos['nama'];
	$barcode = $pos['barcode'];
	$merk  = $pos['merk'];
	$kondisi = $r['kondisi'];
	$kualitas = $r['kualitas'];
	$kategori = $r['kategori'];
	$tambahan = $r['tambahan'];
	$stok = $pos['stok'];
	$harga = $pos['het'];
	$gi = glob(PHOTO.'/'.$id.'/*');
	$i6 = '';
	$i9 = '';
	if (!empty($gi)){
		foreach ($gi as $im){
			$i = 0;
			$im = SITEURL.'/p/'.$id.'/'.basename($im);
			if ($i>1){} else {$i6 = $im;}
			$i9 .= '<div class="single_view_slide"><img onerror="this.onerror=null; this.src=\''.SITEURL.'/images/load.gif\'" src="'.$im.'" class="img-fluid" alt="" /></div>';
			$i++;
		}
	}
	
	if (empty($kondisi)){$kondisi='BARU';}
	if ($kualitas!=='ORIGINAL'){$kua1 = '';$kua2 = '';} else {
		$kua2 = '<p><span class="label">Kualitas</span> : '.$kualitas.'</p>';
		$kua1 = '<br/>'.$kualitas;
	}
	if (empty($tambahan)){$tambahan='-';}

	// bg-info NEW,bg-warning HOT, bg-danger HOT
	echo '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-12">
		<div class="product_grid card">'.
			'<div class="badge bg-danger text-white position-absolute ft-regular ab-left text-upper">Stok : '.$stok.'</div>'.
			'<div class="badge bg-warning text-white position-absolute ft-regular ab-right text-upper">'.$kondisi.$kua1.'</div>'.
			'<div class="card-body p-0"> 
				<div class="shop_thumb position-relative"> 
					<a class="card-img-top d-block overflow-hidden" href="'.$purl.'">
						<img class="card-img-top" onerror="this.onerror=null; this.src=\''.SITEURL.'/images/load.gif\'" src="'.$i6.'" alt="'.$ptitle.'" style="height:15rem; object-fit:cover;">
					</a> 
					<div class="product-hover-overlay bg-light d-flex align-items-center justify-content-center"> 
						<div class="edlio">
							<a href="#!" data-toggle="modal" data-target="#quickview'.$id.'" class="fs-sm ft-medium">
								<i class="fas fa-eye mr-1"></i>Quick View
							</a>
						</div> 
					</div> 
				</div> 
			</div>'.
			'<div class="card-footers b-0 p-3 px-2 bg-white d-flex align-items-start justify-content-center"> 
				<div class="text-left"> 
					<div class="text-center"> 
						<h5 style="height:2rem;" class="fw-bolder fs-sm mb-0 lh-1 mb-1">
							<a href="'.$purl.'">'.$ptitle.'</a>
						</h5> 
						<div class="elis_rty">
							<span class="ft-bold fs-sm text-dark">'.rp($harga).',00</span>
						</div> 
					</div> 
				</div> 
			</div>'.
			'<div class="bg-success d-flex align-items-center justify-content-center"> 
				<div class="edlio">
					<a href="'.$order.urlencode('Saya order '.$ptitle).'%0a'.urlencode($purl).'" class="btn text-white btn-block mb-1">
						<i class="lni lni-shopping-basket mr-2"></i>Pesan Sekarang
					</a>
				</div> 
			</div>'.
		'</div>
	</div>';

	$all .= '<div class="modal fade lg-modal" id="quickview'.$id.'" tabindex="-1" role="dialog" aria-labelledby="quickviewmodal" aria-hidden="true"><div class="modal-dialog modal-xl login-pop-form" role="document"><div class="modal-content" id="quickviewmodal"><div class="modal-headers"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="ti-close"></span> </button> </div>'.
	'<div class="modal-body"><div class="quick_view_wrap"><div class="quick_view_thmb"><div class="quick_view_slide">'.$i9.'</div></div>'.
	'<div class="quick_view_capt"><div class="prd_details">';
	if (!empty($kategori)){
		$all .= '<div class="prt_01 mb-1"><span class="text-light bg-info rounded px-2 py-1"><a href="'.SITEURL.'/kategori/'.strtolower($kategori).'/">'.$kategori.'</a></span></div>';
	} else {}
	$all .= '<div class="prt_02 mb-2"><h2 class="ft-bold mb-1">'.$ptitle.'</h2><div class="text-left"><div class="elis_rty">'.
	'<span class="ft-bold theme-cl fs-lg mr-2">'.rp($harga).',00</span>'.
	'</div></div></div>'.
	'<div class="prt_03 mb-3">'.
	// '<p><span class="label">Kode</span> : '.$barcode.'</p>'.
	'<p><span class="label">Merk</span> : '.$merk.'</p>'.
	'<p><span class="label">Stok</span> : '.$stok.'</p>'.
	'<p><span class="label">Kondisi</span> : '.$kondisi.'</p>'.
	$kua2.
	'<p><span class="label">Keterangan</span> : '.$tambahan.'</p>'.
	'</div>'.
	'<div class="prt_05 mb-4"><div class="form-row mb-7">'.
	'<div class="col-12 col-lg"><a href="'.$order.urlencode('Saya order '.$ptitle).'%0a'.urlencode($purl).'" class="text-white btn btn-block custom-height bg-success mb-2"><i class="lni lni-shopping-basket mr-2"></i>Pesan Sekarang</a></div>'.
	'<div class="col-12 col-lg-auto"><a href="'.$purl.'" class="btn custom-height btn-default btn-block mb-2 text-dark"><i class="lni lni-eye mr-2"></i>View Details</a></div>'.
	'</div></div>'.
	'</div></div></div></div></div></div>';
}					

echo '</div></div></div>';
echo $ordermodal;
echo $all;
echo '</div></section>';
foot();
?>