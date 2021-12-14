<?php

require_once '../Controllers/init.php';

if(Input::exists()) {
    // Login user
    $user = new User();
    $remember = (Input::get('remember') === 'on') ? true : false;

    $login = $user->login(Input::get('email'), Input::get('password'), $remember);
    if($login) {
        Redirect::to('../Views/dashboard.php');
    } else {
        Redirect::to('../index.php?login=failed');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Weez Garden</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome/css/all.min.css">
    <!-- Ionicons -->
    <!--    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!--    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">-->

    <style>
        .login_layout {
            align-items: center;
            /*background: #e9ecef;*/
            display: flex;
            flex-direction: column;
            height: 100vh;
            /*justify-content: center;*/
            padding-top: 10%;
        }
    </style>
</head>


<body class="hold-transition login_layout">
<!--login-page-->
<div><img src="../Logic/image/logo.png"></div>
<div class="login-box" >
    <div class="login-logo">
        <b></b>
    </div>
    <!-- /.login-logo -->
    <div class="card">

        <div class="card-body login-card-body">
            <p class="login-box-msg">
                <?php
                if (isset($_GET['login']))
                {
                    if($_GET['login'] == 'failed')
                        echo 'Sorry, Login Failed';
                }else{
                    echo ' Sign in to start your session';
                }
                ?>
            </p>

            <form action="" method="post" id="quickForm">
                <div class="form-group input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email" name="email" id="eamil" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fa fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group input-group mb-3">
                    <!--autocomplete="off"-->
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4" style="top: 17px;">
                        <p class="mb-0">
                            <!--<a href="../Logic/register.php" class="text-center">Register a new membership</a>-->
                        </p>
                    </div>
                    <div class="col-4" >
                        <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </div>
                <p></p>
                <p></p>
                <p class="login-box-msg">* if you lose your password, please contract administrator</p>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- jquery-validation -->
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#quickForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 6 characters long"
                },

            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

</body>

</html>


