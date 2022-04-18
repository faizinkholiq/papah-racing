<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_barang = $_GET['this'];
$nama = $_POST['nama'];
$path = str_replace('/adm/page/barang','/p/'.trim($id_barang),dirname(__FILE__));
echo 'test : '.$path;
print_r($_FILES['gambar']);
if(!file_exists($path)){
	mkdir($path);
} else {}
$f = $_FILES;
$jum = count($f['gambar']['name']);
for ($i = 0; $i < $jum; $i++) {
  $nama_file = $f['gambar']['name'][$i];
	$ukuran_file = $f['gambar']['size'][$i];
	$tipe_file = $f['gambar']['type'][$i];
	$tmp_file = $f['gambar']['tmp_name'][$i];
	echo $nama_file.'<br>';
	echo $ukuran_file.'<br>';
	echo $tipe_file.'<br>';
	echo $tmp_file.'<br>';
	if($ukuran_file <= 4000000){  
		if(move_uploaded_file($tmp_file, $path.'/'.$nama_file)){
			header("location: main?url=ubah-barang&this=".$id_barang);     
		} else {       
			echo "Maaf, Terjadi kesalahan.";
			echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";      
		}
	} else {   
		echo "Maaf, Ukuran gambar yang diupload tidak boleh lebih dari 4MB";    
		echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";  
	}

} 
/* 

	echo '<h3>aaa '.$nama_file.'</h3>';
	if($tipe_file == "image/jpeg" || $tipe_file == "image/png"){
	} else {
		echo "Maaf, Tipe gambar yang diupload harus JPG / JPEG / PNG."; 
		echo "<br><a href='main?url=ubah-barang&this=".$id_barang."'>Kembali Ke Form</a><br>";
	}
*/
