<?php
$query = mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan!='1' AND id_pelanggan!='2' ORDER BY id_pelanggan DESC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-handshake'></i> Data Pelanggan</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
        <a href="main?url=tambah-pelanggan" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
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
                    <th>Type</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                        <th>Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
                    $now = date('Y-m-d h:i:s');
                    $start = date('Y-m').'-01 00:00:00';
                    $qq = mysqli_query($con, "SELECT * FROM penjualan WHERE tanggal > '".$start."' ORDER BY id_pelanggan DESC");
                    $arr = array();
                    foreach ($qq as $q){
                     $arr[$q['id_pelanggan']] += $q['total_bayar'];
                    }
                    $bln3 = date('Y-m-d h:i:s',strtotime('-1 month', strtotime($start)));
                    $qk = mysqli_query($con, "SELECT * FROM penjualan WHERE tanggal > '".$bln3."' AND tanggal < '".$start."' ORDER BY id_pelanggan DESC");
                    $arz = [];
                    foreach ($qk as $k){
                        if (!isset($arz[$k['id_pelanggan']])) {
                            $arz[$k['id_pelanggan']] = 0;
                        }
                        
                        $arz[$k['id_pelanggan']] += $k['total_bayar'];
                    }
                } else {}
                ?>
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td class="text-left"><?= $data['nama']; ?></td>
                        <?php
                        if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
                            if (isset($arr[$data['id_pelanggan']])){
                                if ($arr[$data['id_pelanggan']]==0){
                                    echo '<td>0</td>';
                                } else {
                                    echo '<td>'.rp($arr[$data['id_pelanggan']]).'</td>';
                                }
                            } else {
                                echo '<td>0</td>';
                            }
                            if (isset($arz[$data['id_pelanggan']])){
                                if ($arz[$data['id_pelanggan']]==0){
                                    echo '<td>0</td>';
                                } else {
                                    echo '<td>'.rp($arz[$data['id_pelanggan']]).'</td>';
                                }
                            } else {
                                echo '<td>0</td>';
                            }
                            
                        } else {}
                        ?>
                        <td><?= ucwords($data['type']); ?></td>
                        <td class="text-left"><?= $data['alamat']; ?></td>
                        <td class="text-right"><?= $data['kontak']; ?></td>
                        <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                            <td>
                                <!-- <a href="" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a> -->
                                <a href="main?url=ubah-pelanggan&this=<?= $data['id_pelanggan']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                                <a href="process/action?url=hapuspelanggan&this=<?= $data['id_pelanggan']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>