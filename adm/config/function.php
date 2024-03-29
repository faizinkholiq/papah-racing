<?php

function rp($str)
{
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

function tgl($date)
{
    $array_bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $date = strtotime($date);
    $tanggal = date('j', $date);
    $bulan = $array_bulan[date('n', $date)];
    $tahun = date('Y', $date);
    $result = $tanggal . " " . $bulan . " " . $tahun;
    return ($result);
}

function arr_remove_empty($arr) {
    return array_values(array_filter($arr, function($v) {
        return !empty($v) || $v === 0;
    }));
}