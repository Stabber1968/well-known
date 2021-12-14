<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

$data=[];
$act = $_GET['act'];

if($act == "edit"){
    $id = $_GET['id'];
    $mUser = $user->find($id);
    $mUser = $mUser->results();
}

require_once('layout/header.php');
require_once('layout/navbar.php');
?>
    <body class="hold-transition sidebar-mini layout-fixed" id="page-top">
<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="dashbord_content">
                    <form method="post" action="../Logic/saveUser.php" enctype='multipart/form-data' id="editUserFormValidate">
                        <fieldset>
                            <legend class="hidden-first">
                                <?php
                                if($act == 'add') echo "Add New User";
                                if($act == 'edit') echo "Edit User";
                                ?>
                            </legend>
                            <input name="cat" type="hidden" value="users">
                            <input name="id" type="hidden" value="<?=$id?>">
                            <input name="act" type="hidden" value="<?=$act?>">

                            <label>Name</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Full name" id="fullName" name="fullName" value="<?=$mUser[0]->name?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <label>Email</label>
                            <div class="form-group input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email" value="<?=$mUser[0]->email?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            <label>Password</label>
                            <div class="form-group input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" id="password" name="password" value="<?=$mUser[0]->password?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" value=" Save " class="btn btn-success">
                            <a href="user.php" style="color: white" class="btn btn-success float-right">Back</a>

                    </form>
                </div>
            </div>
        </section>
    </div>

<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>