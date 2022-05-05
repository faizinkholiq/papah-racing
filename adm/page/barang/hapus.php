<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
if (isset($_GET['this'])&&isset($_GET['img'])){
	$id_barang = $_GET['this'];
	$gambar = $_GET['img'];
	$path = str_replace('/adm/page/barang','/p/'.trim($id_barang),dirname(__FILE__));
	$file = $path.'/'.$gambar;
	// echo $file;
	if(file_exists($file)){
		$x = unlink($file);
		if ($x){
			header("location: main?url=ubah-barang&this=".$id_barang);  
		} else {
			echo "Maaf, Terjadi kesalahan.";
			echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a>";  
		}
	} else {
		echo "Maaf, File tidak ditemukan.";
		echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a>";  
	}
} else {
	header("location: main?url=ubah-barang&this=".$id_barang); 
}


?>