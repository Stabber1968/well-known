<?php

require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_dry = new DryPlant();
require('ssp.class.php');
function generateCode($limit){
    $code = '';
    for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
    return $code;
}
$act = $_GET['act'];
if($act == 'gethistory') {

    $hist_lot_id = $_GET['hist_lot_id'];
    $cons = $_GET['cons'];
    $where_custom = "WHERE lot_id='" . $hist_lot_id . "'";
    if(strlen($cons) > 0) {
        $where_custom = $where_custom." AND `event` LIKE '%".$cons."%'" ; 
    }
    // $where_custom = $where_custom." AND `event` LIKE '%dry room%';";
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
            $roomList = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "Veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsDry.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsDry.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsDry.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsDry.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //dd
    if (Input::get('act') == 'generate'){
        //generate lot ID
        //$numberList = $p_general->getNumberListOrderBy('plants','1','=','1','lot_ID');
        $numberList = $p_general->getValueOfAnyTable('last_index','1','=','1');
        $numberList = $numberList->results();
        if($numberList[0]->mother){
            $last_lot_UID = $numberList[0]->lot;
        }else{
            $last_lot_UID = 0;
        }
        $lot_ID = $last_lot_UID + 1;
        $lot_ID_text = $p_general->getTextOflotID($lot_ID);
        $s = [];
        $s[0] = $lot_ID;
        $s[1] = $lot_ID_text;
        echo json_encode($s);
    }
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
        $isSame = $p_dry->isSameQR($validateQR);
        if ($isSame){
            echo json_encode('SameQRCode');
        }
        echo json_encode($isSame);
    }

    // if(Input::get('act') == 'add'){
    //     try {
    //         // Create Plant in Plants Table
    //         $quantity = Input::get('quantity');
    //         $quantity = intval($quantity);
    //         //location = room
    //         $location = Input::get('location');
    //         for ($i = 1; $i <= $quantity; $i++) {
    //             $unique_num_qrcode = generateCode(16);
    //             $qr_code = substr($unique_num_qrcode,0,4)."-".substr($unique_num_qrcode,4,4)."-".substr($unique_num_qrcode,8,4)."-".substr($unique_num_qrcode,12,4);
    //             $numberList = $p_general->getNumberListOrderBy('plants','1','=','1','plant_UID');
    //             $numberList = $numberList->results();
    //             if($numberList[0]->plant_UID){
    //                 $plant_UID = $numberList[0]->plant_UID + 1;
    //             }else{
    //                 $plant_UID = 1;
    //             }
    //             $p_dry->create(array(
    //                 'qr_code'	=> $qr_code,
    //                 'name'	=> Input::get('name'),
    //                 'location'	=> $location,
    //                 'planting_date'	=> Input::get('planting_date'),
    //                 'mother_id'	=> Input::get('select_mother_id'),
    //                 'genetic'	=> Input::get('genetic'),
    //                 'observation'	=> Input::get('observation'),
    //                 'plant_UID'	=> $plant_UID,
    //             ));
    //             // Register Dry Room : Plant in index_dry table
    //             $createdPlantQRCode =  $qr_code;
    //             if($location){
    //                 $p_dry->CreateRelationDryRoomAndPlant($location,$createdPlantQRCode);
    //             }
    //             //Register Plant at History
    //             $p_general->registerHistory($createdPlantQRCode,$user->data()->id,'add');
    //         }
    //         Redirect::to('../Views/plantsDry.php?room='.$location);
    //     } catch(Exception $e) {
    //         die($e->getMessage());
    //     }
    // }
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
            $roomList = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $lotInfo[0] -> location);
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
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsDry.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsDry.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    if(Input::get('act') == 'edit'){
        try {
            $id = Input::get('id'); // id of lot ID
            $Info = $p_general->getValueOfAnyTable('lot_id','id','=',$id);
            $Info = $Info->results();
            $lot_ID = $Info[0]->lot_ID;
            $lot_ID_text = $p_general->getTextOflotID($lot_ID);
            //For history --> Compare value between previous value and update value
            $prevLocation = $Info[0]->location;
            $prevNote = $Info[0]->observation;
            $prevBornDate = $Info[0]->born_date;
            $prevHarvestDate = $Info[0]->harvest_date;
            if($prevLocation != Input::get('location')){
                $prevRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',$prevLocation);
                $prevRoomInfo = $prevRoomInfo->results();
                $updateRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',Input::get('location'));
                $updateRoomInfo = $updateRoomInfo->results();
                $eventChangeLocation = $lot_ID_text.' Changed Location from *Dry('.$prevRoomInfo[0]->name.')* to *Dry('.$updateRoomInfo[0]->name.')* </br>';
            }
            if($prevBornDate != Input::get('born_date')){
                $eventChangeBornDate = $lot_ID_text.' Changed Born Date from *'.$prevBornDate.'* to *'.Input::get('born_date').'* </br>';
            }
            if($prevHarvestDate != Input::get('harvest_date')){
                $eventChangeHarvestDate = $lot_ID_text.' Changed Harvest Date from *'.$prevHarvestDate.'* to *'.Input::get('harvest_date').'* </br>';
            }
            if($prevNote != Input::get('note')){
                $eventChangeNote = $lot_ID_text.' Changed Note from *'.$prevNote.'* to *'.Input::get('note').'*';
            }
            //location = room id
            $location = Input::get('location');
            // Update lot in lot id Table
            $p_dry->update(array(
                'location'	=> $location,
                'born_date'	=> Input::get('born_date'),
                'harvest_date'	=> Input::get('harvest_date'),
                'note'	=> Input::get('note'),
            ),$id);
            // Update location in index_dry
            if($location){
                $p_dry->UpdateLocationOfIndexTable($location,$lot_ID);
            }
            //Register at History
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $eventChangeLocation.$eventChangeBornDate.$eventChangeHarvestDate." in dry room reason: ".$eventChangeNote;
            $p_general->registerHistoryLot($lot_ID,$user->data()->id,$event, $user->data()->name, $Info[0] ->qr_code,$room_name,Input::get('note'));
            $showRoom = Input::get('showRoom');
            if($showRoom){
                Redirect::to('../Views/plantsDry.php?room='.$showRoom);
            }else{
                Redirect::to('../Views/plantsDry.php');
            }
        } catch(Exception $e) {
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
            $event = $m_lot_ID_text . ' destroy in dry room.';
            $p_general->registerHistoryLot($lot_id,$user->data()->id,$event, $user->data()->name, $deleteInfo[0] ->qr_code);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('lot_id','lot_ID','=',$lot_id);
            $result = $p_general->deleteValueOfAnyTable('index_dry','lot_id','=',$lot_id);
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
            $roomList = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            //history
            $event = $lot_ID_text. " waste ".$waste_weightofPlant. " Kg in ". $roomList[0]->name. " room.";
            if(strlen($waste_note) > 0){
                $event = $event. " reason: ".$waste_note;
            }
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code, $room_name, $note);
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsDry.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsDry.php');
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
        // $p_general->updateValueOfAnyTable('lot_id', array(
        //     'waste_weight' => $waste_weight
        // ), $prevLotIDInfo[0]->id);
        $roomList = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $prevLotIDInfo[0] -> location);
        $roomList = $roomList->results();
        //history
        $event = $lot_ID_text. " destroy ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
        if(strlen($del_note) > 0){
            $event = $event. " reason: ".$del_note;
        }
        $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code, $room_name, $note);
        // ...
        // remove plants
        $result = $p_general->deleteValueOfAnyTable('plants', 'lot_ID', '=', $prevLotIDInfo[0]->lot_ID);
        $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $prevLotIDInfo[0]->lot_ID);
        $result = $p_general->deleteValueOfAnyTable('index_dry', 'lot_id', '=', $prevLotIDInfo[0]->lot_ID);
        $showRoom = Input::get('showRoom');
        if ($showRoom) {
            Redirect::to('../Views/plantsDry.php?room=' . $showRoom);
        } else {
            Redirect::to('../Views/plantsDry.php');
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

    if (Input::get('act') == 'getGeneticID'){
        $selectedPlantUID = Input::get('selectedPlantUID');
        $plantInfo = $p_general->getValueOfAnyTable('plants','plant_UID','=',$selectedPlantUID);
        $plantInfo = $plantInfo->results();
        $genetic_ID = $plantInfo[0]->genetic;
        $mother_ID = $plantInfo[0]->mother_id;
        $mother_text = $plantInfo[0]->mother_text;
        $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=',$genetic_ID);
        $geneticInfo = $geneticInfo->results();
        $genetic_name = $geneticInfo[0]->genetic_name;
        $s = [];
        $s[0] = $genetic_ID;
        $s[1] = $genetic_name;
        $s[2] = $mother_ID;
        $s[3] = $mother_text;
        echo json_encode($s);
    }

    if (Input::get('act') == 'transfer') {
        $startLot_ID = Input::get('start_lot_ID');
        $endLot_ID = Input::get('end_lot_ID');
        $selectedTrimmingRoomID = Input::get('trimming_room_ID');
        $dry_method = Input::get('dry_method');
        $admin_id = Input::get('trans_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();
        //for history
        $dryMethodInfo = $p_general->getValueOfAnyTable('dry_method','id','=',$dry_method);
        $dryMethodInfo = $dryMethodInfo->results();
        $dry_method_text = $dryMethodInfo[0]->name;
        try {
            for ($lot_ID = $startLot_ID; $lot_ID <= $endLot_ID; $lot_ID++) {
                //verify exist
                $exist = $p_general->getValueOfAnyTable('index_dry','lot_id','=',$lot_ID);
                $exist = $exist->results();
                if(!$exist){
                    continue;
                }
                //update Lot ID
                $lotInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$lot_ID);
                $lotInfo = $lotInfo->results();
                $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
                if ($lotInfo[0] -> weight_out == null || $lotInfo[0] ->weight_out == 0) {
                    $event = $m_lot_ID_text . ' transfer faild reason: not enough weight';
                    $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code);
                    continue;
                }

                $dryRoomID = $lotInfo[0]->location; //for history
                $weight_out = $lotInfo[0]->weight_out;
                $p_dry->updateLotID(array(
                    'location'	=> $selectedTrimmingRoomID,
                    'room_date' => date("m/d/Y"),
                    'dry_method' => $dry_method,
                    'weight_in' => $weight_out,
                    'weight_out' => '0',
                ),$lotInfo[0]->id);
                //transfer dry to trimming --> delete id at index_dry
                $p_dry->deleteValueOfAnyTable('index_dry','lot_id','=',$lot_ID);
                // Register lot to Trimming Room = index_trimming table
                $p_dry->transferRelationTrimmingRoomAndLotID($selectedTrimmingRoomID,$lot_ID);
                //Register Lot ID at History
                
                $dryRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',$dryRoomID);
                $dryRoomInfo = $dryRoomInfo->results();
                $m_dry_room_text = $dryRoomInfo[0]->name;
                $trimmingRoomInfo = $p_general->getValueOfAnyTable('room_dry','id','=',$selectedTrimmingRoomID);
                $trimmingRoomInfo = $trimmingRoomInfo->results();
                $m_trimming_room_text = $trimmingRoomInfo[0]->name;
                //Calculate Today - lot Date(born date) = Reporting days
//                $orgDate = $lotInfo[0]->room_date; //room date
//                $date = str_replace('/', '-', $orgDate);
//                $new_date = date("m/d/Y", strtotime($date));
                $m_lot_date=date_create($lotInfo[0]->room_date);
                $m_today=date_create(date("m/d/Y"));
                $diff=date_diff($m_lot_date,$m_today);
                $m_days = $diff->format("%a");
                //End//
                $event = $m_lot_ID_text.' was transfer from *Dry('.$m_dry_room_text.')* to *Trimming('.$m_trimming_room_text.')* with *Dry Method('.$dry_method_text.')* after *'.$m_days.'* days Supervisor: '.$adminInfo[0]->name ;
                $p_general->registerHistoryLot($lot_ID,$user->data()->id,$event, $user->data()->name, $lotInfo[0] ->qr_code, $m_trimming_room_text);
            }
            Redirect::to('../Views/plantsDry.php');
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
            $exist = $p_general->getValueOfAnyTable('index_dry','lot_id','=',$lot_ID);
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

}


