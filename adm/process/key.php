<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-user'></i> Reset Password Administrator</h3>
    </div>
</div>
<div class="wrapper">
    <form action="" method="post">
        <input type="hidden" name="id_user" value="1">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" value="administrator" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" minlength="6" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <br><input type="submit" name="proses" value="Simpan">
            </div>
        </div>

    </form>
</div>

<?php
require '../config/connect.php';

if(isset($_POST['proses'])){
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	mysqli_query($con, "UPDATE user SET  password='$password' WHERE id_user='".$_POST['id_user']."'");

echo "Password telah dirubah menjadi = <b>".$_POST['password']."</b>";

}

?>