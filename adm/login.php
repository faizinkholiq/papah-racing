<?php
require 'config/connect.php';
session_start();
if (isset($_SESSION['id_user'])) {
    header('location:main');
}
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM toko WHERE id_toko='1' "));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.css" />
    <link rel="stylesheet" href="assets/css/login.css">

    <link rel="icon" href="assets/img/logo.png">
    <title>Login</title>
</head>

<body>
    <div class="content text-center">
        <a href="<?=SITEURL?>"><img class="mb-4" src="assets/img/<?= $data['logo_header']; ?>" width="80%" alt="Logo"></a>
        <form action="process/action?url=login" method="post">
            <label class="sr-only" for="username">Username</label>
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class='fas fa-user'></i></div>
                </div>
                <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
            </div>
            <label class="sr-only" for="password">Password</label>
            <div class="input-group mb-2 mr-sm-2" style="border: 1px solid #ced4da; border-radius: .25rem;">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" style="border:none"><i class='fas fa-key'></i></div>
                    </div>
                    <input type="password" class="form-control" placeholder="Password*" name="password" id="password" data-toggle="password" style="border: none; border-left: 1px solid #ced4da;">
                    <div class="input-group-append">
                        <span class="input-group-text" style="background-color: #ffffff; border: none;"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-block"><i class='fas fa-sign-in-alt mr-2'></i>Login</button>
            </div>
            <!-- <input type="text" name="username" placeholder="Input your email or username"> -->
            <!-- <input type="password" name="password" placeholder="Input your password"> -->
            <!-- <input type="submit" name="login" value="login"> -->
        </form>
        <br>
        <p>Knalpot Racing Speedshop Group</p>
    </div>
</body>

<footer>
	<script src="<?=SITEURL?>/js/jquery.min.js"></script>
    <script src="<?=SITEURL?>/js/bootstrap-show-password.min.js"></script>;
</footer>

</html>