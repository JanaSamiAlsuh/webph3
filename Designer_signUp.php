<?php
session_start();
require_once('includes/connection.php');
include('includes/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Designers | Registration Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="index.php"><b>Designer</b></a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register a new membership</p>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-group mb-3">
                    <input type="text" name="firstName" class="form-control" placeholder="First name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="lastName" class="form-control" placeholder="Last name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="emailAdress">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="ConfirmPassword" class="form-control" placeholder="Retype password" name="ConfirmPassword">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="brandName" class="form-control" placeholder="brand name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <label class="col-5">Brand Image</label>
                    <input type="file" name="logo" class="form-control ">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas logo-xl"></span>
                        </div>
                    </div>
                </div>

                <div><span class="text-danger" id="error"></span></div>
                <div class="row my-3">
                    <div class=" col-4">

                    </div>
                    <!-- /.col -->
                    <div class=" col-4">
                        <button name="submit" type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

<div>  <a href="designer_login.php" class="text-center">I already have a membership</a></div>

<div><a href="index.php" class="text-center">Back Home</a></div>

        </div>
        <!-- /.form-box -->

    </div><!-- /.card -->

</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<?php


if (!empty($_POST) && isset($_POST['submit'])) {

    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastname = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $emailAdress = isset($_POST['emailAdress']) ? $_POST['emailAdress'] : '';
    $brandName = isset($_POST['brandName']) ? $_POST['brandName'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] :
        '';
    $email = mysqli_real_escape_string($conn, $emailAdress);
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastname);
    $password = mysqli_real_escape_string($conn, $password);
    $check_exist = checkEmail($email, $conn);
    /// $check_user= checkUserName($user_name,$conn);
    if (!$check_exist) {
        echo '
<script>
document.getElementById("error").style.display="block";
document.getElementById("error").innerText="Email is already registered!";
</script>';
    } else if ($check_exist) {

        if (isset($_FILES['logo'])) {
            $errors = array();
            $file_name = $_FILES['logo']['name'];
            $file_size = $_FILES['logo']['size'];
            $file_tmp = $_FILES['logo']['tmp_name'];
            $file_type = $_FILES['logo']['type'];
            $array = explode('.', $_FILES['logo']['name']);
            $file_ext = strtolower(end($array));
            $extensions = array("jpeg", "jpg", "png");
            if (in_array($file_ext, $extensions) === false) {
                $errors[] =
                    "extension is not allowed,please choose a JPEG or PNG.";
            }
            if ($file_size > 2097152) {

                $errors[] =
                    'File size must not larger than 2MB';
            }
            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, "uploads/" . $file_name);
            } else {
                print_r($errors);
            }
        }
        $logoImgFileName = $file_name ? $file_name : "";
        $insert_std = insertDesigner($firstName, $lastname, $emailAdress, $brandName, $password, $logoImgFileName, $conn);
        /*UPLOad Files*/

        /******************/
        if ($insert_std > 0) {
            $_SESSION['designer_logged'] = true;
            $_SESSION['designer_id'] = $insert_std;
            $_SESSION['role'] = 'designer';
echo '<script>
    window.location="designer_home.php?designer='.$insert_std.'";</script>';




        }
    }
}
?>
<script>
    var password = document.getElementById("password")
        , confirm_password = document.getElementById("ConfirmPassword");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match  ");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
</body>
</html>
