<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$pUser = new User();

$currentUserInfo = $pUser->data();
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 color-sidebar-background">
    <!-- Brand Logo -->
    <a href="./dashboard.php" class="brand-link">
        <span class="brand-text font-weight-light" style='font-size:35px;color:white;'>Data Garden</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php
                    echo "$currentUserInfo->name";
                    ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($pUser->data()->superAdmin == '1') { ?>
                    <li class="nav-item" id="li_dashaboard_admin">
                        <a href="../Views/dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-chart-area"></i>
                            <p>
                                Admin Dashboard
                            </p>
                        </a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item" id="li_dashaboard_user">
                        <a href="../Views/dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                User Dashboard
                            </p>
                        </a>
                    </li>
                <?php
                }
                ?>
                <!---
                <li class="nav-header"></li>
                <li class="nav-item has-treeview" id="ul_root_r">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cannabis "></i>
                        <p>
                            Registers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" id="ul_aaa">
                            <a href="test.php" class="nav-link">
                                <i class="nav-icon fas fa-tint"></i>
                                <p>Lab</p>
                            </a>
                        </li>
                        <li class="nav-item" id="ul_bbb">
                            <a href="test.php" class="nav-link">
                                <i class="nav-icon fas fa-tint"></i>
                                <p>Capa</p>
                            </a>
                        </li>

                    </ul>
                </li>
--->
                <?php
                if (
                    $pUser->hasPemissions('mother') ||
                    $pUser->hasPemissions('clone') ||
                    $pUser->hasPemissions('veg') ||
                    $pUser->hasPemissions('flower')
                ) {
                ?>
                    <li class="nav-header"></li>
                    <li class="nav-item has-treeview" id="ul_root_g">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cannabis "></i>
                            <p>
                                <?= $_SESSION['lang_Grow_Room'] ?>
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($pUser->hasPemissions('mother')) { ?>
                                <li class="nav-item" id="ul_role">
                                    <a href="plantsMother.php" class="nav-link ">
                                        <i class="nav-icon fas fa-female "></i>
                                        <p> <?= $_SESSION['lang_Mother_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('clone')) { ?>
                                <li class="nav-item" id="ul_clone">
                                    <a href="plantsClone.php" class="nav-link">
                                        <i class="nav-icon fas fa-transgender "></i>
                                        <p><?= $_SESSION['lang_Clone_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('veg')) { ?>
                                <li class="nav-item" id="ul_vegetation">
                                    <a href="plantsVeg.php" class="nav-link">
                                        <i class="nav-icon fas fa-leaf "></i>
                                        <p><?= $_SESSION['lang_Vegetation_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('flower')) { ?>
                                <li class="nav-item" id="ul_flower">
                                    <a href="plantsFlower.php" class="nav-link">
                                        <i class="nav-icon fas fa-tree "></i>
                                        <p><?= $_SESSION['lang_Flower_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php
                if (
                    $pUser->hasPemissions('dry') ||
                    $pUser->hasPemissions('trimming') ||
                    $pUser->hasPemissions('packing') ||
                    $pUser->hasPemissions('vault')
                ) {
                ?>
                    <li class="nav-header"></li>
                    <li class="nav-item has-treeview" id="ul_root_h">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cannabis "></i>
                            <p>
                                <?= $_SESSION['lang_Havests'] ?>
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($pUser->hasPemissions('dry')) { ?>
                                <li class="nav-item" id="ul_dry">
                                    <a href="plantsDry.php" class="nav-link">
                                        <i class="nav-icon fas fa-tint"></i>
                                        <p><?= $_SESSION['lang_Dry_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('trimming')) { ?>
                                <li class="nav-item" id="ul_trimming">
                                    <a href="plantsTrimming.php" class="nav-link">
                                        <i class="nav-icon fa fa-cut"></i>
                                        <p><?= $_SESSION['lang_Trimming_Plants'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('packing')) { ?>
                                <li class="nav-item" id="ul_packing">
                                    <a href="plantsPacking.php" class="nav-link">
                                        <i class="nav-icon fab fa-dropbox"></i>
                                        <p><?= $_SESSION['lang_Packing'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($pUser->hasPemissions('vault')) { ?>
                                <li class="nav-item" id="ul_vault">
                                    <a href="plantsVault.php" class="nav-link">
                                        <i class="nav-icon fas fa-lock"></i>
                                        <p><?= $_SESSION['lang_Vault'] ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <li class="nav-header"></li>
                <?php if ($pUser->hasPemissions('sell')) { ?>
                    <li class="nav-item" id="ul_sell">
                        <a href="plantsSell.php" class="nav-link">
                            <i class="nav-icon fas fa-euro-sign "></i>
                            <p><?= $_SESSION['lang_Sales'] ?></p>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($pUser->hasPemissions('history')) { ?>
                    <li class="nav-item" id="ul_history">
                        <a href="history.php" class="nav-link">
                            <i class="nav-icon fas fa-binoculars "></i>
                            <p><?= $_SESSION['lang_History'] ?></p>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($pUser->hasPemissions('client')) { ?>
                    <li class="nav-item" id="ul_client">
                        <a href="client.php" class="nav-link">
                            <i class="nav-icon fas fa-users "></i>
                            <p><?= $_SESSION['lang_Client'] ?></p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-header"></li>

                <li class="nav-item has-treeview" id="ul_root_s">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs "></i>
                        <p>
                            <?= $_SESSION['lang_Settings'] ?>
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($pUser->hasPemissions('genetic')) { ?>
                            <li class="nav-item" id="li_genetic">
                                <a href="genetic.php" class="nav-link">
                                    <i class="nav-icon fas fa-cog "></i>
                                    <p>
                                        <?= $_SESSION['lang_Genetic'] ?>
                                    </p>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($pUser->hasPemissions('user')) { ?>
                        <?php } ?>
                        <li class="nav-item" id="li_user">
                            <a href="../Views/user.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    <?= $_SESSION['lang_User_Management'] ?>
                                </p>
                            </a>
                        </li>


                        <?php if ($pUser->hasPemissions('setting')) { ?>
                            <li class="nav-item" id="li_user_permissions">
                                <a href="../Views/userPermissions.php" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        <?= $_SESSION['lang_User_Permissions'] ?>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item" id="li_backup">
                                <a href="../Views/backup.php" class="nav-link">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>
                                        <?= $_SESSION['lang_backup'] ?>
                                    </p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-header">Logout</li>
                <li class="nav-item">
                    <a href="../Logic/logout.php" class="nav-link" name="logout">
                        <i class="nav-icon 	fas fa-sign-out-alt " style="
                                    font-size: 1.1rem;
                                    margin-right: .2rem;
                                    text-align: center;
                                    width: 1.6rem;"></i>
                        <p>
                            <?= $_SESSION['lang_Log_out'] ?>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $(function() {
        // $('#example2').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": false,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        // });
        //Initialize Select2 Elements
        //$('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        //User
        $('#editUserFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                },
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Your password must be at least 6 characters long"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Mother
        $('#editMotherRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editMotherPlantFormValidate').validate({
            rules: {
                qr_code: {
                    required: true,
                },
                name: {
                    required: true,
                },
                plant_UID: {
                    required: true,
                },
                quantity: {
                    required: true,
                    integer: true,
                },
                location: {
                    required: true,
                },
                genetic: {
                    required: true,
                },
                name: {
                    required: true,
                },
                seed: {
                    required: true,
                },
                planting_date: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Clone
        $('#editClonePlantFormValidate').validate({
            rules: {
                select_mother_id_add: {
                    required: true,
                },
                genetic_name: {
                    required: true,
                },
                quantity: {
                    required: true,
                    integer: true,
                },
                location: {
                    required: true,
                },
                planting_date: {
                    required: true,
                },
                name: {
                    required: true,
                },
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editCloneRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },

            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //dd
        $('#cloneTransferFormValidate').validate({
            rules: {
                start_plant_UID: {
                    required: true,
                },
                end_plant_UID: {
                    required: true,
                },
                veg_room_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Vegetation
        $('#editVegPlantFormValidate').validate({
            rules: {
                select_mother_id: {
                    required: true,
                },
                genetic_name: {
                    required: true,
                },
                name: {
                    required: true,
                },
                quantity: {
                    required: true,
                },
                location: {
                    required: true,
                },
                planting_date: {
                    required: true,
                }
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editVegRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#vegTrnasferFormValidate').validate({
            rules: {
                start_lot_ID: {
                    required: true,
                },
                end_lot_ID: {
                    required: true,
                },
                flower_room_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        //Flower
        $('#editFlowerPlantFormValidate').validate({
            rules: {
                select_mother_id: {
                    required: true,
                },
                genetic_name: {
                    required: true,
                },
                name: {
                    required: true,
                },
                quantity: {
                    required: true,
                },
                location: {
                    required: true,
                },
                planting_date: {
                    required: true,
                }
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editFlowerRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#flowerTrnasferFormValidate').validate({
            rules: {
                start_lot_ID: {
                    required: true,
                },
                end_lot_ID: {
                    required: true,
                },
                dry_room_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editClientFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editGeneticFormValidate').validate({
            rules: {
                genetic_name: {
                    required: true,
                },
                plant_name: {
                    required: true,
                },
                grams: {
                    required: true,
                    integer: true,
                },
                htc: {
                    required: true,
                    integer: true,
                },
                cbd: {
                    required: true,
                    integer: true,
                },
                photo_clone: {
                    required: true,
                    integer: true,
                },
                photo_veg: {
                    required: true,
                    integer: true,
                },
                photo_flower: {
                    required: true,
                    integer: true,
                },
                other: {
                    required: true,
                    integer: true,
                },
            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#dryPackingFormValidate').validate({
            rules: {
                start_lot_ID: {
                    required: true,
                },
                end_lot_ID: {
                    required: true,
                },
                trimming_room_ID: {
                    required: true,
                },
                dry_method: {
                    required: true,
                },
            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        //Dry
        $('#editDryPlantFormValidate').validate({
            rules: {
                select_mother_id: {
                    required: true,
                },
                genetic_name: {
                    required: true,
                },
                name: {
                    required: true,
                },
                quantity: {
                    required: true,
                },
                location: {
                    required: true,
                },
                planting_date: {
                    required: true,
                }
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editDryRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        $('#LotFormValidate').validate({
            rules: {
                packing_genetic_id: {
                    required: true,
                },
                clones_quantity: {
                    required: true,
                },
                location_create: {
                    required: true,
                },
                born_date: {
                    required: true,
                },
                plant_name_create: {
                    required: true,
                },

            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#clonePackingFormValidateExistLotID').validate({
            rules: {
                start_plant_UID_exist_lot_ID: {
                    required: true,
                },
                end_plant_UID_exist_lot_ID: {
                    required: true,
                },
                selectedLotID: {
                    required: true,
                },
            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        //Trimming
        $('#editTrimmingPlantFormValidate').validate({
            rules: {
                select_mother_id: {
                    required: true,
                },
                genetic_name: {
                    required: true,
                },
                name: {
                    required: true,
                },
                quantity: {
                    required: true,
                },
                location: {
                    required: true,
                },
                planting_date: {
                    required: true,
                }
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#editTrimmingRoomFormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#trimmingTransferFormValidate').validate({
            rules: {
                lot_ID: {
                    required: true,
                },
                start_lot_ID: {
                    required: true,
                },
                end_lot_ID: {
                    required: true,
                },
                trimming_method: {
                    required: true,
                },
                packing_room_ID: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#packingTransferFormValidate').validate({
            rules: {
                transfer_lot_ID: {
                    required: true,
                },
                amount: {
                    required: true,
                    integer: true,
                },
                thc_content: {
                    required: true,
                    integer: true,
                },
                cbd_content: {
                    required: true,
                    integer: true,
                },
                other: {
                    required: true,
                    integer: true,
                },
                location: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Vault
        $('#editVaultFormValidate').validate({
            rules: {
                lot_ID: {
                    required: true,
                },
                amount: {
                    required: true,
                    integer: true,
                },
                thc_content: {
                    required: true,
                    integer: true,
                },
                cbd_content: {
                    required: true,
                    integer: true,
                },
                other: {
                    required: true,
                    integer: true,
                },
                location: {
                    required: true,
                },
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        //Sell
        $('#editSellFormValidate').validate({
            rules: {
                genetic: {
                    required: true,
                },
                lot_ID: {
                    required: true,
                },
                grams: {
                    required: true,
                },
                client: {
                    required: true,
                },
                sell_date: {
                    required: true,
                },
                grams_price: {
                    required: true,
                },
                total_price: {
                    required: true,
                },
                invoice_number: {
                    required: true,
                },
            },
            messages: {
                name1: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $('#createNewCompoundFormValidate').validate({
            rules: {
                genetic_id_transfer_compound: {
                    required: true,
                },
                start_lot_ID_transfer_compound: {
                    required: true,
                },
                lot_ID_text_transfer_compound: {
                    required: true,
                },
                dry_room_id_transfer_compound: {
                    required: true,
                },
                end_lot_ID_transfer_compound: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter a Name"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

<script>
    $('.main-sidebar li').click(function() {
        localStorage.setItem('clicked_menu_g', this.id);
        if (this.id != 'ul_root_g') {
            localStorage.removeItem('child_menu_g');
        }
    });

    $('#ul_root_g > ul li').click(function() {
        localStorage.setItem('child_menu_g', this.id);
    });

    var current_menu_g = localStorage.getItem('clicked_menu_g');
    $('#' + current_menu_g + '>a').addClass('active');

    var child_menu_g = localStorage.getItem('child_menu_g');
    $('#' + child_menu_g + ' a').addClass('active');

    if (child_menu_g != undefined) {
        $('#ul_root_g').addClass('menu-open');
    }

    //Setting Menu
    $('.main-sidebar li').click(function() {
        localStorage.setItem('clicked_menu_s', this.id);
        if (this.id != 'ul_root_s') {
            localStorage.removeItem('child_menu_s');
        }
    });

    $('#ul_root_s > ul li').click(function() {
        localStorage.setItem('child_menu_s', this.id);
    });

    var current_menu_s = localStorage.getItem('clicked_menu_s');
    $('#' + current_menu_s + '>a').addClass('active');

    var child_menu_s = localStorage.getItem('child_menu_s');
    $('#' + child_menu_s + ' a').addClass('active');

    if (child_menu_s != undefined) {
        $('#ul_root_s').addClass('menu-open');
    }

    //Harvest Menu ul_root_h
    $('.main-sidebar li').click(function() {
        localStorage.setItem('clicked_menu_h', this.id);
        if (this.id != 'ul_root_h') {
            localStorage.removeItem('child_menu_h');
        }
    });
    $('#ul_root_h > ul li').click(function() {
        localStorage.setItem('child_menu_h', this.id);
    });
    var current_menu_h = localStorage.getItem('clicked_menu_h');
    $('#' + current_menu_h + '>a').addClass('active');
    var child_menu_h = localStorage.getItem('child_menu_h');
    $('#' + child_menu_h + ' a').addClass('active');
    if (child_menu_h != undefined) {
        $('#ul_root_h').addClass('menu-open');
    }

    //Harvest Menu ul_root_r
    $('.main-sidebar li').click(function() {
        localStorage.setItem('clicked_menu_r', this.id);
        if (this.id != 'ul_root_r') {
            localStorage.removeItem('child_menu_r');
        }
    });
    $('#ul_root_r > ul li').click(function() {
        localStorage.setItem('child_menu_r', this.id);
    });
    var current_menu_r = localStorage.getItem('clicked_menu_r');
    $('#' + current_menu_r + '>a').addClass('active');
    var child_menu_r = localStorage.getItem('child_menu_r');
    $('#' + child_menu_r + ' a').addClass('active');
    if (child_menu_r != undefined) {
        $('#ul_root_r').addClass('menu-open');
    }
</script>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->