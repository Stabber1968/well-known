<?php
require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
//$p_general = new General();
require('ssp.class.php');
$p_packing = new PackingPlant();
function generateCode($limit){
    $code = '';
    for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
    return $code;
}
$act = $_GET['act'];
if($act == 'gethistory') {

    $hist_lot_id = $_GET['hist_lot_id'];
    // file_put_contents('debug_log.txt', print_r($hist_lot_id, true),FILE_APPEND | LOCK_EX);
    $cons = $_GET['cons'];
    $where_custom = "WHERE lot_id='" . $hist_lot_id . "'";
    if(strlen($cons) > 0) {
        $where_custom = $where_custom." AND `event` LIKE '%".$cons."%'" ; 
    }
    // $where_custom = $where_custom." AND `event` LIKE '%trimming room%';";
    $columns = array(
        array('db' => 'date', 'dt' => 'td_date'),
        array(
            'db' => 'user_id',  'dt' => 'td_username',
            'formatter' => function ($d, $row) {
                $p_general = new General();
                $userInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $d);
                $userInfo = $userInfo->results();
                return  $userInfo[0]->name;
            }
        ),
        array('db' => 'event',   'dt' => 'td_event',),
    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, 'history', 'id', $columns, $sJoin, $where_custom)
    );
}
if($act == 'sample') {
    $hist_lot_id = $_GET['lot_id'];
    $cons = $_GET['date'];
    $where_custom = "WHERE lot_id='" . $hist_lot_id . "'";
    if(strlen($cons) > 0) {
        $where_custom = $where_custom." AND `event` LIKE '%sample%".$cons."%'" ; 
    }
    $columns = array(
        array('db' => 'date', 'dt' => 'td_date'),
        array(
            'db' => 'user_id',  'dt' => 'td_username',
            'formatter' => function ($d, $row) {
                $p_general = new General();
                $userInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $d);
                $userInfo = $userInfo->results();
                return  $userInfo[0]->name;
            }
        ),
        array('db' => 'event',   'dt' => 'td_event',),
    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, 'history', 'id', $columns, $sJoin, $where_custom)
    );

}
if(Input::exists()) {
    // sample button
    if(Input::get('act') == 'sample_weight_Plant') {
        try{
            $sample_ref = Input::get('sample_ref');
            $lot_ID = Input::get('sample_lot_ID');
            $lot_ID_text = Input::get("sample_lot_ID_text");
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "Veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsPacking.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Pesticide to plant
    if(Input::get('act') == 'pest_sub_Plant') {
        try{
            $pest_note = Input::get('pest_note');
            $lot_ID = Input::get('pest_lot_ID');
            $lot_ID_text = Input::get("pest_lot_ID_text");
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsPacking.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //unneed because of multi generation
    if (Input::get('act') == 'generate'){
        $qr_code = $p_general->generateQRCode();
        $unique_num_plantUID =  generateCode(8);
        $plant_UID = substr($unique_num_plantUID,0,4)."-".substr($unique_num_plantUID,4,4);
        $s = [];
        $s[0] = $qr_code;
        $s[1] = $plant_UID;
        echo json_encode($s);
    }
    /////////////end////////////
    if (Input::get('act') == 'user_validate') {
        try {
            $user_pass = Input::get('user_pass');
            $admin_pass = Input::get('admin_pass');
            $admin_id = Input::get('admin_id');
            $res = $user->validateUser($user->data()->id, $user_pass,$admin_pass, $admin_id);
            echo json_encode($res);
        } catch (Exception $e) {
            // file_put_contents('debug_log.txt', print_r($e->getMessage(), true),FILE_APPEND | LOCK_EX);
            die($e->getMessage());
        }
        
    }
    if (Input::get('act') == 'validate'){
        $validateQR = Input::get('qr_code');
        $validatePlantUID = Input::get('plant_UID');
        $isSame = $p_packing->isSameQR($validateQR);
        if ($isSame){
            echo json_encode('SameQRCode');
        }
        echo json_encode($isSame);
    }

    if(Input::get('act') == 'edit'){
        try {
            $id = Input::get('id');
            $Info = $p_general->getValueOfAnyTable('lot_id','id','=',$id);
            $Info = $Info->results();
            $lot_ID = $Info[0]->lot_ID;
            $lot_ID_text = $p_general->getTextOflotID($lot_ID);
            //For history --> Compare value between previous value and update value
            $prevLocation = $Info[0]->location;
            $prevNote = $Info[0]->note;
            $prevBornDate = $Info[0]->born_date;
            $prevHarvestDate = $Info[0]->harvest_date;
            $prevDryMethod = $Info[0]->dry_method;
            $prevTrimmingMethod = $Info[0]->trimming_method;
            if($prevLocation != Input::get('location')){
                $prevRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',$prevLocation);
                $prevRoomInfo = $prevRoomInfo->results();
                $updateRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',Input::get('location'));
                $updateRoomInfo = $updateRoomInfo->results();
                $eventChangeLocation = $lot_ID_text.' Changed Location from *Trimming('.$prevRoomInfo[0]->name.')* to *Trimming('.$updateRoomInfo[0]->name.')* </br>';
            }
            if($prevBornDate != Input::get('born_date')){
                $eventChangeBornDate = $lot_ID_text.' Changed Born Date from *'.$prevBornDate.'* to *'.Input::get('born_date').'* </br>';
            }
            if($prevHarvestDate != Input::get('harvest_date')){
                $eventChangeHarvestDate = $lot_ID_text.' Changed Harvest Date from *'.$prevHarvestDate.'* to *'.Input::get('harvest_date').'* </br>';
            }
            if($prevDryMethod != Input::get('dry_method')){
                $prevDryMethodInfo = $p_general->getValueOfAnyTable('dry_method','id','=',$prevDryMethod);
                $prevDryMethodInfo = $prevDryMethodInfo->results();
                $updateDryMethodInfo = $p_general->getValueOfAnyTable('dry_method','id','=',Input::get('dry_method'));
                $updateDryMethodInfo = $updateDryMethodInfo->results();
                $eventChangeDryMethod = $lot_ID_text.' Changed Dry Method from *'.$prevDryMethodInfo[0]->name.'* to *'.$updateDryMethodInfo[0]->name.'* </br>';
            }
            if($prevTrimmingMethod != Input::get('trimming_method')){
                $prevTrimmingMethodInfo = $p_general->getValueOfAnyTable('trimming_method','id','=',$prevTrimmingMethod);
                $prevTrimmingMethodInfo = $prevTrimmingMethodInfo->results();
                $updateTrimmingMethodInfo = $p_general->getValueOfAnyTable('trimming_method','id','=',Input::get('trimming_method'));
                $updateTrimmingMethodInfo = $updateTrimmingMethodInfo->results();
                $eventChangeTrimmingMethod = $lot_ID_text.' Changed Trimming Method from *'.$prevTrimmingMethodInfo[0]->name.'* to *'.$updateTrimmingMethodInfo[0]->name.'* </br>';
            }
            if($prevNote != Input::get('note')){
                $eventChangeNote = $lot_ID_text.' Changed Note from *'.$prevNote.'* to *'.Input::get('note').'*';
            }
            //location = room id
            $location = Input::get('location');
            // Update lot in lot id Table
            $p_packing->update(array(
                'location'	=> $location,
                'born_date'	=> Input::get('born_date'),
                'harvest_date'	=> Input::get('harvest_date'),
                'dry_method'	=> Input::get('dry_method'),
                'trimming_method'	=> Input::get('trimming_method'),
                'note'	=> Input::get('note'),
            ),$id);
            // Update location in index_trimming
            if($location){
                $p_packing->UpdateLocationOfIndexTable($location,$lot_ID);
            }
            //Register at History
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $eventChangeLocation.$eventChangeBornDate.$eventChangeHarvestDate.$eventChangeDryMethod.$eventChangeTrimmingMethod.$eventChangeNote;
            $p_general->registerHistoryLot($lot_ID,$user->data()->id,$event, $user->data()->name,$Info[0]->qr_code, $room_name,  Input::get('note'));
            $showRoom = Input::get('showRoom');
            if($showRoom){
                Redirect::to('../Views/plantsPacking.php?room='.$showRoom);
            }else{
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
    // Add weight to plant
    if(Input::get('act') == 'add_weight_Plant') {
        try{
            $add_weight = (int)Input::get('add_weightofplant');
            $sub_weight = (int)Input::get('sub_weightofplant');
            $lot_ID = Input::get('edit_lot_ID');
            $lot_ID_text = Input::get("edit_lot_ID_text");
            //update number of plant in lot id

            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
            $event_in = "";
            $event_out="";
            $and = "";
            if($add_weight > 0 && $sub_weight > 0) $and = " and ";
            if ($add_weight > 0) {
                $event_in = "come in ".$add_weight." Kg";
                $add_weight = (int)$lotInfo[0] -> weight_in + $add_weight;
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'weight_in' => $add_weight,
                ), $lotInfo[0]->id);
            }
            if ($sub_weight > 0) {
                $event_out = "go out ".$sub_weight." Kg";
                $sub_weight = (int)$lotInfo[0] -> weight_out + $sub_weight;
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'weight_out' => $sub_weight,
                ), $lotInfo[0]->id);
            }
            $event = $lot_ID_text." set weight to ".$event_in . $and . $event_out." in ".$roomList[0]->name;
            //.$weight." kg in clone room.";
            $note = "weight";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsPacking.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //delete for lot id
    if (Input::get('act') == 'delete'){
        $List = Input::get('idList');
        foreach($List as $lot_id){
            //Register History
            $deleteInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_id);
            $deleteInfo = $deleteInfo->results();
            $m_lot_ID_text = $p_general->getTextOflotID($lot_id);
            $event = 'Deleted *'.$m_lot_ID_text.'*';
            $p_general->registerHistoryLot($lot_id,$user->data()->id,$event, $user->data()->name,$deleteInfo[0]->qr_code);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('lot_id','lot_ID','=',$lot_id);
            $result = $p_general->deleteValueOfAnyTable('index_packing','lot_id','=',$lot_id);
        }
        echo json_encode("success");
    }
    // Waste some plants
    if (Input::get('act') == 'waste_sub_Plant') {
        try {
            // get info
            $lot_ID = Input::get('waste_lot_ID');
            $lot_ID_text = Input::get('waste_lot_ID_text');
            $waste_weightofPlant = Input::get('waste_weightofplant');
            $waste_note = Input::get('waste_note');
            // get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();            
            $roomList = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            //history
            $event = $lot_ID_text. " waste ".$waste_weightofPlant. " Kg in ". $roomList[0]->name. " room.";
            if(strlen($waste_note) > 0){
                $event = $event. " reason: ".$waste_note;
            }
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code, $room_name, $note);
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsPacking.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //delete something from every lot
    if (Input::get('act') == 'del_sub_Plant') {
        try {
            // get info
            $lot_ID = Input::get('del_lot_ID');
            $lot_ID_text = Input::get('del_lot_ID_text');
            $del_weightofPlant = Input::get('del_weightofplant');
            $del_note = Input::get('del_note');
            $admin_id = Input::get('del_adminID');
            $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
            $adminInfo = $adminInfo->results();

            // get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();
            $waste_weight = strval($prevLotIDInfo[0]->waste_weight + 0 + ($del_weightofPlant + 0));
            
            $roomList = $p_general->getValueOfAnyTable('room_packing', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            //history
            // $event = "Deleted ".$del_numberofPlant . " of Plants from " . $lot_ID_text ;
            $event = $lot_ID_text. " destroy ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
            if(strlen($del_note) > 0){
                $event = $event. " reason: ".$del_note;
            }
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code, $room_name, $note);
            // ...
            // remove plants
            $result = $p_general->deleteValueOfAnyTable('plants', 'lot_ID', '=', $prevLotIDInfo[0]->lot_ID);
            $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $prevLotIDInfo[0]->lot_ID);
            
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsPacking.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsPacking.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'selectMother'){
        $selectedMotherID = Input::get('selectedMotherID');
        $MotherInfo = $p_general->getValueOfAnyTable('plants','id','=',$selectedMotherID);
        $MotherInfo = $MotherInfo->results();
        $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=',$MotherInfo[0]->genetic);
        $geneticInfo = $geneticInfo->results();
        echo json_encode($geneticInfo[0]);
    }

    if (Input::get('act') == 'transfer') {
        $lot_ID = Input::get('transfer_lot_ID');
        $packing_number= Input::get('packing_number');
        //$plant_name = Input::get('plant_name');
        //$scientific_name = Input::get('scientific_name');
        $producer_name = Input::get('producer_name');
        $place_origin = Input::get('place_origin');
        $cultivation_date = Input::get('cultivation_date');
        $harvest_date = Input::get('harvest_date');
        $packing_date = Input::get('packing_date');
        $expiration_date = Input::get('expiration_date');
        $plant_part = Input::get('plant_part');
        $amount = Input::get('amount');
        $thc_content = Input::get('thc_content');
        $cbd_content = Input::get('cbd_content');
        $other = Input::get('other');
        $selectedVaultRoomID = Input::get('location');
        $note = Input::get('note');
        $last_packing = Input::get('last_packing');
        $admin_id = Input::get('trans_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();

        if($plant_part == "flower"){
            $seeds_amount = "";
            $grams_amount = $amount;
        }
        if($plant_part == "seed"){
            $seeds_amount = $amount;
            $grams_amount = "";
        }
        $lotInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
        $lotInfo = $lotInfo->results();
        $geneticID = $lotInfo[0]->genetic_ID;
        try {
            $p_packing->createVault(array(
                'lot_ID'	=> $lot_ID,
                'genetic_ID' => $geneticID,
                'packing_number' => $packing_number,
                'packing_date'	=> $packing_date,
                'grams_amount'	=> $grams_amount,
                'seeds_amount'	=> $seeds_amount,
                'thc'	=> $thc_content,
                'cbd'	=> $cbd_content,
                'other'	=> $other,
                'note'	=> $note,
                'location'	=> $selectedVaultRoomID,
                'producer_name' => $producer_name,
                'place_origin' => $place_origin,
                'room_date' => date("m/d/Y"),
            ));
            $p_packing->updateLotID(array(
                'location'	=> $selectedVaultRoomID,
            ),$lot_ID);
            //delete relationship
            if($last_packing){
                $p_general->deleteValueOfAnyTable('index_packing','lot_id','=',$lot_ID);
            }
            //for History
            $lot_ID_text = $p_general->getTextOflotID($lot_ID);
            $packing_number_text = $p_general->getTextOfPackingNumber($packing_number);
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_vault', 'id', '=', $selectedVaultRoomID);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $_SESSION['lang_packing_number'].' '.$packing_number_text.'('.$lot_ID_text.')'.' transfer from *Packing* to *Vault* Supervisor: '.$adminInfo[0]->name;
            $p_general->registerHistoryPacking($lot_ID,$user->data()->id,$event,$packing_number, $room_name,$note );


            Redirect::to('../Views/plantsPacking.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'multi_print'){
        $start_ID = Input::get('start_ID');
        $end_ID = Input::get('end_ID');
        $data = array();
        for($lot_ID=$start_ID; $lot_ID<=$end_ID; $lot_ID++){
            $lotIDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
            $lotIDInfo = $lotIDInfo->results();
            //verify exist
            $exist = $p_general->getValueOfAnyTable('index_packing','lot_id','=',$lot_ID);
            $exist = $exist->results();
            if(!$exist){
                continue;
            }
            $s = [];
            $s[0] = $lotIDInfo[0]->qr_code; //data = qr code
            $s[1] = $p_general->getTextOflotID($lot_ID); //filename
            array_push($data, $s);
        }
        echo json_encode($data);
    }

    if (Input::get('act') == 'getTransferInfo'){
        $lot_ID = Input::get('lot_ID');
        $lot_ID_info = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
        $lot_ID_info = $lot_ID_info->results();
        //genetic info for plant name and scientific name
        $geneticID = $lot_ID_info[0]->genetic_ID;
        $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=',$geneticID);
        $geneticInfo =$geneticInfo->results();
        //lot info at vault for seed or flower
        $vaultInfo = $p_general->getValueOfAnyTable('vault','lot_ID','=', $lot_ID);
        $vaultInfo = $vaultInfo->results();

        //result
        $plant_name = $geneticInfo[0]->plant_name;
        $scientific_name = $geneticInfo[0]->genetic_name;
        $cultivation_date = $lot_ID_info[0]->born_date;
        $harvest_date = $lot_ID_info[0]->harvest_date;
        $packing_date = date('d/m/Y');
        $varTime = DateTime::createFromFormat('d/m/Y', $packing_date);
        $date1 =  $varTime->format('m/d/Y'); // format to standard time format.
        $expiration_date = date('d/m/Y', strtotime($date1.' +1 year'));
        $lot_ID_text = $p_general->getTextOflotID($lot_ID);
        $qr_code = $lot_ID_info[0]->qr_code;
        //generate packing number according to each lot id
        $packing_number = $p_general->getLastPackingNumberOfLotID($lot_ID);
        $packing_number_text = $p_general->getTextOfPackingNumber($packing_number);
        $s = [];
        $s[0] = $plant_name;
        $s[1] = $scientific_name;
        $s[2] = $cultivation_date;
        $s[3] = $harvest_date;
        $s[4] = $packing_date;
        $s[5] = $expiration_date;
        $s[6] = $packing_number;
        $s[7] = $packing_number_text;

        echo json_encode($s);
    }
}


