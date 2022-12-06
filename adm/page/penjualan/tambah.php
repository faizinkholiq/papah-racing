<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cash-register'></i> Tambah Penjualan</h3>
    </div>
    <div class="col-4">
        <a href="main?url=penjualan" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="wrapper">
            <form action="process/action?url=tambahbarangpenjualan" method="post">
                <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                <input type="hidden" class="form-control" name="type" value="<?= $_GET['type'] ?>">
                <input type="hidden" class="form-control" name="qty" value="1">
                <input type="hidden" class="form-control" name="diskon" value="0">
                <div class="row">
                    <div class="col-9 col-lg-11">
                        <div class="form-group row">
                            <input type="text" class="form-control ml-2" id="barcode" name="barcode" autocomplete="off" placeholder="Scan Barcode" required>
                        </div>
                    </div>
                    <div class="col-3 col-lg-1">
                        <a href="#" class="btn btn-primary" data-target="#barang" data-toggle="modal"><i class='fas fa-search'></i></a>
                    </div>
                </div>
                <div class="form-row text-center">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success"><i class='fas fa-plus-circle mr-2'></i>Tambah Barang</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table id="editable_table" class="table table-striped table-bordered mt-3" style="width:100%">
                    <thead>
                        <tr class="text-center">
                            <th>Barcode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Diskon/Pcs</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM penjualan_temp INNER JOIN barang ON penjualan_temp.id_barang=barang.id_barang WHERE id_user='" . $_SESSION['id_user'] . "'");
                        foreach ($query as $data) :
                        ?>
                            <tr class="text-center">
                                <td><?= $data['barcode']; ?></td>
                                <td><?= $data['nama']; ?></td>
                                <td><?= rp($data['harga']); ?></td>
                                <td><?= rp($data['diskon']); ?></td>
                                <td><?= $data['qty']; ?></td>
                                <td><?= ucwords($data['type']); ?></td>
                                <td><?= rp($data['total_harga']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm editbarang" data-target="#editbarangpenjualan" data-toggle="modal" data-id="<?= $data['id_barang']; ?>" data-barcode="<?= $data['barcode']; ?>" data-nama="<?= $data['nama']; ?>" data-harga="<?= $data['harga']; ?>" data-diskon="<?= $data['diskon']; ?>" data-qty="<?= $data['qty']; ?>"><i class='fas fa-edit'></i></a>
                                    <a href=" process/action?url=hapusbarangpenjualan&this=<?= $data['id_barang']; ?>&user=<?= $_SESSION['id_user'] ?>" class="btn btn-danger btn-sm"><i class='fas fa-trash-alt'></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="wrapper">
            <form action="process/action?url=tambahpenjualan" method="post">
                <div class="form-group row">
                    <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= tgl(date('d-m-Y')) ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_pelanggan" class="col-sm-3 col-form-label">Pelanggan</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                            <option value="">-- Select --</option>
                            <?php
                            $query_pelanggan = mysqli_query($con, "SELECT * FROM pelanggan WHERE id_pelanggan != '2' AND type='" . $_GET['type'] . "'");
                            foreach ($query_pelanggan as $ql) :
                            ?>
                                <option value="<?= $ql['id_pelanggan']; ?>">(<?= ucfirst(substr($ql['type'], 0 , 1)) ?>) <?= $ql['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_user" class="col-sm-3 col-form-label">Cashier</label>
                    <div class="col-sm-9">
                        <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                        <input type="text" class="form-control" id="id_user" value="<?= $_SESSION['nama'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_transaksi" class="col-sm-3 col-form-label">Total</label>
                    <div class="col-sm-9">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <?php $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM penjualan_temp WHERE id_user='" . $_SESSION['id_user'] . "'"))["total"]; ?>
                            <input type="text" class="form-control uang" id="total_transaksi" name="total_transaksi" value="<?= $total_transaksi ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_bayar" class="col-sm-3 col-form-label">Bayar</label>
                    <div class="col-sm-9">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="text" class="form-control uang" id="total_bayar" name="total_bayar" value="0" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_pelanggan" class="col-sm-3 col-form-label">Tipe</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="tipe_bayar" name="tipe_bayar" required>
                            <option value="">-- Select --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="form-row text-center">
                    <div class="col-12">
                        <?php
                        $cek = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penjualan_temp WHERE id_user='" . $_SESSION['id_user'] . "'"));
                        if (!isset($cek['id_user']) || $cek['id_user'] == 0) {
                            echo "<button type='submit' class='btn btn-primary btn-block' disabled><i class='fas fa-money-bill-alt mr-2'></i>Bayar</button>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-block'><i class='fas fa-money-bill-alt mr-2'></i>Bayar</button>";
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Barang -->
<div id="barang" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <table style="width: 100%;" id="barangTable" class="table table-bordered table-striped table-hover text-center mt-3">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <!-- <div class="modal-footer">
            </div> -->
        </div>
    </div>
</div>

<!-- Modal Edit Barang Penjualan -->
<div id="editbarangpenjualan" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Barang Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <form action="process/action?url=ubahbarangpenjualan" method="post">
                    <input type="hidden" class="form-control" name="ubah_id_user" value="<?= $_SESSION['id_user'] ?>">
                    <input type="hidden" class="form-control" name="ubah_id_barang" id="ubah_id_barang">
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
                        <label for="ubah_diskon" class="col-sm-2 col-form-label">Diskon/Pcs</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input type="text" class="form-control uang" id="ubah_diskon" name="ubah_diskon" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ubah_qty" class="col-sm-2 col-form-label">Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" min="1" class="form-control" id="ubah_qty" name="ubah_qty" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ubah_type" class="col-sm-2 col-form-label">Type Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ubah_type" name="ubah_type" value="<?= ucwords($_GET['type']) ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row text-center">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const sess_data = <?= json_encode($_SESSION) ?>;

    $(document).ready(function () {
        var dt = $('#barangTable').DataTable({
            dom: "Bfrtip",
            ajax: {
                url: 'process/action?url=getbarang',
                type: "POST",
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "barcode" },
                { data: "nama" },
                { data: "stok", className: "text-center", },
                { data: "aksi_pilih", className: "text-center", },
            ],
            ordering: false
        });
    });
</script>