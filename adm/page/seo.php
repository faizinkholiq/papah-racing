<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM seo"));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-cog'></i> SEO</h3>
    </div>
    <div class="col-4"><a href="main?url=supplier" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <div class="form-group row">
        <label for="nama_toko" class="col-sm-2 col-form-label">Total Visitor</label>
        <div class="col-sm-10"><?= isset($data['visitor'])? $data['visitor'] : 0; ?></div>
    </div>
    <div class="form-group row">
        <label for="alamat_toko" class="col-sm-2 col-form-label">Barang Paling Banyak Terjual</label>
        <div class="col-sm-10">
        </div>
    </div>
    <div class="form-group row">
        <label for="kontak_toko" class="col-sm-2 col-form-label">Pelanggan dengan Pembelian Tertinggi</label>
        <div class="col-sm-10">
        </div>
    </div>
</div>

<script>
// $(document).ready(function(){
//     if(navigator.geolocation){
//         navigator.geolocation.getCurrentPosition(showLocation);
//     }else{ 
//         $('#location').html('Geolocation is not supported by this browser.');
//     }
// });

// function showLocation(position){
//     var latitude = position.coords.latitude;
//     var longitude = position.coords.longitude;
//     $.ajax({
//         type:'POST',
//         url:'process/action?url=getlocation',
//         data:'latitude='+latitude+'&longitude='+longitude,
//         success:function(msg){
//             if(msg){
//                $("#location").html(msg);
//             }else{
//                 $("#location").html('Not Available');
//             }
//         }
//     });
// }
</script>