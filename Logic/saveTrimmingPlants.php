<?php
 require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
require('ssp.class.php');
$p_trimming = new TrimmingPlant();

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
            $roomList = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "Veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsTrimming.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsTrimming.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsTrimming.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsTrimming.php');
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
        $isSame = $p_trimming->isSameQR($validateQR);
        if ($isSame){
            echo json_encode('SameQRCode');
        }
        echo json_encode($isSame);
    }

    if(Input::get('act') == 'edit'){
        try {
            $id = Input::get('id'); //id of lot ID at lot_id table
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
            if($prevNote != Input::get('note')){
                $eventChangeNote = $lot_ID_text.' Changed Note from *'.$prevNote.'* to *'.Input::get('note').'*';
            }
            //location = room id
            $location = Input::get('location');
            // Update lot in lot id Table
            $p_trimming->update(array(
                'location'	=> $location,
                'born_date'	=> Input::get('born_date'),
                'harvest_date'	=> Input::get('harvest_date'),
                'dry_method'	=> Input::get('dry_method'),
                'note'	=> Input::get('note'),
            ),$id);
            // Update location in index_trimming
            if($location){
                $p_trimming->UpdateLocationOfIndexTable($location,$lot_ID);
            }
            //Register at History
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $eventChangeLocation.$eventChangeBornDate.$eventChangeHarvestDate.$eventChangeDryMethod." in trimming room reason: ".$eventChangeNote;
            $p_general->registerHistoryLot($lot_ID,$user->data()->id,$event, $user->data()->name, $Info[0] -> qr_code,$room_name, Input::get('note'), null);
            $showRoom = Input::get('showRoom');
            if($showRoom){
                Redirect::to('../Views/plantsTrimming.php?room='.$showRoom);
            }else{
                Redirect::to('../Views/plantsTrimming.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $lotInfo[0] -> location);
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
                Redirect::to('../Views/plantsTrimming.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsTrimming.php');
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
            $event = $m_lot_ID_text . ' destroy in trimming room.';
            $p_general->registerHistoryLot($lot_id,$user->data()->id,$event, $user->data()->name, $deleteInfo[0] -> qr_code);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('lot_id','lot_ID','=',$lot_id);
            $result = $p_general->deleteValueOfAnyTable('index_trimming','lot_id','=',$lot_id);
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
            $roomList = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $prevLotIDInfo[0] -> location);
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
                Redirect::to('../Views/plantsTrimming.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsTrimming.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_trimming', 'id', '=', $prevLotIDInfo[0] -> location);
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
            $result = $p_general->deleteValueOfAnyTable('index_trimming', 'lot_id', '=', $prevLotIDInfo[0]->lot_ID);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsTrimming.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsTrimming.php');
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
        $startLot_ID = Input::get('start_lot_ID');
        $endLot_ID = Input::get('end_lot_ID');
        $selectedPackingRoomID = Input::get('packing_room_ID');
        $trimming_method = Input::get('trimming_method');
        $admin_id = Input::get('trans_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();
        //for history
        $trimmingMethodInfo = $p_general->getValueOfAnyTable('trimming_method','id','=',$trimming_method);
        $trimmingMethodInfo = $trimmingMethodInfo->results();
        $trimming_method_text = $trimmingMethodInfo[0]->name;
        try {
            for ($lot_ID = $startLot_ID; $lot_ID <= $endLot_ID; $lot_ID++) {
                //verify exist
                $exist = $p_general->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID);
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

                $trimmingRoomID = $lotInfo[0]->location; //for history
                $weight_out = $lotInfo[0]->weight_out;
                $p_general->updateValueOfAnyTable('lot_id',array(
                    'location'	=> $selectedPackingRoomID,
                    'room_date' => date("m/d/Y"),
                    'trimming_method' => $trimming_method,
                    'weight_in' => $weight_out,
                    'weight_out' => '0',
                ),$lotInfo[0]->id);
                //transfer trimming to trimming --> delete id at index_trimming
                $p_general->deleteValueOfAnyTable('index_trimming','lot_id','=',$lot_ID);
                // Register lot to Trimming Room = index_trimming table
                $p_trimming->transferRelationPackingRoomAndLotID($selectedPackingRoomID,$lot_ID);
                //Register Lot ID at History
                
                $trimmingRoomInfo = $p_general->getValueOfAnyTable('room_trimming','id','=',$trimmingRoomID);
                $trimmingRoomInfo = $trimmingRoomInfo->results();
                $m_trimming_room_text = $trimmingRoomInfo[0]->name;
                $packingRoomInfo = $p_general->getValueOfAnyTable('room_packing','id','=',$selectedPackingRoomID);
                $packingRoomInfo = $packingRoomInfo->results();
                $m_packing_room_text = $packingRoomInfo[0]->name;
                //Calculate Today - lot Date(born date) = Reporting days
                //room date
//                $orgDate = $lotInfo[0]->room_date;
//                $date = str_replace('/', '-', $orgDate);
//                $new_date = date("m/d/Y", strtotime($date));
                $m_lot_date=date_create($lotInfo[0]->room_date);
                $m_today=date_create(date("m/d/Y"));
                $diff=date_diff($m_lot_date,$m_today);
                $m_days = $diff->format("%a");
                //End//
                $event = $m_lot_ID_text.' was transfer from *trimming('.$m_trimming_room_text.')* to *Trimming('.$m_packing_room_text.')* with *Trimming Method('.$trimming_method_text.')* after *'.$m_days.'* days Supervisor: '.$adminInfo[0]->name ;
                $p_general->registerHistoryLot($lot_ID,$user->data()->id,$event, $user->data()->name, $lotInfo[0] -> qr_code, $m_packing_room_text);
            }
            Redirect::to('../Views/plantsTrimming.php');
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
            $exist = $p_general->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID);
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


