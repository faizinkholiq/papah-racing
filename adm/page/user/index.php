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
        <table id="userTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Bulan Ini</th>
                    <th>Bulan Lalu</th>
                    <th>Username</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Terakhir Login</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    const sess_data = <?= json_encode($_SESSION) ?>;
    const page = <?=isset($_GET["page"])? (int)$_GET["page"] : 0 ?>;
    
    let dt = $('#userTable').DataTable({
        dom: "ZBlrtip",
        ajax: {
            url: 'process/action?url=getuser',
            type: "POST"
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "row_no", orderable: false },
            { data: "nama" },
            { data: "bulan_ini", visible: false, },
            { data: "bulan_lalu", visible: false, },
            { data: "username" },
            { data: "alamat" },
            { data: "kontak" },
            { data: "jabatan", className: "text-center", },
            { data: "status", className: "text-center", },
            { data: "last_login", visible: false, className: "text-center", },
            { data: "aksi", visible: false, className: "text-center", orderable: false },
        ],
        ordering: true,
        order: [],
        bLengthChange: true,
        paging: true,
        lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
        pageLength: 10,
    });

    $(document).ready(function () {
        if (sess_data["id_jabatan"] == 1 || sess_data["id_jabatan"] == 2){
            dt.columns([2,3,9,10]).visible(true);
        }

        if(page != null && page != ""){
            setTimeout(() => {
                dt.page(page).draw(false);
            }, 100)
        }

        $('#userTable').on( 'page.dt', function () {
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
      $('#pelangganTable tbody tr').each(function( index ) {
        $('td', this ).first().html(index + 1);
      } );
    }

    function editUser(id) {
        const info = dt.page.info();
        const url = "main?url=ubah-user&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

    function hapusUser(id) {
        let ask = window.confirm("Anda yakin ingin hapus data ini?");
        if (ask) {
            const info = dt.page.info();
            const url = "process/action?url=hapususer&this="+id+"&page="+info.page
            window.open(url, "_self")
        }
    }

    function setAktif(id, aktif) {
        const info = dt.page.info();
        const url = "process/action?url=setaktifuser&this="+id+"&aktif="+aktif+"&page="+info.page
        window.open(url, "_self")
    }

    function resetPassword(id) {
        const info = dt.page.info();
        const url = "main?url=reset-password-user&this="+id+"&page="+info.page
        window.open(url, "_self")
    }

</script>