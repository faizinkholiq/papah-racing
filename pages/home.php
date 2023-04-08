<?php
head();
$kontaks = mysqli_query($con, "SELECT * FROM kontak WHERE letak = 'order' AND aktif = 1 ORDER BY id ASC");
$list_kontak = [];
foreach($kontaks as $key => $value) {
	$list_kontak[] = $value;
}
echo '<section class="py-2 br-bottom br-top" style="margin-top: 1rem;">
		<div class="container">
			<div class="row align-items-center justify-content-start">
				<h3 style="font-weight:bold; padding-left:15px; padding-top:10px; color: grey;">ALL PRODUCT</h3>			
			</div>
		</div>
	</section>';


if(isset($px) && ($px == "cari" || $px == "kategori" || $px == "merk")) {
	echo '
		<section class="middle" style="padding-top:10px!important;"><div class="container">
		<a href="'.SITEURL.'" class="btn btn-sm bg-light text-dark fs-md ft-medium mb-4"><i class="lni lni-chevron-left mr-2"></i> Kembali</a>
		<div class="row align-items-center" style="margin: 0;">';
}else{
	echo '<section class="middle"><div class="container"><div class="row align-items-center" style="margin: 0;">';
}

$all = '';$j = 1;
foreach ($posts as $pos){
	if(empty($pos['nama'])) continue;
	
	$id = $pos['id_barang'];
	$purl = SITEURL.'/produk/'.$id.'/';
	$ptitle = $pos['nama'];
	$barcode = $pos['barcode'];
	$merk  = $pos['merk'];
	$stok  = $pos['stok'];
	$kondisi = $pos['kondisi'];
	$kualitas = $pos['kualitas'];
	$kategori = $pos['kategori'];
	$tambahan = $pos['tambahan'];
	$tipe_pelanggan = $pos['tipe_pelanggan'];
	$berat = $pos['berat'];
	$deskripsi = !empty($pos['deskripsi'])? $pos['deskripsi'] : '-';
	$harga = $pos['het'];
	$gi = glob(PHOTO.'/'.$id.'/*');
	$i6 = '';
	$i9 = '';
	$selected_brg = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM foto_barang WHERE id_barang='$id'"));

	if (empty($gi)){} else {
		$i = 0;
		foreach ($gi as $im){
			if(!empty($selected_brg["name"])) {
				$im = SITEURL.'/p/'.$id.'/'.$selected_brg["name"];
			}else{
				$im = SITEURL.'/p/'.$id.'/'.basename($im);
			}

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
	// if ($stok==0){$stok='Habis';}
	// bg-info NEW,bg-warning HOT, bg-danger HOT
	echo '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
		<div class="product_grid card">'.
			'<div class="badge bg-danger text-white position-absolute ft-regular ab-left text-upper">STOK : '.$stok.'</div>'.
			'<div class="badge bg-warning text-white position-absolute ft-regular ab-right text-upper">'.$kondisi.$kua1.'</div>'.
			'<div class="card-body p-0">
				<div class="shop_thumb position-relative"> 
					<a class="card-img-top d-block overflow-hidden" href="'.$purl.'">
						<img style="object-fit: cover; height: 10rem;" class="card-img-top" 
							onerror="this.onerror=null; this.src=\''.SITEURL.'/images/load.gif\'" src="'.$i6.'" alt="'.$ptitle.'">
					</a>';
					echo !empty($tipe_pelanggan)? '<div style="top: 9rem;" class="badge bg-dark-blue text-white position-absolute ft-regular ab-left text-upper">'.$tipe_pelanggan.'</div>' : '';
					echo !empty($berat)? '<div style="top: 9rem;" class="badge bg-blue text-white position-absolute ft-regular ab-right text-upper">'.$berat.'gr</div>' : '';
					echo'<div class="product-hover-overlay bg-light d-flex align-items-center justify-content-center"> 
						<div class="edlio">
							<a href="#" data-toggle="modal" data-target="#quickview'.$id.'" class="fs-sm ft-medium">
								<i class="fas fa-eye mr-1"></i>Quick View
							</a>
						</div>
					</div>
				</div>
			</div>'.
			'<div class="card-footers b-0 p-3 px-2 bg-white d-flex align-items-start justify-content-center"> 
				<div class="text-left"> 
					<div class="text-center"> 
						<h5 class="fw-bolder fs-sm mb-0 lh-1 mb-1">
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
					<a href="#!" onclick="pesanSekarang(`'.$ptitle.'`, `'.urlencode('Saya order '.$ptitle).'`, `'.urlencode($purl).'`)" class="btn text-white btn-block mb-1">
						<i class="lni lni-shopping-basket mr-2"></i>Pesan Sekarang
					</a>
				</div> 
			</div>
		</div>
	</div>';

	$all .= '<div class="modal fade lg-modal quickviewmodal" id="quickview'.$id.'" tabindex="-1" role="dialog" aria-labelledby="quickviewmodal" aria-hidden="true">
		<div class="modal-dialog modal-xl login-pop-form" role="document">
			<div class="modal-content" id="quickviewmodal">
				<div class="modal-headers"> 
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
						<span class="ti-close"></span> 
					</button> 
				</div>'.
				'<div class="modal-body">
					<div class="quick_view_wrap">
						<div class="quick_view_thmb">
							<div class="quick_view_slide">'.$i9.'</div>
						</div>'.
						'<div class="quick_view_capt">
							<div class="prd_details">';
							if (!empty($kategori)){
								$all .= '
									<div class="prt_01 mb-1">
										<span class="text-light bg-info rounded px-2 py-1">
											<a href="'.SITEURL.'/kategori/'.strtolower($kategori).'/">'.$kategori.'</a>
										</span>
									</div>';
							} else {}

	$all .= '<div class="prt_02 mb-2 mt-4">
		<h2 class="ft-bold mb-1">'.$ptitle.'</h2>
		<div class="text-left">
			<div class="elis_rty">'.
			'<span class="ft-bold theme-cl fs-lg mr-2">'.rp($harga).',00</span>'.
			'</div>
		</div>
	</div>'.
	'<div class="prt_03 mb-3">'.
		'<p><span class="label">Merk</span> : '.$merk.'</p>'.
		'<p><span class="label">Stok</span> : '.$stok.'</p>'.
		'<p><span class="label">Kondisi</span> : '.$kondisi.'</p>'.
		$kua2.
		'<p><span class="label">Keterangan</span> : '.$tambahan.'</p>'.
		'<p><span class="label">Deskripsi</span> : '.$deskripsi.'</p>'.
	'</div>'.
	'<div class="prt_05 mb-4">
		<div class="form-row mb-7">'.
			'<div class="col-12 col-lg">
				<a href="#!" 
					onclick="pesanSekarang(`'.$ptitle.'`, `'.urlencode('Saya order '.$ptitle).'`, `'.urlencode($purl).'`)" 
					class="text-white btn btn-block custom-height bg-success mb-2">
					<i class="lni lni-shopping-basket mr-2"></i>Pesan Sekarang</a>
			</div>'.
			'<div class="col-12 col-lg-auto">
				<a href="'.$purl.'" class="btn custom-height btn-default btn-block mb-2 text-dark">
					<i class="lni lni-eye mr-2"></i>View Details
				</a>
			</div>'.
		'</div></div>'.
	'</div></div></div></div></div></div></div>';
	
	$j++;
}

echo '
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="ordermodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg login-pop-form" role="document">
		<div class="modal-content" id="ordermodalLabel" style="padding:1rem">
			<div class="modal-headers"> 
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="ti-close"></span> </button> 
			</div>
			<div class="modal-body">
				<div class="text-center mb-4" style="font-size: 1rem;">
					<h3 class="m-0 ft-regular"><span id="kontakTitle"></span></h3><hr/>
					Silahkan melakukan pemesanan dengan menghubungi salah satu kontak dibawah ini :
					<div id="kontakList" class="mt-4" style="display: flex; gap: 1rem; flex-direction: column;  padding: 0; justify-content: center; align-items: center;">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
echo '</div>';
echo '<div class="row" style="margin:1rem 0 2rem"><div class="col-xl-12 col-lg-12 col-md-12 text-center">';

if($paging):

$pnv = '<a href="'.$nvurl.'" title="First Page" rel="nofollow" class="pnv bg-facebook">001</a>';
if(!empty($_GET["kategori"])){
	$nvurl .= "&";
}else{
	$nvurl .= "?";
}

$pns = [];

if($total_page > 1 && $total_page < 11){
	$pns = range(2, $total_page); 
	
	foreach ($pns as $pn){
		$np = sprintf('%03d',$pn);
		$page_selected = ($pn == $_GET["page"])? 'paging-selected' : 'bg-facebook';
		$pnv .= '<a href="'.$nvurl.'page='.$np.'" title="Page '.$pn.'" class="pnv '.$page_selected .'">'.$np.'</a>';
	} 

} else {
	
	if ($page<4){
		$pns = range(2,5);
	} elseif ($page>3&&$page+2<$total_page-1){
		$pns = range($page-2,$page+2);
	} else {
		$pns = range($total_page-5,$total_page-1);
	}

	$prev = $pns[0] - 1;
	$pnv .= '<a href="'.$nvurl.'page='.sprintf('%03d',$prev).'"><span class="pnv bg-blue">&llarr;</span></a>';
	$next = 1;
	foreach ($pns as $pn){ 
		$np = sprintf('%03d',$pn);
		$page_selected = (!empty($_GET["page"]) && $pn == $_GET["page"])? 'paging-selected' : 'bg-facebook';
		$pnv .= '<a href="'.$nvurl.'page='.$np.'" title="Page '.$pn.'" class="pnv '.$page_selected .'">'.$np.'</a>';

		if($pn == $pns[count($pns)-1]){
			$next += $pn;
		}
	}
	
	$pnv .= '<a href="'.$nvurl.'page='.sprintf('%03d',$next).'"><span class="pnv bg-blue">&rrarr;</span></a>
	<a href="'.sprintf('%03d',$nvurl).'page='.$total_page.'" title="Last Page" class="pnv bg-facebook">'.$total_page.'</a>';
}

if ($total_page>1){
	echo '<div class="align-items-center" style="display: flex; gap: 0.1rem; justify-content: center;">'.$pnv.'</div>';
}

echo '</div></div>';
endif;

echo '</div></section>';
echo $all;		
foot();		
?>

<script>
	const list_kontak = <?= json_encode($list_kontak) ?>;
	const order_url = '<?= $order ?>';

	function pesanSekarang(title, text, target_url) {
		$('.quickviewmodal').modal('hide');
		$('#kontakTitle').text(title);
		$('#kontakList').html('');
		list_kontak.forEach((v) => {
			$('#kontakList').append(`
				<a href="https://wa.me/${v.kontak}?text=${text}%0a${target_url}" target="_blank">
					<div class="li-kontak">
						<div class="mini-wa mr-2" style="color: white; background:#46df1b;">
							<i class="lni lni-whatsapp"></i>
						</div>
						${v.keterangan} (${v.kontak})
					</div>
				</a>
			`)
		});
		$('#orderModal').modal('show');
	}

</script>