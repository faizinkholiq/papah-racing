<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM seo WHERE id = 1"));

?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cog'></i> SEO</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>

<div class="wrapper mt-4">
    <div class="row" style="font-size: 1.3rem;">
        <div class="col-sm-2"><i class="fas fa-users mr-2"></i>Total Visitor</div>
        <div class="col-sm-10"><?= isset($data['visitor'])? $data['visitor'] : 0; ?></div>
    </div>
</div>

<div class="wrapper mt-4">
    <h4><i class='fas fa-box mr-2'></i> Barang Paling Banyak Dibeli</h4><hr/>
    <div class="table-responsive mt-3">
        <div class="mb-2 mt-4" style="display: flex; flex-direction: row; gap: 2rem;">
            <div class="form-group" style="display: flex; gap: 1rem;">
                <label class="col-form-label">Dari :</label>
                <input type="date" class="form-control" id="barangFrom" name="from" required style="width: 12rem;">
            </div>
            <div class="form-group" style="display: flex; gap: 1rem;">
                <label class="col-form-label">Sampai :</label>
                <input type="date" class="form-control" id="barangTo" name="to" required style="width: 12rem;">
            </div>
            <button onclick="doFilter('barang')" type="button" class="btn btn-primary" style="height: fit-content;"><i class='fas fa-search mr-2'></i>Cari</button>
            <div style="border-right: 1px solid #cccccc; margin-bottom: 1rem;"></div>
            <button onclick="resetFilter('barang')" type="button" class="btn btn-secondary" style="height: fit-content;"><i class='fas fa-redo mr-2'></i>Reload Data</button>
        </div>
        <table id="barangTable" class="table table-striped table-bordered mt-2" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Barcode</th>
                    <th>Barang</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="wrapper mt-4">
    <h4><i class='fas fa-handshake mr-2'></i> Pelanggan Paling Banyak melakukan Pembelian</h4><hr/>
    <div class="table-responsive mt-3">
        <div class="mb-2 mt-4" style="display: flex; flex-direction: row; gap: 2rem;">
            <div class="form-group" style="display: flex; gap: 1rem;">
                <label class="col-form-label">Dari :</label>
                <input type="date" class="form-control" id="pelangganFrom" name="from" required style="width: 12rem;">
            </div>
            <div class="form-group" style="display: flex; gap: 1rem;">
                <label class="col-form-label">Sampai :</label>
                <input type="date" class="form-control" id="pelangganTo" name="to" required style="width: 12rem;">
            </div>
            <button onclick="doFilter('pelanggan')" type="button" class="btn btn-primary" style="height: fit-content;"><i class='fas fa-search mr-2'></i>Cari</button>
            <div style="border-right: 1px solid #cccccc; margin-bottom: 1rem;"></div>
            <button onclick="resetFilter('pelanggan')" type="button" class="btn btn-secondary" style="height: fit-content;"><i class='fas fa-redo mr-2'></i>Reload Data</button>
        </div>
        <table id="pelangganTable" class="table table-striped table-bordered mt-2" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Nama</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    let dt_barang_params = {
        from: "",
        to: "",
    }

    let dt_pelanggan_params = {
        from: "",
        to: "",
    }

    let dt_barang = $('#barangTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=getmaxbarang',
            type: "POST",
            data: {
                from: () => dt_barang_params.from,
                to: () => dt_barang_params.to
            },
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "id_barang" },
            { data: "barcode" },
            { data: "nama" },
            { data: "total_penjualan" },
        ],
        ordering: false,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    let dt_pelanggan = $('#pelangganTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=getmaxpelanggan',
            type: "POST",
            data: {
                from: () => dt_pelanggan_params.from,
                to: () => dt_pelanggan_params.to
            },
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "id_pelanggan" },
            { data: "nama" },
            { data: "total_penjualan" },
        ],
        ordering: false,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    $(document).ready(function () {

        dt_barang.on( 'draw', function (a) {
          rewriteColNumbers('#barangTable')
          console.log(a);
        } );

        dt_pelanggan.on( 'draw', function () {
          rewriteColNumbers('#pelangganTable')
        } );

    });

    function rewriteColNumbers(table) {
      $(table + ' tbody tr').each(function( index ) {
        let val = $('td', this ).first().text();
        if (val != "No data available in table") {
            $('td', this ).first().html(index + 1);
        } 
      } );
    }

    function resetFilter(type) {
        switch(type){
            case 'barang':
                $('#barangFrom').val("");
                $('#barangTo').val("");
                dt_barang_params.from = "";
                dt_barang_params.to = "";
                dt_barang.ajax.reload();
                break;
            case 'pelanggan':
                $('#pelangganFrom').val("");
                $('#pelangganTo').val("");
                dt_pelanggan_params.from = "";
                dt_pelanggan_params.to = "";
                dt_pelanggan.ajax.reload();
                break;
        }
    }


    function doFilter(type) {
        switch(type){
            case 'barang':
                dt_barang_params.from = $('#barangFrom').val();
                dt_barang_params.to = $('#barangTo').val();
                
                if(dt_barang_params.from.length > 0 && dt_barang_params.to.length > 0) {
                    if(dt_barang_params.from <= dt_barang_params.to){
                        dt_barang.ajax.reload();
                    }else{
                        alert("Tanggal sampai harus lebih besar");
                    }
                }else{
                    alert("Tanggal Dari & Sampai harus diisi");
                }

                break;
            case 'pelanggan':
                dt_pelanggan_params.from = $('#pelangganFrom').val();
                dt_pelanggan_params.to = $('#pelangganTo').val();
                
                if(dt_pelanggan_params.from.length > 0 && dt_pelanggan_params.to.length > 0) {
                    if(dt_pelanggan_params.from <= dt_pelanggan_params.to){
                        dt_pelanggan.ajax.reload();
                    }else{
                        alert("Tanggal sampai harus lebih besar");
                    }
                }else{
                    alert("Tanggal Dari & Sampai harus diisi"); 
                }

                break;
        }
    }

</script>