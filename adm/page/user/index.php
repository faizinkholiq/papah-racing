<?php
$query = mysqli_query($con, "SELECT * FROM user WHERE id_jabatan!='1' ORDER BY id_jabatan ASC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class="fas fa-user"></i> Data User</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
        <a href="main?url=tambah-user" class="btn btn-primary"><i class="fas fa-plus-circle mr-2"></i>Tambah Data</a>
    <?php } ?>
    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered display" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
										 <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                        <th>Bulan Ini</th>
                        <th>Bulan Lalu</th>
                    <?php } ?>
                    <th>Username</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { ?>
                        <th>Terakhir Login</th>
                        <th width="15%">Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
								if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
									$now = date('Y-m-d h:i:s');
									$start = date('Y-m').'-01 00:00:00';
									$qq = mysqli_query($con, "SELECT * FROM penjualan WHERE tanggal > '".$start."' ORDER BY id_user DESC");
									$arr = array();
									foreach ($qq as $q){
									 $arr[$q['id_user']] += $q['total_bayar'];
									}
									$bln3 = date('Y-m-d h:i:s',strtotime('-1 month', strtotime($start)));
									$qk = mysqli_query($con, "SELECT * FROM penjualan WHERE tanggal > '".$bln3."' AND tanggal < '".$start."' ORDER BY id_user DESC");
									$arz = array();
									foreach ($qk as $k){
                                        if (!isset($arz[$k['id_user']])) {
                                            $arz[$k['id_user']] = 0;
                                        }

									    $arz[$k['id_user']] += $k['total_bayar'];
									}
                } else {}
								$no = 1;
                foreach ($query as $data) : ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center"><?= $data['nama']; ?></td>
												<?php
                        if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
                            if (isset($arr[$data['id_user']])){
                                if ($arr[$data['id_user']]==0){
                                    echo '<td>0</td>';
                                } else {
                                    echo '<td>'.rp($arr[$data['id_user']]).'</td>';
                                }
                            } else {
                                echo '<td>0</td>';
                            }
                            if (isset($arz[$data['id_user']])){
                                if ($arz[$data['id_user']]==0){
                                    echo '<td>0</td>';
                                } else {
                                    echo '<td>'.rp($arz[$data['id_user']]).'</td>';
                                }
                            } else {
                                echo '<td>0</td>';
                            }
                            
                        } else {}
                        ?>
                        <td class="text-center"><?= $data['username']; ?></td>
                        <td><?= $data['alamat']; ?></td>
                        <td class="text-center"><?= $data['kontak']; ?></td>
                        <?php
                        $query_jabatan = mysqli_query($con, "SELECT * FROM jabatan WHERE id_jabatan='" . $data['id_jabatan'] . "'");
                        foreach ($query_jabatan as $qj) :
                            if ($qj['nama'] == 'Owner') {
                                echo "<td class='text-center'><span class='badge badge-success'>" . $qj['nama'] . "</span></td>";
                            } else if ($qj['nama'] == 'Manager') {
                                echo "<td class='text-center'><span class='badge badge-warning'>" . $qj['nama'] . "</span></td>";
                            } else if ($qj['nama'] == 'Admin') {
                                echo "<td class='text-center'><span class='badge badge-info'>" . $qj['nama'] . "</span></td>";
														} else if ($qj['nama'] == 'Marketer') {
                                echo "<td class='text-center'><span class='badge badge-danger'>" . $qj['nama'] . "</span></td>";
                            } else if ($qj['nama'] == 'Reseller') {
                                echo "<td class='text-center'><span class='badge badge-primary'>" . $qj['nama'] . "</span></td>";
														} else if ($qj['nama'] == 'Distributor') {
                                echo "<td class='text-center'><span class='badge badge-secondary'>" . $qj['nama'] . "</span></td>";
                            } else {
                                echo "<td class='text-center'><span class='badge badge-dark'>" . $qj['nama'] . "</span></td>";
                            }
                        endforeach; 
                        
                        $aktif = ($data["aktif"])? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Not Aktif</span>';
                        echo "<td class='text-center'>". $aktif ."</td>";
                        ?>
                        
                        <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2') { 
                        if ($data['last_login']!==null){
                            $tago = time_ago(substr($data['last_login'],0,-3));
                        } else {}
                        echo "<td class='text-center'>" . $tago . "</td>";
                        ?>
                            <td class="text-center">
                                <a title="Set Aktif" href="main?url=aktif&this=<?= $data['id_user']; ?>" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a>
                                <a title="Ubah User" href="main?url=ubah-user&this=<?= $data['id_user']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                                <a title="Reset Password" href="main?url=reset-password-user&this=<?= $data['id_user']; ?>" class="btn btn-warning btn-sm"><i class='fas fa-key'></i></a>
                                <a title="Hapus User" href="process/action?url=hapususer&this=<?= $data['id_user']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php
function time_ago($timestamp){
	date_default_timezone_set('Asia/Jakarta');  
	$time_ago = strtotime($timestamp);  
	$current_time = time();  
	$time_difference = $current_time - $time_ago;  
	$seconds = $time_difference;  
	$minutes      = round($seconds / 60 );        // value 60 is seconds  
	$hours        = round($seconds / 3600);       //value 3600 is 60 minutes * 60 sec  
	$days         = round($seconds / 86400);      //86400 = 24 * 60 * 60;  
	$weeks        = round($seconds / 604800);     // 7*24*60*60;  
	$months       = round($seconds / 2629440);    //((365+365+365+365+366)/5/12)*24*60*60  
	$years        = round($seconds / 31553280);   //(365+365+365+365+366)/5 * 24 * 60 * 60  
	if($seconds <= 60) {  
	 return "Just Now";  
	} else if($minutes <=60) {  
	 if($minutes==1){  
		 return "one minute ago";  
	 }else {  
		 return "$minutes minutes ago";  
	 }  
	} else if($hours <=24) {  
	 if($hours==1) {  
		 return "an hour ago";  
	 } else {  
		 return "$hours hrs ago";  
	 }  
	}else if($days <= 7) {  
	 if($days==1) {  
		 return "yesterday";  
	 }else {  
		 return "$days days ago";  
	 }  
	}else if($weeks <= 4.3) {  //4.3 == 52/12
	 if($weeks==1){  
		 return "a week ago";  
	 }else {  
		 return "$weeks weeks ago";  
	 }  
	} else if($months <=12){  
	 if($months==1){  
		 return "a month ago";  
	 }else{  
		 return "$months months ago";  
	 }  
	}else {  
	 if($years==1){  
		 return "one year ago";  
	 }else {  
		 return "$years years ago";  
	 }  
	}  
 } 
?>