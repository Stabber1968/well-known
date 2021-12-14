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

//get total room list
$mMotherRoomList = $p_general->getValueOfAnyTable('room_mother', '1', '=', '1');
$mMotherRoomList = $mMotherRoomList->results();
// ...

$pMother = new MotherPlant();

if ($_GET['room']) {
    $motherRoomID = $_GET['room'];
    // get Plantrs List From MotherRoomID
    $plantsIDList = $p_general->getValueOfAnyTable('index_mother', 'room_id', '=', $motherRoomID, 'plant_id');
    $plantsIDList = $plantsIDList->results();
    // ....
} else {
    // get total Plants List 
    $plantsIDList = $p_general->getValueOfAnyTable('index_mother', '1', '=', '1', 'plant_id');
    $plantsIDList = $plantsIDList->results();
    // ...
}

// For search "p" is id of plant, not plant UID
if ($_GET['p']) {
    $searchPlantID = $_GET['p'];
    foreach ($plantsIDList as $plant) {
        $tmp_plantsIDList = array();
        if ($plant->plant_id == $searchPlantID) {
            array_push($tmp_plantsIDList, $plant);
            break;
        }
    }
    $plantsIDList = $tmp_plantsIDList;
}

$k = 0;
?>
<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="mother" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mother_plants_tab" data-toggle="pill" href="#mother_plants_content" role="tab" aria-controls="mother_plants_tab" aria-selected="true">Mother Plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mother_rooms_tab" href="roomsMother.php">Mother Rooms</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="mother_plants_content" role="tabpanel" aria-labelledby="mother_plants_content">

                    <!-- Mother Plants Section-->
                    <div class="content-header nopadding">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input name="currentRoomID" id="currentRoomID" type="hidden" value="<?= $motherRoomID ?>">
                                        <select class="form-control select2bs4" id="selectedMotherRoomID" style="width: 100%;">
                                            <!--SelectBox Mother Room-->
                                            <option value="0">All Mother Room</option>
                                            <?php
                                            if ($mMotherRoomList) {
                                                $k = 0;
                                                foreach ($mMotherRoomList as $mMotherRoom) {
                                            ?>
                                                    <option <?php
                                                            if ($motherRoomID == $mMotherRoom->id) {
                                                                echo 'selected';
                                                            }
                                                            ?> value="<?= $mMotherRoom->id ?>"><?= $mMotherRoom->name ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>

                                <div class="col-sm-5"></div>

                                <div class="col-sm-5" style="text-align: right">
                                    <a class="btn btn-md bg-gradient-green " href="#" id="btn_addMotherPlant">
                                        <i class="fas fa-plus"></i>
                                        Create Mother Plants
                                    </a>
                                    <a class="btn bg-gradient-yellow btn-md white" id="btn_multiPrint" data-target="#modal-multi-print-label" href="#modal-multi-print-label" data-toggle="modal">
                                        <i class="fas fa-print"></i>
                                        Multi Print
                                    </a>
                                    <!-- <a class="btn bg-gradient-danger btn-md" data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Mother Plants
                                    </a> -->
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-hover table-bordered table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="select_all"></th>
                                        <th style="display:none">id</th>
                                        <th style="display:none">QR code</th>
                                        <th style="display:none">Plant ID Real</th>
                                        <th>Plant ID</th>
                                        <th>Name</th>
                                        <th style="display:none">Genetic ID</th>
                                        <th>Genetic</th>
                                        <th>Mother ID</th>
                                        <th style="display:none">Location ID</th>
                                        <th>Location</th>
                                        <th>Planting Date</th>
                                        <th>Days</th>
                                        <th style="display:none">Observations</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Mother Plants to Room (Add)-->
<div class="modal fade" id="modal-add-Mother-Plants">
    <div class="modal-dialog width-modal-large">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Mother Plant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="addMotherPlantFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="add">
                        <input name="showRoom" id="showRoom" type="hidden" value="<?= $_GET['room'] ?>">

                        <div class="row">
                            <div class="col-6">
                                <div id="plant_UID_section">
                                    <label>Plant ID</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Unique Number" id="plant_UID_text" name="plant_UID_text" autocomplete="off" readonly>
                                        <input type="hidden" class="form-control" id="mother_UID" name="mother_UID">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="qr_code_section">
                                    <label>QR code</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="QR code" id="qr_code" name="qr_code" autocomplete="off" readonly>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="quantity_section">
                                    <label>Quantity</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Quantity" id="quantity" name="quantity">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p style= "font-size:12px;display:None;color:red;" id="add_number">Please input quantity.</p>
                                </div>

                                <label>Genetic</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="genetic" id="genetic" style="width: 100%;">
                                        <option value="">Select Genetic</option>
                                        <?php
                                        $pGenetic = new Genetic();
                                        $mGeneticList = $pGenetic->getAllOfInfo();
                                        $mGeneticList = $mGeneticList->results();

                                        foreach ($mGeneticList as $mGenetic) {
                                        ?>
                                            <option value="<?= $mGenetic->id ?>"><?= $mGenetic->genetic_name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <p style= "font-size:12px;display:None;color:red;" id="add_genetic">Please select genetic.</p>
                                </div>

                                <label>Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Location(Room)</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="location" id="location" style="width: 100%;">
                                        <option value="">Select Room</option>
                                        <?php
                                        $pMotherRoom = new MotherRoom();
                                        $mMotherRoomList = $pMotherRoom->getAllOfInfo();
                                        $mMotherRoomList = $mMotherRoomList->results();
                                        foreach ($mMotherRoomList as $mMotherRoom) {
                                        ?>
                                            <option value="<?= $mMotherRoom->id ?>"><?= $mMotherRoom->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <p style= "font-size:12px;display:None;color:red;" id="add_location">Please select location.</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <label>Mother ID / Seed </label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Seed" id="seed" name="seed">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                                <p style= "font-size:12px;display:None;color:red;" id="add_motherid">Please input Mother ID / Seed.</p>

                                <label>Planting Date</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="planting_date" name="planting_date" />
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <label>Observations</label>
                                <div class="form-group input-group mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Enter ..." id="observation" name="observation" style="height:100px"></textarea>
                                </div>
                                <p style= "font-size:12px;display:None;color:red;" id="add_note">Please input observation.</p>
                            </div>
                        </div>
                </div>
                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_saveMotherPlant" class="btn btn-primary" value="Save">Save</input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->
<!-- Modal Create Mother Plants to Room (Add / Edit)-->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog width-modal-large">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Plant ID: <span id="lot_ID_text_header" ></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row modal-body">
                <div class="col-12 col-sm-6">
                    <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="editMotherPlantFormValidate">
                        <!--body-->
                        <div class="modal-body">
                            <fieldset>
                                <input name="edit_id" id="edit_id" type="hidden" value="">
                                <input name="act" id="act" type="hidden" value="edit">
                                <input name="edit_showRoom" id="edit_showRoom" type="hidden" value="<?= $_GET['room'] ?>">
                                <input type="hidden" class="form-control" id="edit_plant_UID_text" name="edit_plant_UID_text">
                                <input type="hidden" class="form-control" id="edit_mother_UID" name="edit_mother_UID">
                                <div class="row">
                                    <div class="col-5">
                                        <label>Name: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Name" id="edit_name" name="edit_name" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-user"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Genetic: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group">
                                            <select class="form-control select2bs4" name="edit_genetic" id="edit_genetic" style="width: 100%;">
                                                <option value="">Select Genetic</option>
                                                <?php
                                                $pGenetic = new Genetic();
                                                $mGeneticList = $pGenetic->getAllOfInfo();
                                                $mGeneticList = $mGeneticList->results();

                                                foreach ($mGeneticList as $mGenetic) {
                                                ?>
                                                    <option value="<?= $mGenetic->id ?>"><?= $mGenetic->genetic_name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>QR code: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group input-group mb-3">
                                            <input type="text" class="form-control" placeholder="QR code" id="edit_qr_code" name="edit_qr_code" autocomplete="off" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-user"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-5">
                                        <label>Quantity: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Quantity" id="edit_quantity" name="edit_quantity">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-user"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-5">
                                        <label>Location(Room): </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group">
                                            <select class="form-control select2bs4" name="edit_location" id="edit_location" style="width: 100%;">
                                                <option value="">Select Room</option>
                                                <?php
                                                $pMotherRoom = new MotherRoom();
                                                $mMotherRoomList = $pMotherRoom->getAllOfInfo();
                                                $mMotherRoomList = $mMotherRoomList->results();
                                                foreach ($mMotherRoomList as $mMotherRoom) {
                                                ?>
                                                    <option value="<?= $mMotherRoom->id ?>"><?= $mMotherRoom->name ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Mother ID / Seed: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Seed" id="edit_seed" name="edit_seed">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-envelope"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Planting Date: </label>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group input-group mb-3">
                                            <div class="input-group date" id="edit_reservationdate" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="edit_planting_date" name="edit_planting_date" />
                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Observations</label>
                                    <div class="form-group input-group mb-3">
                                        <textarea class="form-control" rows="3" placeholder="Enter ..." id="edit_observation" name="edit_observation" style="height:150px"></textarea>
                                    </div>
                                    <p style= "font-size:12px;display:None;color:red;" id="edit_note">Please input observation.</p>
                                </div>
                        </div>
                        <!--footer-->
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <div id="action_section">
                                <a class="btn bg-gradient-yellow btn-sm" id="btn_printQRCode" data-dismiss="modal">
                                    <i class="fas fa-print"></i>
                                    Print
                                </a>
                            </div>
                            <button type="button" class="btn btn-primary" value="Save" id="btn_save_edit">Save</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="row justify-content-around modal-body">
                        <button type="button" class="btn btn-sm bg-gradient-blue" id="btn_historylog">Logs</button>
                        <button type="button" class="btn btn-sm bg-gradient-blue " id="btn_sample">Sample</button>
                        <button type="button" class="btn btn-sm bg-gradient-blue " id="btn_pesticide">Pesticide</button>
                        <button type="button" class="btn btn-sm bg-gradient-blue" id="btn_weightbtn" hidden>LOT Weight</button>
                        <button type="button" class="btn btn-sm bg-gradient-blue " id="btn_waste">Waste</button>
                        <button type="button" class="btn btn-sm bg-gradient-blue" id="btn_deletePlants">Destroy Plants</button>
                    </div>
                    <div class="row modal-body">
                        <!-- sample form -->
                        <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="sampleForm" style="width:100%;">
                                <!-- <div class="modal-body"> -->
                                <fieldset>
                                    <input name="id" id="id" type="hidden" value="">
                                    <input name="act" id="act" type="hidden" value="sample_weight_Plant">
                                    <input type="hidden" class="form-control" id="sample_lot_ID" name="sample_lot_ID">
                                    <input type="hidden" class="form-control" id="sample_lot_ID_text" name="sample_lot_ID_text">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-5">
                                                    <label>Document Ref N&#176: </label>
                                                </div>
                                                <div class="col-7">
                                                    <div class="form-group input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Ref number" id="sample_ref" name="sample_ref" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <p style= "font-size:12px;display:None;color:red;" id="val_weight">Please input correct value</p>
                                        </div>
                                    </div>
                                </fieldset>
                            <!-- </div> -->
                            <div class="rown justify-content-between" style="float:right;">
                                <button type="button" class="btn bg-gradient-green" id="btn_sample_s">Save</button>
                            </div>
                        </form>
                        <!-- Pesticide form -->
                        <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="pestForm" style="width: 100%;" >
                            <!-- <div class="modal-body"> -->
                                <fieldset>
                                    <input name="id" id="id" type="hidden" value="">
                                    <input name="act" id="act" type="hidden" value="pest_sub_Plant">
                                    <input type="hidden" class="form-control" id="pest_lot_ID" name="pest_lot_ID">
                                    <input type="hidden" class="form-control" id="pest_lot_ID_text" name="pest_lot_ID_text">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Observation: </label>
                                            <div class="form-group input-group mb-3">
                                                <textarea class="form-control" rows="4" placeholder="Enter ..." id="pest_note" name="pest_note" style="height: 150px"></textarea>
                                            </div>
                                        </div>
                                        <p style= "font-size:12px;display:None;color:red;" id="p_pest">Please input text.</p>
                                    </div>
                                </fieldset>
                            <!-- </div> -->
                            <!--footer-->
                            <div class="rown justify-content-between" style="float:right;">
                                <!-- <button type="button" class="btn bg-gradient-green" data-dismiss="modal">Cancel</button> -->
                                <button type="button" class="btn bg-gradient-green" id="btn_pest">Add</button>
                            </div>
                        </form>
                        <!-- weight form -->
                        <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="weightForm" style="width:100%;">
                            <!-- <div class="modal-body"> -->
                                <fieldset>
                                    <input name="id" id="id" type="hidden" value="">
                                    <input name="act" id="act" type="hidden" value="add_weight_Plant">
                                    <input type="hidden" class="form-control" id="edit_lot_ID" name="edit_lot_ID">
                                    <input type="hidden" class="form-control" id="edit_lot_ID_text" name="edit_lot_ID_text">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-5">
                                            <label>Plant Weight: </label></div>
                                            <div class="col-7">
                                                <div class="form-group input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Weight of Plant" id="add_weightofplant" name="add_weightofplant" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            Kg
                                                        </div>
                                                    </div>
                                                </div>
                                                <p style= "font-size:12px;display:None;color:red;" id="weight_weight">Please input correct value</p>
                                            </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </fieldset>
                            <!-- </div> -->
                            <div class="rown justify-content-between" style="float:right;">
                                <button type="button" class="btn bg-gradient-green" id="btn_addWeight">Add</button>
                            </div>
                        </form>
                        <!-- Waste form -->
                        <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="wasteForm" style="width:100%;">
                            <!-- <div class="modal-body"> -->
                                <fieldset>
                                    <input name="id" id="id" type="hidden" value="">
                                    <input name="act" id="act" type="hidden" value="waste_sub_Plant">
                                    <input type="hidden" class="form-control" id="waste_lot_ID" name="waste_lot_ID">
                                    <input type="hidden" class="form-control" id="waste_lot_ID_text" name="waste_lot_ID_text">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                            <div class="col-5"><label>Plant Weight</label></div>
                                            <div class="col-7">
                                                <div class="form-group input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Plant weight" id="waste_weightofplant" name="waste_weightofplant" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            Kg
                                                        </div>
                                                    </div>
                                                </div>
                                                <p style= "font-size:12px;display:None;color:red;" id="waste_weight">Please input correct value</p>
                                            </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label>Waste Reason: </label>
                                            <div class="form-group input-group mb-3">
                                                <textarea class="form-control" rows="3" placeholder="Enter ..." id="waste_note" name="waste_note" style="height: 150px"></textarea>
                                            </div>
                                            <p style= "font-size:12px;display:None;color:red;" id="waste_reason">Please input waste reason.</p>
                                        </div>
                                    </div>
                                </fieldset>
                            <!-- </div> -->
                            <!--footer-->
                            <div class="rown justify-content-between" style="float:right;">
                                <!-- <button type="button" class="btn bg-gradient-green" data-dismiss="modal">Cancel</button> -->
                                <button type="button" class="btn bg-gradient-green" id="btn_waste_s">Save</button>
                            </div>
                        </form>
                         <!-- delete form  -->
                        <form method="post" action="../Logic/saveMotherPlants.php" enctype='multipart/form-data' id="deleteForm" >
                            <!-- <div class="modal-body"> -->
                                <fieldset>
                                    <input name="id" id="id" type="hidden" value="">
                                    <input name="act" id="act" type="hidden" value="del_sub_Plant">
                                    <input type="hidden" class="form-control" id="del_lot_ID" name="del_lot_ID">
                                    <input type="hidden" class="form-control" id="del_adminID" name="del_adminID">
                                    <!-- <input type="hidden" class="form-control" id="del_lot_ID_text" name="del_lot_ID_text"> -->
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Plant ID</label>
                                            <div class="form-group input-group mb-3">
                                                <input id="cur_numberofplant" name="cur_numberofplant" value = "" type="hidden">
                                                <input type="text" class="form-control" id="del_lot_ID_text" name="del_lot_ID_text" autocomplete="off" readonly>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <p style= "font-size:12px;display:None;color:red;" id="val_number">Please input value between from 1 to 10.</p>
                                        </div>
                                        <div class="col-6">
                                            <label>Plant Weight</label>
                                            <div class="form-group input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Weight" id="del_weightofplant" name="del_weightofplant" autocomplete="off">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Kg
                                                    </div>
                                                </div>
                                            </div>
                                            <p style= "font-size:12px;display:None;color:red;" id="del_weight">Please input correct value</p>
                                        </div>
                                        <div class="col-12">
                                            <label>Delete Reason: </label>
                                            <div class="form-group input-group mb-3">
                                                <textarea class="form-control" rows="3" placeholder="Enter ..." id="del_note" name="del_note" style="height: 50px"></textarea>
                                            </div>
                                            <p style= "font-size:12px;display:None;color:red;" id="del_reason">Please input delete reason.</p>
                                        </div>
                                    </div>
                                </fieldset>
                            <!-- </div> -->
                            <!--footer-->
                            <div class="rown justify-content-between" style="float:right;">
                                <!-- <button type="button" class="btn bg-gradient-green" data-dismiss="modal">Cancel</button> -->
                                <button type="button" class="btn bg-gradient-danger" id="btn_delete">Delete</button>
                            </div>
                        </form>
                        
                    </div>
                    <div class="row">
                        <div style="width: 100%;">
                            <table id="historylog_below" class="table table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>User</th>
                                        <th>Event</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
<!-- Modal Print Label-->
<div class="modal fade" id="modal-print-label">
    <div class="modal-dialog width-modal-print-label">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Print Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--body-->
            <div class="modal-body label_body" id="label_body">
                <form method="post" action="#" id="printJS-form">
                    <div class="printBorder">
                        <div class="print_body">
                            <div class="print_qr" id="print_qr">
                                <img src="../QR_Code/default.png">
                            </div>
                            <div class="print_info">
                                <div class="title_font">
                                    <div class="print_name" id="print_name"></div>
                                </div>
                                <div class="plant_id_font" id="print_id">plant id</div>
                                <div class="print_mother_id" id="print_mother_id">Mother ID</div>
                                <div class="print_date" id="print_date">born date</div>
                                <div class="print_qr_code" id="print_qr_code"><?= $qr_code ?></div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <!--footer-->
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" onclick="printJS({ printable: 'printJS-form', type: 'html',css: '../dist/css/print.css'})">
                    Print
                </button>
            </div>

        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal Multi Print Label-->
<div class="modal fade" id="modal-multi-print-label">
    <div class="modal-dialog width-modal-middle ">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Multi Print Mother Plant Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--body-->
            <div class="modal-body">
                <div class="row">

                    <div class="col-5">
                        <div class="form-group">
                            <label>Start Plant ID</label>
                            <select class="form-control select2bs4" name="start_plant_UID" id="start_plant_UID" style="width: 100%;">
                                <option value="">Select Plant ID</option>
                                <?php
                                $motherPlantsIDList = $p_general->getValueOfAnyTable('index_mother', '1', '=', '1', 'plant_id');
                                $motherPlantsIDList = $motherPlantsIDList->results();
                                foreach ($motherPlantsIDList as $motherPlant) {
                                    $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $motherPlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $p_general->getTextOfMotherUID($plantInfo[0]->mother_UID);
                                ?>
                                    <option value="<?= $plantInfo[0]->mother_UID ?>"><?= $plant_UID_text ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-2" style="padding-top: 40px;text-align: center;"> ~ </div>

                    <div class="col-5">
                        <div class="form-group">
                            <label>End Plant ID</label>
                            <select class="form-control select2bs4" name="end_plant_UID" id="end_plant_UID" style="width: 100%;">
                                <option value="">Select Plant ID</option>
                                <?php
                                $motherPlantsIDList = $p_general->getValueOfAnyTable('index_mother', '1', '=', '1', 'plant_id');
                                $motherPlantsIDList = $motherPlantsIDList->results();
                                foreach ($motherPlantsIDList as $motherPlant) {
                                    $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $motherPlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $p_general->getTextOfMotherUID($plantInfo[0]->mother_UID);
                                ?>
                                    <option value="<?= $plantInfo[0]->mother_UID ?>"><?= $plant_UID_text ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!--footer-->
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="#" id="btn_gotoMultiPrintLabelPage" class="btn btn-primary">Print Label</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->


<div class="modal fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete Plants</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Really Delete Plants&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-outline-light" id="btn_deleteMotherPlants">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
<!-- Validate user modal. -->
<div class="modal fade" id="modal-validation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Validate user</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <fieldset>
                    <input name="act_validation" id="act_validation" type="hidden" value="">
                    <!-- <input name="lot_id_delete" id="lot_id_delete" type="hidden" value=""> -->
                <div class="row">
                        <div class="col-5">
                        <div class="form-group">
                                <select class="form-control select2bs4" name="supervisor_ID" id="supervisor_ID" style="width: 100%;">
                                    <!--SelectBox Lot ID-->
                                    <option value="">Select Supervisor </option>
                                    <?php
                                    $supervisorList = $p_general->getValueOfAnyTable('users', 'supervisor', '=', 1);
                                    $supervisorList = $supervisorList->results();
                                    foreach($supervisorList as $supervisor) {
                                        ?>    
                                        <option value="<?= $supervisor->id ?>"><?= $supervisor->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Supervisor Password" id="val_adminpass" name="val_adminpass" style="-webkit-text-security:disc;">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p style= "font-size:12px;display:None;color:red;padding-left:1rem;" id="val_adminpass_val">Please select supervisor and input Supervisor password correctly.</p>
                    </div>
                    <div class="row" style="margin:1rem;">
                        <div class="col-5"><label>User Password: </label></div>
                        <div class="col-7">
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="User Password" id="val_userpass" name="val_userpass" style="-webkit-text-security:disc;">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                            <p style= "font-size:12px;display:None;color:red;" id="val_userpass_val">Please input your password correctly.</p>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_confirm">Confirm</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- / .modal -->
<script>
    //
    // Updates "Select all" control in a data table
    //
    //Variables for delete current items from plants.
    var current_id = null;
    var current_mother_uid = null;
    var current_plant_uid_txt = null;
    var current_numberofPlant = null;
    var current_del_weight= null;
    var current_del_reason = null;
    var current_note = null;
    function updateDataTableSelectAllCtrl(table) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[type="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
        } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    function HttpGetRequest(name) {
        if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search))
            return decodeURIComponent(name[1]);
    }

    $(document).ready(function() {
        $("input[type='search']").wrap("<form>");
        $("input[type='search']").closest("form").attr("autocomplete","off");
       $('#btn_sample_s').click(function() {
            $('#sampleForm').submit();
        })
        $('#btn_pest').click(function() {
            var p_pest = $("#pest_note").val();
            
            if(p_pest.length == 0){
                document.getElementById("p_pest").style.display = "block";
                return ;
            }
            $('#pestForm').submit();
        })
        $('#btn_waste_s').click(function() {
            document.getElementById("waste_weight").style.display = "None";
            document.getElementById("waste_reason").style.display = "None";
            var weightofPlant = Number($('#waste_weightofplant').val());
            var waste_reason = $('#waste_note').val();
            var flg = 0;

            if(Number.isNaN(weightofPlant) || weightofPlant == 0){
                document.getElementById("waste_weight").style.display = "block";
                flg = 1;
            } 
            if(waste_reason.length == 0){
                document.getElementById("waste_reason").style.display = "block";
                flg = 1;
            }
            if (flg == 1) return ;
            $('#wasteForm').submit();
            return false;
        })
        $(document).on('click', '#btn_sample', function() {
            $('#id').val(current_id);
            $("#sample_lot_ID").val(current_mother_uid);
            $("#sample_lot_ID_text").val(current_plant_uid_txt);
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            var ref_txt = "";
            console.log(current_mother_uid);
            console.log(today);
            $.ajax({
                method: 'GET',
                url: '../Logic/saveMotherPlants.php',
                data: {
                    act: 'sample',
                    date: today,
                    mother_uid: current_mother_uid
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj.data.length);
                    ref_txt = current_mother_uid + "-" + (obj.data.length + 1).toString() + "/" +  today;
                    $("#sample_ref").val(ref_txt);
                }
            })
            if(ref_txt.length == 0 ) {
                $("#sample_ref").val('');
            }
            var table = $('#historylog_below').DataTable({
                "processing": true,
                // "serverSide": true,
                searching: false,
                "bDestroy": true,
                info:false,
                "scrollY":        "200px",
                "scrollCollapse": true,
                "paging":         false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory', 'cons':'sample'},
                },
                order: [0, 'desc'],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            $("#sampleForm").show();
            $("#pestForm").hide();
            $("#deleteForm").hide();
            $("#wasteForm").hide();
            $("#weightForm").hide();
            $("#btn_sample").addClass("bg-gradient-green").removeClass("bg-gradient-blue");
            $("#btn_pesticide").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_deletePlants").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_waste").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
        });
        $(document).on('click', '#btn_pesticide', function() {
            $('#id').val(current_id);
            $("#pest_lot_ID").val(current_mother_uid);
            $("#pest_lot_ID_text").val(current_plant_uid_txt);
            var table = $('#historylog_below').DataTable({
                "processing": true,
                // "serverSide": true,
                searching: false,
                "bDestroy": true,
                info:false,
                "scrollY":        "200px",
                "scrollCollapse": true,
                "paging":         false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory', 'cons':'pesticide'},
                },
                order: [0, 'desc'],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            $("#pestForm").show();
            $("#sampleForm").hide();
            $("#deleteForm").hide();
            $("#wasteForm").hide();
            $("#weightForm").hide();
            $("#btn_pesticide").addClass("bg-gradient-green").removeClass("bg-gradient-blue");
            $("#btn_sample").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_deletePlants").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_waste").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
        });
        //Waste some of plants
        $(document).on('click', '#btn_waste', function() {
            $('#id').val(current_id);
            $("#waste_lot_ID").val(current_mother_uid);
            $("#waste_lot_ID_text").val(current_plant_uid_txt);
            var table = $('#historylog_below').DataTable({
                "processing": true,
                searching: false,
                "bDestroy": true,
                info:false,
                "scrollY":        "200px",
                "scrollCollapse": true,
                "paging":         false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory', 'cons':'waste'},
                },
                order: [0, 'desc'],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            // $("#historylog_below").show();
            $('#act_delete').val("plants");
            $("#wasteForm").show();
            $("#sampleForm").hide();
            $("#pestForm").hide();
            $("#deleteForm").hide();
            $("#weightForm").hide();
            $("#btn_waste").addClass("bg-gradient-green").removeClass("bg-gradient-blue");
            $("#btn_sample").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_deletePlants").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_pesticide").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
        })
        // Array holding selected row IDs
        var rows_selected = [];
        // datatable
        var table = $('#example1').DataTable({
            "processing": true,
            "serverSide": true,
            initComplete: function() {
                $(this.api().table().container()).find('input').parent().wrap('<form>').parent().attr('autocomplete', 'off');
            },
            "ajax": {
                "url": "../Logic/tableMotherPlants.php",
                "data": {
                    "room": HttpGetRequest('room')
                }
            },
            order: [1, 'asc'],
            "aoColumnDefs": [{
                    data: 'checkbox_id',
                    "aTargets": [0], // Column number which needs to be modified
                    "mRender": function(data, type, row) { // o, v contains the object and value for the column
                        // return '<input class="plantCheckBox" type="checkbox" id="checkbox_' + data + '" value="' + data + '">';
                        return '<input type="checkbox">';
                    },
                    "sClass": 'dt-body-center', // Optional - class to be applied to this table cell
                    "bSearchable": false,
                    "bSortable": false,
                    "sWidth": "1%",
                },
                {
                    data: 'td_id',
                    "sClass": "td_id d-none",
                    // "bVisible": false,
                    "aTargets": [1]
                },
                {
                    data: 'td_qr_code',
                    "sClass": "td_qr_code d-none",
                    // "bVisible": false,
                    "aTargets": [2],
                },
                {
                    data: 'td_mother_UID',
                    "sClass": "td_mother_UID d-none",
                    "aTargets": [3],
                },
                {
                    data: 'td_plant_UID_text',
                    "sClass": "td_plant_UID_text",
                    // "bVisible": false,
                    "aTargets": [4],
                },
                {
                    data: 'td_name',
                    "sClass": "td_name",
                    "aTargets": [5],
                },
                {
                    data: 'td_genetic_id',
                    "sClass": "td_genetic_id d-none",
                    // "bVisible": false,
                    "aTargets": [6],
                },
                {
                    data: 'td_genetic',
                    "sClass": "td_genetic",
                    "aTargets": [7],
                },
                {
                    data: 'td_mother_id',
                    "sClass": "td_mother_id",
                    "aTargets": [8],
                },
                
                {
                    data: 'td_location_id',
                    "sClass": "td_location_id d-none",
                    // "bVisible": false,
                    "aTargets": [9],
                },
                {
                    data: 'td_location',
                    "sClass": "td_location",
                    "aTargets": [10],
                },

                {
                    data: 'td_planting_date',
                    "sClass": "td_planting_date",
                    "aTargets": [11],
                },
                {
                    data: 'td_days',
                    "sClass": "td_days",
                    "aTargets": [12],
                },
                {
                    data: 'td_observation',
                    "sClass": "td_observation d-none",
                    "aTargets": [13],
                },
                {
                    data: 'buttons',
                    "aTargets": [14],
                    "mRender": function(data, type, row) { // o, v contains the object and value for the column
                        return '<a class="btn bg-gradient-green btn-sm" id="btn_edit" href="#" data-dismiss="modal"> ' +
                            '<i class="fas fa-pencil-alt"></i>' +
                            'Edit' +
                            '</a>'
                    },
                }
            ],
            "rowCallback": function(row, data, dataIndex) {
                // Get row ID
                var rowId = data['checkbox_id'];
                // If row ID is in the list of selected row IDs
                if ($.inArray(rowId, rows_selected) !== -1) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
        });
        // Handle click on checkbox
        $('#example1 tbody').on('click', 'input[type="checkbox"]', function(e) {
            var $row = $(this).closest('tr');
            // Get row data
            var data = table.row($row).data();
            // Get row ID
            var rowId = data['checkbox_id'];
            // Determine whether row ID is in the list of selected row IDs
            var index = $.inArray(rowId, rows_selected);
            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected.push(rowId);
                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected.splice(index, 1);
            }
            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // // Handle click on table cells with checkboxes
        // $('#example1').on('click', 'tbody td, thead th:first-child', function(e) {
        //     $(this).parent().find('input[type="checkbox"]').trigger('click');
        // });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table.table().container()).on('click', function(e) {
            if (this.checked) {
                $('#example1 tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example1 tbody input[type="checkbox"]:checked').trigger('click');
            }
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        table.on('draw', function() {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });


        //END

        $('#start_plant_UID').change(function() {
            var selectedPlantUID = $(this).val();
            $(this).data('options', $('#start_plant_UID option').clone());
            options = $(this).data('options');
            var cnt = options.length;
            var option_array = [];
            if (selectedPlantUID) {
                for (var i = 0; i < cnt; i++) {
                    var value = options[i].value;
                    if (parseInt(value) >= parseInt(selectedPlantUID)) {
                        option_array.push(options[i]);
                    }
                }
                $('#end_plant_UID').html(option_array);
                $('#end_plant_UID').select2();
            } else {
                for (var i = 0; i < cnt; i++) {
                    var value = options[i].value;
                    option_array.push(options[i]);
                }
                $('#end_plant_UID').html(option_array);
                $('#end_plant_UID').select2();
            }
        })

        //Go to Multi Print Label Page
        $('#btn_gotoMultiPrintLabelPage').click(function() {
            var start_ID = $('#start_plant_UID').val();
            var end_ID = $('#end_plant_UID').val();
            var plant_type = "mother";
            var location = 'index_mother';
            $.ajax({
                method: 'POST',
                url: '../Logic/saveMotherPlants.php',
                data: {
                    act: "multi_print",
                    start_ID: start_ID,
                    end_ID: end_ID
                },
                success: function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    $.ajax({
                        method: 'POST',
                        url: '../Utilities/phpqrcode/index.php',
                        data: {
                            act: "multi",
                            data: data
                        },
                        success: function(data) {
                            var obj = JSON.parse(data);
                            console.log(obj);
                        }
                    })
                }
            })
            $.redirect('../Views/printLabel.php', {
                start_ID: start_ID,
                end_ID: end_ID,
                plant_type: plant_type,
                location: location
            }, 'POST', '_blank');
        })

        $(document).on('click', '#btn_printQRCode', function() {
            
            var id = $("#edit_id").val();
            var qr_code= $("#edit_qr_code").val();
            var name = $("#edit_name").val();
            var planting_date = $("#edit_planting_date").val();
            var mother_id = $("#edit_seed").val();
            var mother_id_text = $("#edit_seed").val();
            var plant_UID = $("#edit_mother_UID").val();
            var plant_UID_text = $("#edit_plant_UID_text").val();
            var observation = $("#edit_observation").val();
            var location = $('#edit_location').val();
            var genetic = $('#edit_genetic').val();

            //var data = "Name: " + name + ", Plant ID: " + plant_UID_text + ", Mother ID: "+ mother_id_text + ", Date: " + planting_date + ", Code: " + qr_code;
            var data = qr_code;
            var filename = plant_UID_text;
            /*
             Possible Post Datas : data, level, size But now i sent only data
             level: 'L','M','Q','H'    default is 'L'
             size: 1 - 10              default is  4
             */
            $.ajax({
                method: 'POST',
                url: '../Utilities/phpqrcode/index.php',
                data: {
                    data: data,
                    filename: filename
                },
                success: function(data) {
                    //var obj = JSON.parse(data);
                    //console.log(obj);
                }
            })
            setTimeout(
                function() {
                    //show modal printlabel
                    document.getElementById("print_qr").innerHTML = '<img src="../QR_Code/' + filename + '.png" height="124px">';
                    document.getElementById("print_name").innerHTML = name;
                    document.getElementById("print_id").innerHTML = "<?php echo $_SESSION['label']; ?>" + '-' + plant_UID_text;
                    document.getElementById("print_mother_id").innerHTML = mother_id_text;
                    document.getElementById("print_date").innerHTML = planting_date;
                    document.getElementById("print_qr_code").innerHTML = qr_code;
                    $("#modal-print-label").modal('show');
                }, 300);
        })

        //click add mother plant
        $("#btn_addMotherPlant").click(function() {
            var act = "add"
            //get Current Date
            var currentDate = _getCurrentDate();
            
            $("#planting_date").val(currentDate);

            $("#genetic").prop("disabled", false);
            $("#seed").prop("disabled", false);


            $.ajax({
                method: 'POST',
                url: '../Logic/saveMotherPlants.php',
                data: {
                    act: 'generate'
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj)
                    /*
                     obj[0] : $qr_code
                     obj[1] : $plant_UID
                     obj[2] : $plant_UID_text
                     */
                    $('#qr_code').val(obj[0]);
                    $('#mother_UID').val(obj[1]);
                    $('#plant_UID_text').val(obj[2]);
                }
            })
            $("#act").val(act);
            //hidden QR code input section when create clone plants
            $("#qr_code_section").prop("hidden", true);
            $("#plant_UID_section").prop("hidden", true);
            //show Modal
            $("#modal-add-Mother-Plants").modal('show');
        });

        $(document).on('click', '#btn_deletePlants', function() {
            $('#id').val(current_id);
            $("#del_lot_ID").val(current_mother_uid);
            $("#del_lot_ID_text").val(current_plant_uid_txt);
            var table = $('#historylog_below').DataTable({
                "processing": true,
                // "serverSide": true,
                searching: false,
                "bDestroy": true,
                info: false,
                paging: false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory', 'cons':'destroy'},
                },
                order: [0, 'desc'],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            $('#act_delete').val("plants");
            $("#deleteForm").show();
            $("#weightForm").hide();
            $("#sampleForm").hide();
            $("#wasteForm").hide();
            $("#pestForm").hide();
            $("#btn_sample").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_waste").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_pesticide").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_deletePlants").addClass("bg-gradient-green").removeClass("bg-gradient-blue");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
        })
        $(document).on('click', '#btn_historylog', function() {
            $('#hist_lot_id').val(current_mother_uid);
            var table = $('#historylog_below').DataTable({
                "processing": true,
                searching: false,
                "bDestroy": true,
                info: false,
                scrollY:        '400px',
                scrollCollapse: true,
                paging:         false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory'},
                },
                aaSorting: [[0, 'desc']],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            // $('#act_delete').val("plants");
            $("#deleteForm").hide();
            $("#weightForm").hide();
            $("#sampleForm").hide();
            $("#pestForm").hide();
            $("#wasteForm").hide();
            $("#btn_sample").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_waste").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_pesticide").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-green").removeClass("bg-gradient-blue");
            $("#btn_deletePlants").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
        })
        $("#btn_confirm").click(function() {
            document.getElementById("val_userpass_val").style.display = "None";
            document.getElementById("val_adminpass_val").style.display = "None";
            var user_pass = $('#val_userpass').val();
            var admin_pass = $('#val_adminpass').val();
            var admin_id = $('#supervisor_ID').val();
            var act = $('#act_validation').val();
            var flg = 0;
            if(user_pass.length == 0) {
                flg = 1;
                document.getElementById("val_userpass_val").style.display = "Block";
            }
            if(admin_pass.length == 0) {
                flg = 1;
                document.getElementById("val_adminpass_val").style.display = "Block";
            }
            if(flg == 1) return 0;

            $.ajax({
                    method: 'POST',
                    url: '../Logic/saveClonePlants.php',
                    dataType: "text",
                    data: {
                        act: 'user_validate',
                        user_pass: user_pass,
                        admin_pass: admin_pass,
                        admin_id : admin_id
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
                        // console.log(data);
                        if (obj == 'faild') {
                            alert('Validation faild. Please check again');
                            return 0;
                        } else {
                            $("#modal-validation").modal('hide');
                            if(act == 'transfer'){
                                $('#vegTrnasferFormValidate').submit();
                            }
                            else if(act =='delete') {
                                $('#del_adminID').val(admin_id);
                                $('#deleteForm').submit();
                            }
                        }
                    }
                })
        });
        //Delete sub plants
        $('#btn_delete').click(function(){
            document.getElementById("del_weight").style.display = "None";
            document.getElementById("del_reason").style.display = "None";
            var weightofPlant = Number($('#del_weightofplant').val());
            var reason = $("#del_note").val();
            var flg = 0;
            current_del_weight = weightofPlant;
            current_del_reason = $('#del_note').val();
            if(Number.isNaN(weightofPlant) || weightofPlant == 0){
                document.getElementById("del_weight").style.display = "block";
                flg = 1;
            } 
            if(reason.length == 0){
                document.getElementById("del_reason").style.display = "block";
                flg = 1;
            }
            if(flg == 1) return ;
            // $('#deleteForm').submit();
            $('#act_validation').val('delete');
            $("#modal-validation").modal('show');
            return false;
        })
        $('#btn_save_edit').click(function() {
            // $("#modal-create-new-lotID").modal.close();
            // event.preventDefault();
            document.getElementById("edit_note").style.display = "none";
            var note = $('#edit_observation').val();
            if(note.length == 0 || note.localeCompare(current_note) == 0) {
                document.getElementById("edit_note").style.display = "block";
                return false;
            }
            $('#editMotherPlantFormValidate').submit();
            return false;
        })

        //Edit mother plant
        //reference url https://jsfiddle.net/1s9u629w/1/
        $(document).on('click', '#btn_edit', function() {
            var act = "edit";
            $("#act").val(act);

            $("#deleteForm").hide();
            $("#weightForm").hide();
            $("#sampleForm").hide();
            $("#pestForm").hide();
            $("#wasteForm").hide();
            $("#btn_sample").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_waste").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_pesticide").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_deletePlants").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_weightbtn").addClass("bg-gradient-blue").removeClass("bg-gradient-green");
            $("#btn_historylog").addClass("bg-gradient-green").removeClass("bg-gradient-blue");

            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name = $row.find('.td_name').text();
            var location = $row.find('.td_location_id').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var genetic = $row.find('.td_genetic_id').text();
            var mother_UID = $row.find('.td_mother_UID').text();
            var plant_UID_text = $row.find('.td_plant_UID_text').text();
            var observation = $row.find('.td_observation').text();

            // Save current Row ID and lot_ID, lot_id_txt;
            current_id = id;
            current_mother_uid = mother_UID;
            current_plant_uid_txt = plant_UID_text;
            current_note = observation;
            $("#lot_ID_text_header").text(plant_UID_text);

            $("#edit_id").val(id);
            $("#edit_qr_code").val(qr_code);
            $("#edit_name").val(name);
            $("#edit_planting_date").val(planting_date);
            $("#edit_seed").val(mother_id);
            $("#edit_mother_UID").val(mother_UID);
            $("#edit_plant_UID_text").val(plant_UID_text);
            $("#edit_observation").val(observation);
            $('#edit_location').val(location);
            $('#edit_location').select2().trigger('change');

            $('#edit_genetic').val(genetic);
            $('#edit_genetic').select2().trigger('change');

            $("#edit_quantity_section").prop("hidden", true);
            $("#edit_quantity").prop('disabled', true);

            //verify the mother have clone plants
            $.ajax({
                method: 'POST',
                url: '../Logic/saveMotherPlants.php',
                data: {
                    act: 'verifyMotherHaveClonePlants',
                    motherID: mother_UID
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj == 'exist') {
                        $("#edit_genetic").prop("disabled", true);
                        $("#edit_seed").prop("disabled", true);
                    }else {
                        $("#edit_genetic").prop("disabled", false);
                        $("#edit_seed").prop("disabled", false);
                    }
                }
            })
            // ...
            var table = $('#historylog_below').DataTable({
                "processing": true,
                searching: false,
                "bDestroy": true,
                info: false,
                scrollY:        '400px',
                scrollCollapse: true,
                paging:         false,
                "ajax": {
                    "url": "../Logic/saveMotherPlants.php",
                    "data": {'mother_uid':current_mother_uid, "act":'gethistory'},
                },
                aaSorting: [[0, 'desc']],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    }
                ],

            });
            setTimeout(function() { 
                $("#btn_historylog").click();
            }, 150);
            $("#modal-edit").modal('show');

        });

        //modal clear when close
        $('#modal-edit').on('hidden.bs.modal', function() {
            $("#qr_code").val("");
            $("#name").val("");
            $("#location").val("");
            $('#location').select2().trigger('change');
            $("#planting_date").val("");
            $("#seed").val("");
            $("#observation").val("");
            $("#plant_UID").val("");
            $("#plant_UID_text").val("");
            $('#genetic').val("");
            $('#genetic').select2().trigger('change');
            //Show QR code input section when create clone plants
            $("#qr_code_section").prop("hidden", false);
            $("#plant_UID_section").prop("hidden", false);
            $("#quantity_section").prop("hidden", false);
            $("#quantity").prop('disabled', false);
        })

        $("#btn_saveMotherPlant").click(function() {
            // Send post request to register in Database
            var qr_code = $('#qr_code').val();
            var plant_UID = $('#plant_UID').val();
            var act = $('#act').val();
            document.getElementById("add_genetic").style.display = "None";
            document.getElementById("add_number").style.display = "None";
            document.getElementById("add_location").style.display = "None";
            document.getElementById("add_note").style.display = "None";
            document.getElementById("add_motherid").style.display = "None";
            var genetic = $('#genetic').find(":selected").val();
            var location = $('#location').find(":selected").val();
            var note = $('#observation').val();
            var seed = $('#seed').val();
            var num = Number($('#quantity').val());

            var flg = 0;
            if(Number.isNaN(num) || num == 0) {
                document.getElementById("add_number").style.display = "block";
                flg = 1;
            }
            if(genetic.length == 0) {
                document.getElementById("add_genetic").style.display = "block";
                flg = 1;
            }
            if(location.length == 0) {
                document.getElementById("add_location").style.display = "block";
                flg = 1;
            }
            if(note.length == 0) {
                document.getElementById("add_note").style.display = "block";
                flg = 1;
            }
            if(seed.length == 0) {
                document.getElementById("add_motherid").style.display = "block";
                flg = 1;
            }
            if(flg == 1) return ;
            $("#btn_saveMotherPlant").prop("disabled", true);
            $('#addMotherPlantFormValidate').submit();
            return false;
        })

        //Change Event of select box (Mother Room)
        $('#selectedMotherRoomID').on('change', function() {
            var motherRoomID = this.value;
            if (motherRoomID == 0) {
                $.redirect('../Views/plantsMother.php');
            } else {
                $.redirect('../Views/plantsMother.php', {
                        room: motherRoomID
                    },
                    'GET');
            }
        });

        var checkedPlantList = [];

        //Click Delete Selected Plants Button
        $('#btn_deleteMotherPlants').click(function() {
            checkedPlantList = [];
            
            //get checked list
            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, id) {
                checkedPlantList.push(id);
            });
            // ...

            var motherRoomID = $('#currentRoomID').val();
            $.ajax({
                method: 'POST',
                url: '../Logic/saveMotherPlants.php',
                data: {
                    act: 'delete',
                    idList: checkedPlantList
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj == 'exist') {
                        toastr.error("You can't Delete Genetic Because of Plants are using.")
                    }
                    if (obj == 'success') {
                        $.redirect('../Views/plantsMother.php', {
                                room: motherRoomID
                            },
                            'GET');
                    }
                }
            })


            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        })

        // change genetice select box ==> put plant name automatically on plant Name field
        $('select#genetic').change(function() {

            var selectedGeneticID = $(this).val();
            console.log(selectedGeneticID);

            $.ajax({
                method: 'POST',
                url: '../Logic/saveGenetic.php',
                data: {
                    act: 'getPlantName',
                    selectedGeneticID: selectedGeneticID
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    $('#name').val(obj);
                }

            })
        })
        //Click hostory button for a mother plant
        $(document).on('click', '#btn_historyMotherPlant', function() {
            event.preventDefault();
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var mother_UID = $row.find('.td_mother_UID').text();
            $.redirect('../Views/history.php', {
                    id: mother_UID,
                    type: 'mother'
                },
                'POST');
        })
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