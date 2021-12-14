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

$rTrimming = new TrimmingRoom();
$mTrimmingRoomList = $rTrimming->getAllOfInfo();
$mTrimmingRoomList = $mTrimmingRoomList->results();

$pTrimming = new TrimmingPlant();

if($_GET['room']){
    $trimmingRoomID = $_GET['room'];
    $plantsIDList = $pTrimming->getPlantsListFromTrimmingRoomID($trimmingRoomID);
}else{
    $plantsIDList = $pTrimming->getValueOfAnyTable('index_trimming','1','=','1','plant_id');
    $plantsIDList = $plantsIDList->results();
}

$count = $pTrimming->count();

// For search "p" is id of plant, not plant UID
if($_GET['p']){
    $searchPlantID = $_GET['p'];
    foreach($plantsIDList as $plant){
        $tmp_plantsIDList = array();
        if($plant->plant_id == $searchPlantID){
            array_push($tmp_plantsIDList,$plant);
            break;
        }
    }
    $plantsIDList = $tmp_plantsIDList;
}

$mLotList = $rTrimming->getValueOfAnyTable('lot_id','1','=','1');
$mLotList = $mLotList->results();

if($_GET['lot']){
    $lot_ID = $_GET['lot'];
}else{
    $lot_ID = '0';
}

$k = 0;
?>
<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="trimming" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="trimming_plants_tab" data-toggle="pill" href="#trimming_plants_content" role="tab" aria-controls="trimming_plants_tab" aria-selected="true">Trimming plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="trimming_rooms_tab" href="roomsTrimming.php" >Trimming Rooms</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="trimming_plants_content" role="tabpanel" aria-labelledby="trimming_plants_content">
                    <!-- Trimming Plants Section-->
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input name="currentRoomID" id="currentRoomID" type="hidden" value="<?=$trimmingRoomID?>">
                                        <select class="form-control select2bs4" id="selectedTrimmingRoomID" style="width: 100%;">
                                            <option value="0">All Trimming Room</option>
                                            <?php
                                            if($mTrimmingRoomList) {
                                                $k = 0;
                                                foreach ($mTrimmingRoomList as $mTrimmingRoom) {
                                                    ?>
                                                    <option
                                                        <?php
                                                        if ($trimmingRoomID == $mTrimmingRoom->id){
                                                            echo 'selected';
                                                        }
                                                        ?>
                                                        value="<?=$mTrimmingRoom->id?>"  ><?=$mTrimmingRoom->name?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input name="currentLotID" id="currentLotID" type="hidden" value="<?=$lot_ID?>">
                                        <select class="form-control select2bs4" id="selectedLotID" style="width: 100%;">
                                            <!--SelectBox Lot ID-->
                                            <option value="0">All Lots </option>
                                            <?php
                                            if($mLotList) {
                                                $k = 0;
                                                foreach ($mLotList as $mLot) {
                                                    $exist = $pTrimming->getValueOfAnyTable('index_trimming','lot_id','=',$mLot->lot_ID);
                                                    $exist = $exist->results();
                                                    if($exist){
                                                        ?>
                                                        <option
                                                            <?php
                                                            if ($lot_ID == $mLot->lot_ID){
                                                                echo 'selected';
                                                            }
                                                            ?>
                                                            value="<?=$mLot->lot_ID?>"><?php
                                                            $lot_ID_text = $pTrimming->getTextOflotID($mLot->lot_ID);
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
                                <div class="col-sm-2">
                                    Lot Date : <?php
                                    $lot_ID_Info = $pTrimming->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
                                    $lot_ID_Info = $lot_ID_Info->results();
                                    $lot_date = $lot_ID_Info[0]->date;
                                    echo($lot_date);
                                    ?>
                                </div>
                                <div class="col-sm-6" style="text-align: right">
                                    <a class="btn bg-gradient-primary btn-md" href="#" id="btn_transferToVault">
                                        <i class="fas fa-paper-plane"></i>
                                        Transfer to Packing Room
                                    </a>
                                    <a class="btn bg-gradient-yellow btn-md" id="btn_multiPrint" data-target="#modal-multi-print-label" href="#modal-multi-print-label" data-toggle="modal">
                                        <i class="fas fa-print"></i>
                                        Multi Print
                                    </a>
                                    <a class="btn bg-gradient-danger btn-md"  data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Trimming plant
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
                                        <input class="plantCheckBoxTotal" type="checkbox" name="select_all">
                                    </th>
                                    <th style="display:none">id</th>
                                    <th style="display:none">QR code</th>
                                    <th style="display:none">Lot ID really</th>
                                    <th style="display:none">Lot ID</th>
                                    <th style="display:none">Plant ID really</th>
                                    <th>Plant ID</th>
                                    <th>Name</th>
                                    <th style="display:none" >Genetic ID</th>
                                    <th>Genetic</th>
                                    <th style="display:none" >Lot ID really</th>
                                    <th>Lot ID</th>
                                    <th style="display:none" >mother id</th>
                                    <th>Mother ID</th>
                                    <th style="display:none" >Dry Method id</th>
                                    <th>Dry Method</th>
                                    <th style="display:none" >Location ID</th>
                                    <th>Location</th>
                                    <th>Born Date</th>
                                    <th>Days</th>
                                    <th style="display:none">Observations</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="plant_table">
                                <?php
                                foreach($plantsIDList as $plantID){
                                    $plantInfo = $pTrimming->getValueOfAnyTable('plants','id','=',$plantID->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    //Calculate Today - room Date() = Reporting days
                                    $m_room_date=date_create($plantInfo[0]->room_date);
                                    $m_today=date_create(date("m/d/Y"));
                                    $diff=date_diff($m_room_date,$m_today);
                                    $days = $diff->format("%a");
                                    //End//
                                    if($plantInfo[0]->lot_ID == $lot_ID || $lot_ID == '0'){
                                        $k++;
                                        ?>
                                        <tr>
                                            <td>
                                                <input class="plantCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$plantInfo[0]->id?>">
                                            </td>
                                            <td style="display:none" class="td_id" ><?=$plantInfo[0]->id?></td>
                                            <td style="display:none" class="td_qr_code"  ><?=$plantInfo[0]->qr_code?></td>
                                            <td style="display:none" class="td_lot_"><?=$plantInfo[0]->lot_ID?></td>
                                            <td style="display:none"><?php
                                                $lot_ID_text = $pTrimming->getTextOflotID($plantInfo[0]->lot_ID);
                                                echo $lot_ID_text;
                                                ?></td>
                                            <td style="display:none" class="td_plant_UID"><?=$plantInfo[0]->plant_UID?></td>
                                            <td class="td_plant_UID_text"><?php
                                                $plant_UID_text = $pTrimming->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                                echo $plant_UID_text;
                                                ?></td>
                                            <td class="td_name"><?=$plantInfo[0]->name?></td>
                                            <td style="display:none" class="td_genetic_id" ><?=$plantInfo[0]->genetic?></td>
                                            <td class="td_genetic"><?php
                                                $geneticInfo = $pTrimming->getValueOfAnyTable('genetic','id','=',$plantInfo[0]->genetic);
                                                $geneticInfo = $geneticInfo->results();
                                                echo $geneticInfo[0]->genetic_name;
                                                ?>
                                            </td>
                                            <td style="display:none"  class="td_lot_ID"><?=$plantInfo[0]->lot_ID?></td>
                                            <td class="td_lot_ID_text"><?php
                                                $lot_ID_text = $pTrimming->getTextOflotID($plantInfo[0]->lot_ID);
                                                echo $lot_ID_text;
                                                ?></td>
                                            <td style="display:none" class="td_mother_id"><?=$plantInfo[0]->mother_id?></td>
                                            <td class="td_mother_id_text"><?=$plantInfo[0]->mother_text?></td>
                                            <td style="display:none" class="td_dry_method"><?=$plantInfo[0]->dry_method?></td>
                                            <td class="td_dry_method_name"><?php
                                                $dryMethodInfo = $pTrimming->getValueOfAnyTable('dry_method','id','=',$plantInfo[0]->dry_method);
                                                $dryMethodInfo = $dryMethodInfo->results();
                                                echo $dryMethodInfo[0]->name;
                                                ?></td>

                                            <td style="display:none" class="td_location_id"><?=$plantInfo[0]->location?></td>
                                            <td class="td_location"><?php
                                                $roomInfo = $pTrimming->getValueOfAnyTable('room_trimming','id','=',$plantInfo[0]->location);
                                                $roomInfo = $roomInfo->results();
                                                echo $roomInfo[0]->name;
                                                ?>
                                            </td>
                                            <td class="td_planting_date"><?=$plantInfo[0]->planting_date?></td>
                                            <td class="td_days"><?=$days?></td>
                                            <td style="display:none" class="td_observation"><?=$plantInfo[0]->observation?></td>
                                            <td style="text-align: center">
                                                <a class="btn btn-sm bg-gradient-blue " href="#" id="btn_historyTrimmingPlant">
                                                    <i class="fas fa-history"></i>
                                                    History
                                                </a>
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editTrimmingPlant" data-target="#modal-add-Trimming-Plants" href="#modal-add-Trimming-Plants" data-toggle="modal">
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
                                }
                                ?>
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

<!-- Modal Edit Trimming Plants in Room ( Edit)-->
<div class="modal fade" id="modal-add-Trimming-Plants">
    <div class="modal-dialog width-modal-dialog-plants-Trimming">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Trimming Box</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveTrimmingPlants.php" enctype='multipart/form-data' id="editTrimmingPlantFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="">
                        <input name="showRoom" id="showRoom" type="hidden" value="<?=$_GET['room']?>">

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

                                <label>Mother ID</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Mother ID" id="mother_text" name="mother_text" readonly >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Genetic</label>
                                <div class="form-group input-group mb-3">
                                    <input type="hidden"  id="genetic" name="genetic" >
                                    <input type="text" class="form-control" placeholder="Genetic" id="genetic_name" name="genetic_name" readonly >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" readonly >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <div id="quantity_section">
                                    <label>Quantity</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Quantity" id="quantity" name="quantity"  >
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
                                        $pTrimmingRoom = new TrimmingRoom();
                                        $mTrimmingRoomList = $pTrimmingRoom->getAllOfInfo();
                                        $mTrimmingRoomList = $mTrimmingRoomList->results();
                                        foreach($mTrimmingRoomList as $mTrimmingRoom){
                                            ?>
                                            <option value="<?=$mTrimmingRoom->id?>"><?=$mTrimmingRoom->name?></option>
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
                                        <input type="hidden" class="form-control" id="plant_UID" name="plant_UID" >
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label>Lot ID</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Lot ID" id="lot_ID_text" name="lot_ID_text" autocomplete="off" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Born Date</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="planting_date" name="planting_date"/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Dry method</label>
                                    <select class="form-control select2bs4" name="dry_method" id="dry_method" style="width: 100%;">
                                        <option value="" >Select Dry Method</option>
                                        <?php
                                        $dryMethodtList = $pTrimming->getValueOfAnyTable('dry_method','1','=','1');
                                        $dryMethodtList = $dryMethodtList->results();
                                        foreach($dryMethodtList as $dryMethod){
                                            ?>
                                            <option value="<?=$dryMethod->id?>" ><?=$dryMethod->name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <label>Observations</label>
                                <div class="form-group input-group mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Enter ..." id="observation" name="observation" style="height: 130px"></textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="btn_saveTrimmingPlant" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal Transfer lot ID from Trimming to Packing  -->
<div class="modal fade" id="modal-transfer-Trimming-Plants">
    <div class="modal-dialog width-modal-dialog-transfer-plants">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Transfer to Packing Room</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveTrimmingPlants.php" enctype='multipart/form-data' id="trimmingTransferFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="act" id="act" type="hidden" value="transfer">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Lot ID</label>
                                    <select class="form-control select2bs4" name="lot_ID" id="lot_ID" style="width: 100%;">
                                        <option value="" >Select Lot ID</option>
                                        <?php
                                        if($mLotList) {
                                            $k = 0;
                                            foreach ($mLotList as $mLot) {
                                                $exist = $pTrimming->getValueOfAnyTable('index_trimming','lot_id','=',$mLot->lot_ID);
                                                $exist = $exist->results();
                                                if($exist) {
                                                    ?>
                                                    <option
                                                        <?php
                                                        if ($lot_ID == $mLot->lot_ID) {
                                                            echo 'selected';
                                                        }
                                                        ?>
                                                        value="<?= $mLot->lot_ID ?>"><?php
                                                        $lot_ID_text = $pTrimming->getTextOflotID($mLot->lot_ID);
                                                        echo $lot_ID_text;
                                                        ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Flower & Seeds</label>
                                    <select class="form-control select2bs4" name="type" id="type" style="width: 100%;">
                                        <option value="flower" >Flower</option>
                                        <option value="seeds" >Seeds</option>
                                    </select>
                                </div>

                                <div id="flower_section">
                                    <label>Grams amount</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Grams amount" id="grams_amount" name="grams_amount">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="seeds_section">
                                    <label>Seeds amount</label>
                                    <div class="form-group input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Seeds amount" id="seeds_amount" name="seeds_amount">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <label>HTC %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="HTC %" id="htc" name="htc">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Trimming method</label>
                                    <select class="form-control select2bs4" name="trimming_method" id="trimming_method" style="width: 100%;">
                                        <option value="" >Select Trimming Method</option>
                                        <?php
                                        $trimmingMethodtList = $pTrimming->getValueOfAnyTable('trimming_method','1','=','1');
                                        $trimmingMethodtList = $trimmingMethodtList->results();
                                        foreach($trimmingMethodtList as $trimmingMethod){
                                            ?>
                                            <option value="<?=$trimmingMethod->id?>" ><?=$trimmingMethod->name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <label>Vault Date</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group date" id="reservationdate_1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate_1" id="vault_date" name="vault_date"/>
                                        <div class="input-group-append" data-target="#reservationdate_1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <label>CBD %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="CBD" id="cbd" name="cbd">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <label>Other %</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Other %" id="other" name="other">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Vault Room</label>
                                    <select class="form-control select2bs4" name="vault_room_id" id="vault_room_id" style="width: 100%;">
                                        <option value="" >Select Vault Room</option>
                                        <?php
                                        $roomVaultList = $pTrimming->getValueOfAnyTable('room_vault','1','=','1');
                                        $roomVaultList = $roomVaultList->results();
                                        foreach($roomVaultList as $roomVault){
                                            ?>
                                            <option value="<?=$roomVault->id?>" ><?=$roomVault->name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <label>Note</label>
                            <div class="form-group input-group mb-3">
                                <textarea class="form-control" rows="3" placeholder="Enter ..." id="note" name="note" style="height: 204px"></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="btn_transferAction" class="btn btn-primary" value="Transfer to vault and print lot id"></input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete plant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Really Delete plant&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-outline-light" id="btn_deleteTrimmingPlants">Yes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Modal Print Label-->
<div class="modal fade" id="modal-print-label">
    <div class="modal-dialog width-modal-print-label">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Print Lot Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--body-->
            <div class="modal-body label_body" id="label_body" >
                <form method="post" action="#" id="printJS-form" >
                    <div class="printBorder">
                        <div class="print_body">
                            <div class="print_qr"  id="print_qr" >
                                <img src="../QR_Code/default.png">
                            </div>
                            <div class="print_info">
                                <div class="title_font">
                                    <div class="print_name" id="print_name"></div>
                                </div>
                                <div class="plant_id_font" id="print_id">plant id</div>
                                <div class="print_mother_id" id="print_mother_id">Mother ID</div>
                                <div class="print_date" id="print_date">born date</div>
                                <div class="print_qr_code" id="print_qr_code"><?=$qr_code?></div>
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
                <h4 class="modal-title">Multi Print Label</h4>
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
                            <label>Start Plant UID</label>
                            <select class="form-control select2bs4" name="start_plant_UID_print" id="start_plant_UID_print" style="width: 100%;">
                                <option value="" >Select Plant ID</option>
                                <?php
                                $trimmingPlantsIDList = $pTrimming->getValueOfAnyTable('index_trimming','1','=','1','plant_id');
                                $trimmingPlantsIDList = $trimmingPlantsIDList->results();
                                foreach($trimmingPlantsIDList as $trimmingPlant){
                                    $plantInfo = $pTrimming->getValueOfAnyTable('plants','id','=',$trimmingPlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $pTrimming->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                    ?>
                                    <option value="<?=$plantInfo[0]->plant_UID?>" ><?=$plant_UID_text?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-1" style="padding-top: 40px;text-align: center;"> ~ </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>End Plant UID</label>
                            <select class="form-control select2bs4" name="end_plant_UID_print" id="end_plant_UID_print" style="width: 100%;">
                                <option value="" >Select Plant ID</option>
                                <?php
                                $trimmingPlantsIDList = $pTrimming->getValueOfAnyTable('index_trimming','1','=','1','plant_id');
                                $trimmingPlantsIDList = $trimmingPlantsIDList->results();
                                foreach($trimmingPlantsIDList as $trimmingPlant){
                                    $plantInfo = $pTrimming->getValueOfAnyTable('plants','id','=',$trimmingPlant->plant_id);
                                    $plantInfo = $plantInfo->results();
                                    $plant_UID_text = $pTrimming->getTextOfPlantUID($plantInfo[0]->plant_UID);
                                    ?>
                                    <option value="<?=$plantInfo[0]->plant_UID?>" ><?=$plant_UID_text?></option>
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
                <a href="#" id ="btn_gotoMultiPrintLabelPage" class="btn btn-primary" >Print Label</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<script>

    //Select All , for that let's see https://jsfiddle.net/gyrocode/abhbs4x8/
    function updateDataTableSelectAllCtrl(table){
        var $chkbox_all        = $('#plant_table input[type="checkbox"]');
        var $chkbox_checked    = $('#plant_table input[type="checkbox"]:checked');
        var chkbox_select_all  = $('thead input[name="select_all"]');

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

        $('#btn_transferAction').click(function(){

            //Print Lable
//                var qr_code =  'aa';
//                var lot_ID_text = 'ddd';
//                var mother_ID_text = 'ddd';
//                var name = 'ddd';
//                var packing_date = 'ddd';
//
//                var data = "Name: " + name + ", Plant ID: " + lot_ID_text + ", Mother ID: "+ mother_ID_text + ", Date: " + packing_date + ", Code: " + qr_code;
//                var filename = "Lot_" + lot_ID_text;
////                    alert(data);
//
//                $.ajax({
//                    method:'POST',
//                    url: '../Utilities/phpqrcode/index.php',
//                    data: {data:data,filename:filename},
//                    success:function(data){
//                        var obj = JSON.parse(data);
//                        console.log(obj);
////                            alert(obj);
//                    }
//                })
//
//                //show modal printlabel
//                document.getElementById("print_qr").innerHTML = '<img src="../QR_Code/'+ filename +'.png">';
//                document.getElementById("print_name").innerHTML = name ;
//                document.getElementById("print_id").innerHTML = lot_ID_text ;
//                document.getElementById("print_mother_id").innerHTML = mother_ID_text ;
//                document.getElementById("print_date").innerHTML = packing_date ;
//                document.getElementById("print_qr_code").innerHTML = qr_code ;
//
//                $("#modal-print-label").modal('show');

            event.preventDefault();
            $("#trimmingTransferFormValidate"). submit();
        });

        $(document).on('click', '#btn_printQRCode', function () {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name =  $row.find('.td_name').text();
            var location = $row.find('.td_location_id').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var mother_id_text = $row.find('.td_mother_id_text').text();
            var genetic = $row.find('.td_genetic_id').text();
            var plant_UID = $row.find('.td_plant_UID').text();
            var plant_UID_text = $row.find('.td_plant_UID_text').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();
            //var data = "Name: " + name + ", Plant ID: " + plant_UID_text + ", Mother ID: "+ mother_id_text + ", Date: " + planting_date + ", Code: " + qr_code;
            var data = qr_code;
            var filename = plant_UID_text;

            /*
             Possible Post Datas : data, level, size But now i sent only data
             level: 'L','M','Q','H'    default is 'L'
             size: 1 - 10              default is  4
             */
            $.ajax({
                method:'POST',
                url: '../Utilities/phpqrcode/index.php',
                data: {data:data,filename:filename},
                success:function(data){
                    var obj = JSON.parse(data);
                    console.log(obj);
                }
            })
            setTimeout(
                function()
                {
                    //show modal printlabel
                    document.getElementById("print_qr").innerHTML = '<img src="../QR_Code/'+ filename +'.png" width="124px">';
                    document.getElementById("print_name").innerHTML = name ;
                    document.getElementById("print_id").innerHTML = "<?php echo $_SESSION['label']; ?>" + '-' + plant_UID_text ;
                    document.getElementById("print_mother_id").innerHTML = mother_id_text ;
                    document.getElementById("print_date").innerHTML = planting_date ;
                    //document.getElementById("print_qr_code").innerHTML = qr_code ;
                    document.getElementById("print_qr_code").innerHTML = lot_ID_text ;
                    $("#modal-print-label").modal('show');
                }, 300);
        })


        $('#start_plant_UID_print').change(function(){
            var selectedPlantUID = $(this).val();
            $(this).data('options', $('#start_plant_UID_print option').clone());
            options = $(this).data('options');
            var cnt= options.length;
            var option_array = [];
            if(selectedPlantUID){
                for(var i=0;i<cnt;i++){
                    var value = options[i].value;
                    if(parseInt(value) >= parseInt(selectedPlantUID)){
                        option_array.push(options[i]);
                    }
                }
                $('#end_plant_UID_print').html(option_array);
                $('#end_plant_UID_print').select2();
            }else{
                for(var i=0;i<cnt;i++){
                    var value = options[i].value;
                    option_array.push(options[i]);
                }
                $('#end_plant_UID_print').html(option_array);
                $('#end_plant_UID_print').select2();
            }
        })

        //Go to Multi Print Label Page
        $('#btn_gotoMultiPrintLabelPage').click(function(){
            var start_ID = $('#start_plant_UID_print').val();
            var end_ID = $('#end_plant_UID_print').val();
            var plant_type = "plant";
            var location = "index_trimming";
            $.ajax({
                method:'POST',
                url: '../Logic/saveTrimmingPlants.php',
                data: {act: "multi_print",start_ID:start_ID, end_ID: end_ID},
                success:function(data){
                    var data = JSON.parse(data);
                    $.ajax({
                        method:'POST',
                        url: '../Utilities/phpqrcode/index.php',
                        data: {act: "multi",data:data},
                        success:function(data){
                            var obj = JSON.parse(data);
                            console.log(obj);
                        }
                    })
                }
            })
            $.redirect('../Views/printLabel.php',{
                start_ID: start_ID,
                end_ID: end_ID,
                plant_type:plant_type,
                location: location
            }, 'POST','_blank');
        })

        // For Select All
        var table = $('#example1').dataTable({
            'order': [1, 'asc'],
            "sPaginationType": "full_numbers",

            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0] }
            ],
            stateSave: true,
        });

        table.on('draw.dt', function(){

            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('#example1 tbody').on('click', 'input[type="checkbox"]', function(e){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('input[name="select_all"]').on('click', function(e) {
            if(this.checked){
                $('#example1 tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example1 tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });
        //END

        //Click transfer plant button
        $('#btn_transferToVault').click(function () {
            //get Current Date
            var currentDate = _getCurrentDate();
            $("#vault_date").val(currentDate);
            $('#seeds_section').hide();
            //show Modal
            $("#modal-transfer-Trimming-Plants").modal('show');
        });

        //Edit Trimming plant
        //reference url https://jsfiddle.net/1s9u629w/1/
        $(document).on('click', '#btn_editTrimmingPlant', function () {
            var act = "edit";
            $("#act").val(act);
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name =  $row.find('.td_name').text();
            var location = $row.find('.td_location_id').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var mother_id_text = $row.find('.td_mother_id_text').text();
            var genetic_id = $row.find('.td_genetic_id').text();
            var genetic_name = $row.find('.td_genetic').text();
            var plant_UID = $row.find('.td_plant_UID').text();
            var plant_UID_text = $row.find('.td_plant_UID_text').text();
            var observation = $row.find('.td_observation').text();
            var dry_method = $row.find('.td_dry_method').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();

            $("#id").val(id);
            $("#qr_code").val(qr_code);
            $("#name").val(name);
            $("#planting_date").val(planting_date);
            $("#plant_UID").val(plant_UID);
            $("#plant_UID_text").val(plant_UID_text);
            $("#observation").val(observation);
            $("#genetic_name").val(genetic_name);
            $("#genetic").val(genetic_id);
            $("#mother_text").val(mother_id_text);
            $("#dry_method").val(dry_method);
            $('#dry_method').select2().trigger('change');
            $('#location').val(location);
            $('#location').select2().trigger('change');
            $('#select_mother_id').val(mother_id);
            $('#select_mother_id').select2().trigger('change');
            $("#quantity_section").prop("hidden",true);
            $("#quantity").prop('disabled', true);
            $("#lot_ID_text").val(lot_ID_text);

        });

        //modal clear when close
        $('#modal-add-Trimming-Plants').on('hidden.bs.modal', function () {
            $("#qr_code").val("");
            $("#name").val("");
            $("#location").val("");
            $("#planting_date").val("");
            $("#seed").val("");
            $("#observation").val("");
            $("#plant_UID").val("");
            $("#plant_UID_text").val("");
            $("#quantity").val("");

            //Show QR code input section when create Trimming plants
            $("#qr_code_section").prop("hidden",false);
            $("#plant_UID_section").prop("hidden",false);
            $("#quantity_section").prop("hidden",false);
            $("#quantity").prop('disabled', false);
        })

        //modal clear when close
        $('#modal-transfer-Trimming-Plants').on('hidden.bs.modal', function () {
            $('#lot_ID').val("");
            $('#lot_ID').select2().trigger('change');
            $('#vault_room_id').val("");
            $('#vault_room_id').select2().trigger('change');
            $("#grams_amount").val("");
            $("#htc").val("");
            $("#trimming_method").val("");
            $("#vault_date").val("");
            $("#cbd").val("");
            $("#other").val("");
            $("#note").val("");
        })

        $('#type').change(function(){
            var type = $(this).val();
            if(type == "flower"){
                $('#seeds_section').hide();
                $('#flower_section').show();
                $('#seeds_amount').val("");
                $('#grams_amount').val("");

            }
            if(type == "seeds"){
                $('#flower_section').hide();
                $('#seeds_section').show();
                $('#seeds_amount').val("");
                $('#grams_amount').val("");
            }
        })

        $('#select_mother_id').change(function(){
            var selectedMotherID = $(this).val();

            if(selectedMotherID){
                $.ajax({
                    method:'POST',
                    url: '../Logic/saveTrimmingPlants.php',
                    data:{act:'selectMother', selectedMotherID:selectedMotherID},
                    success:function (data) {
                        var obj = JSON.parse(data);
                        $('#genetic').val(obj.id);
                        $('#genetic_name').val(obj.genetic_name);
                        $('#name').val(obj.plant_name);
                    }
                })
            }else {
                $('#genetic').val('');
                $('#genetic_name').val('');
                $('#name').val('');
            }
        });

        $("#btn_saveTrimmingPlant").click(function(){

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

            if (act == 'add'){
                if (qr_code){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveTrimmingPlants.php',
                        data:{act:'validate', qr_code:qr_code},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameQRCode'){
                                alert('Exist Same QRCode');
                            }else {
                                $('#ediTrimmingPlantFormValidate').submit();
                            }
                        }
                    })
                }
            }

            if (act == 'edit'){
                $('#editTrimmingPlantFormValidate').submit();
            }

            return false;
        })

        //Change Event of select box (Trimming Room)
        $('#selectedTrimmingRoomID').on('change', function() {
            var trimmingRoomID = this.value;
            var selectedLotID = $('#selectedLotID').val();

            $.redirect('../Views/plantsTrimming.php',
                {
                    room: trimmingRoomID,
                    lot: selectedLotID
                },
                'GET');
        });

        $('#selectedLotID').on('change', function() {
            var trimmingRoomID = $('#selectedTrimmingRoomID').val();
            var selectedLotID = this.value;

            $.redirect('../Views/plantsTrimming.php',
                {
                    room: trimmingRoomID,
                    lot: selectedLotID
                },
                'GET');
        });

        var checkedPlantList = [];
        var allPages = table.fnGetNodes();

        //Click Delete Selected Plants Button
        $('#btn_deleteTrimmingPlants').click(function(){
            checkedPlantList = [];
            $.each($("input[class='plantCheckBox']:checked", allPages), function(){
                console.log($(this).val());

                //push selected Trimming plants ID
                checkedPlantList.push($(this).val());

            });

            var trimmingRoomID = $('#currentRoomID').val();

            $.ajax({
                method:'POST',
                url: '../Logic/saveTrimmingPlants.php',
                data:{act:'delete', idList:checkedPlantList},
                success:function (data) {
                    $.redirect('../Views/plantsTrimming.php',
                        {
                            room: trimmingRoomID
                        },
                        'GET');
                }
            })
            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        })

        //Click hostory button for a Trimming plant
        $(document).on('click', '#btn_historyTrimmingPlant', function () {
            event.preventDefault();
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var name =  $row.find('.td_name').text();
            var location = $row.find('.td_location').text();
            var planting_date = $row.find('.td_planting_date').text();
            var mother_id = $row.find('.td_mother_id').text();
            var genetic = $row.find('.td_genetic').text();
            var plant_UID = $row.find('.td_plant_UID').text();

            $.redirect('../Views/history.php',
                {
                    id: plant_UID,
                    type:'plant'
                },
                'POST');
        })
    });

    //Date range picker
    $('#reservationdate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
    $('#reservationdate_1').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
