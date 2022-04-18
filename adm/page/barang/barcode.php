<html>

<head>
    <style>
        p.inline {
            display: inline-block;
        }

        span {
            font-size: 13px;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */

        }
    </style>
</head>

<body onload="window.print();">
    <div style="margin-left: 5%">
        <?php
        include '../../config/barcode128.php';

        $barcode = $_POST['ubah_barcode'];
        $nama = $_POST['ubah_nama'];
        $het = $_POST['ubah_harga'];

        for ($i = 1; $i <= $_POST['ubah_qty']; $i++) {
            echo "<p class='inline'><span ><b>Name:<br>$nama</b></span>" . bar128(stripcslashes($barcode)) . "<span ><b>Price: Rp. " . $het . " </b><span></p>&nbsp&nbsp&nbsp&nbsp";
        }

        ?>
    </div>
</body>

</html>