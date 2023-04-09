<?php
$date = date('Y-m-d');
$t_user = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_user) AS total FROM user"))["total"];
$total_user = $t_user - 1;
$total_barang = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_barang) AS total FROM barang"))["total"];
$t_supplier = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_supplier) AS total FROM supplier"))["total"];
$total_supplier = $t_supplier - 1;
$t_pelanggan = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_pelanggan) AS total FROM pelanggan"))["total"];
$total_pelanggan = $t_pelanggan - 2;
// Pembelian Hari Ini
$transaksi_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian WHERE pembelian.temp = 0 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$lunas_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM pembelian WHERE pembelian.temp = 0 AND status='Lunas' AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$hutang_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM pembelian WHERE pembelian.temp = 0 AND status='Hutang' AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$pendapatan_pem = $lunas_pem + $hutang_pem;
$kekurangan_pem = $transaksi_pem - $pendapatan_pem;
$jum_transaksi_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(no_po) AS total FROM pembelian WHERE pembelian.temp = 0 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$jum_barang_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_barang) AS total FROM pembelian JOIN pembelian_det ON pembelian.no_po=pembelian_det.no_po WHERE pembelian.temp = 0 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$qty_barang_pem = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM pembelian JOIN pembelian_det ON pembelian.no_po=pembelian_det.no_po WHERE pembelian.temp = 0 AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];

// Penjualan Hari Ini
$transaksi_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$lunas_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_transaksi) AS total FROM penjualan WHERE status='Lunas' AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$hutang_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_bayar) AS total FROM penjualan WHERE status='Hutang' AND DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$pendapatan_penj = $lunas_penj + $hutang_penj;
$kekurangan_penj = $transaksi_penj - $pendapatan_penj;
$jum_transaksi_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(no_faktur) AS total FROM penjualan WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$jum_barang_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id_barang) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];
$qty_barang_penj = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(qty) AS total FROM penjualan JOIN penjualan_det ON penjualan.no_faktur=penjualan_det.no_faktur WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];

// Pengeluaran Hari Ini
$jumlah_pengeluaran = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(jumlah) AS total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '$date' AND '$date'"))["total"];

// Laporan Harian
$result_transaksi = mysqli_query($con, "
SELECT 
    SUM(CASE WHEN tipe_bayar = 'Cash' THEN total_transaksi ELSE 0 END) total_cash, 
    SUM(CASE WHEN tipe_bayar = 'Transfer' THEN total_transaksi ELSE 0 END) total_transfer, 
    SUM(CASE WHEN tipe_bayar = 'MarketPlace' THEN total_transaksi ELSE 0 END) total_marketplace 
FROM penjualan 
LEFT JOIN pelanggan ON pelanggan.id_pelanggan = penjualan.id_pelanggan
LEFT JOIN user ON user.id_user = penjualan.id_user
WHERE (penjualan.daily != true OR penjualan.daily IS NULL)");

$data_transaksi = mysqli_fetch_assoc($result_transaksi);
$summary["cash"] = isset($data_transaksi["total_cash"]) && !empty($data_transaksi["total_cash"]) ? $data_transaksi["total_cash"] : 0 ;
$summary["transfer"] = isset($data_transaksi["total_transfer"]) && !empty($data_transaksi["total_transfer"]) ? $data_transaksi["total_transfer"] : 0 ;
$summary["marketplace"] = isset($data_transaksi["total_marketplace"]) && !empty($data_transaksi["total_marketplace"]) ? $data_transaksi["total_marketplace"] : 0 ;

if ($_SESSION['id_jabatan'] == '8'||$_SESSION['id_jabatan'] == '7'||$_SESSION['id_jabatan'] == '6'||$_SESSION['id_jabatan'] == '4'){ 
	$query = mysqli_query($con, "SELECT * FROM barang ORDER BY created DESC");

    if($_SESSION['id_jabatan'] == '7'){
        $banners = mysqli_query($con, "SELECT * FROM banner ORDER BY order_no ASC LIMIT 5");
        echo '
        <div class="row mb-2">
            <div class="col-lg-12">
                <div id="carouselBanners" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">';
                        foreach ($banners as $key => $ban){
                        $active = ($key==0)? 'active' : '';
                            echo '<li data-target="#carouselBanners" data-slide-to="'.$key.'" class="'.$active.'"></li>';
                        }
                    echo'</ol>
                    <div class="carousel-inner">';
                    foreach ($banners as $key => $ban){
                        $active = ($key==0)? 'active' : '';
                        echo '<div class="carousel-item '.$active .'">
                            <img style="height: 27vw; width: 100%; object-fit: contain;" class="d-block w-100" src="'.SITEURL.'/banner/'.$ban["photo"].'" alt="Image '. ($key+1) .'">
                        </div>';
                    }
                    echo '</div>
                    <a class="carousel-control-prev" href="#carouselBanners" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselBanners" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>';
    }
    
    echo '<div class="wrapper">'.
	'<div class="table-responsive mt-3">'.
	'<table class="table table-striped table-bordered display" style="width:100%">'.
	'<thead>'.
	'<tr class="text-center">'.
	'<th>No.</th>'.
	'<th>Barcode</th>'.
	'<th>Nama</th>'.
	'<th>Merk</th>'.
	'<th>Stok</th>';

	if ($_SESSION['id_jabatan'] == '6') { 
		echo '<th>Harga Reseller</th><th>Harga Umum</th><th>Lihat Gambar</th>';
	} else if ($_SESSION['id_jabatan'] == '7') {
		echo '<th>Harga Distributor</th><th>Harga Reseller</th><th>Harga Umum</th><th>Lihat Gambar</th>';
	} else if ($_SESSION['id_jabatan'] == '4') {
		echo '<th>Harga Distributor</th><th>Harga Reseller</th><th>Harga Umum</th><th>Aksi</th>';
	} else {
		echo '<th>Harga Bengkel</th><th>Harga Umum</th>';
	}

	echo '</tr></thead><tbody>';
	$no = 1;
	foreach ($query as $data){
		echo '<tr class="text-center">'.
		'<td>'.$no.'</td>'.
		'<td class="text-left">'.$data['barcode'].'</td>'.
		'<td class="text-left">'.$data['nama'].'</td>'.
		'<td>'.$data['merk'].'</td>';
		 if ($data['stok'] <= 2) { 
				echo '<td><span class="badge badge-danger">'.$data['stok'].'</span></td>';
		 } else if ($data['stok'] <= 5) { 
				echo '<td><span class="badge badge-warning">'.$data['stok'].'</span></td>';
		 } else {
			echo '<td>'.$data['stok'].'</td>';
		 }
					
		if ($_SESSION['id_jabatan'] == '6') { 
			echo '<td>'.rp($data['reseller']).'</td><td>'.rp($data['het']).'</td>'.'<td class="text-center">'.'<a href="main?url=ubah-barang&this='.$data['id_barang'].'" class="btn btn-primary btn-sm"><i class="fas fa-photo-video"></i></a>'.
			'</td>';
		} elseif ($_SESSION['id_jabatan'] == '7') {
			echo '<td>'.rp($data['distributor']).'</td><td>'.rp($data['reseller']).'</td><td>'.rp($data['het']).'</td>'.'<td class="text-center">'.
			'<a href="main?url=ubah-barang&this='.$data['id_barang'].'" class="btn btn-primary btn-sm"><i class="fas fa-photo-video"></i></a>'.
			'</td>';
		} elseif ($_SESSION['id_jabatan'] == '4') {
			echo '<td>'.rp($data['distributor']).'</td><td>'.rp($data['reseller']).'</td><td>'.rp($data['het']).'</td>'.'<td class="text-center">'.
			'<a href="main?url=ubah-barang&this='.$data['id_barang'].'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>'.
			// '<a href="process/action?url=hapusbarang&this='.$data['id_barang'].'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm(\'Anda yakin ingin hapus data ini?\')"><i class="fas fa-trash-alt"></i></a>'.
			'</td>';
		} else {
			echo '<th>'.rp($data['bengkel']).'</th><th>'.rp($data['het']).'</th>';
		}							
		echo '</tr>';
		$no++;
	}
	echo '</tbody></table></div></div>';
} else { ?>
    <div class="row">
        <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
            <div class="col-md-6 col-lg-3 mb-2">
                <div class="card border-radius">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <div class="kotak-icon bg-danger">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="col-md-8 ">
                            <div class="card-body">
                                <p class="card-text">Total User</p>
                                <h2 class="card-title text-right font-weight-bolder"><?= $total_user; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card-footer text-right">
                                <p class="card-text"><a class="text-danger" href="main?url=user"><small>Lihat semua</small></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-2">
                <div class="card border-radius">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <div class="kotak-icon bg-warning">
                                <i class='fas fa-store'></i>
                            </div>
                        </div>
                        <div class="col-md-8 ">
                            <div class="card-body">
                                <p class="card-text">Total Supplier</p>
                                <h2 class="card-title text-right font-weight-bolder"><?= $total_supplier; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card-footer text-right">
                                <p class="card-text"><a class="text-warning" href="main?url=supplier"><small>Lihat semua</small></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-6 col-lg-3 mb-2">
            <div class="card border-radius">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <div class="kotak-icon bg-primary">
                            <i class='fas fa-box'></i>
                        </div>
                    </div>
                    <div class="col-md-8 ">
                        <div class="card-body">
                            <p class="card-text">Total Barang</p>
                            <h2 class="card-title text-right font-weight-bolder"><?= $total_barang; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-footer text-right">
                            <p class="card-text"><a class="text-primary" href="main?url=barang"><small>Lihat semua</small></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
            <div class="card border-radius">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <div class="kotak-icon bg-info">
                            <i class='fas fa-handshake'></i>
                        </div>
                    </div>
                    <div class="col-md-8 ">
                        <div class="card-body">
                            <p class="card-text">Total Pelanggan</p>
                            <h2 class="card-title text-right font-weight-bolder"><?= $total_pelanggan; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-footer text-right">
                            <p class="card-text"><a class="text-info" href="main?url=pelanggan"><small>Lihat semua</small></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
            <div class="col-md-6">
                <div class="card bg-light mb-3">
                    <div class="card-header font-weight-bolder">Pembelian Hari Ini</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <div class="card pem-day">
                                    <div class="card-body bg-info">
                                        <div class="icon text-info m-auto"><i class="fas fa-shopping-cart"></i></div>
                                        <hr>
                                        <span class="card-text text-white">
                                            <div class="row font-weight-bolder">
                                                <div class="col-6">Transaksi</div>
                                                <div class="col-6 text-right"><?php if ($transaksi_pem == 0) {
                                                                                    echo rp('0');
                                                                                } else {
                                                                                    echo rp($transaksi_pem);
                                                                                } ?></div>
                                                <div class="col-6">Pembayaran</div>
                                                <div class="col-6 text-right"><?php if ($pendapatan_pem == 0) {
                                                                                    echo rp('0');
                                                                                } else {
                                                                                    echo rp($pendapatan_pem);
                                                                                } ?></div>
                                                <div class="col-6">Kekurangan</div>
                                                <div class="col-6 text-right"><?php if ($kekurangan_pem == 0) {
                                                                                    echo rp('0');
                                                                                } else {
                                                                                    echo rp($kekurangan_pem);
                                                                                } ?></div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <div class="card pem-day">
                                    <div class="card-body bg-secondary">
                                        <div class="icon text-secondary m-auto"><i class="fas fa-box-open"></i></div>
                                        <hr>
                                        <span class="card-text text-white">
                                            <div class="row font-weight-bolder">
                                                <div class="col-8">Purchase Order</div>
                                                <div class="col-4 text-right"><?= $jum_transaksi_pem; ?></div>
                                                <div class="col-8">Jenis Barang</div>
                                                <div class="col-4 text-right"><?= $jum_barang_pem; ?></div>
                                                <div class="col-8">Quantity</div>
                                                <div class="col-4 text-right"><?php if ($qty_barang_pem == 0) {
                                                                                    echo '0';
                                                                                } else {
                                                                                    echo $qty_barang_pem;
                                                                                } ?></div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-header font-weight-bolder">Penjualan Hari Ini</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <div class="card pem-day">
                                <div class="card-body bg-success">
                                    <div class="icon text-success m-auto"><i class="fas fa-cash-register"></i></div>
                                    <hr>
                                    <span class="card-text text-white">
                                        <div class="row font-weight-bolder">
                                            <div class="col-6">Transaksi</div>
                                            <div class="col-6 text-right"><?php if ($transaksi_penj == 0) {
                                                                                echo rp('0');
                                                                            } else {
                                                                                echo rp($transaksi_penj);
                                                                            } ?></div>
                                            <div class="col-6">Pendapatan</div>
                                            <div class="col-6 text-right"><?php if ($pendapatan_penj == 0) {
                                                                                echo rp('0');
                                                                            } else {
                                                                                echo rp($pendapatan_penj);
                                                                            } ?></div>
                                            <div class="col-6">Kekurangan</div>
                                            <div class="col-6 text-right"><?php if ($kekurangan_penj == 0) {
                                                                                echo rp('0');
                                                                            } else {
                                                                                echo rp($kekurangan_penj);
                                                                            } ?></div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="card pem-day">
                                <div class="card-body bg-danger">
                                    <div class="icon text-danger m-auto"><i class="fas fa-cubes"></i></div>
                                    <hr>
                                    <span class="card-text text-white">
                                        <div class="row font-weight-bolder">
                                            <div class="col-8">Invoice</div>
                                            <div class="col-4 text-right"><?= $jum_transaksi_penj; ?></div>
                                            <div class="col-8">Jenis Barang</div>
                                            <div class="col-4 text-right"><?= $jum_barang_penj; ?></div>
                                            <div class="col-8">Quantity</div>
                                            <div class="col-4 text-right"><?php if ($qty_barang_penj == 0) {
                                                                                echo '0';
                                                                            } else {
                                                                                echo $qty_barang_penj;
                                                                            } ?></div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-header font-weight-bolder">Pengeluaran Hari Ini</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="card pem-day">
                                <div class="card-body bg-primary">
                                    <div class="icon text-primary m-auto"><i class="fas fa-wallet"></i></div>
                                    <hr>
                                    <span class="card-text text-white">
                                        <div class="row font-weight-bolder">
                                            <div class="col-6">Pendapatan Kotor</div>
                                            <div class="col-6 text-right"><?php if ($pendapatan_penj == 0) {
                                                                                echo rp('0');
                                                                            } else {
                                                                                echo rp($pendapatan_penj);
                                                                            } ?></div>
                                            <div class="col-6">Pengeluaran Toko</div>
                                            <div class="col-6 text-right"><?php if ($jumlah_pengeluaran == 0) {
                                                                                echo rp('0');
                                                                            } else {
                                                                                echo rp($jumlah_pengeluaran);
                                                                            } ?></div>
                                            <div class="col-6">Pendapatan Bersih</div>
                                            <div class="col-6 text-right"><?= rp($pendapatan_penj - $jumlah_pengeluaran) ?></div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light mb-3">
                <div class="card-header font-weight-bolder">Laporan Harian</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="card pem-day" style="background: none; border: none">
                                <div class="card-body">
                                    <div class="icon text-white bg-secondary m-auto"><i class="fas fa-calendar-alt"></i></div>
                                    <hr>
                                    <span class="card-text">
                                        <div class="row font-weight-bolder text-secondary">
                                            <div class="col-6">Cash</div>
                                            <div class="col-6 text-right"><?php if ($summary["cash"] == 0) { echo rp('0'); } else { echo rp($summary["cash"]);} ?></div>
                                            <div class="col-6">Transfer</div>
                                            <div class="col-6 text-right"><?php if ($summary["transfer"]== 0) { echo rp('0'); } else { echo rp($summary["transfer"]);} ?></div>
                                            <div class="col-6">MarketPlace</div>
                                            <div class="col-6 text-right"><?php if ($summary["marketplace"]== 0) { echo rp('0'); } else { echo rp($summary["marketplace"]);} ?></div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6 bg-light border-radius">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div> -->
    </div>
<?php } ?>