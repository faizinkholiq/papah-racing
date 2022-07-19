<?php
//koneksi kedatabase
require "../../config/connect.php";

// nama file
$filename = "data barang-" . date('Ymd') . ".csv";

//header info for browser
header("Content-Type:text/x-csv");
header('Content-Disposition: attachment; filename="' . $filename . '";');

//menampilkan data sebagai array dari tabel barang
$out = array();
$sql = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 ORDER BY created DESC");
while ($data = mysqli_fetch_assoc($sql)) $out[] = $data;

// create a file pointer connected to the output stream
$fh = fopen('php://output', 'w');
$heading = false;
if (!empty($out))
    foreach ($out as $row) {
        if (!$heading) {
            //menampilkan nama kolom di baris pertama
            fputcsv($fh, array_keys($row));
            $heading = true;
        }
        // looping data dari database  
        fputcsv($fh, array_values($row));
    }
fclose($fh);
