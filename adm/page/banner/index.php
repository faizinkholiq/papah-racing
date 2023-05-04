<link rel="stylesheet" href="assets/dropzone/dropzone.min.css" type="text/css" />
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-images'></i> Data Banner</h3>
    </div>
</div>
<div class="wrapper">
    <div class="card bg-light mb-3">
        <div class="card-header font-weight-bolder">5 Teratas akan ditampilkan di home page</div><br>
        <div class="card-body" style="text-align: center">
            <div class="container">
                <div class="dropzone dz-clickable" id="myDrop">
                    <div class="dz-default dz-message" data-dz-message="">
                        <span>Drop files here to upload</span>
                    </div>
                </div>
                <div class="form-group row mt-4 col-sm-6">
                    <label class="col-sm-2 col-form-label">Type :</label>
                    <div class="col-sm-6">
                        <select id="typeBannerCombo" class="form-control" name="type">
                            <option value="Website">Website</option>
                            <option value="Distributor">Distributor</option>
                        </select>
                    </div>
                </div>
                <input type="button" id="add_file" value="Upload Gambar" class="btn btn-primary mt-3">
            </div>
            <hr class="my-5">
            <div class="container-fluid mb-5">
                <?php
                $contents = ["Website", "Distributor"];
                ?>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <?php foreach ($contents as $key => $value): ?>
                    <li class="nav-item">
                        <a onclick="changeType('<?= $value ?>')" class="nav-link <?= ($key == 0)? 'active' : '' ?>" id="pills-<?= strtolower($value) ?>-tab" data-toggle="pill" href="#pills-<?= strtolower($value) ?>" role="tab" aria-controls="pills-<?= strtolower($value) ?>" aria-selected="true"><?= $value ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <a href="javascript:void(0);" class="btn btn-outline-primary reorder mb-3" id="updateReorder">Simpan urutan</a>
                    <div id="reorder-msg" class="alert alert-warning mt-3">
                        <i class="fa fa-3x fa-exclamation-triangle float-right"></i> 1. Drag foto untuk menentukan urutan.<br>2. Klik 'Simpan urutan' ketika selesai.
                    </div>
                    <?php foreach ($contents as $key => $value): ?>       
                    <div class="tab-pane fade show <?= ($key == 0)? 'active' : '' ?>" id="pills-<?= strtolower($value) ?>" role="tabpanel" aria-labelledby="pills-<?= strtolower($value) ?>-tab">
                        <div class="gallery">
                            <?php
                            //Fetch all images from database
                            $images = mysqli_query($con, "SELECT * FROM banner WHERE type='".$value."' ORDER BY order_no ASC");
                            if(mysqli_num_rows($images) > 0){
                            ?>
                            <ul class="row nav nav-pills" id="myGalery<?= $value ?>" style="gap: 1rem;">
                            <?php foreach($images as $row){ ?>
                                <li data-id="<?= $row['id']; ?>" id="<?= strtolower($value) ?>_image_li_<?php echo $row['id']; ?>" class="col-lg-2 col-md-3 col-sm-12 ui-sortable-handle"
                                style="
                                    background: white;
                                    padding: 0;
                                    overflow: hidden;
                                    box-shadow: 1px 1px 10px 1px #aaa;
                                    border-radius: 10px;
                                    height: 10rem;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: flex-end;
                                    cursor: move;
                                ">
                                    <img src="<?= str_replace("admin.", "", SITEURL) ?>/banner/<?php echo $row['photo']; ?>" alt="" class="img-thumbnail"
                                    style="
                                        border: none;
                                        width: 100%;
                                        height: 7rem;
                                        padding: 0;
                                        object-fit: cover;
                                    ">
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus" 
                                        onclick="removeImage(<?= $row['id'] ?>)"
                                        style="
                                            width: 2.5rem;
                                            margin-top: 10px;
                                            margin-right: 10px;
                                        ">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </li>
                            <?php } ?>
                            </ul>
                            <?php } else { ?>
                            <h5 style="margin-top:3rem;">No Banner Found.</h5>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/dropzone/dropzone.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>

Dropzone.autoDiscover = false;
let banner_type = 'Website';

$(document).ready(function(){

    $("#myDrop").sortable({
        items: '.dz-preview',
        cursor: 'move',
        opacity: 0.5,
        containment: '#myDrop',
        distance: 20,
        tolerance: 'pointer',
    });

    $('#myGaleryWebsite').sortable({
        tolerance: 'pointer',
        cursor: 'move',
    })

    $('#myGaleryDistributor').sortable({
        tolerance: 'pointer',
        cursor: 'move',
    })
 
    $("#myDrop").disableSelection();

    $("#updateReorder").click(function( e ){
        if(!$("#updateReorder i").length){
            $(this).html('').prepend('<i class="fa fa-spin fa-spinner"></i>');
            $("#reorder-msg").html( "Reordering Photos - This could take a moment. Please don't navigate away from this page." ).removeClass('light_box').addClass('notice notice_error');
    
            var h = [];
            $("#myGalery"+banner_type+" li").each(function() {  h.push($(this).attr('data-id'));  });
                
            $.ajax({
                type: "POST",
                url: "<?=SITEURL?>/adm/process/action?url=update-order",
                data: {ids: " " + h + "", type: banner_type},
                success: function(data){
                    data = JSON.parse(data)
                    if(data.success == 1){
                        setTimeout(() => {
                            $("#updateReorder").html('Simpan urutan');                        
                        }, 500);
                    }else{
                        alert("Update order failed");
                        $("#updateReorder").html('Simpan urutan');
                    }
                }
            }); 
            return false;
        }       
        e.preventDefault();     
    });
     
    //Dropzone script     
    var myDropzone = new Dropzone("div#myDrop", 
    { 
         paramName: "files", // The name that will be used to transfer the file
         addRemoveLinks: true,
         uploadMultiple: true,
         autoProcessQueue: false,
         parallelUploads: 50,
         maxFilesize: 5, // MB
         acceptedFiles: ".png, .jpeg, .jpg, .gif",
         url: "<?=SITEURL ?>/adm/process/action?url=upload-banner",
    });
     
    myDropzone.on("sending", function(file, xhr, formData) {
      var filenames = [];
       
      $('.dz-preview .dz-filename').each(function() {
        filenames.push($(this).find('span').text());
      });
     
      formData.append('filenames', filenames);
      formData.append('type', $('#typeBannerCombo').val())
    });
     
    /* Add Files Script*/
    myDropzone.on("success", function(file, message){
        $("#msg").html(message);
        window.location.reload();
    });
      
    myDropzone.on("error", function (data) {
         $("#msg").html('<div class="alert alert-danger">There is some thing wrong, Please try again!</div>');
    });
      
    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
    });
      
    $("#add_file").on("click",function (){
        myDropzone.processQueue();
    });
     
});

function removeImage(id){
    if (window.confirm('Anda yakin ingin menghapus data ini?')){
        // They clicked Yes
        $.ajax({
            type: "GET",
            url: "<?=SITEURL?>/adm/process/action?url=hapus-banner&this="+id,
            success: function(data){
                window.location.reload();
            }
        }); 
    }
}

function changeType(val){
    banner_type = val;
}

</script>