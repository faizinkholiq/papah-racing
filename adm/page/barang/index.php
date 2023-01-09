<?php
$query = mysqli_query($con, "SELECT * FROM barang WHERE deleted = 0 ORDER BY created DESC");
$aset = 0;
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-box'></i> Data Barang</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2" ):
        foreach ($query as $data){
            $aset += floatval($data['stok'])*floatval($data['modal']);
        }   
        
        echo '<h3 style="color:red;">Total Aset : '.rp($aset).'</h3>';
		?>
    <?php endif; ?>
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2"): ?>
        <a href="main?url=tambah-barang" class="btn btn-primary mb-2"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <?php endif; ?>
    <?php if ($_SESSION['id_jabatan'] == "1" || $_SESSION['id_jabatan'] == "2" || $_SESSION['id_jabatan'] == "3" || $_SESSION['id_jabatan'] == "5"): ?>
        <a href="page/barang/cetak.php" target="_blank" class="btn btn-secondary mb-2"><i class='fas fa-print mr-2'></i>Cetak Data</a>
        <a href="page/barang/export_excel.php" target="_blank" class="btn btn-success mb-2"><i class='fas fa-file-excel mr-2'></i>Export Excel</a>
        <!-- <a href="page/barang/export_csv.php" target="_blank" class="btn btn-success"><i class='fas fa-file-csv mr-2'></i>Export CSV</a> -->
    <?php endif; ?>

    <?php if ($_SESSION['id_jabatan'] == '6'): ?>
        <div class="table-responsive mt-3">
            <table id="barangTable" class="table table-striped table-bordered " style="width:100%">
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
                </tbody>
            </table>
        </div>
    <?php elseif ($_SESSION['id_jabatan'] == '7'): ?>
        <div class="table-responsive mt-3">
            <table id="barangTable" class="table table-striped table-bordered " style="width:100%">
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
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="table-responsive mt-3">
            <table id="barangTable" class="table table-striped table-bordered " style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th class="align-middle" rowspan="2">No.</th>
                        <th class="align-middle" rowspan="2">Barcode</th>
                        <th class="align-middle" rowspan="2">Nama</th>
                        <th class="align-middle" rowspan="2">Merk</th>
                        <th class="align-middle" rowspan="2">Stok</th>
                        <th id="thHarga" colspan="6">Harga</th>
                        <th class="align-middle" rowspan="2">Aksi</th>
                        <th class="align-middle" rowspan="2">Lihat Gambar</th>
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
                </tbody>
            </table>
        </div>
    <?php endif; ?>
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
    const sess_data = <?= json_encode($_SESSION) ?>;
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;

    let columns = [];
    let columnDefs = [];

    if (sess_data["id_jabatan"] == 6) {
        columns = [
            { data: "row_no" },
            { data: "barcode" },
            { data: "nama" },
            { data: "merk" },
            { data: "stok" },
            { data: "reseller" },
            { data: "het" },
        ];
    } else if (sess_data["id_jabatan"] == 7) {
        columns = [
            { data: "row_no" },
            { data: "barcode" },
            { data: "nama" },
            { data: "merk" },
            { data: "stok" },
            { data: "distributor" },
            { data: "reseller" },
            { data: "het" },
        ];
    }else {
        columns = [
            { data: "row_no" },
            { data: "barcode" },
            { data: "nama" },
            { data: "merk" },
            { data: "stok" },
            { data: "modal", visible: false },
            { data: "distributor" },
            { data: "reseller" },
            { data: "bengkel" },
            { data: "admin" },
            { data: "het" },
            { data: "aksi", visible: false, class:"text-center" },
            { data: "gambar", visible: false, class:"text-center"},
        ];
    }

    let dt = $('#barangTable').DataTable({
        dom: "Bfrtip",
        ajax: {
            url: 'process/action?url=getbarang',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: columns,
        ordering: false
    });

    $(document).ready(()=>{
        if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2 || sess_data["id_jabatan"] == 5){
            if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2) {
                dt.columns([5]).visible(true);
                dt.columns([11]).visible(true);
            }else{
                dt.columns([12]).visible(true);
            }
        }else{
            dt.columns([12]).visible(true);
        }

        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#barangTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });
    });

    function editBarang(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-barang&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusBarang(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapusbarang&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>