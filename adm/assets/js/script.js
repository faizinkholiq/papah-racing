$(document).ready(function() {
    $("table.display").DataTable({
      order: [],
    });
    
    $( '.uang' ).mask('000.000.000.000', {reverse: true});

    // pilih barang dari pencarian
    $(document).on('click', '#pilihbarang', function() {
      // var id_barang = $(this).data('id');
      var barcode = $(this).data('barcode');
      // var nama = $(this).data('nama');
      // var stok = $(this).data('stok');

      $('#barcode').val(barcode);
      $('#barang').modal('hide');
  });
  // mengambil nilai value untuk edit
  $(document).on('click', '.editbarang', function() {
    var id_barang = $(this).data('id');
    var barcode = $(this).data('barcode');
    var nama = $(this).data('nama');
    var harga = $(this).data('harga');
    var diskon = $(this).data('diskon');
    var qty = $(this).data('qty');
    var reverse = harga.toString().split('').reverse().join(''),
    harga_ribuan = reverse.match(/\d{1,3}/g);
    harga_ribuan = harga_ribuan.join('.').split('').reverse().join('');

    $('#ubah_id_barang').val(id_barang);
    $('#ubah_barcode').val(barcode);
    $('#ubah_nama').val(nama);
    $('#ubah_harga').val(harga_ribuan);
    $('#ubah_diskon').val(diskon);
    $('#ubah_qty').val(qty);

  });
//     var ctx = document.getElementById('myChart').getContext('2d');
// var myChart = new Chart(ctx, {
//     type: 'bar',
//     data: {
//         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
//         datasets: [{
//             label: '# of Votes',
//             data: [12, 19, 3, 5, 2, 3],
//             backgroundColor: [
//                 'rgba(255, 99, 132, 0.2)',
//                 'rgba(54, 162, 235, 0.2)',
//                 'rgba(255, 206, 86, 0.2)',
//                 'rgba(75, 192, 192, 0.2)',
//                 'rgba(153, 102, 255, 0.2)',
//                 'rgba(255, 159, 64, 0.2)'
//             ],
//             borderColor: [
//                 'rgba(255, 99, 132, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 206, 86, 1)',
//                 'rgba(75, 192, 192, 1)',
//                 'rgba(153, 102, 255, 1)',
//                 'rgba(255, 159, 64, 1)'
//             ],
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: {
//             y: {
//                 beginAtZero: true
//             }
//         }
//     }
// });
});