<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-cart'></i> Tambah Pembelian</h3>
    </div>
    <div class="col-4">
        <a href="main?url=pembelian" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="wrapper">
            <form action="process/action?url=tambahbarangpembelian" method="post">
                <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                <input type="hidden" class="form-control" name="qty" value="1">
                <div class="row">
                    <div class="col-9 col-lg-11">
                        <div class="form-group row">
                            <!-- <label for="barcode" class="col-sm-2 col-form-label">Barcode</label> -->
                            <!-- <div class="col-sm-10"> -->
                            <input type="text" class="form-control ml-2" id="barcode" name="barcode" autocomplete="off" placeholder="Scan Barcode" required>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="col-3 col-lg-1">
                        <a href="#" class="btn btn-primary" data-target="#barang" data-toggle="modal"><i class='fas fa-search'></i></a>
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label for="barcode" class="col-sm-2 col-form-label">Barcode</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="barcode" name="barcode" autocomplete="off" required>
                    </div>
                </div> -->
                <!-- <div class="form-group row">
                    <label for="qty" class="col-sm-2 col-form-label">Quantity</label>
                    <div class="col-sm-10">
                        <input type="number" min="1" class="form-control" id="qty" name="qty" required>
                    </div>
                </div> -->
                <div class="form-row text-center">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success"><i class='fas fa-plus-circle mr-2'></i>Tambah Barang</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mt-3" style="width:100%">
                    <thead>
                        <tr class="text-center">
                            <th>Barcode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Quantity</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM pembelian_temp INNER JOIN barang ON pembelian_temp.id_barang=barang.id_barang WHERE id_user='" . $_SESSION['id_user'] . "'");
                        foreach ($query as $data) :
                        ?>
                            <tr class="text-center">
                                <td class="text-left"><?= $data['barcode']; ?></td>
                                <td class="text-left"><?= $data['nama']; ?></td>
                                <td><?= rp($data['modal']); ?></td>
                                <td><?= $data['qty']; ?></td>
                                <td><?= rp($data['total_harga']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm editbarang" data-target="#editbarangpembelian" data-toggle="modal" data-id="<?= $data['id_barang']; ?>" data-barcode="<?= $data['barcode']; ?>" data-nama="<?= $data['nama']; ?>" data-harga="<?= $data['modal']; ?>" data-qty="<?= $data['qty']; ?>"><i class='fas fa-edit'></i></a>
                                    <a href="process/action?url=hapusbarangpembelian&this=<?= $data['id_barang']; ?>&user=<?= $_SESSION['id_user'] ?>" class="btn btn-danger btn-sm"><i class='fas fa-trash-alt'></i></a>
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
            <form action="process/action?url=tambahpembelian" method="post">
                <div class="form-group row">
                    <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= tgl(date('d-m-Y')) ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_supplier" class="col-sm-2 col-form-label">Supplier</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="id_supplier" name="id_supplier" required>
                            <option value="">-- Select --</option>
                            <?php
                            $query_supplier = mysqli_query($con, "SELECT * FROM supplier");
                            foreach ($query_supplier as $qs) :
                            ?>
                                <option value="<?= $qs['id_supplier']; ?>"><?= $qs['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_user" class="col-sm-2 col-form-label">Cashier</label>
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                        <input type="text" class="form-control" id="id_user" value="<?= $_SESSION['nama'] ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_transaksi" class="col-sm-2 col-form-label">Total</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <?php $total_transaksi = mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(total_harga) AS total FROM pembelian_temp WHERE id_user='" . $_SESSION['id_user'] . "'"))["total"]; ?>
                            <input type="text" class="form-control uang" id="total_transaksi" name="total_transaksi" value="<?= $total_transaksi ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_bayar" class="col-sm-2 col-form-label">Bayar</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp.</div>
                            </div>
                            <input type="text" class="form-control uang" id="total_bayar" name="total_bayar" value="0" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label for="total_kembalian" class="col-sm-2 col-form-label">Kembalian</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="total_kembalian" name="total_kembalian" readonly>
                    </div>
                </div> -->
                <div class="form-row text-center">
                    <div class="col-12">
                        <?php
                        $cek = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pembelian_temp WHERE id_user='" . $_SESSION['id_user'] . "'"));
                        if ($cek['id_user'] == 0) {
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
                <table class="table table-bordered table-striped table-hover text-center display mt-3">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM barang");
                        foreach ($query as $data) {
                        ?>
                            <tr class="text-left">
                                <td><?= $data['barcode']; ?></td>
                                <td><?= $data['nama']; ?></td>
                                <td class="text-center"><?= $data['stok']; ?></td>
                                <td class="text-center">
                                    <button id="pilihbarang" class="btn btn-sm btn-info" data-id="<?= $data['id_barang']; ?>" data-barcode="<?= $data['barcode']; ?>" data-nama="<?= $data['nama']; ?>" data-stok="<?= $data['stok']; ?>">Pilih</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- <div class="modal-footer">
            </div> -->
        </div>
    </div>
</div>

<!-- Modal Edit Barang Pembelian -->
<div id="editbarangpembelian" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Barang Pembelian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body table-responsive">
                <form action="process/action?url=ubahbarangpembelian" method="post">
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
                        <label for="ubah_qty" class="col-sm-2 col-form-label">Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" min="1" class="form-control" id="ubah_qty" name="ubah_qty" required>
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