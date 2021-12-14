<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}
//$p_general = new General();

require_once('layout/header.php');
require_once('layout/navbar.php');

$pPackingRoom = new PackingRoom();
$mPackingRoomList = $pPackingRoom->getAllOfInfo();
$mPackingRoomList = $mPackingRoomList->results();

$pPacking = new PackingPlant();

if($_GET['room']){
    $packingRoomID = $_GET['room'];
    $lotIDList = $p_general->getValueOfAnyTable('index_packing','room_id','=',$packingRoomID);
    $lotIDList = $lotIDList->results();
}else{
    $lotIDList = $p_general->getValueOfAnyTable('index_packing','1','=','1');
    $lotIDList = $lotIDList->results();
}



$count = $pPacking->count();

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

$mLotList = $p_general->getValueOfAnyTable('lot_id','1','=','1');
$mLotList = $mLotList->results();

if($_GET['lot']){
    $lot_ID = $_GET['lot'];
}else{
    $lot_ID = '0';
}

$k = 0;
?>
<div class="content-wrapper" xmlns="http://www.w3.org/1999/html">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="packing" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="packing_plants_tab" data-toggle="pill" href="#packing_plants_content" role="tab" aria-controls="packing_plants_tab" aria-selected="true">Packing plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="packing_rooms_tab" href="roomsPacking.php" >Packing Rooms</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="packing_plants_content" role="tabpanel" aria-labelledby="packing_plants_content">
                    <!-- Packing Plants Section-->
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input name="currentRoomID" id="currentRoomID" type="hidden" value="<?=$packingRoomID?>">
                                        <select class="form-control select2bs4" id="selectedPackingRoomID" style="width: 100%;">
                                            <option value="0">All Packing Room</option>
                                            <?php
                                            if($mPackingRoomList) {
                                                $k = 0;
                                                foreach ($mPackingRoomList as $mPackingRoom) {
                                                    ?>
                                                    <option
                                                        <?php
                                                        if ($packingRoomID == $mPackingRoom->id){
                                                            echo 'selected';
                                                        }
                                                        ?>
                                                        value="<?=$mPackingRoom->id?>"><?=$mPackingRoom->name?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6" style="text-align: right">
                                    <a class="btn bg-gradient-primary btn-md" href="#" id="btn_transferView">
                                        <i class="fas fa-paper-plane"></i>
                                        Transfer to Vault Room
                                    </a>
                                    <a class="btn bg-gradient-yellow btn-md" id="btn_multiPrint" data-target="#modal-multi-print-label" href="#modal-multi-print-label" data-toggle="modal">
                                        <i class="fas fa-print"></i>
                                        Multi Print
                                    </a>
                                    <a class="btn bg-gradient-danger btn-md"  data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Packing plant
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
                                    <th style="display: none">id</th>
                                    <th style="display: none">qr code</th>
                                    <th style="display: none">Lot Id really</th>
                                    <th >Lot Id</th>
                                    <th style="display: none">Genetic ID</th>
                                    <th>Genetic</th>
                                    <th>Name</th>
                                    <th>Born Date</th>
                                    <th>Harvest Date</th>
                                    <th style="display: none">Dry Method ID</th>
                                    <th>Dry Method</th>
                                    <th style="display: none">Trimming Method ID</th>
                                    <th>Trimming Method</th>
                                    <th>Days</th>
                                    <th style="display: none">location</th>
                                    <th style="display: none">note</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <!-- <tbody id="plant_table">
                                <?php
                                foreach($lotIDList as $lotID) {
                                    $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lotID->lot_id);
                                    $lotInfo = $lotInfo->results();
                                    $k++;
                                    //Calculate Today - room Date() = Reporting days
                                    $m_room_date=date_create($lotInfo[0]->room_date);
                                    $m_today=date_create(date("m/d/Y"));
                                    $diff=date_diff($m_room_date,$m_today);
                                    $days = $diff->format("%a");
                                    //End//
                                    ?>
                                    <tr>
                                        <td>
                                            <input class="plantCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$lotInfo[0]->lot_ID?>">
                                        </td>
                                        <td style="display: none" class="td_id"><?=$lotInfo[0]->id?></td>
                                        <td style="display: none" class="td_qr_code"><?=$lotInfo[0]->qr_code?></td>
                                        <td style="display: none" class="td_lot_ID"><?=$lotInfo[0]->lot_ID?></td>
                                        <td class="td_lot_ID_text"><?=$p_general->getTextOflotID($lotInfo[0]->lot_ID)?></td>
                                        <td style="display: none" class="td_genetic_id><?=$lotInfo[0]->genetic_ID?></td>
                                        <td class="td_genetic_text"><?php
                                            $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=', $lotInfo[0]->genetic_ID);
                                            $geneticInfo = $geneticInfo->results();
                                            echo $geneticInfo[0]->genetic_name;
                                            ?></td>
                                        <td class="td_genetic_name"><?=$geneticInfo[0]->plant_name?></td>
                                        <td class="td_born_date"><?=$lotInfo[0]->born_date?></td>
                                        <td class="td_harvest_date"><?=$lotInfo[0]->harvest_date?></td>
                                        <td style="display: none" class="td_dry_method"><?=$lotInfo[0]->dry_method?></td>
                                        <td class="td_dry_method_name"><?php
                                            $dryMethodInfo = $p_general->getValueOfAnyTable('dry_method','id','=',$lotInfo[0]->dry_method);
                                            $dryMethodInfo = $dryMethodInfo->results();
                                            echo $dryMethodInfo[0]->name?></td>
                                        <td style="display: none" class="td_trimming_method"><?=$lotInfo[0]->trimming_method?></td>
                                        <td class="td_trimming_method_name"><?php
                                            $trimmingMethodInfo = $p_general->getValueOfAnyTable('trimming_method','id','=',$lotInfo[0]->trimming_method);
                                            $trimmingMethodInfo = $trimmingMethodInfo->results();
                                            echo $trimmingMethodInfo[0]->name?></td>
                                        <td><?=$days?></td>
                                        <td style="display: none" class="td_location"><?=$lotInfo[0]->location?></td>
                                        <td style="display: none" class="td_note"><?=$lotInfo[0]->note?></td>

                                        <td style="text-align: center">
                                            <a class="btn btn-sm bg-gradient-blue " href="#" id="btn_history">
                                                <i class="fas fa-history"></i>
                                                History
                                            </a>
                                            <a class="btn bg-gradient-green btn-sm" id="btn_edit" data-target="#modal-edit" href="#modal-edit" data-toggle="modal">
                                                <i class="fas fa-pencil-alt"></i>
                                                Edit
                                            </a>
                                            <a class="btn bg-gradient-yellow btn-sm" id="btn_singlePrint">
                                                <i class="fas fa-print"></i>
                                                Print
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody> -->
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


<!-- Modal edit-->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog width-modal-dialog-plants-Dry">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Packing Lot ID</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="../Logic/savePackingPlants.php" enctype='multipart/form-data' id="editDryPlantFormValidate">
                <!--body-->
                <div class="modal-body">
                    <input name="act" id="act" type="hidden" value="">
                    <input name="showRoom" id="showRoom" type="hidden" value="<?=$packingRoomID?>">
                    <input name="id" id="id" type="hidden" value="">
                    <div class="row">
                        <div class="col-6">
                            <label>Lot ID</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Lot ID" id="lot_ID_text" name="lot_ID_text" autocomplete="off" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
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
                            <label>Genetic</label>
                            <div class="form-group input-group mb-3">
                                <input type="hidden"  id="genetic" name="genetic" >
                                <input type="text" class="form-control" placeholder="Genetic" id="genetic_text" name="genetic_text" readonly >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <label>Name</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Name" id="genetic_name" name="genetic_name" readonly >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <label>Dry Method</label>
                            <div class="form-group">
                                <select class="form-control select2bs4" name="dry_method" id="dry_method" style="width: 100%;">
                                    <option value="">Select dry method</option>
                                    <?php
                                    $mDryMethodList =$p_general->getValueOfAnyTable('dry_method','1','=','1');
                                    $mDryMethodList = $mDryMethodList->results();
                                    foreach($mDryMethodList as $mDryMethod){
                                        ?>
                                        <option value="<?=$mDryMethod->id?>"><?=$mDryMethod->name?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label>Trimming Method</label>
                            <div class="form-group">
                                <select class="form-control select2bs4" name="trimming_method" id="trimming_method" style="width: 100%;">
                                    <option value="">Select trimming method</option>
                                    <?php
                                    $mTrimmingMethodList =$p_general->getValueOfAnyTable('trimming_method','1','=','1');
                                    $mTrimmingMethodList = $mTrimmingMethodList->results();
                                    foreach($mTrimmingMethodList as $mTrimmingMethod){
                                        ?>
                                        <option value="<?=$mTrimmingMethod->id?>"><?=$mTrimmingMethod->name?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Born Date</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group date" id="borndate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#borndate" id="born_date" name="born_date" readonly>
                                    <div class="input-group-append" data-target="#borndate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <label>Harvest Date</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group date" id="harvestdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#harvestdate" id="harvest_date" name="harvest_date"/>
                                    <div class="input-group-append" data-target="#harvestdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <label>Location(Room)</label>
                            <div class="form-group">
                                <select class="form-control select2bs4" name="location" id="location" style="width: 100%;">
                                    <option value="">Select Room</option>
                                    <?php
                                    $mPackingRoomList = $p_general->getValueOfAnyTable('room_packing','1','=','1');
                                    $mPackingRoomList = $mPackingRoomList->results();
                                    foreach($mPackingRoomList as $mRoom){
                                        ?>
                                        <option value="<?=$mRoom->id?>"><?=$mRoom->name?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label>Note</label>
                            <div class="form-group input-group mb-3">
                                <textarea class="form-control" rows="3" placeholder="Enter ..." id="note" name="note" style="height: 204px"></textarea>
                            </div>
                        </div>
                    </div>
                    </fieldset>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="btn_save" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- Modal Transfer lot ID from Packing to Vault  -->
<div class="modal fade" id="modal-transfer">
    <div class="modal-dialog width-modal-dialog-transfer-plants">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Transfer to Vault</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/savePackingPlants.php" enctype='multipart/form-data' id="packingTransferFormValidate">
                <!--body-->
                <div class="modal-body">
                    <div class="row">
                        <input name="act" id="act" type="hidden" value="transfer">
                        <input name="packing_number" id="packing_number" type="hidden" value="">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Lot Id</label>
                                <select class="form-control select2bs4" name="transfer_lot_ID" id="transfer_lot_ID" style="width: 100%;">
                                    <option value="" >Select Lot ID</option>
                                    <?php
                                    $lotIDList = $p_general->getValueOfAnyTable('index_packing','1','=','1','lot_id');
                                    $lotIDList = $lotIDList->results();
                                    foreach($lotIDList as $lotID){
                                        $lot_ID_text = $p_general->getTextOflotID($lotID->lot_id);
                                        ?>
                                        <option value="<?=$lotID->lot_id?>" ><?=$lot_ID_text?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Plant Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Plant Name" id="plant_name" name="plant_name" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Scientific Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Scientific Name" id="scientific_name" name="scientific_name" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Producer Name</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Producer Name" id="producer_name" name="producer_name" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Place of origin</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Place of origin" id="place_origin" name="place_origin" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Cultivation date</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Cultivation date" id="cultivation_date" name="cultivation_date" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Harvest date</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Harvest date" id="harvest_date_transfer" name="harvest_date" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Packing date</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Packing date" id="packing_date" name="packing_date" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Expiration date</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Expiration date" id="expiration_date" name="expiration_date" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?=$_SESSION['lang_packing_number']?></label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="<?=$_SESSION['lang_packing_number']?>" id="packing_number_text" name="packing_number_text" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Plant Part (Flower & Seed)</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="plant_part" id="plant_part" style="width: 100%;">
                                        <option value="flower">Flower</option>
                                        <option value="seed">Seed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Amount (net weight)</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Amount (net weight)" id="amount" name="amount">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>THC content (%)</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="THC content" id="thc_content" name="thc_content">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>CBD content (%)</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="CBD content" id="cbd_content" name="cbd_content">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other (%)</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Other" id="other" name="other">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Vault Room</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="location" id="location_vault" style="width: 100%;">
                                        <option value="">Select Vault Room</option>
                                        <?php
                                        $mPackingRoomList = $p_general->getValueOfAnyTable('room_vault','1','=','1');
                                        $mPackingRoomList = $mPackingRoomList->results();
                                        foreach($mPackingRoomList as $mPackingRoom){
                                            ?>
                                            <option value="<?=$mPackingRoom->id?>"><?=$mPackingRoom->name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <div class="form-group input-group mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Enter ..." id="note" name="note" style="height: 75px"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="last_packing" id="last_packing">
                                <label>Last Packing</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id ="btn_transferAction" class="btn btn-primary" value="Transfer to vault">Transfer to vault</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->


<!--modal when delete-->
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
                <button type="button" class="btn btn-outline-light" id="btn_delete">Yes</button>
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
    <div class="modal-dialog width-modal-middle">
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
                    <div class="col-5">
                        <div class="form-group">
                            <label>Start Lot ID</label>
                            <select class="form-control select2bs4" name="start_lot_ID_print" id="start_lot_ID_print" style="width: 100%;">
                                <option value="" >Select Lot ID</option>
                                <?php
                                $LotIDList = $p_general->getValueOfAnyTable('index_packing','1','=','1','lot_id');
                                $LotIDList = $LotIDList->results();
                                foreach($LotIDList as $lot){
                                    $lotInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot->lot_id);
                                    $lotInfo = $lotInfo->results();
                                    $lot_ID_text = $p_general->getTextOflotID($lotInfo[0]->lot_ID);
                                    ?>
                                    <option value="<?=$lotInfo[0]->lot_ID?>" ><?=$lot_ID_text?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-2" style="padding-top: 40px;text-align: center;"> ~ </div>

                    <div class="col-5">
                        <div class="form-group">
                            <label>End Lot ID</label>
                            <select class="form-control select2bs4" name="end_lot_ID_print" id="end_lot_ID_print" style="width: 100%;">
                                <option value="" >Select Lot ID</option>
                                <?php
                                $LotIDList = $p_general->getValueOfAnyTable('index_packing','1','=','1','lot_id');
                                $LotIDList = $LotIDList->results();
                                foreach($LotIDList as $lot){
                                    $lotInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot->lot_id);
                                    $lotInfo = $lotInfo->results();
                                    $lot_ID_text = $p_general->getTextOflotID($lotInfo[0]->lot_ID);
                                    ?>
                                    <option value="<?=$lotInfo[0]->lot_ID?>" ><?=$lot_ID_text?></option>
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
                <a href="#" id ="btn_gotoMultiPrintLabelPage" class="btn btn-primary" >Print Label</a>
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

    $( document ).ready( function () {

        $("#btn_confirm").click(function() {
            document.getElementById("val_userpass_val").style.display = "None";
            document.getElementById("val_adminpass_val").style.display = "None";
            var user_pass = $('#val_userpass').val();
            var admin_pass = $('#val_adminpass').val();
            var admin_id = $('#supervisor_ID').val();
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
                            $('#packingTransferFormValidate').submit();
                        }
                    }
                })
        });
        //action of transfer
        $('#btn_transferAction').click(function(){
            event.preventDefault();
            //when checked it is last packing number checkbox
            if ($('#last_packing').is(":checked"))
            {
                swal.fire({
                    title: 'Are you sure?',
                    text: "It is last packing number",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, right!',
                }).then((result) => {
                    if (result.value){
                        $("#modal-validation").modal('show');
                    }
                })
            }else {
                $("#modal-validation").modal('show');
            }
        })

        //Click transfer plant button
        $('#btn_transferView').click(function () {
            //show Modal
            $("#modal-transfer").modal('show');
        });

        //when change lot id at transfer view
        $('#transfer_lot_ID').change(function(){
            var lot_id = $(this).val();
            $.ajax({
                method:'POST',
                url: '../Logic/savePackingPlants.php',
                data: {act: "getTransferInfo",lot_ID:lot_id},
                success:function(data){
                    var data = JSON.parse(data);
                    console.log(data);
                    $('#plant_name').val(data[0]);
                    $('#scientific_name').val(data[1]);
                    $('#producer_name').val('Weezgarden');
                    $('#place_origin').val('Prado(Portugal)')
                    $('#cultivation_date').val(data[2])
                    $('#harvest_date_transfer').val(data[3])
                    $('#packing_date').val(data[4])
                    $('#expiration_date').val(data[5])
                    $('#packing_number').val(data[6])
                    $('#packing_number_text').val(data[7])
                }
            })

        })

        //click print button
        $(document).on('click', '#btn_singlePrint', function () {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var qr_code = $row.find('.td_qr_code').text();
            var name =  $row.find('.td_genetic_name').text();
            var born_date = $row.find('.td_born_date').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();
            //var data = "Name: " + name + ", Plant ID: " + plant_UID_text + ", Mother ID: "+ mother_id_text + ", Date: " + planting_date + ", Code: " + qr_code;
            var data = qr_code;
            var filename = lot_ID_text;
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
                }
            })
            setTimeout(
                function()
                {
                    //show modal printlabel
                    document.getElementById("print_qr").innerHTML = '<img src="../QR_Code/'+ filename +'.png" width="124px">';
                    document.getElementById("print_name").innerHTML = name ;
                    document.getElementById("print_id").innerHTML = "<?php echo $_SESSION['label']; ?>" + '-' + lot_ID_text ;
                    document.getElementById("print_mother_id").innerHTML = '' ; //it is dont need for lot id, need only for plant.
                    document.getElementById("print_date").innerHTML = born_date ;
                    document.getElementById("print_qr_code").innerHTML = qr_code ;
                    $("#modal-print-label").modal('show');
                }, 300);
        })


        $('#start_lot_ID_print').change(function(){
            var selectedPlantUID = $(this).val();
            $(this).data('options', $('#start_lot_ID_print option').clone());
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
                $('#end_lot_ID_print').html(option_array);
                $('#end_lot_ID_print').select2();
            }else{
                for(var i=0;i<cnt;i++){
                    var value = options[i].value;
                    option_array.push(options[i]);
                }
                $('#end_lot_ID_print').html(option_array);
                $('#end_lot_ID_print').select2();
            }
        })

        //Go to Multi Print Label Page
        $('#btn_gotoMultiPrintLabelPage').click(function(){
            var start_ID = $('#start_lot_ID_print').val();
            var end_ID = $('#end_lot_ID_print').val();
            var plant_type = "lot";
            var location = "index_packing";
            $.ajax({
                method:'POST',
                url: '../Logic/savePackingPlants.php',
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

        // START
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
                "url": "../Logic/tablePackingPlants.php",
                "data": {
                    "room": HttpGetRequest('room'),
                    "lot": HttpGetRequest('lot')
                }
            },
            order: [1, 'asc'],
            // "bInfo": false, // hidden showing entires of bottom
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
                    // 'checkboxes': {
                    //     'selectRow': true
                    // },
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
                    data: 'td_lot_ID',
                    "sClass": "td_lot_ID d-none",
                    // "bVisible": false,
                    "aTargets": [3],
                },
                {
                    data: 'td_lot_ID_text',
                    "sClass": "td_lot_ID_text",
                    "aTargets": [4],
                },
                {
                    data: 'td_genetic_id',
                    "sClass": "td_genetic_id d-none",
                    // "bVisible": false,
                    "aTargets": [5],
                },
                {
                    data: 'td_genetic_text',
                    "sClass": "td_genetic_text",
                    "aTargets": [6],
                },
                {
                    data: 'td_genetic_name',
                    "sClass": "td_genetic_name ",
                    "aTargets": [7],
                },
                
                {
                    data: 'td_born_date',
                    "sClass": "td_born_date",
                    "aTargets": [8],
                },
                {
                    data: 'td_harvest_date',
                    "sClass": "td_harvest_date",
                    "aTargets": [9],
                },
                {
                    data: 'td_dry_method',
                    "sClass": "td_dry_method d-none",
                    "aTargets": [10],
                },
                {
                    data: 'td_dry_method_name',
                    "sClass": "td_dry_method_name",
                    "aTargets": [11],
                },
                {
                    data: 'td_trimming_method',
                    "sClass": "td_trimming_method d-none",
                    "aTargets": [12],
                },
                {
                    data: 'td_trimming_method_name',
                    "sClass": "td_trimming_method_name",
                    "aTargets": [13],
                },
                {
                    data: 'td_days',
                    "sClass": "td_days",
                    "aTargets": [14],
                },
                {
                    data: 'td_location',
                    "sClass": "td_location d-none",
                    // "bVisible": false,
                    "aTargets": [15],
                },
                
                {
                    data: 'td_note',
                    "sClass": "td_note d-none",
                    // "bVisible": false,
                    "aTargets": [16],
                },
                {
                    data: 'buttons',
                    "aTargets": [17],
                    "mRender": function(data, type, row) { // o, v contains the object and value for the column
                        return '<a class="btn btn-sm bg-gradient-blue " href="#" id="btn_history">' +
                            '<i class="fas fa-history"></i>' +
                            'History' +
                            '</a>' +
                            '<a class="btn bg-gradient-green btn-sm" id="btn_edit" data-target="#modal-edit" href="#modal-edit" data-toggle="modal">' +
                            '<i class="fas fa-pencil-alt"></i>' +
                            'Edit' +
                            '</a>' +
                            '<a class="btn bg-gradient-yellow btn-sm" id="btn_singlePrint">' +
                            '<i class="fas fa-print"></i>' +
                            'Print'
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

        // END


        //Edit Packing plant
        //reference url https://jsfiddle.net/1s9u629w/1/
        $(document).on('click', '#btn_edit', function () {
            var act = "edit";
            $("#act").val(act);
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();
            var genetic_text = $row.find('.td_genetic_text').text();
            var genetic_name = $row.find('.td_genetic_name').text();
            var born_date = $row.find('.td_born_date').text();
            var harvest_date = $row.find('.td_harvest_date').text();
            var location = $row.find('.td_location').text();
            var note = $row.find('.td_note').text();
            var dry_method = $row.find('.td_dry_method').text();
            var trimming_method = $row.find('.td_trimming_method').text();
            $("#id").val(id);
            $("#qr_code").val(qr_code);
            $("#lot_ID_text").val(lot_ID_text);
            $("#genetic_text").val(genetic_text);
            $("#genetic_name").val(genetic_name);
            $("#born_date").val(born_date);
            $("#harvest_date").val(harvest_date);
            $('#location').val(location);
            $('#location').select2().trigger('change');
            $('#dry_method').val(dry_method);
            $('#dry_method').select2().trigger('change');
            $('#trimming_method').val(trimming_method);
            $('#trimming_method').select2().trigger('change');
            $("#note").val(note);
        });

        //modal clear when close
        $('#modal-add-Packing-Plants').on('hidden.bs.modal', function () {
            $("#qr_code").val("");
            $("#name").val("");
            $("#location").val("");
            $("#planting_date").val("");
            $("#seed").val("");
            $("#observation").val("");
            $("#plant_UID").val("");
            $("#plant_UID_text").val("");
            $("#quantity").val("");
            //Show QR code input section when create Packing plants
            $("#qr_code_section").prop("hidden",false);
            $("#plant_UID_section").prop("hidden",false);
            $("#quantity_section").prop("hidden",false);
            $("#quantity").prop('disabled', false);
        })

        //modal clear when close
        $('#modal-transfer').on('hidden.bs.modal', function () {
            $('#lot_ID').val("");
            $('#lot_ID').select2().trigger('change');
            $('#vault_room_id').val("");
            $('#vault_room_id').select2().trigger('change');
            $("#grams_amount").val("");
            $("#htc").val("");
            $("#trimming_method").val("");
            $("#cbd").val("");
            $("#other").val("");
            $("#note").val("");
        })

//        $('#type').change(function(){
//            var type = $(this).val();
//            if(type == "flower"){
//                $('#seeds_section').hide();
//                $('#flower_section').show();
//                $('#seeds_amount').val("");
//                $('#grams_amount').val("");
//            }
//            if(type == "seeds"){
//                $('#flower_section').hide();
//                $('#seeds_section').show();
//                $('#seeds_amount').val("");
//                $('#grams_amount').val("");
//            }
//        })

        $('#select_mother_id').change(function(){
            var selectedMotherID = $(this).val();
            if(selectedMotherID){
                $.ajax({
                    method:'POST',
                    url: '../Logic/savePackingPlants.php',
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

        //Change Event of select box (Packing Room)
        $('#selectedPackingRoomID').on('change', function() {
            var packingRoomID = this.value;
            var selectedLotID = $('#selectedLotID').val();
            $.redirect('../Views/plantsPacking.php',
                {
                    room: packingRoomID,
                    lot: selectedLotID
                },
                'GET');
        });

        $('#selectedLotID').on('change', function() {
            var packingRoomID = $('#selectedPackingRoomID').val();
            var selectedLotID = this.value;
            $.redirect('../Views/plantsPacking.php',
                {
                    room: packingRoomID,
                    lot: selectedLotID
                },
                'GET');
        });

        var checkedPlantList = [];

        //Click Delete Selected Plants Button
        $('#btn_delete').click(function(){
            checkedPlantList = [];
            //get checked list
            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, id) {
                checkedPlantList.push(id);
            });
            // ...
            var packingRoomID = $('#currentRoomID').val();
            $.ajax({
                method:'POST',
                url: '../Logic/savePackingPlants.php',
                data:{act:'delete', idList:checkedPlantList},
                success:function (data) {
                    $.redirect('../Views/plantsPacking.php',
                        {
                            room: packingRoomID
                        },
                        'GET');
                }
            })
            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        })

        //Click hostory button for a Packing plant
        $(document).on('click', '#btn_history', function () {
            event.preventDefault();
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var lot_ID = $row.find('.td_lot_ID').text();
            $.redirect('../Views/history.php',
                {
                    lot_id: lot_ID,
                    type:'lot'
                },
                'GET');
        })
    });

    //Date range picker
    $('#borndate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
    $('#harvestdate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
</script>

<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
