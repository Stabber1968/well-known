<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();

if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

$pGenetic = new Genetic();
$mGeneticList = $pGenetic->getAllOfInfo();
$mGeneticList = $mGeneticList->results();

$pTrimmingMethod = new TrimmingMethod();
$mTrimmingMethodLists = $pTrimmingMethod->getAllOfInfo();
$mTrimmingMethodLists = $mTrimmingMethodLists->results();

$pDryMethod = new DryMethod();
$mDryMethodLists = $pDryMethod->getAllOfInfo();
$mDryMethodLists = $mDryMethodLists->results();

?>

<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="settingGenetic" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php
                    if($_GET['page'] == ""){
                        echo "active";
                    }else{
                        echo $_GET['page'] == "genetic"? "active": "";
                    }
                    ?> " id="setting_genetic_tab" data-toggle="pill" href="#setting_genetic_content" role="tab" aria-controls="setting_genetic_tab" aria-selected="<?php echo $_GET['page'] == "genetic"? "true": "false"?>">Genetic</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $_GET['page'] == "trimmingmethod"? "active": "";?>" id="setting_trimming_method" data-toggle="pill" href="#setting_trimming_method_content" role="tab" aria-controls="setting_trimming_method" aria-selected="true">Trimming Method</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $_GET['page'] == "drymethod"? "active": "";?>" id="setting_drying_method" data-toggle="pill" href="#setting_drying_method_content" role="tab" aria-controls="setting_drying_method" aria-selected="<?php echo $_GET['page'] == 'drymethod'? 'true': 'false'?>">Drying Method</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <!-- Setting Genetic Section-->
                <div class="tab-pane fade <?php
                if($_GET['page'] == ""){
                    echo "show active";
                }else{
                    echo $_GET['page'] == "genetic"? " show active": "";
                }?>" id="setting_genetic_content" role="tabpanel" aria-labelledby="setting_genetic_content">
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-success"id="btn_addGeneticPlant">
                                        <i class="fas fa-plus"></i>  Create Medical Plant
                                    </button>
                                </div>

                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-danger " id="btn_deleteGenetic">
                                        <i class="fas fa-trash"></i> Delete Medical Plant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="table_genetic" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>
                                        <input class="geneticCheckBoxTotal" type="checkbox" name="select_all_genetic">
                                    </th>
                                    <th style="display:none">id</th>
                                    <th>Scientific Name</th>
                                    <th>Variety Plant Name</th>
                                    <th>Photoperiode Clone</th>
                                    <th>Photoperiode Veg</th>
                                    <th>Photoperiode Flower</th>
                                    <th>Grams or Seeds</th>
                                    <th>Htc %</th>
                                    <th>Cbd %</th>
                                    <th>Other %</th>
                                    <th class="not">Edit</th>
                                </tr>
                                </thead>
                                <tbody id="genetic_table_body">
                                <?php
                                $k = 0;
                                foreach($mGeneticList as $mGenetic){
                                    $k++;
                                    ?>
                                    <tr>
                                        <td>
                                            <input class="geneticCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$mGenetic->id?>">
                                        </td>
                                        <td class="td_id" style="display:none"><?=$mGenetic->id?></td>
                                        <td class="td_genetic_name"><?=$mGenetic->genetic_name?></td>
                                        <td class="td_plant_name"><?=$mGenetic->plant_name?></td>
                                        <td class="td_photo_clone"><?=$mGenetic->photo_clone?></td>
                                        <td class="td_photo_veg"><?=$mGenetic->photo_veg?></td>
                                        <td class="td_photo_flower"><?=$mGenetic->photo_flower?></td>
                                        <td class="td_grams"><?=$mGenetic->grams?></td>
                                        <td class="td_htc"><?=$mGenetic->htc?></td>
                                        <td class="td_cbd"><?=$mGenetic->cbd?></td>
                                        <td class="td_other"><?=$mGenetic->other?></td>
                                        <td><a class="btn bg-gradient-green btn-sm" id="btn_editGenetic" data-target="#modal-add-Genetic" href="#modal-add-Genetic" data-toggle="modal"><i class="fas fa-pencil-alt"></i> Edit</a></td>

                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!-- Setting Trimming Method Section-->
                <div class="tab-pane fade <?php echo $_GET['page'] == "trimmingmethod"? " show active": "";?>" id="setting_trimming_method_content" role="tabpanel" aria-labelledby="setting_trimming_method_content">
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-success"id="btn_addTrimmingMethod">
                                        <i class="fas fa-plus"></i>  Create Trimming Method
                                    </button>
                                </div>

                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-danger " id="btn_deleteTrimmingMethod">
                                        <i class="fas fa-trash"></i> Delete Trimming Method
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="table_trimmingMethod" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>
                                        <input class="trimmingMethodCheckBoxTotal" type="checkbox" name="select_all_trimmingMethod">
                                    </th>
                                    <th style="display:none">id</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>

                                </thead>
                                <tbody id="trimmingMethod_table_body">
                                <?php
                                if($mTrimmingMethodLists){
                                    $k = 0;
                                    foreach ($mTrimmingMethodLists as $mTrimmingMethod):
                                        $k++;
                                        ?>
                                        <tr>
                                            <td>
                                                <input class="trimmingMethodCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$mTrimmingMethod->id?>">
                                            </td>
                                            <td style="display:none" class="td_id"><?php echo $mTrimmingMethod->id?></td>
                                            <td class="td_name"><?php echo $mTrimmingMethod->name?></td>
                                            <td style="text-align: center">
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editTrimmingMethod" data-target="#modal-add-trimming-method" href="#modal-add-trimming-method" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach;} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

                <!-- Setting Dry Method Section-->
                <div class="tab-pane fade <?php echo $_GET['page'] == "drymethod"? " show active": "";?> " id="setting_drying_method_content" role="tabpanel" aria-labelledby="setting_drying_method_content">
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-success"id="btn_addDryMethod">
                                        <i class="fas fa-plus"></i>  Create Dry Method
                                    </button>
                                </div>

                                <div class="col-sm-2" style="text-align:right">
                                    <button type="button" class="btn btn-danger " id="btn_deleteDryMethod">
                                        <i class="fas fa-trash"></i> Delete Dry Method
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="table_dryMethod" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>
                                        <input class="dryMethodCheckBoxTotal" type="checkbox" name="select_all_dryMethod">
                                    </th>
                                    <th style="display:none">id</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>

                                </thead>
                                <tbody id="dryMethod_table_body">
                                <?php
                                if($mDryMethodLists){
                                    $k = 0;
                                    foreach ($mDryMethodLists as $mDryMethod):
                                        $k++;
                                        ?>
                                        <tr>
                                            <td>
                                                <input class="dryMethodCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$mDryMethod->id?>">
                                            </td>
                                            <td style="display:none" class="td_id"><?php echo $mDryMethod->id?></td>
                                            <td class="td_name"><?php echo $mDryMethod->name?></td>
                                            <td style="text-align: center">
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editDryMethod" data-target="#modal-add-dry-method" href="#modal-add-dry-method" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach;} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Modal Create Genetic Plants to Room (Add / Edit)-->
<div class="modal fade" id="modal-add-Genetic">
    <div class="modal-dialog width-modal-dialog-setting-Genetic">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Identification of the Medicinal Plant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveGenetic.php" enctype='multipart/form-data' id="editGeneticFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id_genetic" id="id_genetic" type="hidden" value="">
                        <input name="act_genetic" id="act_genetic" type="hidden" value="">

                        <div class="row">
                            <div class="col-6">
                                <label>Scientific Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Genetic Name" id="genetic_name" name="genetic_name" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Variety Plant Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Plant Name" id="plant_name" name="plant_name" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Grams or seeds</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Grams" id="grams" name="grams" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Htc %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Htc %" id="htc" name="htc" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Cbd %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Cbd %" id="cbd" name="cbd" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label>Photoperiode Clone</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Photoperiode Clone" id="photo_clone" name="photo_clone">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Photoperiode Vegetation</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Photoperiode Vegetation" id="photo_veg" name="photo_veg">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <label>Photoperiode Flower</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Photoperiode Flower" id="photo_flower" name="photo_flower">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Other %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Other %" id="other" name="other" >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="btn_saveGenetic" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->


<!-- Trimming Method modal (Add/Edit) -->
<div class="modal fade" id="modal-add-trimming-method">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Add New Trimming Method</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveTrimmingMethod.php" enctype='multipart/form-data' id="editTrimmingMethodFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id_trimmingMethod" id="id_trimmingMethod" type="hidden" value="">
                        <input name="act_trimmingMethod" id="act_trimmingMethod" type="hidden" value="">

                        <label>Name</label>
                        <div class="form-group input-group mb-3">
                            <input type="text" class="form-control" placeholder="Name" id="name_trimmingMethod" name="name_trimmingMethod">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="saveTrimmingMethod" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dry Method modal (Add/Edit) -->
<div class="modal fade" id="modal-add-dry-method">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Add New Dry Method</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveDryMethod.php" enctype='multipart/form-data' id="editDryMethodFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id_dryMethod" id="id_dryMethod" type="hidden" value="">
                        <input name="act_dryMethod" id="act_dryMethod" type="hidden" value="">

                        <label>Name</label>
                        <div class="form-group input-group mb-3">
                            <input type="text" class="form-control" placeholder="Name" id="name_dryMethod" name="name_dryMethod">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="saveDryMethod" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Select All , for that let's see https://jsfiddle.net/gyrocode/abhbs4x8/
    function updateDataTableSelectAllCtrl_genetic(table){
        var $chkbox_all        = $('#genetic_table_body input[type="checkbox"]');
        var $chkbox_checked    = $('#genetic_table_body input[type="checkbox"]:checked');
        var chkbox_select_all  = $('thead input[name="select_all_genetic"]');

        // If none of the checkboxes are checked
        if($chkbox_checked.length === 0){
            chkbox_select_all[0].checked = false;
            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length){
            chkbox_select_all[0].checked = true;

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all[0].checked = false;
        }
    }

    function updateDataTableSelectAllCtrl_trimmingMethod(table){
        var $chkbox_all        = $('#trimmingMethod_table_body input[type="checkbox"]');
        var $chkbox_checked    = $('#trimmingMethod_table_body input[type="checkbox"]:checked');
        var chkbox_select_all  = $('thead input[name="select_all_trimmingMethod"]');

        // If none of the checkboxes are checked
        if($chkbox_checked.length === 0){
            chkbox_select_all[0].checked = false;
            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length){
            chkbox_select_all[0].checked = true;

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all[0].checked = false;
        }
    }

    function updateDataTableSelectAllCtrl_dryMethod(table){
        var $chkbox_all        = $('#dryMethod_table_body input[type="checkbox"]');
        var $chkbox_checked    = $('#dryMethod_table_body input[type="checkbox"]:checked');
        var chkbox_select_all  = $('thead input[name="select_all_dryMethod"]');

        // If none of the checkboxes are checked
        if($chkbox_checked.length === 0){
            chkbox_select_all[0].checked = false;
            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length){
            chkbox_select_all[0].checked = true;

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all[0].checked = false;
        }
    }

    $( document ).ready( function () {

//######### Genetic ################
        //click add Genetic
        $( "#btn_addGeneticPlant" ).click(function() {
            var act_genetic = "add"
            $("#act_genetic").val(act_genetic);
            //show Modal
            $("#modal-add-Genetic").modal('show');
        });

        //Edit Genetic
        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editGenetic').click(function() {
            var act_genetic = "edit";
            $("#act_genetic").val(act_genetic);

            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var genetic_name = $row.find('.td_genetic_name').text();
            var plant_name =  $row.find('.td_plant_name').text();
            var photo_clone = $row.find('.td_photo_clone').text();
            var photo_veg = $row.find('.td_photo_veg').text();
            var photo_flower = $row.find('.td_photo_flower').text();
            var grams = $row.find('.td_grams').text();
            var htc = $row.find('.td_htc').text();
            var cbd = $row.find('.td_cbd').text();
            var other = $row.find('.td_other').text();

            $("#id_genetic").val(id);
            $("#genetic_name").val(genetic_name);
            $("#plant_name").val(plant_name);
            $("#photo_clone").val(photo_clone);
            $("#photo_veg").val(photo_veg);
            $("#photo_flower").val(photo_flower);
            $("#grams").val(grams);
            $("#htc").val(htc);
            $("#cbd").val(cbd);
            $("#other").val(other);
        });

        //modal clear when close
        $('#modal-add-Genetic').on('hidden.bs.modal', function () {
            $("#id").val("");
            $("#genetic_name").val("");
            $("#plant_name").val("");
            $("#photo_clone").val("");
            $("#photo_veg").val("");
            $("#photo_flower").val("");
            $("#grams").val("");
            $("#htc").val("");
            $("#cbd").val("");
            $("#other").val("");
        })

        $("#btn_saveGenetic").click(function(){
            // Send post request to register in Database
            var genetic_name = $('#genetic_name').val();
            var act_genetic = $('#act_genetic').val();

            if (act_genetic == 'add'){
                if (genetic_name){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveGenetic.php',
                        data:{act:'validate', genetic_name:genetic_name},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameGenetic'){
                                alert('Exist Same Genetic');
                            }else {
                                $('#editGeneticFormValidate').submit();
                            }
                        }
                    })
                }else {
                    $('#editGeneticFormValidate').submit();
                }
            }

            if (act_genetic == 'edit'){
                $('#editGeneticFormValidate').submit();
            }
            return false;
        })

        //Change Event of select box (Clone Room)
        $('#selectedCloneRoomID').on('change', function() {
            var cloneRoomID = this.value;
            $.redirect('../Views/plantsClone.php',
                {
                    room: cloneRoomID
                },
                'GET');
        });

        // ---- start Genetic Table
        //Event of Click / unClick Plant CheckBox  class name = geneticCheckBox
        var m_geneticTable = $('#table_genetic').dataTable({
            'order': [1, 'asc'],
            "sPaginationType": "full_numbers",
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0] }
            ],
            stateSave: true,
        });

        m_geneticTable.on('draw.dt', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_genetic(m_geneticTable);
        });

        $('#table_genetic tbody').on('click', 'input[type="checkbox"]', function(e){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_genetic(m_geneticTable);
        });

        $('input[name="select_all_genetic"]').on('click', function(e) {
            if(this.checked){
                $('#table_genetic tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#table_genetic tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });

        var checkedList = [];
        var allPages_genetic = m_geneticTable.fnGetNodes();

        //Click Delete Button
        $('#btn_deleteGenetic').click(function(){
            checkedList = [];
            $.each($("input[class='geneticCheckBox']:checked", allPages_genetic), function(){
                //push selected genetic ID
                checkedList.push($(this).val());
            });

            if (checkedList === undefined || checkedList.length == 0) {
                alert("There is no selected items")
            }else {
                $.ajax({
                    method:'POST',
                    url: '../Logic/saveGenetic.php',
                    data:{act:'delete', idList:checkedList},
                    success:function (data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj == 'exist'){
                            toastr.error("You can't Delete Genetic Because of Plants are using.")
                        }
                        if (obj == 'success'){
                            $.redirect('../Views/genetic.php');
                        }
                    }
                })
            }
            console.log('currently checked list');
            console.log(checkedList)
        })

        // ---- end Genetic Table
//######### Genetic End ################

//######### Trimming Method ################
        $( "#btn_addTrimmingMethod" ).click(function() {
            var act_trimmingMethod = "add"
            $("#act_trimmingMethod").val(act_trimmingMethod);
            $("#modal-add-trimming-method").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editTrimmingMethod').click(function() {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var act = "edit";
            var name =  $row.find('.td_name').text();

            $("#id_trimmingMethod").val(id);
            $("#act_trimmingMethod").val(act);
            $("#name_trimmingMethod").val(name);
        });

        //modal close
        $('#modal-add-trimming-method').on('hidden.bs.modal', function () {
            $("#name_trimmingMethod").val("");
        })

        //submit
        $('input#saveTrimmingMethod').click(function (event) {
            var name = $('input#name_trimmingMethod').val();
            var act_trimmingMethod = $('input#act_trimmingMethod').val();

            if (act_trimmingMethod == 'add'){
                if (name){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveTrimmingMethod.php',
                        data:{act:'validate', name:name},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameName'){
                                alert('Exist Same Name');
                            }else{
                                $('#editTrimmingMethodFormValidate').submit();
                            }
                        }
                    })
                }
            }

            if (act_trimmingMethod == 'edit'){
                $('#editTrimmingMethodFormValidate').submit();
            }

            return false;
        })

        // ---- start Trimming Method Table
        //Event of Click / unClick Plant CheckBox  class name = geneticCheckBox
        var m_trimmingMethodTable = $('#table_trimmingMethod').dataTable({
            //'order': [1, 'asc'],
            "sPaginationType": "full_numbers",
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0] }
            ],
            stateSave: true,
        });

        m_trimmingMethodTable.on('draw.dt', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_trimmingMethod(m_trimmingMethodTable);
        });

        $('#table_trimmingMethod tbody').on('click', 'input[type="checkbox"]', function(e){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_trimmingMethod(m_trimmingMethodTable);
        });

        $('input[name="select_all_trimmingMethod"]').on('click', function(e) {
            if(this.checked){
                $('#table_trimmingMethod tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#table_trimmingMethod tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });
        // ---- end  Trimming Method  Table

        //var checkedList = [];
        var allPages_trimmingMethod = m_trimmingMethodTable.fnGetNodes();

        //Click Delete Button
        $('#btn_deleteTrimmingMethod').click(function(){
            checkedList = [];
            $.each($("input[class='trimmingMethodCheckBox']:checked", allPages_trimmingMethod), function(){
                //push selected TrimmingMethod ID
                checkedList.push($(this).val());
            });

            if (checkedList === undefined || checkedList.length == 0) {
                alert("There is no selected items")
            }else {
                $.ajax({
                    method:'POST',
                    url: '../Logic/saveTrimmingMethod.php',
                    data:{act:'delete', idList:checkedList},
                    success:function (data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj == 'exist'){
                            toastr.error("You can't Delete Trimming Method Because of Plants are using.")
                        }
                        if (obj == 'success'){
                            $.redirect('../Views/genetic.php',{
                                page: 'trimmingmethod'
                            },'GET');
                        }
                    }
                })
            }
            console.log('currently checked list');
            console.log(checkedList)
        })
//######### Trimming Method End################

//######### Dry Method ################
        $( "#btn_addDryMethod" ).click(function() {
            var act_dryMethod = "add"
            $("#act_dryMethod").val(act_dryMethod);
            $("#modal-add-dry-method").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editDryMethod').click(function() {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var act = "edit";
            var name =  $row.find('.td_name').text();

            $("#id_dryMethod").val(id);
            $("#act_dryMethod").val(act);
            $("#name_dryMethod").val(name);
        });

        //modal close
        $('#modal-add-dry-method').on('hidden.bs.modal', function () {
            $("#name_dryMethod").val("");
        })

        //submit
        $('input#saveDryMethod').click(function (event) {
            var name = $('input#name_dryMethod').val();
            var act_dryMethod = $('input#act_dryMethod').val();

            if (act_dryMethod == 'add'){
                if (name){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveDryMethod.php',
                        data:{act:'validate', name:name},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameName'){
                                alert('Exist Same Name');
                            }else{
                                $('#editDryMethodFormValidate').submit();
                            }
                        }
                    })
                }
            }

            if (act_dryMethod == 'edit'){
                $('#editDryMethodFormValidate').submit();
            }

            return false;
        })

        // ---- start Dry Method Table
        //Event of Click / unClick Plant CheckBox  class name = geneticCheckBox
        var m_dryMethodTable = $('#table_dryMethod').dataTable({
            //'order': [1, 'asc'],
            "sPaginationType": "full_numbers",
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0] }
            ],
            stateSave: true,
        });

        m_dryMethodTable.on('draw.dt', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_dryMethod(m_dryMethodTable);
        });

        $('#table_dryMethod tbody').on('click', 'input[type="checkbox"]', function(e){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl_dryMethod(m_dryMethodTable);
        });

        $('input[name="select_all_dryMethod"]').on('click', function(e) {
            if(this.checked){
                $('#table_dryMethod tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#table_dryMethod tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });
        // ---- end  Dry Method  Table

        //var checkedList = [];
        var allPages_dryMethod = m_dryMethodTable.fnGetNodes();

        //Click Delete Button
        $('#btn_deleteDryMethod').click(function(){
            checkedList = [];
            $.each($("input[class='dryMethodCheckBox']:checked", allPages_dryMethod), function(){
                //push selected DryMethod ID
                checkedList.push($(this).val());
            });

            if (checkedList === undefined || checkedList.length == 0) {
                alert("There is no selected items")
            }else {
                $.ajax({
                    method:'POST',
                    url: '../Logic/saveDryMethod.php',
                    data:{act:'delete', idList:checkedList},
                    success:function (data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj == 'exist'){
                            toastr.error("You can't Delete Dry Method Because of Plants are using.")
                        }
                        if (obj == 'success'){
                            $.redirect('../Views/genetic.php',{
                                page: 'drymethod'
                            },'GET');
                        }
                    }
                })
            }
            console.log('currently checked list');
            console.log(checkedList)
        })
//######### Dry Method End################


    });


    //Date range picker
    $('#reservationdate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
