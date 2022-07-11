<?php
	head();
    $active = isset($_GET["active"])? $_GET["active"] : "" ;
?>
	
<section class="py-2 br-bottom br-top">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb"> 
						<li class="breadcrumb-item">
							<a href="<?=SITEURL?>/">Home</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							Peraturan Pelayanan
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</section>
<section style="padding: 2rem 0 4rem;">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 desktop-only nav nav-pills">
				<div class="my-list-group" style="padding: 0 0 2rem;">
					<a class="my-list-group-item my-list-group-item-action" href="<?=SITEURL?>">Beranda</a>
					<a class="my-list-group-item my-list-group-item-action <?= ($active == 'pelayanan')? 'active' : '' ?>" data-toggle="pill" href="#pelayanan">Peraturan Pelayanan</a>
					<a class="my-list-group-item my-list-group-item-action <?= ($active == 'garansi')? 'active' : '' ?>" data-toggle="pill" href="#garansi">Garansi</a>
					<a class="my-list-group-item my-list-group-item-action <?= ($active == 'tentang')? 'active' : '' ?>" data-toggle="pill" href="#tentang">Tentang Kami</a>
					<a class="my-list-group-item my-list-group-item-action <?= ($active == 'join')? 'active' : '' ?>" data-toggle="pill" href="#join">Join Us</a>
				</div>
			</div>
            <div class="col-lg-3 mobile-only nav nav-pills" style="padding:0;">
				<div class="my-nav-group" style="padding: 0 0 1rem;">
					<a class="my-nav-group-item" href="<?=SITEURL?>">Beranda</a>
					<a class="my-nav-group-item <?= ($active == 'pelayanan')? 'active' : '' ?>" data-toggle="pill" href="#pelayanan">Peraturan Pelayanan</a>
					<a class="my-nav-group-item <?= ($active == 'garansi')? 'active' : '' ?>" data-toggle="pill" href="#garansi">Garansi</a>
					<a class="my-nav-group-item <?= ($active == 'tentang')? 'active' : '' ?>" data-toggle="pill" href="#tentang">Tentang Kami</a>
					<a class="my-nav-group-item <?= ($active == 'join')? 'active' : '' ?>" data-toggle="pill" href="#join">Join Us</a>
				</div>
			</div>
			<div class="col-lg-9 tab-content">
				<div id="pelayanan" class="tab-pane <?=($active == 'pelayanan')? 'active' : '' ?>">
					<h1 class="ml-lg-4 ml-sm-0"><strong>Peraturan Pelayanan</strong></h1>
					<div class="my-flat-card mt-lg-4 ml-lg-4 ml-sm-0">
						<p>Papahracing.com melayani online 24 jam melalui cust.service kami via chat. Kontak chat tersedia di halaman depan website dan link aktip ke whatapps konsumen secara langsung dengan klik tombol icon whatapps.</p>
						<p>
							Untuk toko kita buka dari jam 09.00-18.00. Pemesanan kita usahakan 3 kali dalam sehari sesuai jam pemesanan. 
							<ol>
								<li>Pemesanan malam-pagi dikirim pagi-siang,</li>
								<li>Pemesanan pagi-siang dikirim siang-sore,</li>
								<li>Pemesanan sore-malem dikirim sore-malem.</li>
							</ol>
						</p>
						<p>Kita menggunakan kurir pengiriman konvensional seperti tiki, jne, j&t, sicepat,ninja, pos, wahana, baraka, KIB, DLL.
						<p>Untuk grosir dan quantity besar atau berat kita menggunakan banyak pengiriman berbasis angkutan trucking (truk),</p>
						<p>Untuk pengiriman instan (cepat) kita bisa menggunakan jasa aplikasi gojek dan grab.</p>
						</p>
						<p>Selain semua diatas kita juga siap berkordinasi dengan konsumen menggunakan expedisi langganan konsumen selama masih di wilayah jakarta.</p>
						<p>Terima Kasih</p>
					</div>
				</div>
				<div id="garansi" class="tab-pane <?=($active == 'garansi')? 'active' : '' ?>">
                    <h1 class="ml-lg-4 ml-sm-0"><strong>Garansi</strong></h1>
                    <div class="my-flat-card mt-lg-4 ml-lg-4 ml-sm-0">
                        <p>
                            Garansi kami:
                            <ol>
                                <li>Kami memberikan garansi atas kerusakan barang dalam pengiriman selama konsumen membeli menggunakan asuransi pengiriman. Tanpa asuransi dalam pengiriman oleh kurir bukan tanggung jawab kami.</li>
                                <li>Garansi tukar barang jika ada kesalahan dalam pengiriman barang pesanan. Dan semua pengiriman balik dan kirim kembali merupakan tanggung jawab kami.</li>
                                <li>Tidak ada garansi untuk kerusakan/ tidak berfungsi untuk part original(sampai sekarang original belum pernah ada pengalaman komplain).</li>
                                <li>Tidak ada tukar barang jika itu sudah sesuai pesanan konsumen.</li>
                                <li>Untuk barang copotan garansi hanya 1 bulan setelah pembayaran. Diharapkan segera di pakai(pasang) sehingga garansi kita masih aktip selama kurun waktu tersebut.</li>
                                <li>Barang yang kurang dari pemesanan dan kita susulkan merupakan tanggung jawab biaya kirim dari kami.</li>
                                <li>semua garansi berlaku selama bukti transaksi masih tersedia (histori chat/bukti tf/nota).</li>
                            </ol>
                        </p>
                        <p style="color:red;">WARNING : Tidak menerima garansi apapun diluar peraturan service kita diatas.</p>
                    </div>
                </div>
                <div id="tentang" class="tab-pane <?=($active == 'tentang')? 'active' : '' ?>">
                    <h1 class="ml-lg-4 ml-sm-0"><strong>Tentang Kami</strong></h1>
                    <div class="my-flat-card mt-lg-4 ml-lg-4 ml-sm-0">
                        <p>Papahracing.com adalah situs jual-beli dunia otomotif khususnya barang balap dan harian. Ada ribuan katalog sparepart racing yang kita siapkan untuk mencukupi kebutuhan anda. 
                        Website papahracing.com bisa diakses menggunakan handphone,pc,laptop,tablet,dll selama terhubung dengan jaringan internet.</p> 

                        <p>Kita berkerja sama dengan berbagai merk dagang kenamaan yang familiar di pasar otomotif tanah air baik itu brand lokal maupun internasional. 
                        Brand yang ada merupakan hasil pilihan kita dengan kesesuaian permintaan pasar dan kualitas barang tersebut. 
                        Jadi hanya produk dan merk-merk yang berkualitas aja yang kita pilih.</p>
                        
                        <p>Didalam usaha papahracing.com dibantu banyak admin yang bekerja sebagai customer service, packaging, pengiriman dan managemen.</p>

                        <p>Sebagian besar produk kita adalah original, dan kita lampirkan informasinya di setiap detail barang di website bagian detail produk,yaitu banyaknya stok, original atau fake, baru atau bekas, berat barang, bisa digrosir atau tidak.</p>
                        
                        <p>Papahracing.com adalah cabang dan dalam pengawasan knalpot-racing.com speedshop. Yang tentunya sudah dikenal dan familiar didunia penjualan sparepart dan ajang balap tanah air.</p> 
                        
                        <p>Visi papahracing.com dapat menjadi platform online otomotif kesayangan kita semua. Dan Misi kita menjadi toko online otomotif terbesar di tanah air.</p>
                    </div>
                </div>
                <div id="join" class="tab-pane <?=($active == 'join')? 'active' : '' ?>">
                    <h1 class="ml-lg-4 ml-sm-0"><strong>Join Us</strong></h1>
                    <div class="my-flat-card mt-lg-4 ml-lg-4 ml-sm-0">
                        <h3>Pedagang</h3>
						
                        <p>Kami memberikan akses login untuk member pedagang mengetahui semua jumlah stok ditoko kita dan membaginya menjadi 3 harga yaitu distributor, 
							reseller dan HET (Harga Eceran Tertinggi). 
							Jadi member bisa membeli dan menjual dengan bisa mengetahui kapasitas keuntungan yang di dapat.</p>
						
                        <p><a href="https://wa.me/6281385595027?text=<?=urlencode('Saya join sebagai Pedagang')?>" style="color:#fff; font-weight:bold;" class="btn bg-success my-btn btn-join">JOIN</a></p>
						
                        <h3 style="margin-top:2rem">Penjual</h3>

						<p>Anda produsen/importir/APM???</p>

						<p>Kami menyiapkan Team untuk membantu anda menjual produk dengan cara yang lebih efisien karena kami sudah memiliki banyak agen distributor dan reseller dan kami juga memiliki admin yang sudah siap promosikan produk anda.</p>

						<p><a href="https://wa.me/6281385595027?text=<?=urlencode('Saya join sebagai Penjual')?>" style="color:#fff; font-weight:bold;" class="btn bg-success my-btn btn-join">JOIN</a></p>					
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>

<?php 
	foot();
?>