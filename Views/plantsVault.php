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
//$p_general = new General();

$rVault = new VaultRoom();
$mVaultRoomList = $rVault->getAllOfInfo();
$mVaultRoomList = $mVaultRoomList->results();
$pVault = new VaultPlant();
if($_GET['room']){
    $vaultRoomID = $_GET['room'];
    $lot_IDInfoList = $p_general->getValueOfAnyTable('vault','location','=',$vaultRoomID);
    $lot_IDInfoList = $lot_IDInfoList->results();
}else{
    $lot_IDInfoList = $p_general->getValueOfAnyTable('vault','1','=','1');
    $lot_IDInfoList = $lot_IDInfoList->results();
}
$count = $pVault->count();
// For search "p" is id of plant, not plant UID
if($_GET['p']){
    $searchLotID = $_GET['p'];
    foreach($lotIDList as $lotID){
        $tmp_lotIDList = array();
        if($lotID->lot_id == $searchLotID){
            array_push($tmp_lotIDList,$lotID);
            break;
        }
    }
    $lotIDList = $tmp_lotIDList;
}
$mGeneticList = $p_general->getValueOfAnyTable('genetic','1','=','1');
$mGeneticList = $mGeneticList->results();
if($_GET['genetic']){
    $genetic_ID = $_GET['genetic'];
}else{
    $genetic_ID = '0';
}
$mLotList = $p_general->getValueOfAnyTable('lot_id','1','=','1');
$mLotList = $mLotList->results();
$k = 0;
?>


<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="vault" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="vault_plants_tab" data-toggle="pill" href="#vault_plants_content" role="tab" aria-controls="vault_plants_tab" aria-selected="true">Vault Plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vault_rooms_tab" href="roomsVault.php" >Vault Rooms</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="vault_plants_content" role="tabpanel" aria-labelledby="vault_plants_content">
                    <!-- Vault Plants Section-->
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <input name="currentRoomID" id="currentRoomID" type="hidden" value="<?=$vaultRoomID?>">

                                        <select class="form-control select2bs4" id="selectedVaultRoomID" style="width: 100%;">
                                            <!--SelectBox Vault Room-->
                                            <option value="0">All Vault Room</option>
                                            <?php
                                            if($mVaultRoomList) {
                                                $k = 0;
                                                foreach ($mVaultRoomList as $mVaultRoom) {
                                                    ?>
                                                    <option
                                                        <?php
                                                        if ($vaultRoomID == $mVaultRoom->id){
                                                            echo 'selected';
                                                        }
                                                        ?>
                                                        value="<?=$mVaultRoom->id?>"  ><?=$mVaultRoom->name?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-4">
                                    <?php
                                    if($genetic_ID){
                                        $total_grams = 0;
                                        $total_seeds_amount = 0;
                                        $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=',$genetic_ID);
                                        $geneticInfo = $geneticInfo->results();
                                        $geneticinfoList = $p_general->getValueOfAnyTable('vault','genetic_ID','=',$genetic_ID);
                                        $geneticinfoList = $geneticinfoList->results();
                                        foreach($geneticinfoList as $geneticinfo){
                                            $total_grams += intval($geneticinfo->grams_amount);
                                            $total_seeds_amount += intval($geneticinfo->seeds_amount);
                                        }
                                        echo "HTC %: ".$geneticInfo[0]->htc."&nbsp&nbsp&nbsp CBD %: ".$geneticInfo[0]->cbd."&nbsp&nbsp&nbsp OTHER %: ".$geneticInfo[0]->other."&nbsp&nbsp&nbsp Total Grams: ".$total_grams."&nbsp&nbsp&nbsp Total Seeds: ".$total_seeds_amount;
                                    }
                                    ?>
                                </div>

                                <div class="col-sm-4" style="text-align:right">
<!--                                    <a class="btn bg-gradient-green btn-md" id="btn_reportPrint" data-target="#modal-report" href="#modal-report" data-toggle="modal">-->
<!--                                        <i class="fas fa-print"></i>-->
<!--                                        Report Print-->
<!--                                    </a>-->
<!--                                    <a class="btn bg-gradient-yellow btn-md" id="btn_multiPrint" data-target="#modal-multi-print-label" href="#modal-multi-print-label" data-toggle="modal">-->
<!--                                        <i class="fas fa-print"></i>-->
<!--                                        Multi Print-->
<!--                                    </a>-->
                                    <a class="btn bg-gradient-danger btn-md"  data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Vault Lot
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
                                    <th style="display:none" >id</th>
                                    <th style="display:none">qr code</th>
                                    <th style="display:none" >Lot Id really</th>
                                    <th>Lot Id</th>
                                    <th style="display:none">Packing number really</th>
                                    <th><?=$_SESSION['lang_packing_number']?></th>
                                    <th style="display:none" >Genetic id</th>
                                    <th>Genetic</th>
                                    <th>Name</th>
                                    <th style="display:none">Producer Name</th>
                                    <th style="display:none">Place of origin</th>
                                    <th>Born Date</th>
                                    <th>Harvest Date</th>
                                    <th>Packing Date</th>
                                    <th style="display:none">Expiration Date</th>
                                    <th>Flower Amount</th>
                                    <th>Seeds Amount</th>
                                    <th>THC %</th>
                                    <th>CBD %</th>
                                    <th>Other %</th>
                                    <th style="display:none" >location</th>
                                    <th style="display:none" >note</th>
                                    <th>Days</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <!-- <tbody id="plant_table">
                                <?php
                                foreach($lot_IDInfoList as $lotInfo) {
                                    if ($lotInfo->genetic_ID == $genetic_ID || $genetic_ID == '0') {
                                        //Calculate Today - Planting Date(born date) = Reporting days
                                        $m_room_date=date_create($lotInfo->room_date);
                                        $m_today=date_create(date("m/d/Y"));
                                        $diff=date_diff($m_room_date,$m_today);
                                        $days = $diff->format("%a");
                                        //End//
                                        $k++;
                                        ?>
                                        <tr>
                                            <td>
                                                <input class="plantCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$lotInfo->packing_number?>">
                                            </td>
                                            <td style="display:none" class="td_id" ><?=$lotInfo->id?></td>
                                            <td style="display:none" class="td_lot_ID" ><?=$lotInfo->lot_ID?></td>
                                            <td style="display:none" class="td_qr_code"><?php
                                                $lot_IDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lotInfo->lot_ID);
                                                $lot_IDInfo = $lot_IDInfo->results();
                                                echo $lot_IDInfo[0]->qr_code;
                                                ?></td>
                                            <td class="td_lot_ID_text" ><?php
                                                $lot_ID_text = $p_general->getTextOflotID($lotInfo->lot_ID);
                                                echo $lot_ID_text?></td>
                                            <td style="display:none" class="td_packing_number"><?=$lotInfo->packing_number?></td>

                                            <td class="td_packing_number_text"><?php echo $p_general->getTextOfPackingNumber($lotInfo->packing_number) ?></td>
                                            <td style="display:none" class="td_genetic_ID" ><?=$lotInfo->genetic_ID?></td>
                                            <td class="td_genetic_text" ><?php
                                                $genetic_Info = $p_general->getValueOfAnyTable('genetic','id','=',$lotInfo->genetic_ID);
                                                $genetic_Info = $genetic_Info->results();
                                                echo $genetic_Info[0]->genetic_name?></td>
                                            <td class="td_genetic_name" ><?php
                                                //$genetic_Info = $p_general->getValueOfAnyTable('genetic','id','=',$lotInfo->genetic_ID);
                                                //$genetic_Info = $genetic_Info->results();
                                                echo $genetic_Info[0]->plant_name;
                                                ?></td>
                                            <td style="display:none" class="td_producer_name" ><?=$lotInfo->producer_name?></td>
                                            <td style="display:none" class="td_place_origin" ><?=$lotInfo->place_origin?></td>

                                            <td class="td_born_date"><?php
                                                $lot_ID = $lotInfo->lot_ID;
                                                $lot_IDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
                                                $lot_IDInfo = $lot_IDInfo->results();
                                                echo $lot_IDInfo[0]->born_date;
                                                ?></td>
                                            <td class="td_harvest_date" ><?=$lot_IDInfo[0]->harvest_date?></td>
                                            <td class="td_packing_date" ><?=$lotInfo->packing_date?></td>
                                            <td style="display:none" class="td_expiration_date" ><?php
                                                $varTime = DateTime::createFromFormat('d/m/Y', $lotInfo->packing_date);
                                                $date1 =  $varTime->format('m/d/Y'); // format to standard time format.
                                                $expiration_date = date('d/m/Y', strtotime($date1.' +1 year'));
                                                echo $expiration_date?></td>
                                            <td class="td_gram_amount" ><?=$lotInfo->grams_amount?></td>
                                            <td class="td_seeds_amount" ><?=$lotInfo->seeds_amount?></td>
                                            <td class="td_thc_content" ><?=$lotInfo->thc?></td>
                                            <td class="td_cbd_content" ><?=$lotInfo->cbd?></td>
                                            <td class="td_other" ><?=$lotInfo->other?></td>
                                            <td style="display:none" class="td_location"><?=$lotInfo->location?></td>
                                            <td style="display:none" class="td_note"><?=$lotInfo->note?></td>
                                            <td class="td_days"><?=$days?></td>

                                            <td style="text-align: center">
                                                <a class="btn btn-sm bg-gradient-blue " href="#" id="btn_history">
                                                    <i class="fas fa-history"></i>
                                                    History
                                                </a>
                                                <a class="btn bg-gradient-green btn-sm" id="btn_edit" data-target="#modal-edit" href="#modal-edit" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>
                                                <a class="btn bg-gradient-yellow btn-sm" id="btn_report" data-target="#modal-report" href="#modal-report" data-toggle="modal">
                                                    <i class="fas fa-print"></i>
                                                    Report
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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
                <h4 class="modal-title">Vault Lot ID</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="../Logic/saveVault.php" enctype='multipart/form-data' id="editVaultFormValidate">
                <!--body-->
                <div class="modal-body">
                    <input name="act" id="act" type="hidden" value="">
                    <input name="showRoom" id="showRoom" type="hidden" value="<?=$vaultRoomID?>">
                    <input name="id" id="id" type="hidden" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lot Id</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Lot ID" id="lot_ID_text" name="lot_ID_text" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
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
                                    <input type="text" class="form-control" placeholder="Harvest date" id="harvest_date" name="harvest_date" readonly>
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
                                    <select class="form-control select2bs4" name="location" id="location" style="width: 100%;">
                                        <option value="">Select Vault Room</option>
                                        <?php
                                        $mVaultRoomList = $p_general->getValueOfAnyTable('room_vault','1','=','1');
                                        $mVaultRoomList = $mVaultRoomList->results();
                                        foreach($mVaultRoomList as $mRoom){
                                            ?>
                                            <option value="<?=$mRoom->id?>"><?=$mRoom->name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <div class="form-group input-group mb-3">
                                    <textarea class="form-control" rows="3" placeholder="Enter ..." id="note" name="note" style="height: 111px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

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
                <button type="button" class="btn btn-outline-light" id="btn_delete">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Print Label-->
<div class="modal fade" id="modal-print-label">
    <div class="modal-dialog width-modal-print-label">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Packing Lot Label</h4>
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
                                <div hidden class="print_mother_id" id="print_mother_id">Mother ID</div>
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
                <h4 class="modal-title">Multi Print Packing Lot Label</h4>
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
                            <label>Start Lot ID</label>
                            <select class="form-control select2bs4" name="start_Lot_ID_print" id="start_Lot_ID_print" style="width: 100%;">
                                <option value="" >Select Lot ID</option>
                                <?php
                                $lotIDList = $p_general->getValueOfAnyTable('index_vault','1','=','1','lot_id');
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
                    </div>

                    <div class="col-1" style="padding-top: 40px;text-align: center;"> ~ </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>End Lot ID</label>
                            <select class="form-control select2bs4" name="end_Lot_ID_print" id="end_Lot_ID_print" style="width: 100%;">
                                <option value="" >Select Lot ID</option>
                                <?php
                                $lotIDList = $p_general->getValueOfAnyTable('index_vault','1','=','1','lot_id');
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


<!-- Modal Report Print Label-->
<div class="modal fade" id="modal-report">
    <div class="modal-dialog width-modal-dialog-plants-Dry">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Report Print</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--body-->
            <div class="modal-body">
                <input type="hidden" id="qr_code_report" name="qr_code_report" value="">
                <input type="hidden" id="lot_ID_report" name="lot_ID_report" value="">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lot Id</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Lot ID" id="lot_ID_text_report" name="lot_ID_text" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Plant Name</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Plant Name" id="plant_name_report" name="plant_name" readonly>
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
                                <input type="text" class="form-control" placeholder="Scientific Name" id="scientific_name_report" name="scientific_name" readonly>
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
                                <input type="text" class="form-control" placeholder="Producer Name" id="producer_name_report" name="producer_name" readonly>
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
                                <input type="text" class="form-control" placeholder="Place of origin" id="place_origin_report" name="place_origin" readonly>
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
                                <input type="text" class="form-control" placeholder="Cultivation date" id="cultivation_date_report" name="cultivation_date" readonly>
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
                                <input type="text" class="form-control" placeholder="Harvest date" id="harvest_date_report" name="harvest_date" readonly>
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
                                <input type="text" class="form-control" placeholder="Packing date" id="packing_date_report" name="packing_date" readonly>
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
                                <input type="text" class="form-control" placeholder="Expiration date" id="expiration_date_report" name="expiration_date" readonly>
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
                                <input type="text" class="form-control" placeholder="<?=$_SESSION['lang_packing_number']?>" id="packing_number_text_report" name="packing_number_text" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label>Plant Part (Flower & Seed)</label>-->
<!--                            <div class="form-group">-->
<!--                                <select class="form-control select2bs4" name="plant_part" id="plant_part_report" style="width: 100%;" readonly>-->
<!--                                    <option value="flower">Flower</option>-->
<!--                                    <option value="seed">Seed</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="form-group">
                            <label>Plant Part (Flower & Seed)</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Plant Part" id="plant_part_report" name="plant_part" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount (net weight)</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Amount (net weight)" id="amount_report" name="amount" readonly>
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
                                <input type="text" class="form-control" placeholder="THC content" id="thc_content_report" name="thc_content" readonly>
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
                                <input type="text" class="form-control" placeholder="CBD content" id="cbd_content_report" name="cbd_content" readonly>
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
                                <input type="text" class="form-control" placeholder="Other" id="other_report" name="other" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--footer-->
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="#" id ="btn_gotoReportPrintLabelPage" class="btn btn-primary" >Print</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

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

        //go Report page when click
        $('#btn_gotoReportPrintLabelPage').click(function(){
            var lot_ID = $('#lot_ID_report').val();
            var plant_name = $('#plant_name_report').val();
            var scientific_name = $('#scientific_name_report').val();
            var plant_part = $('#plant_part_report').val();
            var producer_name = $('#producer_name_report').val();
            var place_origin = $('#place_origin_report').val()
            var cultivation_date = $('#cultivation_date_report').val()
            var harvest_date = $('#harvest_date_report').val()
            var packing_date = $('#packing_date_report').val()
            var expiration_date = $('#expiration_date_report').val()
            var packaging_number = $('#packing_number_text_report').val()
            var thc_content = $('#thc_content_report').val();
            var cbd_content = $('#cbd_content_report').val();
            var amount = $('#amount_report').val();
            var qr_code = $('#qr_code_report').val();
            var lot_ID_text = $('#lot_ID_text_report').val();
            var data = qr_code;
            var filename = lot_ID_text;
            $.ajax({
                method:'POST',
                url: '../Utilities/phpqrcode/index.php',
                data: {data:data,filename:filename},
                success:function(data){
                }
            })
            setTimeout(
                function()
                {
                    $.redirect('../Views/ReportPrintLabelPacking.php',{
                        lot_ID: lot_ID,
                        plant_name: plant_name,
                        scientific_name: scientific_name,
                        plant_part: plant_part,
                        producer_name: producer_name,
                        place_origin: place_origin,
                        cultivation_date: cultivation_date,
                        harvest_date: harvest_date,
                        packing_date: packing_date,
                        expiration_date: expiration_date,
                        packaging_number: packaging_number,
                        thc_content: thc_content,
                        cbd_content: cbd_content,
                        amount: amount,
                        lot_ID_text: lot_ID_text,
                    }, 'POST','_blank');
                }, 300);
        })

        $(document).on('click', '#btn_report', function () {
            var act = "edit";
            $("#act").val(act);
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var lot_ID = $row.find('.td_lot_ID').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();
            var genetic_text = $row.find('.td_genetic_text').text();
            var genetic_name = $row.find('.td_genetic_name').text();
            var producer_name = $row.find('.td_producer_name').text();
            var place_origin = $row.find('.td_place_origin').text();
            var born_date = $row.find('.td_born_date').text(); // cultivation_date
            var harvest_date = $row.find('.td_harvest_date').text();
            var packing_date = $row.find('.td_packing_date').text();
            var expiration_date = $row.find('.td_expiration_date').text();
            var packing_number_text = $row.find('.td_packing_number_text').text();
            //verify flower or seed
            var grams_amount = $row.find('.td_gram_amount').text();
            var seeds_amount = $row.find('.td_seeds_amount').text();
            if(grams_amount){
                var plant_part = "flower";
                var amount = $row.find('.td_gram_amount').text();
            }
            if(seeds_amount){
                var plant_part = "seed";
                var amount = $row.find('.td_seeds_amount').text();
            }
            var thc_content = $row.find('.td_thc_content').text();
            var cbd_content = $row.find('.td_cbd_content').text();
            var other = $row.find('.td_other').text();
            var location = $row.find('.td_location').text();
            var note = $row.find('.td_note').text();
            //var last_packing = $row.find('.td_last_packing').text();
            $("#id").val(id);
            $("#lot_ID_report").val(lot_ID);
            $("#qr_code_report").val(qr_code);
            $("#lot_ID_text_report").val(lot_ID_text);
            $("#plant_name_report").val(genetic_name);
            $("#scientific_name_report").val(genetic_text);
            $("#producer_name_report").val(producer_name);
            $("#place_origin_report").val(place_origin);
            $("#cultivation_date_report").val(born_date);
            $("#harvest_date_report").val(harvest_date);
            $("#packing_date_report").val(packing_date);
            $("#expiration_date_report").val(expiration_date);
            $("#packing_number_text_report").val(packing_number_text);
            $("#plant_part_report").val(plant_part);
//            $('#plant_part_report').select2().trigger('change');
            $("#amount_report").val(amount);
            $("#thc_content_report").val(thc_content);
            $("#cbd_content_report").val(cbd_content);
            $("#other_report").val(other);
        })

        //Go to Multi Print Label Page
        $('#btn_gotoMultiPrintLabelPage').click(function(){
            var start_ID = $('#start_Lot_ID_print').val();
            var end_ID = $('#end_Lot_ID_print').val();
            var plant_type = "lot";
            var location = "index_vault";
            $.ajax({
                method:'POST',
                url: '../Logic/saveVault.php',
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
            "ajax": {
                "url": "../Logic/tableVaultPlants.php",
                "data": {
                    "room": HttpGetRequest('room'),
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
                    data: 'td_packing_number',
                    "sClass": "td_packing_number d-none",
                    // "bVisible": false,
                    "aTargets": [5],
                },
                {
                    data: 'td_packing_number_text',
                    "sClass": "td_packing_number_text",
                    // "bVisible": false,
                    "aTargets": [6],
                },
                {
                    data: 'td_genetic_ID',
                    "sClass": "td_genetic_ID d-none",
                    // "bVisible": false,
                    "aTargets": [7],
                },
                {
                    data: 'td_genetic_text',
                    "sClass": "td_genetic_text",
                    "aTargets": [8],
                },
                {
                    data: 'td_genetic_name',
                    "sClass": "td_genetic_name ",
                    "aTargets": [9],
                },
                {
                    data: 'td_producer_name',
                    "sClass": "td_producer_name d-none",
                    // "bVisible": false,
                    "aTargets": [10],
                },
                {
                    data: 'td_place_origin',
                    "sClass": "td_place_origin d-none",
                    // "bVisible": false,
                    "aTargets": [11],
                },
                {
                    data: 'td_born_date',
                    "sClass": "td_born_date",
                    "aTargets": [12],
                },
                {
                    data: 'td_harvest_date',
                    "sClass": "td_harvest_date",
                    "aTargets": [13],
                },
                {
                    data: 'td_packing_date',
                    "sClass": "td_packing_date",
                    "aTargets": [14],
                },
                {
                    data: 'td_expiration_date',
                    "sClass": "td_expiration_date d-none",
                    "aTargets": [15],
                },
                {
                    data: 'td_gram_amount',
                    "sClass": "td_gram_amount",
                    "aTargets": [16],
                },
                {
                    data: 'td_seeds_amount',
                    "sClass": "td_seeds_amount",
                    "aTargets": [17],
                },
                {
                    data: 'td_thc_content',
                    "sClass": "td_thc_content",
                    "aTargets": [18],
                },
                {
                    data: 'td_cbd_content',
                    "sClass": "td_cbd_content",
                    "aTargets": [19],
                },
                {
                    data: 'td_other',
                    "sClass": "td_other",
                    "aTargets": [20],
                },
                {
                    data: 'td_location',
                    "sClass": "td_location d-none",
                    // "bVisible": false,
                    "aTargets": [21],
                },
                {
                    data: 'td_note',
                    "sClass": "td_note d-none",
                    // "bVisible": false,
                    "aTargets": [22],
                },
                {
                    data: 'td_days',
                    "sClass": "td_days",
                    "aTargets": [23],
                },
                {
                    data: 'buttons',
                    "aTargets": [24],
                    "mRender": function(data, type, row) { // o, v contains the object and value for the column
                        return '<a class="btn btn-sm bg-gradient-blue " href="#" id="btn_history">' +
                            '<i class="fas fa-history"></i>' +
                            'History' +
                            '</a>' +
                            '<a class="btn bg-gradient-green btn-sm" id="btn_edit" data-target="#modal-edit" href="#modal-edit" data-toggle="modal">' +
                            '<i class="fas fa-pencil-alt"></i>' +
                            'Edit' +
                            '</a>' +
                            '<a class="btn bg-gradient-yellow btn-sm" id="btn_report" data-target="#modal-report" href="#modal-report" data-toggle="modal">' +
                            '<i class="fas fa-print"></i>' +
                            'Report'
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

        //Click transfer plant button
        $('#btn_transferVaultPlant').click(function () {
            //show Modal
            $("#modal-transfer-Vault-Plants").modal('show');
        });

        //Edit Vault Lot
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
            var producer_name = $row.find('.td_producer_name').text();
            var place_origin = $row.find('.td_place_origin').text();
            var born_date = $row.find('.td_born_date').text(); // cultivation_date
            var harvest_date = $row.find('.td_harvest_date').text();
            var packing_date = $row.find('.td_packing_date').text();
            var expiration_date = $row.find('.td_expiration_date').text();
            var packing_number_text = $row.find('.td_packing_number_text').text();
            //verify flower or seed
            var grams_amount = $row.find('.td_gram_amount').text();
            var seeds_amount = $row.find('.td_seeds_amount').text();
            if(grams_amount){
                var plant_part = "flower";
                var amount = $row.find('.td_gram_amount').text();
            }
            if(seeds_amount){
                var plant_part = "seed";
                var amount = $row.find('.td_seeds_amount').text();
            }
            var thc_content = $row.find('.td_thc_content').text();
            var cbd_content = $row.find('.td_cbd_content').text();
            var other = $row.find('.td_other').text();
            var location = $row.find('.td_location').text();
            var note = $row.find('.td_note').text();
            //var last_packing = $row.find('.td_last_packing').text();

            $("#id").val(id);
            $("#qr_code").val(qr_code);
            $("#lot_ID_text").val(lot_ID_text);
            $("#plant_name").val(genetic_name);
            $("#scientific_name").val(genetic_text);
            $("#producer_name").val(producer_name);
            $("#place_origin").val(place_origin);
            $("#cultivation_date").val(born_date);
            $("#harvest_date").val(harvest_date);
            $("#packing_date").val(packing_date);
            $("#expiration_date").val(expiration_date);
            $("#packing_number_text").val(packing_number_text);
            $("#plant_part").val(plant_part);
            $('#plant_part').select2().trigger('change');
            $("#amount").val(amount);
            $("#thc_content").val(thc_content);
            $("#cbd_content").val(cbd_content);
            $("#other").val(other);
            $("#location").val(location);
            $('#location').select2().trigger('change');

            $("#note").val(note);
        });

        //modal clear when close
        $('#modal-edit').on('hidden.bs.modal', function () {
            $("#grams_amount").val("");
            $("#seeds_amount").val("");
        })

        $('#type').change(function(){
            var type = $(this).val();
            if(type == "flower"){
                $('#flower_section').show();
                $('#seeds_section').hide();
            }
            if(type == "seeds"){
                $('#seeds_section').show();
                $('#flower_section').hide();
            }
        })

        //Change Event of select box (Vault Room)
        $('#selectedVaultRoomID').on('change', function() {
            var vaultRoomID = this.value;
            var geneticID = $('#selectedGeneticID').val();
            $.redirect('../Views/plantsVault.php',
                {
                    room: vaultRoomID,
                    genetic:geneticID
                },
                'GET');
        });

        //Change Event of select box (Vault Room)
        $('#selectedGeneticID').on('change', function() {
            var geneticID = this.value;
            var vaultRoomID =  $('#selectedVaultRoomID').val();
            $.redirect('../Views/plantsVault.php',
                {
                    room: vaultRoomID,
                    genetic:geneticID
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

            var vaultRoomID = $('#currentRoomID').val();
            $.ajax({
                method:'POST',
                url: '../Logic/saveVault.php',
                data:{act:'delete', idList:checkedPlantList},
                success:function (data) {
                    $.redirect('../Views/plantsVault.php',
                        {
                            room: vaultRoomID
                        },
                        'GET');
                }
            })
            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        });

        //Click history button for a Vault plant
        $(document).on('click', '#btn_history', function () {
            event.preventDefault();
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var lot_ID = $row.find('.td_lot_ID').text();
            var packing_number = $row.find('.td_packing_number').text();
            $.redirect('../Views/history.php',
                {
                    lot_id: lot_ID,
                    packing_number:packing_number
                },
                'GET');
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
