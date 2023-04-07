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
    <h4><i class='fas fa-box mr-2'></i> Barang Paling Banyak Dibeli</h4>
    <div class="table-responsive mt-3">
        <table id="barangTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
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
    <h4><i class='fas fa-handshake mr-2'></i> Pelanggan Paling Banyak melakukan Pembelian</h4>
    <div class="table-responsive mt-3">
        <table id="pelangganTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    
    let dt_barang = $('#barangTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=getmaxbarang',
            type: "POST"
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
            type: "POST"
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

        dt_barang.on( 'draw', function () {
          rewriteColNumbers('#barangTable')
        } );

        dt_pelanggan.on( 'draw', function () {
          rewriteColNumbers('#pelangganTable')
        } );

    });

    function rewriteColNumbers(table) {
      $(table + ' tbody tr').each(function( index ) {
        $('td', this ).first().html(index + 1);
      } );
    }

</script>