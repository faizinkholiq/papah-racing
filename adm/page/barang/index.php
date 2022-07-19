<?php
$query = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 ORDER BY created DESC");
$aset = 0;
if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
	foreach ($query as $data){
		$aset += floatval($data['stok'])*floatval($data['modal']);
	}
} else {}
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-box'></i> Data Barang</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { 
		echo '<h3 style="color:red;">Total Aset : '.rp($aset).'</h3>';
		?>
        <a href="main?url=tambah-barang" class="btn btn-primary mb-2"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php } ?>
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2" || $_SESSION['id_jabatan'] == "3" || $_SESSION['id_jabatan'] == "5") { ?>
        <a href="page/barang/cetak.php" target="_blank" class="btn btn-secondary mb-2"><i class='fas fa-print mr-2'></i>Cetak Data</a>
        <a href="page/barang/export_excel.php" target="_blank" class="btn btn-success mb-2"><i class='fas fa-file-excel mr-2'></i>Export Excel</a>
        <!-- <a href="page/barang/export_csv.php" target="_blank" class="btn btn-success"><i class='fas fa-file-csv mr-2'></i>Export CSV</a> -->
    <?php } ?>
    <!-- <?php
            $cekstok = mysqli_query($con, "SELECT * FROM barang WHERE stok < 5");
            foreach ($cekstok as $cs) {
                if ($cs['stok'] <= 2) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> Sisa Stok Barang <strong><?= $cs['nama']; ?></strong> Sangat Minimal !!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
                } else {
        ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> Sisa Stok Barang <strong><?= $cs['nama']; ?></strong> Sudah Hampir Habis !!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    <?php }
            } ?> -->
    <?php if ($_SESSION['id_jabatan'] == '6') { ?>
        <div class="table-responsive mt-3">
            <table id="tb-barang" class="table table-striped table-bordered " style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th>Merk</th>
                        <th>Stok</th>
                        <th>Harga Reseller</th>
                        <th>Harga Umum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($query as $data) : ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td class="text-left"><?= $data['barcode']; ?></td>
                            <td class="text-left"><?= $data['nama']; ?></td>
                            <td><?= $data['merk']; ?></td>
                            <?php if ($data['stok'] <= 2) { ?>
                                <td><span class="badge badge-danger"><?= $data['stok']; ?></span></td>
                            <?php } else if ($data['stok'] <= 5) { ?>
                                <td><span class="badge badge-warning"><?= $data['stok']; ?></span></td>
                            <?php } else { ?>
                                <td><?= $data['stok']; ?></td>
                            <?php } ?>
                            <td><?= rp($data['reseller']); ?></td>
                            <td><?= rp($data['het']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } else if ($_SESSION['id_jabatan'] == '7') { ?>
        <div class="table-responsive mt-3">
            <table id="tb-barang" class="table table-striped table-bordered " style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th>Merk</th>
                        <th>Stok</th>
                        <th>Harga Distributor</th>
                        <th>Harga Reseller</th>
                        <th>Harga Umum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($query as $data) : ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td class="text-left"><?= $data['barcode']; ?></td>
                            <td class="text-left"><?= $data['nama']; ?></td>
                            <td><?= $data['merk']; ?></td>
                            <?php if ($data['stok'] <= 2) { ?>
                                <td><span class="badge badge-danger"><?= $data['stok']; ?></span></td>
                            <?php } else if ($data['stok'] <= 5) { ?>
                                <td><span class="badge badge-warning"><?= $data['stok']; ?></span></td>
                            <?php } else { ?>
                                <td><?= $data['stok']; ?></td>
                            <?php } ?>
                            <td><?= rp($data['distributor']); ?></td>
                            <td><?= rp($data['reseller']); ?></td>
                            <td><?= rp($data['het']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="table-responsive mt-3">
            <table id="tb-barang" class="table table-striped table-bordered " style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th class="align-middle" rowspan="2">No.</th>
                        <th class="align-middle" rowspan="2">Barcode</th>
                        <th class="align-middle" rowspan="2">Nama</th>
                        <th class="align-middle" rowspan="2">Merk</th>
                        <th class="align-middle" rowspan="2">Stok</th>
                        <th colspan="6">Harga</th>
                        <?php 
												if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") {
													echo '<th class="align-middle" rowspan="2">Aksi</th>';
												} elseif ($_SESSION['id_jabatan'] == "5"){
													echo '<th class="align-middle" rowspan="2">Lihat Gambar</th>';
												} else {} 
												?>
                    </tr>
                    <tr>
                        <th>Modal</th>
                        <th>Distributor</th>
                        <th>Reseller</th>
                        <th>Bengkel</th>
                        <th>Admin</th>
                        <th>HET</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($query as $data) : ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td class="text-left">
                                <a href="#" class="btn btn-secondary btn-sm editbarang" data-target="#cetakbarcode" data-toggle="modal" data-barcode="<?= $data['barcode']; ?>" data-nama="<?= $data['nama']; ?>" data-harga="<?= $data['het']; ?>"><i class='fas fa-barcode'></i></a>
                                <?= $data['barcode']; ?>
                            </td>
                            <td class="text-left"><?= $data['nama']; ?></td>
                            <td><?= $data['merk']; ?></td>
                            <?php if ($data['stok'] <= 2) { ?>
                                <td><span class="badge badge-danger"><?= $data['stok']; ?></span></td>
                            <?php } else if ($data['stok'] <= 5) { ?>
                                <td><span class="badge badge-warning"><?= $data['stok']; ?></span></td>
                            <?php } else { ?>
                                <td><?= $data['stok']; ?></td>
                            <?php } ?>
                            <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                                <td class="text-left"><?= rp($data['modal']); ?></td>
                            <?php } else { ?>
                                <td><?= '-'; ?></td>
                            <?php } ?>
                            <td class="text-left"><?= rp($data['distributor']); ?></td>
                            <td class="text-left"><?= rp($data['reseller']); ?></td>
                            <td class="text-left"><?= rp($data['bengkel']); ?></td>
                            <td class="text-left"><?= rp($data['admin']); ?></td>
                            <td class="text-left"><?= rp($data['het']); ?></td>
                            <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2") { ?>
                                <td class="text-center">
                                    <!-- <a href="" class="btn btn-info btn-sm"><i class='fas fa-eye'></i></a> -->
                                    <a href="#!" onclick="editBarang(<?=$data['id_barang']?>)" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                                    <a href="process/action?url=hapusbarang&this=<?= $data['id_barang']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                                </td>
                            <?php 
                                } elseif ($_SESSION['id_jabatan'] == "5"){
                                    echo '<td class="text-center">'.'<a href="main?url=ubah-barang&this='.$data['id_barang'].'" class="btn btn-primary btn-sm"><i class="fas fa-photo-video"></i></a>'.'</td>';
                                } else {} 
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>


<!-- Modal Cetak Barcode -->
<div id="cetakbarcode" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Cetak Barcode Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <form action="page/barang/barcode.php" method="post" target="_blank">
                    <div class="form-group row">
                        <label for="ubah_barcode" class="col-sm-2 col-form-label">Barcode</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ubah_barcode" name="ubah_barcode" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ubah_nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ubah_nama" name="ubah_nama" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ubah_harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input type="text" class="form-control uang" id="ubah_harga" name="ubah_harga" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ubah_qty" class="col-sm-2 col-form-label">Copy</label>
                        <div class="col-sm-10">
                            <input type="number" min="1" class="form-control" id="ubah_qty" name="ubah_qty" required>
                        </div>
                    </div>
                    <div class="form-row text-center">
                        <div class="col-12">
                            <button id="btnCetakBarcode" type="submit" class="btn btn-secondary"><i class='fas fa-print mr-2'></i>Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;
    let tb_barang = $("#tb-barang").DataTable({
        order: [],
    });

    $(document).ready(function() {
        // $("#tb-barang").on('draw.dt', function () {
        //     console.log(tb_barang.page.info())
        // });
        if(page != null && page != ""){
            tb_barang.page(page).draw(false);
        }
    });

    function editBarang(id) {
        let page_now = tb_barang.page.info().page;
        window.open("main?url=ubah-barang&this="+id+"&page="+page_now, "_self")
    }
</script>