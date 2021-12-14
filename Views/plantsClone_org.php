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

$rClone = new CloneRoom();
$mCloneRoomList = $rClone->getAllOfInfo();
$mCloneRoomList = $mCloneRoomList->results();

$pClone = new ClonePlant();

if ($_GET['room']) {
    $cloneRoomID = $_GET['room'];
    $plantsIDList = $pClone->getPlantsListFromCloneRoomID($cloneRoomID);
} else {

    $plantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
    $plantsIDList = $plantsIDList->results();
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

$mLotList = $p_general->getValueOfAnyTable('lot_id', '1', '=', '1');
$mLotList = $mLotList->results();

$k = 0;
?>


<div class="content-wrapper">

    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="clone" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="clone_plants_tab" data-toggle="pill" href="#clone_plants_content" role="tab" aria-controls="clone_plants_tab" aria-selected="true">Clone Plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="clone_rooms_tab" href="roomsClone.php">Clone Rooms</a>
                </li>
                <!--                    <li class="nav-item">-->
                <!--                        <a class="nav-link" id="clone_history_tab" href="historyClonePlant.php" >History</a>-->
                <!--                    </li>-->
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">

                <div class="tab-pane fade show active" id="clone_plants_content" role="tabpanel" aria-labelledby="clone_plants_content">

                    <!-- Clone Plants Section-->

                    <div class="content-header nopadding">
                        <div class="container-fluid">
                            <div class="row mb-2">

                                <div class="col-sm-2">

                                    <div class="form-group">
                                        <input name="currentRoomID" id="currentRoomID" type="hidden" value="<?= $cloneRoomID ?>">

                                        <select class="form-control select2bs4" id="selectedCloneRoomID" style="width: 100%;">

                                            <!--SelectBox Clone Room-->

                                            <option value="0">All Clone Room</option>
                                            <?php
                                            if ($mCloneRoomList) {
                                                $k = 0;
                                                foreach ($mCloneRoomList as $mCloneRoom) {

                                            ?>

                                                    <option <?php
                                                            if ($cloneRoomID == $mCloneRoom->id) {
                                                                echo 'selected';
                                                            }
                                                            ?> value="<?= $mCloneRoom->id ?>"><?= $mCloneRoom->name ?></option>

                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- /.form-group -->

                                </div>

                                <div class="col-sm-3">

                                </div>

                                <div class="col-sm-7" style="text-align: right">

                                    <a class="btn btn-md bg-gradient-green " href="#" id="btn_addClonePlant">
                                        <i class="fas fa-plus"></i>
                                        Create Clone Plants
                                    </a>
                                    <a class="btn bg-gradient-primary btn-md" href="#" id="btn_transferClonePlant">
                                        <i class="fas fa-paper-plane"></i>
                                        Transfer to Vegetation Room
                                    </a>
                                    <a class="btn bg-gradient-yellow btn-md" id="btn_multiPrint" data-target="#modal-multi-print-label" href="#modal-multi-print-label" data-toggle="modal">
                                        <i class="fas fa-print"></i>
                                        Multi Print
                                    </a>
                                    <a class="btn bg-gradient-danger btn-md" data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Clone Plants
                                    </a>
                                </div>

                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                    <!-- /.content-header -->
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="select_all">
                                        </th>
                                        <th style="display:none">id</th>
                                        <th style="display:none">QR code</th>
                                        <th style="display:none">Plant ID really</th>
                                        <th>Plant ID</th>
                                        <th>Name</th>
                                        <th style="display:none">Genetic ID</th>
                                        <th>Genetic</th>
                                        <th style="display:none">mother id</th>
                                        <th>Mother ID</th>
                                        <th style="display:none">mother id exist on mother room</th>
                                        <th style="display:none">Location ID</th>
                                        <th>Location</th>
                                        <th>Born Date</th>
                                        <th>Days</th>
                                        <th style="display:none">Observations</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="plant_table">

                                    <?php

                                    foreach ($plantsIDList as $plantID) {
                                        $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $plantID->plant_id);
                                        $plantInfo = $plantInfo->results();

                                        //Calculate Today - Planting Date(born date) = Reporting days
                                        $m_room_date = date_create($plantInfo[0]->room_date);
                                        $m_today = date_create(date("m/d/Y"));
                                        $diff = date_diff($m_room_date, $m_today);
                                        $days = $diff->format("%a");
                                        //End//
                                        $k++;

                                    ?>
                                        <tr>
                                            <td>
                                                <input class="plantCheckBox" type="checkbox" id="checkbox_<?= $k ?>" value="<?= $plantInfo[0]->id ?>">
                                            </td>
                                            <td style="display:none" class="td_id"><?= $plantInfo[0]->id ?></td>
                                            <td style="display:none" class="td_qr_code"><?= $plantInfo[0]->qr_code ?></td>
                                            <td style="display:none" class="td_plant_UID"><?= $plantInfo[0]->plant_UID ?></td>
                                            <td class="td_plant_UID_text"><?php
                                                                            $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                                                            echo $plant_UID_text;
                                                                            ?></td>
                                            <td class="td_name"><?= $plantInfo[0]->name ?></td>
                                            <td style="display:none" class="td_genetic_id"><?= $plantInfo[0]->genetic ?></td>
                                            <td class="td_genetic"><?php
                                                                    $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $plantInfo[0]->genetic);
                                                                    $geneticInfo = $geneticInfo->results();
                                                                    echo $geneticInfo[0]->genetic_name;
                                                                    ?>
                                            </td>
                                            <td style="display:none" class="td_mother_id"><?= $plantInfo[0]->mother_id ?></td>
                                            <td class="td_mother_id_text"><?= $plantInfo[0]->mother_text ?></td>
                                            <td style="display:none" class="td_mother_id_exist"><?php
                                                                                                $exist = $p_general->getValueOfAnyTable('plants', 'id', '=', $plantInfo[0]->mother_id);
                                                                                                $exist = $exist->results();
                                                                                                echo sizeof($exist);
                                                                                                ?>
                                            </td>
                                            <td style="display:none" class="td_location_id"><?= $plantInfo[0]->location ?></td>
                                            <td class="td_location"><?php
                                                                    $roomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $plantInfo[0]->location);
                                                                    $roomInfo = $roomInfo->results();
                                                                    echo $roomInfo[0]->name;
                                                                    ?>
                                            </td>
                                            <td class="td_planting_date"><?= $plantInfo[0]->planting_date ?></td>
                                            <td class="td_days"><?= $days ?></td>
                                            <td style="display:none" class="td_observation"><?= $plantInfo[0]->observation ?></td>
                                            <td style="text-align: center">
                                                <a class="btn btn-sm bg-gradient-blue " href="#" id="btn_historyClonePlant">
                                                    <i class="fas fa-history"></i>
                                                    History
                                                </a>
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editClonePlant" data-target="#modal-add-Clone-Plants" href="#modal-add-Clone-Plants" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>
                                                <a class="btn bg-gradient-yellow btn-sm" id="btn_printQRCode">
                                                    <i class="fas fa-print"></i>
                                                    Print
                                                </a>
                                            </td>
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
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Modal Create Clone Plants to Room (Add / Edit)-->
<div class="modal fade" id="modal-add-Clone-Plants">
    <div class="modal-dialog width-modal-dialog-plants-clone">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Clone Plant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveClonePlants.php" enctype='multipart/form-data' id="editClonePlantFormValidate">
                <input name="id" id="id" type="hidden" value="">
                <input name="act" id="act" type="hidden" value="">
                <input name="showRoom" id="showRoom" type="hidden" value="<?= $_GET['room'] ?>">

                <div class="modal-body">
                    <fieldset>
                        <div class="row">
                            <div class="col-6">
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
                                <div id="add_section">
                                    <label>Mother ID</label>
                                    <div class="form-group">
                                        <select class="form-control select2bs4" name="select_mother_id_add" id="select_mother_id_add" style="width: 100%;">
                                            <option value="">Select Mother ID</option>
                                            <?php
                                            $motherPlantIDList = $p_general->getValueOfAnyTable('index_mother', '1', '=', '1', 'plant_id');
                                            $motherPlantIDList = $motherPlantIDList->results();
                                            foreach ($motherPlantIDList as $motherPlantID) {
                                                $motherPlantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $motherPlantID->plant_id);
                                                $motherPlantInfo = $motherPlantInfo->results();
                                            ?>
                                                <option value="<?= $motherPlantInfo[0]->id ?>"><?php
                                                                                                $plant_UID_text = $p_general->getTextOfMotherUID($motherPlantInfo[0]->mother_UID);
                                                                                                echo $plant_UID_text;
                                                                                                ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="edit_section">
                                    <label>Mother ID</label>
                                    <div class="form-group">
                                        <select class="form-control select2bs4" name="select_mother_id_edit" id="select_mother_id_edit" style="width: 100%;">

                                            <option value="">Select Mother ID</option>
                                            <?php
                                            $motherPlantIDList = $p_general->getValueOfAnyTable('index_mother', '1', '=', '1', 'plant_id');
                                            $motherPlantIDList = $motherPlantIDList->results();
                                            foreach ($motherPlantIDList as $motherPlantID) {
                                                $motherPlantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $motherPlantID->plant_id);
                                                $motherPlantInfo = $motherPlantInfo->results();
                                            ?>
                                                <option value="<?= $motherPlantInfo[0]->id ?>"><?php
                                                                                                $plant_UID_text = $p_general->getTextOfMotherUID($motherPlantInfo[0]->mother_UID);
                                                                                                echo $plant_UID_text;
                                                                                                ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <label>Genetic</label>
                                <div class="form-group input-group mb-3">
                                    <input type="hidden" id="genetic" name="genetic">
                                    <input type="text" class="form-control" placeholder="Genetic" id="genetic_name" name="genetic_name" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
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
                                </div>
                                <label>Location(Room)</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="location" id="location" style="width: 100%;">
                                        <option value="">Select Room</option>
                                        <?php
                                        $pCloneRoom = new CloneRoom();
                                        $mCloneRoomList = $pCloneRoom->getAllOfInfo();
                                        $mCloneRoomList = $mCloneRoomList->results();
                                        foreach ($mCloneRoomList as $mCloneRoom) {
                                        ?>
                                            <option value="<?= $mCloneRoom->id ?>"><?= $mCloneRoom->name ?></option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div id="plant_UID_section">
                                    <label>Plant ID</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Unique Number" id="plant_UID_text" name="plant_UID_text" autocomplete="off" readonly>
                                        <input type="hidden" class="form-control" id="plant_UID" name="plant_UID">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label>Born Date</label>
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
                                    <textarea class="form-control" rows="3" placeholder="Enter ..." id="observation" name="observation" style="height: 204px"></textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="btn_saveClonePlant" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal Transfer with New Lot ID -->
<div class="modal fade" id="modal-transfer-new-lotID">
    <div class="modal-dialog width-modal-dialog-transfer-plants">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Transfer to Vegetation Room</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveClonePlants.php" enctype='multipart/form-data' id="LotFormValidate">
                <div class="modal-body">
                    <fieldset>
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="packing_new_lotID">

                        <div class="row">
                            <div class="col-6">

                                <div class="form-group">
                                    <label>Genetic</label>
                                    <select class="form-control select2bs4" name="packing_genetic_id" id="packing_genetic_id" style="width: 100%;">
                                        <option value="">Select Genetic</option>
                                        <?php
                                        $geneticList = $p_general->getValueOfAnyTable('genetic', '1', '=', '1', 'id');
                                        $geneticList = $geneticList->results();
                                        foreach ($geneticList as $genetic) {
                                            $exist = $p_general->getValueOfAnyTable('index_clone', 'genetic_id', '=', $genetic->id);
                                            $exist = $exist->results();
                                            if ($exist) {
                                        ?>
                                                <option value="<?= $genetic->id ?>"><?= $genetic->genetic_name ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Start Plant ID</label>
                                    <select class="form-control select2bs4" name="start_packing_plant_UID_new_lot_ID" id="start_packing_plant_UID_new_lot_ID" style="width: 100%;">
                                        <option value="">Select Plant ID</option>
                                        <?php
                                        $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                        $clonePlantsIDList = $clonePlantsIDList->results();
                                        foreach ($clonePlantsIDList as $clonePlant) {
                                            $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                            $plantInfo = $plantInfo->results();
                                            $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                        ?>
                                            <option data-genetic="<?= $plantInfo[0]->genetic ?>" value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- <label>Mother ID</label>
                                <div class="form-group input-group mb-3">
                                    <input type="hidden"  id="mother_ID" name="mother_ID" >
                                    <input type="text" class="form-control" placeholder="Mother ID" id="mother_text" name="mother_text" readonly >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div> !-->

                                <label>Lot ID</label>
                                <div class="form-group input-group mb-3">
                                    <input type="hidden" class="form-control" id="lot_ID" name="lot_ID">
                                    <input type="text" class="form-control" placeholder="Lot ID" id="lot_ID_text" name="lot_ID_text" autocomplete="off" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <label>Date</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group date" id="reservationdate_1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate_1" id="born_date" name="born_date" />
                                        <div class="input-group-append" data-target="#reservationdate_1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>End Plant ID</label>
                                    <select class="form-control select2bs4" name="end_packing_plant_UID_new_lot_ID" id="end_packing_plant_UID_new_lot_ID" style="width: 100%;">
                                        <option value="">Select Plant ID</option>
                                        <?php
                                        $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                        $clonePlantsIDList = $clonePlantsIDList->results();
                                        foreach ($clonePlantsIDList as $clonePlant) {
                                            $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                            $plantInfo = $plantInfo->results();
                                            $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                        ?>
                                            <option data-genetic="<?= $plantInfo[0]->genetic ?>" value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Transfer to</label>
                                    <select class="form-control select2bs4" name="veg_room_id" id="veg_room_id" style="width: 100%;">
                                        <option value="">Select Vegetation Room</option>
                                        <?php
                                        $roomVegList = $p_general->getValueOfAnyTable('room_veg', '1', '=', '1');
                                        $roomVegList = $roomVegList->results();
                                        foreach ($roomVegList as $roomVeg) {
                                        ?>
                                            <option value="<?= $roomVeg->id ?>"><?= $roomVeg->name ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <label>Note</label>
                        <div class="form-group input-group mb-3">
                            <textarea class="form-control" rows="3" placeholder="Enter ..." id="note" name="note" style="height: 204px"></textarea>
                        </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Transfer To Vegetation Room"></input>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal Transfer to Exist Lot ID -->
<div class="modal fade" id="modal-transfer-exist-lotID">
    <div class="modal-dialog width-modal-dialog-transfer-plants">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Transfer to Vegetation Room</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveClonePlants.php" enctype='multipart/form-data' id="clonePackingFormValidateExistLotID">
                <div class="modal-body">
                    <fieldset>
                        <input name="act" id="act" type="hidden" value="packing_exist_lotID">

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Start Plant ID</label>
                                    <select class="form-control select2bs4" name="start_plant_UID_exist_lot_ID" id="start_plant_UID_exist_lot_ID" style="width: 100%;">
                                        <option value="">Select Plant ID</option>
                                        <?php
                                        $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                        $clonePlantsIDList = $clonePlantsIDList->results();
                                        foreach ($clonePlantsIDList as $clonePlant) {
                                            $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                            $plantInfo = $plantInfo->results();
                                            $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                        ?>
                                            <option data-genetic="<?= $plantInfo[0]->genetic ?>" value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-1" style="padding-top: 40px;text-align: center;"> ~ </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>End Plant ID</label>
                                    <select class="form-control select2bs4" name="end_plant_UID_exist_lot_ID" id="end_plant_UID_exist_lot_ID" style="width: 100%;">

                                        <option value="">Select Plant ID</option>
                                        <?php
                                        $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                        $clonePlantsIDList = $clonePlantsIDList->results();
                                        foreach ($clonePlantsIDList as $clonePlant) {
                                            $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                            $plantInfo = $plantInfo->results();

                                            $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);

                                        ?>
                                            <option data-genetic="<?= $plantInfo[0]->genetic ?>" value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>

                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Transfer to</label>
                                    <select class="form-control select2bs4" name="selectedLotID" id="selectedLotID" style="width: 100%;">
                                        <!--SelectBox Lot ID-->
                                        <option value="">Select Lot ID</option>
                                        <?php
                                        if ($mLotList) {
                                            $k = 0;
                                            foreach ($mLotList as $mLot) {
                                                $exist = $p_general->getValueOfAnyTable('index_veg', 'lot_id', '=', $mLot->lot_ID);
                                                $exist = $exist->results();
                                                if ($exist) {
                                        ?>
                                                    <option data-genetic="<?= $mLot->genetic_ID ?>" value="<?= $mLot->lot_ID ?>"><?php
                                                                                                                                    $lot_ID_text = $p_general->getTextOflotID($mLot->lot_ID);
                                                                                                                                    echo $lot_ID_text;
                                                                                                                                    ?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-1"></div>
                        </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Transfer To Vegetation Room"></input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Warning modal when delete  -->
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
                <button type="button" class="btn btn-outline-light" id="btn_deleteClonePlants">Yes</button>
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
                <h4 class="modal-title">Print Clone Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--body-->
            <div class="modal-body label_body " id="label_body">

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
                <!--                            ,css: '../dist/css/adminlte.min.css'-->
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
    <div class="modal-dialog width-modal-multi-print-label">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Multi Print Clones Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--body-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Start Plant ID</label>
                            <select class="form-control select2bs4" name="start_plant_UID_print" id="start_plant_UID_print" style="width: 100%;">
                                <option value="">Select Plant ID</option>
                                <?php
                                $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                $clonePlantsIDList = $clonePlantsIDList->results();
                                foreach ($clonePlantsIDList as $clonePlant) {
                                    $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                ?>
                                    <option value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-1" style="padding-top: 40px;text-align: center;"> ~ </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label>End Plant ID</label>
                            <select class="form-control select2bs4" name="end_plant_UID_print" id="end_plant_UID_print" style="width: 100%;">
                                <option value="">Select Plant ID</option>
                                <?php
                                $clonePlantsIDList = $p_general->getValueOfAnyTable('index_clone', '1', '=', '1', 'plant_id');
                                $clonePlantsIDList = $clonePlantsIDList->results();
                                foreach ($clonePlantsIDList as $clonePlant) {
                                    $plantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $clonePlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                ?>
                                    <option value="<?= $plantInfo[0]->plant_UID ?>"><?= $plant_UID_text ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2"></div>
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

<!-- Modal Create New Lot ID or Transfer Existing Lot ID when transfer plants-->
<div class="modal fade" id="modal-transfer-type">
    <div class="modal-dialog width-modal-transfer-type">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Choose Transfer Lot ID</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--body-->
            <div class="modal-body">
                <div class="row " style="text-align: center">
                    <div class="col-6">
                        <a class="btn bg-gradient-green btn-md" href="#" id="btn_createNewLotID" data-dismiss="modal">
                            <i class="fas fa-paper-plane"></i>
                            Create New Lot ID
                        </a>
                    </div>
                    <div class="col-6">
                        <a class="btn bg-gradient-primary btn-md" href="#" id="btn_existLotID" data-dismiss="modal">
                            <i class="fas fa-paper-plane"></i>
                            Existing Lot ID
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- /.modal -->


<script>
    //Select All , for that let's see https://jsfiddle.net/gyrocode/abhbs4x8/
    function updateDataTableSelectAllCtrl(table) {
        var $chkbox_all = $('#plant_table input[type="checkbox"]');
        var $chkbox_checked = $('#plant_table input[type="checkbox"]:checked');
        var chkbox_select_all = $('thead input[name="select_all"]');
        // If none of the checkboxes are checked
        if ($chkbox_checked.length === 0) {
            chkbox_select_all[0].checked = false;
            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all[0].checked = true;
            // If some of the checkboxes are checked
        } else {
            chkbox_select_all[0].checked = false;
        }
    }

    $(document).ready(function() {

        $(document).on('click', '#btn_printQRCode', function() {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name = $row.find('.td_name').text();
            var location = $row.find('.td_location_id').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var mother_id_text = $row.find('.td_mother_id_text').text();
            var genetic = $row.find('.td_genetic_id').text();
            var plant_UID = $row.find('.td_plant_UID').text();
            var plant_UID_text = $row.find('.td_plant_UID_text').text();
            //                var data = "Name: " + name + ", Plant ID: " + plant_UID_text + ", Mother ID: "+ mother_id_text + ", Date: " + planting_date + ", Code: " + qr_code;
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
                    var obj = JSON.parse(data);
                    console.log(obj);
                }
            })
            setTimeout(
                function() {
                    //show modal printlabel
                    document.getElementById("print_qr").innerHTML = '<img src="../QR_Code/' + filename + '.png" width="124px">';
                    document.getElementById("print_name").innerHTML = name;
                    document.getElementById("print_id").innerHTML = "<?php echo $_SESSION['label']; ?>" + '-' + plant_UID_text;
                    document.getElementById("print_mother_id").innerHTML = mother_id_text;
                    document.getElementById("print_date").innerHTML = planting_date;
                    document.getElementById("print_qr_code").innerHTML = qr_code;

                    $("#modal-print-label").modal('show');
                }, 300);
        })

        //Go to Multi Print Label Page
        $('#btn_gotoMultiPrintLabelPage').click(function() {
            var start_ID = $('#start_plant_UID_print').val();
            var end_ID = $('#end_plant_UID_print').val();
            var plant_type = "plant";
            var location = "index_clone";
            $.ajax({
                method: 'POST',
                url: '../Logic/saveClonePlants.php',
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

        //At multi print label select
        $('#start_plant_UID_print').change(function() {
            var selectedPlantUID = $(this).val();
            $(this).data('options', $('#start_plant_UID_print option').clone());
            options = $(this).data('options');
            var cnt = options.length;
            var option_array = [];
            for (var i = 0; i < cnt; i++) {
                var value = options[i].value;
                if (parseInt(value) >= parseInt(selectedPlantUID)) {
                    option_array.push(options[i]);
                }
            }
            $('#end_plant_UID_print').html(option_array);
            $('#end_plant_UID_print').select2();
        })

        // datatable
        var table = $('#example1').dataTable({
            'order': [1, 'asc'],
            "sPaginationType": "full_numbers",
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }],
            stateSave: true,
        });

        // $('#example1').DataTable({
        //     "processing": true,
        //     "serverSide": true,
        //     "ajax": {
        //         url: '/data-source',
        //         type: 'POST',
        //         data: {
        //             act: 'datatable',
        //         },
                
        //     }
        // });


        table.on('draw.dt', function() {

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('#example1 tbody').on('click', 'input[type="checkbox"]', function(e) {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('input[name="select_all"]').on('click', function(e) {
            if (this.checked) {
                $('#example1 tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example1 tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });
        //END

        var checkedPlantList = [];
        var allPages = table.fnGetNodes();

        //Click Delete Selected Plants Button
        $('#btn_deleteClonePlants').click(function() {
            checkedPlantList = [];
            $.each($("input[class='plantCheckBox']:checked", allPages), function() {
                //push selected Clone plants ID
                checkedPlantList.push($(this).val());
            });
            var cloneRoomID = $('#currentRoomID').val();
            $.ajax({
                method: 'POST',
                url: '../Logic/saveClonePlants.php',
                data: {
                    act: 'delete',
                    idList: checkedPlantList
                },
                success: function(data) {
                    $.redirect('../Views/plantsClone.php', {
                            room: cloneRoomID
                        },
                        'GET');
                }
            })
            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        })

        //Click transfer plant button
        $('#btn_transferClonePlant').click(function() {
            //show Modal
            $("#modal-transfer-type").modal('show');
        });

        var initialStartPlantList_new;
        var initialEndlantList_new;
        //Click transfer with Create New Lot ID
        $('#btn_createNewLotID').click(function() {
            //get Current Date
            var currentDate = _getCurrentDate();
            // ...


            // generate new lot id
            $.ajax({
                method: 'POST',
                url: '../Logic/saveClonePlants.php',
                data: {
                    act: 'generate'
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    /*
                     obj[0] : $lot_ID
                     obj[1] : $lot_ID_text
                     */
                    $('#lot_ID').val(obj[0]);
                    $('#lot_ID_text').val(obj[1]);
                }
            })
            // ...
            //$("#planting_date").val(currentDate);
            $('#born_date').val(currentDate); // lot born date
            //get plants list
            initialStartPlantList_new = $('#start_packing_plant_UID_new_lot_ID option').clone();
            initialEndlantList_new = $('#end_packing_plant_UID_new_lot_ID option').clone();
            //init select box
            $('#start_packing_plant_UID_new_lot_ID').html(initialStartPlantList_new[0]);
            $('#start_packing_plant_UID_new_lot_ID').select2();
            $('#end_packing_plant_UID_new_lot_ID').html(initialEndlantList_new[0]);
            $('#end_packing_plant_UID_new_lot_ID').select2();
            //show Modal
            $("#modal-transfer-new-lotID").modal('show');
        });



        $('#packing_genetic_id').change(function() {
            var selectedGeneticID = $(this).val();
            if (selectedGeneticID) {
                //start plant
                $('#start_packing_plant_UID_new_lot_ID').html(initialStartPlantList_new);
                options_start = initialStartPlantList_new;

                var cnt = options_start.length;
                var option_array_start = [];
                option_array_start.push(options_start[0]);
                for (var i = 1; i < cnt; i++) {
                    var value = options_start[i].value;
                    var genetic_id = $('#start_packing_plant_UID_new_lot_ID').find("option[value='" + value + "']").data("genetic");
                    if (genetic_id == selectedGeneticID) {
                        option_array_start.push(options_start[i]);
                    }
                }
                $('#start_packing_plant_UID_new_lot_ID').html(option_array_start);
                $('#start_packing_plant_UID_new_lot_ID').select2();

                //end plant
                $('#end_packing_plant_UID_new_lot_ID').html(initialEndlantList_new);
                options_end = initialEndlantList_new;
                var cnt = options_end.length;
                var option_array_end = [];
                option_array_end.push(options_end[0]);
                for (var i = 1; i < cnt; i++) {
                    var value = options_end[i].value;
                    var genetic_id = $('#end_packing_plant_UID_new_lot_ID').find("option[value='" + value + "']").data("genetic");
                    if (genetic_id == selectedGeneticID) {
                        option_array_end.push(options_end[i]);
                    }
                }
                $('#end_packing_plant_UID_new_lot_ID').html(option_array_end);
                $('#end_packing_plant_UID_new_lot_ID').select2();

            } else {

                $('#start_packing_plant_UID_new_lot_ID').html(initialStartPlantList_new);
                $('#start_packing_plant_UID_new_lot_ID').select2();

                $('#end_packing_plant_UID_new_lot_ID').html(initialEndlantList_new);
                $('#end_packing_plant_UID_new_lot_ID').select2();

            }
        });

        // change genetice select box ==> put plant name automatically on plant Name field
        $('#start_packing_plant_UID_new_lot_ID').change(function() {
            var selectedPlantUID = $(this).val();

            //            $.ajax({
            //                method:'POST',
            //                url: '../Logic/saveDryPlants.php',
            //                data: {act:'getGeneticID', selectedPlantUID:selectedPlantUID},
            //                success:function(data){
            //                    var obj = JSON.parse(data);
            //                    //    console.log(obj);
            //                    $('#mother_ID').val(obj[2]);
            //                    $('#mother_text').val(obj[3]);
            //                }
            //            })

            if (selectedPlantUID) {
                $(this).data('options', $('#start_packing_plant_UID_new_lot_ID option').clone());
                options = $(this).data('options');
                var cnt = options.length;
                var option_array = [];
                for (var i = 0; i < cnt; i++) {
                    var value = options[i].value;
                    if (parseInt(value) >= parseInt(selectedPlantUID)) {
                        option_array.push(options[i]);
                    }
                }
                $('#end_packing_plant_UID_new_lot_ID').html(option_array);
                $('#end_packing_plant_UID_new_lot_ID').select2();
            }
        })

        var initialEndPlantList_exist;
        var initialLot_exist;
        //Click transfer with Create New Lot ID
        $('#btn_existLotID').click(function() {
            //get plants list
            initialEndPlantList_exist = $('#end_plant_UID_exist_lot_ID option').clone();
            initialLot_exist = $('#selectedLotID option').clone();

            $('select#end_plant_UID_exist_lot_ID').html(initialEndPlantList_exist[0]);
            $('select#end_plant_UID_exist_lot_ID').select2();

            $('select#selectedLotID').html(initialLot_exist[0]);
            $('select#selectedLotID').select2();
            //show Modal
            $("#modal-transfer-exist-lotID").modal('show');
        });

        $('#start_plant_UID_exist_lot_ID').change(function() {
            var selectedPlantUID = $(this).val();

            var option_array_end = [];
            var option_array_lot = [];

            $('#end_plant_UID_exist_lot_ID').html(initialEndPlantList_exist);
            $('#selectedLotID').html(initialLot_exist);

            var options_end = initialEndPlantList_exist;
            var cnt = options_end.length;

            if (selectedPlantUID) {
                var genetic_id_start = $('#start_plant_UID_exist_lot_ID').find("option[value='" + selectedPlantUID + "']").data("genetic");
                for (var i = 0; i < cnt; i++) {
                    var value = options_end[i].value;
                    var genetic_id_end = $('#end_plant_UID_exist_lot_ID').find("option[value='" + value + "']").data("genetic");
                    if (parseInt(value) >= parseInt(selectedPlantUID)) {
                        if (genetic_id_start == genetic_id_end) {
                            option_array_end.push(options_end[i]);
                        }
                    }
                }
                $('select#end_plant_UID_exist_lot_ID').html(option_array_end);
                $('select#end_plant_UID_exist_lot_ID').select2();

                var options_lot = initialLot_exist;
                var cnt = options_lot.length;
                option_array_lot.push(options_lot[0]);
                for (var i = 0; i < cnt; i++) {
                    var value = options_lot[i].value;
                    var genetic_id_lot = $('#selectedLotID').find("option[value='" + value + "']").data("genetic");
                    if (genetic_id_start == genetic_id_lot) {
                        option_array_lot.push(options_lot[i]);
                    }
                }
                $('select#selectedLotID').html(option_array_lot);
                $('select#selectedLotID').select2();

            } else {
                $('select#end_plant_UID_exist_lot_ID').html(initialEndPlantList_exist[0]);
                $('select#end_plant_UID_exist_lot_ID').select2();

                $('select#selectedLotID').html(initialLot_exist[0]);
                $('select#selectedLotID').select2();
            }
        })


        //click add clone plant
        $("#btn_addClonePlant").click(function() {

            var act = "add"
            //get Current Date
            var currentDate = _getCurrentDate();

            $("#planting_date").val(currentDate);
            $("#act").val(act);

            //hidden QR code input section when create clone plants
            $("#qr_code_section").prop("hidden", true);
            $("#plant_UID_section").prop("hidden", true);
            $("#edit_section").prop("hidden", true);

            //show Modal
            $("#modal-add-Clone-Plants").modal('show');
        });

        //Edit Clone plant
        //reference url https://jsfiddle.net/1s9u629w/1/
        $(document).on('click', '#btn_editClonePlant', function() {

            var act = "edit";
            $("#act").val(act);
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name = $row.find('.td_name').text();
            var location = $row.find('.td_location_id').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var genetic_id = $row.find('.td_genetic_id').text();
            var genetic_name = $row.find('.td_genetic').text();
            var plant_UID = $row.find('.td_plant_UID').text();
            var plant_UID_text = $row.find('.td_plant_UID_text').text();
            var observation = $row.find('.td_observation').text();
            var mother_exist_on_mother_room = $row.find('.td_mother_id_exist').text();

            $("#id").val(id);
            $("#qr_code").val(qr_code);
            $("#name").val(name);
            $("#planting_date").val(planting_date);
            $("#plant_UID").val(plant_UID);
            $("#plant_UID_text").val(plant_UID_text);
            $("#observation").val(observation);
            $("#genetic_name").val(genetic_name);
            $("#genetic").val(genetic_id);
            $('#location').val(location);
            $('#location').select2().trigger('change');
            if (mother_exist_on_mother_room == 0) {} else {
                $('#select_mother_id_edit').val(mother_id);
                $('#select_mother_id_edit').select2().trigger('change');
            }
            $("#quantity_section").prop("hidden", true);
            $("#add_section").prop("hidden", true);
            $("#quantity").prop('disabled', true);
        });

        //modal clear when close
        $('#modal-add-Clone-Plants').on('hidden.bs.modal', function() {
            $("#qr_code").val("");
            $("#name").val("");
            $("#location").val("");
            $("#planting_date").val("");
            $("#seed").val("");
            $("#observation").val("");
            $("#plant_UID").val("");
            $("#plant_UID_text").val("");
            $('#select_mother_id_add').val("");
            $('#select_mother_id_add').select2().trigger('change');
            $('#select_mother_id_edit').val("");
            $('#select_mother_id_edit').select2().trigger('change');
            $("#quantity").val("");

            //Show QR code input section when create clone plants
            $("#qr_code_section").prop("hidden", false);
            $("#plant_UID_section").prop("hidden", false);
            $("#quantity_section").prop("hidden", false);
            $("#add_section").prop("hidden", false);
            $("#edit_section").prop("hidden", false);
            $("#quantity").prop('disabled', false);
        })

        //modal clear when close
        $('#modal-transfer-new-lotID').on('hidden.bs.modal', function() {
            $('#packing_genetic_id').val("");
            $('#packing_genetic_id').select2().trigger('change');
            $('#start_packing_plant_UID_new_lot_ID').val("");
            $('#start_packing_plant_UID_new_lot_ID').select2().trigger('change');
            $('#end_packing_plant_UID_new_lot_ID').val("");
            $('#end_packing_plant_UID_new_lot_ID').select2().trigger('change');
            $('#veg_room_id').val("");
            $('#veg_room_id').select2().trigger('change');

        })

        //        $('#selectedLotID').change(function(){
        //            alert('aaaa');
        //            var seletedLotID = $(this).val();
        //            if(seletedLotID != "0"){
        //                $('#lot_ID_text').remove();
        //                $('#veg_room_id').remove();
        //            }
        //        });



        $('#select_mother_id_add').change(function() {
            var selectedMotherID = $(this).val();

            if (selectedMotherID) {
                $.ajax({
                    method: 'POST',
                    url: '../Logic/saveClonePlants.php',
                    data: {
                        act: 'selectMother',
                        selectedMotherID: selectedMotherID
                    },
                    success: function(data) {

                        var obj = JSON.parse(data);

                        console.log(obj);

                        $('#genetic').val(obj.id);
                        $('#genetic_name').val(obj.genetic_name);
                        $('#name').val(obj.plant_name);

                    }
                })
            } else {
                $('#genetic').val('');
                $('#genetic_name').val('');
                $('#name').val('');
            }


        });

        $('#select_mother_id_edit').change(function() {
            var selectedMotherID = $(this).val();

            if (selectedMotherID) {
                $.ajax({
                    method: 'POST',
                    url: '../Logic/saveClonePlants.php',
                    data: {
                        act: 'selectMother',
                        selectedMotherID: selectedMotherID
                    },
                    success: function(data) {

                        var obj = JSON.parse(data);

                        console.log(obj);

                        $('#genetic').val(obj.id);
                        $('#genetic_name').val(obj.genetic_name);
                        $('#name').val(obj.plant_name);

                    }
                })
            } else {
                $('#genetic').val('');
                $('#genetic_name').val('');
                $('#name').val('');
            }


        });


        $("#btn_saveClonePlant").click(function() {

            ////////First, Send Post request to QR Code Generator Engine
            var name = $("#name").val();
            var plantUID = $("#plant_UID").val();
            var motherID = $("#seed").val();
            var planting_date = $("#planting_date").val();
            var location = $("#location").val();

            var observation = $("#observation").val();


            // Send post request to register in Database

            var qr_code = $('#qr_code').val();
            var plant_UID = $('#plant_UID').val();
            var act = $('#act').val();

            if (act == 'add') {
                //                    if (qr_code){
                //                        event.preventDefault();
                //
                //                        alert('aa')
                //                        $.ajax({
                //                            method:'POST',
                //                            url: '../Logic/saveClonePlants.php',
                //                            data:{act:'validate', qr_code:qr_code},
                //                            success:function (data) {
                //
                //                                var obj = JSON.parse(data);
                //
                //                                if(obj == 'SameQRCode'){
                //                                    alert('Exist Same QRCode');
                //
                //                                }else {

                $('#editClonePlantFormValidate').submit();

                //                                }
                //                            }
                //                        })
                //                    }
            }

            if (act == 'edit') {
                $('#editClonePlantFormValidate').submit();
            }

            return false;
        })


        //Change Event of select box (Clone Room)
        $('#selectedCloneRoomID').on('change', function() {
            var cloneRoomID = this.value;
            if (cloneRoomID == 0) {
                $.redirect('../Views/plantsClone.php');
            } else {
                $.redirect('../Views/plantsClone.php', {
                        room: cloneRoomID
                    },
                    'GET');
            }
        });
        //Click hostory button for a Clone plant
        $(document).on('click', '#btn_historyClonePlant', function() {

            event.preventDefault();

            var $row = $(this).closest('tr');

            var rowID = $row.attr('class').split('_')[1];

            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name = $row.find('.td_name').text();
            var location = $row.find('.td_location').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var genetic = $row.find('.td_genetic').text();
            var plant_UID = $row.find('.td_plant_UID').text();

            $.redirect('../Views/history.php', {
                    id: plant_UID,
                    type: 'plant'
                },
                'POST');
        })

    });


    //Date range picker
    $('#reservationdate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
    //Date range picker
    $('#reservationdate_1').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>