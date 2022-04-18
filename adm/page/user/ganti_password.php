<?php
if (empty($_GET['url'])) {
    header('location:../main');
}
$id_user = $_SESSION['id_user'];
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM user WHERE id_user='$id_user' "));
?>

<div class="row">
    <div class="col-8">
        <h3 class="font-weight-bolder"><i class='fas fa-user'></i> Ganti Password User</h3>
    </div>
    <div class="col-4"><a href="main?url=user" class="btn btn-danger float-right"><i class='fas fa-times-circle mr-2'></i>Back</a></div>
</div>
<div class="wrapper">
    <form action="process/action?url=gantipassworduser" method="post">
        <input type="hidden" name="id_user" value="<?= $id_user; ?>">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" value="<?= $data['username']; ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="password_lama" class="col-sm-2 col-form-label">Password Lama</label>
            <div class="col-sm-10">
                <input type="password" minlength="6" class="form-control" id="password_lama" name="password_lama" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password Baru</label>
            <div class="col-sm-10">
                <input type="password" minlength="6" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password2" class="col-sm-2 col-form-label">Re-type Password</label>
            <div class="col-sm-10">
                <input type="password" minlength="6" class="form-control" id="password2" name="password2" required>
            </div>
        </div>
        <div class="form-row text-center">
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class='fas fa-save mr-2'></i>Simpan</button>
            </div>
        </div>

    </form>
</div>