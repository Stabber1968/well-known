<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if (!$user->isLoggedIn()) {
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

if ($user->data()->superAdmin == '1') {
    $mUserList = $user->getAllOfUsersInfo();
    $mUserLists = $mUserList->results();
} else {
    $mUserList = $p_general->getValueOfAnyTable('users', 'id', '=', $user->data()->id);
    $mUserLists = $mUserList->results();
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php if ($pUser->data()->superAdmin == '1') { ?>
                        <button type="button" class="btn btn-primary" id="btn_addUser">
                            Add New User
                        </button>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">This table includes <?php echo $user->count(); ?> users.</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="display:none">id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="display:none">password</th>
                                    <th style="display:none">Permissions id</th>
                                    <th>Permissions</th>
                                    <th>Language</th>
                                    <th></th>

                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                if ($mUserLists) {
                                    $k = 0;
                                    foreach ($mUserLists as $mUser) :
                                        $k++;
                                ?>
                                        <tr>
                                            <td><?php echo $k ?></td>
                                            <td style="display:none" class="td_id"><?php echo $mUser->id ?></td>
                                            <td class="td_name"><?php echo $mUser->name ?></td>
                                            <td class="td_email"><?php echo $mUser->email ?></td>
                                            <td style="display:none" class="td_password"><?php echo $mUser->password ?></td>
                                            <td style="display:none" class="td_permissions_id"><?php echo $mUser->permissions_id ?></td>
                                            <td class="td_permissions"><?php
                                                                        $permissionsInfo = $p_general->getValueOfAnyTable('user_permissions', 'id', '=', $mUser->permissions_id);
                                                                        $permissionsInfo = $permissionsInfo->results();
                                                                        echo $permissionsInfo[0]->name;
                                                                        ?></td>
                                            <td class="td_language"><?php echo $mUser->language ?></td>
                                            <td style="text-align: center">
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editUser" data-target="#modal-add-user" href="#modal-add-user" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>
                                                <?php if (!$mUser->superAdmin) { ?>
                                                    <a class="btn bg-gradient-danger btn-sm" href="../Logic/saveUser.php?act=delete&id=<?php echo $mUser->id ?>&cat=users">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- User modal (Add/Edit) -->
<div class="modal fade" id="modal-add-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Add New User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveUser.php" enctype='multipart/form-data' id="editUserFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="cat" type="hidden" value="users">
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="">

                        <label>Name</label>
                        <div class="form-group input-group mb-3">
                            <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <label>Email</label>
                        <div class="form-group input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <label>Password</label>
                        <div class="form-group input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <?php if ($pUser->data()->superAdmin == '1') { ?>
                            <label>Permissions</label>
                            <div class="form-group">
                                <select class="form-control select2bs4" name="permissions_id" id="permissions_id" style="width: 100%;">
                                    <option value="">Select Permissions</option>
                                    <?php
                                    $mPermissionsList = $p_general->getValueOfAnyTable('user_permissions', '1', '=', '1');
                                    $mPermissionsList = $mPermissionsList->results();

                                    foreach ($mPermissionsList as $mPermission) {
                                    ?>
                                        <option value="<?= $mPermission->id ?>"><?= $mPermission->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>

                        <label>Language</label>
                        <div class="form-group">
                            <select class="form-control select2bs4" name="language" id="language" style="width: 100%;">
                                <option value="English">English</option>
                                <option value="Portgual">Portugal </option>
                            </select>
                        </div>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="saveUser" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $("#example1").DataTable({
            stateSave: true,
        });

        $("#btn_addUser").click(function() {

            var act = "add"

            $("#act").val(act);
            $("#modal-add-user").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editUser').click(function() {

            var $row = $(this).closest('tr');

            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var act = "edit";
            var name = $row.find('.td_name').text();
            var email = $row.find('.td_email').text();
            var password = $row.find('.td_password').text();
            var permissions_id = $row.find('.td_permissions_id').text();
            var language = $row.find('.td_language').text();



            $("#id").val(id);
            $("#act").val(act);

            $("#name").val(name);
            $("#email").val(email);
            $("#password").val(password);

            $('#permissions_id').val(permissions_id);
            $('#permissions_id').select2().trigger('change');

            $('#language').val(language);
            $('#language').select2().trigger('change');


        });

        //modal close
        $('#modal-add-user').on('hidden.bs.modal', function() {
            $("#name").val("");
            $("#email").val("");

        })

        //submit
        $('input#saveUser').click(function(event) {

            var email = $('input[name=email]').val();
            var act = $('input[name=act]').val();

            if (act == 'add') {
                if (email) {
                    event.preventDefault();
                    $.ajax({
                        method: 'POST',
                        url: '../Logic/saveUser.php',
                        data: {
                            act: 'validate',
                            email: email
                        },
                        success: function(data) {

                            var obj = JSON.parse(data);

                            if (obj == 'SameEmail') {
                                alert('Exist Same Email');

                            } else {
                                $('#editUserFormValidate').submit();
                            }
                        }
                    })
                }
            }

            if (act == 'edit') {
                $('#editUserFormValidate').submit();
            }

            return false;
        })

    });
</script>


<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>