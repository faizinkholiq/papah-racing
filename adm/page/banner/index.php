<link rel="stylesheet" href="assets/dropzone/dropzone.min.css" type="text/css" />
<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-images'></i> Data Banner</h3>
    </div>
</div>
<div class="wrapper">
    <div class="card bg-light mb-3">
        <div class="card-header font-weight-bolder">Maximal foto yang diupload untuk banner adalah 5</div><br>
        <div class="card-body" style="text-align: center">
            <div class="container">
                <div class="dropzone dz-clickable" id="myDrop">
                    <div class="dz-default dz-message" data-dz-message="">
                        <span>Drop files here to upload</span>
                    </div>
                </div>
                <input type="button" id="add_file" value="Upload Gambar" class="btn btn-primary mt-3">
            </div>
            <hr class="my-5">
            <div class="container-fluid mb-5">
                <a href="javascript:void(0);" class="btn btn-outline-primary reorder mb-3" id="updateReorder">Simpan urutan</a>
                <div id="reorder-msg" class="alert alert-warning mt-3">
                    <i class="fa fa-3x fa-exclamation-triangle float-right"></i> 1. Drag foto untuk menentukan urutan.<br>2. Klik 'Simpan urutan' ketika selesai.
                </div>
                <div class="gallery">
                    <ul class="row nav nav-pills" id="myGalery" style="gap: 1rem;">
                    <?php
                        //Fetch all images from database
                        $images = mysqli_query($con, "SELECT * FROM banner ORDER BY order_no ASC");
                        if(!empty($images)){
                            foreach($images as $row){
                        ?>
                        <li id="image_li_<?php echo $row['id']; ?>" class="col-lg-2 col-md-3 col-sm-12 ui-sortable-handle"
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
                            <img src="<?= SITEURL ?>/banner/<?php echo $row['photo']; ?>" alt="" class="img-thumbnail"
                            style="
                                border: none;
                                width: 100%;
                                height: 7rem;
                                padding: 0;
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
                        <?php
                            }
                        }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/dropzone/dropzone.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>

Dropzone.autoDiscover = false;

$(document).ready(function(){

    $("#myDrop").sortable({
        items: '.dz-preview',
        cursor: 'move',
        opacity: 0.5,
        containment: '#myDrop',
        distance: 20,
        tolerance: 'pointer',
    });

    $('#myGalery').sortable({
        tolerance: 'pointer',
        cursor: 'move',
    })
 
    $("#myDrop").disableSelection();

    $("#updateReorder").click(function( e ){
        if(!$("#updateReorder i").length){
            $(this).html('').prepend('<i class="fa fa-spin fa-spinner"></i>');
            $("ul.nav").sortable('destroy');
            $("#reorder-msg").html( "Reordering Photos - This could take a moment. Please don't navigate away from this page." ).removeClass('light_box').addClass('notice notice_error');
    
            var h = [];
            $("ul.nav li").each(function() {  h.push($(this).attr('id').substr(9));  });
                
            $.ajax({
                type: "POST",
                url: "<?=SITEURL?>",
                data: {ids: " " + h + ""},
                success: function(data){
                    if(data==1 || parseInt(data)==1){
                        //window.location.reload();
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
         url: "<?=SITEURL ?>",
    });
     
    myDropzone.on("sending", function(file, xhr, formData) {
      var filenames = [];
       
      $('.dz-preview .dz-filename').each(function() {
        filenames.push($(this).find('span').text());
      });
     
      formData.append('filenames', filenames);
    });
     
    /* Add Files Script*/
    myDropzone.on("success", function(file, message){
        $("#msg").html(message);
        setTimeout(function(){window.location.href="index.php"},200);
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
        console.log('hapus');
    }
}

</script>