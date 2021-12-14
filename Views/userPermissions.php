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

$mUserPermissionsList = $user->getAllOfUserPermissionsInfo();
$mUserPermissionsList = $mUserPermissionsList->results();
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary" id="btn_addUserPermissions">
                        Add New Name Permissions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">This table includes <?php echo $user->count(); ?> permissions.</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="display:none">id</th>
                                    <th>Name</th>
                                    <th>Mother Room</th>
                                    <th>Clone Room</th>
                                    <th>Veg Room</th>
                                    <th>Flower Room</th>
                                    <th>Dry Room</th>
                                    <th>Trimming Room</th>
                                    <th>Packing Room</th>
                                    <th>Vault Room</th>
                                    <th>Sales</th>
                                    <th>History</th>
                                    <th>Client</th>
                                    <th>Permissions</th>
                                    <th>Genetic</th>
                                    <th>User</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($mUserPermissionsList) {
                                    $k = 0;
                                    foreach ($mUserPermissionsList as $mUserPermissions) :
                                        $k++;
                                ?>
                                        <tr>
                                            <td><?php echo $k ?></td>
                                            <td style="display:none" class="td_id"><?php echo $mUserPermissions->id ?></td>
                                            <td class="td_name"><?php echo $mUserPermissions->name ?></td>
                                            <td class="td_mother_room"><?php echo $mUserPermissions->mother ?></td>
                                            <td class="td_clone_room"><?php echo $mUserPermissions->clone ?></td>
                                            <td class="td_veg_room"><?php echo $mUserPermissions->veg ?></td>
                                            <td class="td_flower_room"><?php echo $mUserPermissions->flower ?></td>
                                            <td class="td_dry"><?php echo $mUserPermissions->dry ?></td>
                                            <td class="td_trimming"><?php echo $mUserPermissions->trimming ?></td>
                                            <td class="td_packing"><?php echo $mUserPermissions->packing ?></td>
                                            <td class="td_vault"><?php echo $mUserPermissions->vault ?></td>
                                            <td class="td_sell"><?php echo $mUserPermissions->sell ?></td>
                                            <td class="td_history"><?php echo $mUserPermissions->history ?></td>
                                            <td class="td_client"><?php echo $mUserPermissions->client ?></td>
                                            <td class="td_setting"><?php echo $mUserPermissions->setting ?></td>
                                            <td class="td_genetic"><?php echo $mUserPermissions->genetic ?></td>
                                            <td class="td_user"><?php echo $mUserPermissions->user ?></td>
                                            <td style="text-align: center">
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editUserPermissions" data-target="#modal-add-user-permissions" href="#modal-add-user-permissions" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>
                                                <a class="btn bg-gradient-danger btn-sm" href="../Logic/saveUser.php?act=delete&id=<?php echo $mUserPermissions->id ?>&cat=permissions">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                            </td>

                                        </tr>
                                <?php endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- End content -->


</div>

<!-- User modal (Add/Edit) -->
<div class="modal fade" id="modal-add-user-permissions">
    <div class="modal-dialog width-modal-user-permission_org">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">User Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveUser.php" enctype='multipart/form-data' id="editUserPermissionsFormValidate">

                <!--body-->
                <div class="modal-body">
                    <fieldset>
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

                        <div class="row">
                            <div class="col-6">
                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_mother" type="checkbox" id="checkbox_mother" value="1">
                                        <label for="checkbox_mother">
                                        </label>
                                    </div>
                                    <label>Mother Room</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_clone" type="checkbox" id="checkbox_clone" value="1">
                                        <label for="checkbox_clone">
                                        </label>
                                    </div>
                                    <label>Clone Room</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_veg" type="checkbox" id="checkbox_veg" value="1">
                                        <label for="checkbox_veg">
                                        </label>
                                    </div>
                                    <label>Veg Room</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_flower" type="checkbox" id="checkbox_flower" value="1">
                                        <label for="checkbox_flower">
                                        </label>
                                    </div>
                                    <label>Flower Room</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_dry" type="checkbox" id="checkbox_dry" value="1">
                                        <label for="checkbox_dry">
                                        </label>
                                    </div>
                                    <label>Dry</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_trimming" type="checkbox" id="checkbox_trimming" value="1">
                                        <label for="checkbox_trimming">
                                        </label>
                                    </div>
                                    <label>Trimming</label>
                                </div>
                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_packing" type="checkbox" id="checkbox_packing" value="1">
                                        <label for="checkbox_packing">
                                        </label>
                                    </div>
                                    <label>Packing</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_vault" type="checkbox" id="checkbox_vault" value="1">
                                        <label for="checkbox_vault">
                                        </label>
                                    </div>
                                    <label>Vault</label>
                                </div>
                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_sell" type="checkbox" id="checkbox_sell" value="1">
                                        <label for="checkbox_sell">
                                        </label>
                                    </div>
                                    <label>Sales</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_history" type="checkbox" id="checkbox_history" value="1">
                                        <label for="checkbox_history">
                                        </label>
                                    </div>
                                    <label>History</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_client" type="checkbox" id="checkbox_client" value="1">
                                        <label for="checkbox_client">
                                        </label>
                                    </div>
                                    <label>Client</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_setting" type="checkbox" id="checkbox_setting" value="1">
                                        <label for="checkbox_setting">
                                        </label>
                                    </div>
                                    <label>Permissions</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_genetic" type="checkbox" id="checkbox_genetic" value="1">
                                        <label for="checkbox_genetic">
                                        </label>
                                    </div>
                                    <label>Genetic</label>
                                </div>

                                <div class="col-12">
                                    <div class="icheck-success d-inline">
                                        <input class="plantCheckBoxTotal" name="checkPermissions_user" type="checkbox" id="checkbox_user" value="1">
                                        <label for="checkbox_user">
                                        </label>
                                    </div>
                                    <label>User</label>
                                </div>


                            </div>

                        </div>


                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="saveUserPermissions" class="btn btn-primary" value="Save"></input>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- /.modal -->


<script>
    $(document).ready(function() {

        $("#example1").DataTable({
            stateSave: true,
        });

        $("#btn_addUserPermissions").click(function() {
            var act = "addUserPermissions"
            $("#act").val(act);
            $("#modal-add-user-permissions").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editUserPermissions').click(function() {
            var act = "editPermissions";
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var name = $row.find('.td_name').text();
            var mother_room_permission = $row.find('.td_mother_room').text();
            var clone_room_permission = $row.find('.td_clone_room').text();
            var veg_room_permission = $row.find('.td_veg_room').text();
            var flower_room_permission = $row.find('.td_flower_room').text();
            var dry_permission = $row.find('.td_dry').text();
            var trimming_permission = $row.find('.td_trimming').text();
            var packing_permission = $row.find('.td_packing').text();
            var vault_permission = $row.find('.td_vault').text();
            var sell_permission = $row.find('.td_sell').text();
            var history_permission = $row.find('.td_history').text();
            var client_permission = $row.find('.td_client').text();
            var setting_permission = $row.find('.td_setting').text();
            var genetic_permission = $row.find('.td_genetic').text();
            var user_permission = $row.find('.td_user').text();

            $("#id").val(id);
            $("#act").val(act);

            $("#name").val(name);
            if (mother_room_permission) {
                $("#checkbox_mother").prop("checked", true);
            }
            if (mother_room_permission) {
                $("#checkbox_mother").prop("checked", true);
            }
            if (mother_room_permission) {
                $("#checkbox_mother").prop("checked", true);
            }
            if (clone_room_permission) {
                $("#checkbox_clone").prop("checked", true);
            }
            if (veg_room_permission) {
                $("#checkbox_veg").prop("checked", true);
            }
            if (flower_room_permission) {
                $("#checkbox_flower").prop("checked", true);
            }
            if (dry_permission) {
                $("#checkbox_dry").prop("checked", true);
            }
            if (trimming_permission) {
                $("#checkbox_trimming").prop("checked", true);
            }
            if (packing_permission) {
                $("#checkbox_packing").prop("checked", true);
            }
            if (vault_permission) {
                $("#checkbox_vault").prop("checked", true);
            }
            if (sell_permission) {
                $("#checkbox_sell").prop("checked", true);
            }
            if (history_permission) {
                $("#checkbox_history").prop("checked", true);
            }
            if (client_permission) {
                $("#checkbox_client").prop("checked", true);
            }
            if (setting_permission) {
                $("#checkbox_setting").prop("checked", true);
            }
            if (genetic_permission) {
                $("#checkbox_genetic").prop("checked", true);
            }
            if (user_permission) {
                $("#checkbox_user").prop("checked", true);
            }
        });

        //modal close
        $('#modal-add-user-permissions').on('hidden.bs.modal', function() {
            $("#name").val("");
            $("#checkbox_mother").prop("checked", false);
            $("#checkbox_clone").prop("checked", false);
            $("#checkbox_veg").prop("checked", false);
            $("#checkbox_flower").prop("checked", false);
            $("#checkbox_dry").prop("checked", false);
            $("#trimming_permission").prop("checked", false);
            $("#checkbox_vault").prop("checked", false);
            $("#checkbox_sell").prop("checked", false);
            $("#checkbox_history").prop("checked", false);
            $("#checkbox_client").prop("checked", false);
            $("#checkbox_setting").prop("checked", false);
            $("#checkbox_genetic").prop("checked", false);
            $("#checkbox_user").prop("checked", false);
        })
    });
</script>


<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>