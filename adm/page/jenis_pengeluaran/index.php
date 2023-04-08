<?php
$query = mysqli_query($con, "SELECT * FROM pengeluaran_type ORDER BY id_pengeluaran_type DESC");
?>
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-shopping-basket'></i> Data Jenis Pengeluaran</h3>
    </div>
    <div class="col-4"><a href="index.php" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <a href="main?url=tambah-jenis-pengeluaran" class="btn btn-primary"><i class='fas fa-plus-circle mr-2'></i>Tambah Data</a>
    <div class="table-responsive mt-3">
        <table id="jenisPengeluaranTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th width="10">No.</th>
                    <th>Jenis</th>
                    <th width="250">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($query as $data) : ?>
                    <tr class="text-center">
                        <td><?= $no++; ?></td>
                        <td><?= $data['jenis']; ?></td>
                        <td>
                            <a href="main?url=ubah-jenis-pengeluaran&this=<?= $data['id_pengeluaran_type']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                            <a href="process/action?url=hapusjenispengeluaran&this=<?= $data['id_pengeluaran_type']; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" onclick="return confirm('Anda yakin ingin hapus data ini?')"><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    const sess_data = <?= json_encode($_SESSION) ?>;
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;

    let dt = $('#jenisPengeluaranTable').DataTable({
        dom: "ZBflrtip",
        ajax: {
            url: 'process/action?url=getjenispengeluaran',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no", orderable: false },
            { data: "jenis" },
            { data: "aksi", class:"text-center", orderable: false },
        ],
        ordering: true,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    $(document).ready(function () {
        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#jenisPengeluaranTable').on( 'page.dt', function () {
            const info = dt.page.info();
            const url = new URL(window.location);
            
            url.searchParams.set('page', info.page);
            window.history.pushState({}, '', url);
        });

        dt.on( 'draw', function () {
          rewriteColNumbers()
        } );
    });

    function rewriteColNumbers() {
      $('#merkTable tbody tr').each(function( index ) {
        let val = $('td', this ).first().text();
        if (val != "No data available in table") {
            $('td', this ).first().html(index + 1);
        }
      } );
    }

    function editJenisPengeluaran(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-jenis-pengeluaran&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusJenisPengeluaran(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapusjenispengeluaran&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

</script>