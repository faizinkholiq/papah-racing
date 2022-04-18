<h3>Upload Dokumen<h3>

<form action="" method="POST" enctype="multipart/form-data">
<b>File Upload</b> <input type="file" name="namafile">
<input type="submit" name="proses" value="Upload">
</form>

<?php
if(isset($_POST['proses'])){
$direktori = "../";
$file_name = $_FILES['namafile']['name'];
move_uploaded_file($_FILES['namafile']['tmp_name'],$direktori.$file_name);

echo "<b>File berhasil diupload";
}
?>