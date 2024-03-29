<?php 
$data = mysqli_query($con, "SELECT * FROM penjualan WHERE (daily != true OR daily IS NULL)");
$num_data = mysqli_num_rows($data);
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-calendar-alt'></i> Laporan Harian</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="row">
        <div class="col-lg-2">
            <a href="process/action?url=approveharian" class="btn <?= ($num_data < 1)? 'disabled btn-secondary' : 'btn-success' ?>">
                <span class="text-white font-weight-bold"><i class='fas fa-check mr-2'></i>Approve</span>
            </a>
        </div>
        <div class="col-lg-8 mb-4">
            <div class="row">
                <div class="col-lg-4 font-weight-bold" style="border-right:2px solid #eee;">
                    <div style="font-size: 1.1rem;" class="text-center">Cash :</div>
                    <div id="total_cash" style="font-size: 1.5rem;" class="text-center mt-2">-</div>
                </div>
                <div class="col-lg-4 font-weight-bold">
                    <div style="font-size: 1.1rem;" class="text-center">Transfer :</div>
                    <div id="total_transfer" style="font-size: 1.5rem;" class="text-center mt-2">-</div>
                </div>
                <div class="col-lg-4 font-weight-bold" style="border-left:2px solid #eee;">
                    <div style="font-size: 1.1rem;" class="text-center">MarketPlace :</div>
                    <div id="total_marketplace" style="font-size: 1.5rem;" class="text-center mt-2">-</div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table id="penjualanTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                    <th>Total Bayar</th>
                    <th width="10">Tipe Bayar</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<script>
    const sess_data = <?= json_encode($_SESSION) ?>;

    $(document).ready(function () {
        var dt = $('#penjualanTable').DataTable({
            dom: "ZBflrtip",
            ajax: {
                url: 'process/action?url=getlaporanharian',
                type: "POST",
            },
            initComplete: function( settings, json){
                $('#total_cash').text(json.summary.cash)
                $('#total_transfer').text(json.summary.transfer)
                $('#total_marketplace').text(json.summary.marketplace)
            },
            processing: true,
            serverSide: true,
            columns: [
                { data: "no_faktur" },
                { data: "tanggal" },
                { data: "pelanggan" },
                { data: "type" },
                { data: "status", className: "text-center", },
                { data: "total_transaksi" },
                { data: "total_bayar" },
                { data: "tipe_bayar", className: "text-center", },
                { data: "user", className: "text-center", },
            ],
            ordering: true,
            order: [],
            bLengthChange: true,
            paging: true,
            lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
            pageLength: 10,
        });
    });
</script>