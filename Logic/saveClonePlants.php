<?php

require_once('../Controllers/init.php');

$user = new User();
require('ssp.class.php');
if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_clone = new ClonePlant();

function generateCode($limit)
{
    $code = '';
    for ($i = 0; $i < $limit; $i++) {
        $code .= mt_rand(0, 9);
    }
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
    // $where_custom = $where_custom." AND `event` LIKE '%clone room%';";
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
if ($act == 'getGeneticfromID') {
    $lot_id = $_GET['lot_id'];
    $lotsList = $p_general->getValueOfAnyTable('lot_id', 'lot_id', '=', $lot_id);
    $lotsList = $lotsList->results();
    $geneticList = $p_general->getValueOfAnyTable('genetic', 'id', '=', $lotsList[0]->genetic_ID);
    $geneticList = $geneticList->results();
    echo json_encode($geneticList[0]);
}
if (Input::exists()) {

    if (Input::get('act') == 'generateNewLotID') {
        //generate lot ID
        $numberList = $p_general->getValueOfAnyTable('last_index', '1', '=', '1');
        $numberList = $numberList->results();
        if ($numberList[0]->mother) {
            $last_lot_UID = $numberList[0]->lot;
        } else {
            $last_lot_UID = 0;
        }

        $lot_ID = $last_lot_UID + 1;
        $lot_ID_text = $p_general->getTextOflotID($lot_ID);
        $s = [];
        $s[0] = $lot_ID;
        $s[1] = $lot_ID_text;
        echo json_encode($s);
    }

    if (Input::get('act') == 'GetPlantNameFromGeneticID') {
        $selectedGeneticID = Input::get('selectedGeneticID');

        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $selectedGeneticID);
        $geneticInfo = $geneticInfo->results();

        echo json_encode($geneticInfo[0]->plant_name);
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

    if (Input::get('act') == 'validate') {
        $validateQR = Input::get('qr_code');
        $validatePlantUID = Input::get('plant_UID');

        $isSame = $p_clone->isSameQR($validateQR);
        if ($isSame) {
            echo json_encode('SameQRCode');
        }
        echo json_encode($isSame);
    }
    // sample button
    if(Input::get('act') == 'sample_weight_Plant') {
        try{
            $sample_ref = Input::get('sample_ref');
            $lot_ID = Input::get('sample_lot_ID');
            $lot_ID_text = Input::get("sample_lot_ID_text");
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "clone";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name,$lotInfo[0] -> qr_code , "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsClone.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "clone";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsClone.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    // Add weight to plant
    if(Input::get('act') == 'add_weight_Plant') {
        try{
            $add_weight = (int)Input::get('add_weightofplant');
            $sub_weight = (int)Input::get('sub_weightofplant');
            $lot_ID = Input::get('add_lot_ID');
            $lot_ID_text = Input::get("add_lot_ID_text");
            
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $lotInfo[0] -> location);
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
                Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsClone.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'edit') {
        try {
            // get info
            
            $lot_ID = Input::get('edit_lot_ID');
            $lot_ID_text = Input::get('edit_lot_ID_text');

            $born_date = Input::get('edit_born_date');
            $genetic_ID = Input::get('edit_packing_genetic_id');
            $genetic_plant_name = Input::get('edit_plant_name_create');
            $note = Input::get('edit_note_create');
            $location = Input::get('edit_location_create'); // clone room id
            // $quantity = Input::get('clones_quantity');
            // ...
            $flg = 0;
            //get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();
            // ...

            // genetic
            if ($prevLotIDInfo[0]->genetic_ID != $genetic_ID) {
                $flg = 1;
                $prevGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $prevLotIDInfo[0]->genetic_ID);
                $prevGeneticInfo = $prevGeneticInfo->results();
                $updateGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $genetic_ID);
                $updateGeneticInfo = $updateGeneticInfo->results();
                $eventChangeGenetic = $lot_ID_text . ' Changed Genetic from *' . $prevGeneticInfo[0]->genetic_name . '* to *' . $updateGeneticInfo[0]->genetic_name . '* </br>';
            }
            // born data
            if ($prevLotIDInfo[0]->born_date != $born_date) {
                $flg = 1;
                $eventChangeBornDate = $lot_ID_text . ' Changed Born Date from *' . $prevLotIDInfo[0]->born_date . '* to *' . $born_date . '* </br>';
            }
            // location(room)
            if ($prevLotIDInfo[0]->location != $location) {
                $flg = 1;
                $prevLocationInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $prevLotIDInfo[0]->location);
                $prevLocationInfo = $prevLocationInfo->results();
                $updateLocationInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $location);
                $updateLocationInfo = $updateLocationInfo->results();
                $eventChangeLocation = $lot_ID_text . ' Changed Location from (' . $prevLocationInfo[0]->name . ') to (' . $updateLocationInfo[0]->name . ') </br>';
            }
            // note
            if ($prevLotIDInfo[0]->note != $note) {
                $flg = 1;
                $eventChangeNote = $lot_ID_text . ' Changed Observation from *' . $prevLotIDInfo[0]->note  . '* to *' . $note . '*';
            }

            //create lot ID at lot_id table
            $p_general->updateValueOfAnyTable('lot_id', array(
                'genetic_ID' => $genetic_ID,
                'born_date' => $born_date, // lot born date
                'room_date' => $room_date, // it is same with born date
                'note' => $note,
                'location' => $location,
            ), $prevLotIDInfo[0]->id);
            // ...

            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...

            //history
            if($flg == 1) {
                $event = $eventChangeGenetic . $eventChangeBornDate . $eventChangeLocation. "reason: " . $eventChangeNote;
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code, $room_name, $note);
            }
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsClone.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'transfer') {
        $startLot_ID = Input::get('start_lot_ID');
        $endLot_ID = Input::get('end_lot_ID');
        $selectedVegRoomID = Input::get('veg_room_id');
        $admin_id = Input::get('trans_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();
        try {
            for ($lot_ID = $startLot_ID; $lot_ID <= $endLot_ID; $lot_ID++) {
                //verify exist
                $exist = $p_general->getValueOfAnyTable('index_clone', 'lot_id', '=', $lot_ID);
                $exist = $exist->results();
                if (!$exist) {
                    continue;
                }
                $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
                $lotInfo = $lotInfo->results();
                // ...


                // delete to index_clone
                $p_general->deleteValueOfAnyTable('index_clone', 'lot_id', '=', $lot_ID);
                // ...

                // register to index_veg
                $p_general->createValueOfAnyTable('index_veg', array(
                    'room_id' => $selectedVegRoomID,
                    'genetic_id' => $lotInfo[0]->genetic_ID,
                    'lot_id' => $lot_ID
                ));
                // ...

                //change location of Lot ID
                $currentRoomID = $lotInfo[0]->location;
                $weight_out = $lotInfo[0]->weight_out;
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'location'    => $selectedVegRoomID,
                    'weight_in' => $weight_out,
                    'weight_out' => '0',
                ), $lotInfo[0]->id);
                // ...

                //Register Lot ID at History
                $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
                $currentRoomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $currentRoomID);
                $currentRoomInfo = $currentRoomInfo->results();
                $m_current_room_text = $currentRoomInfo[0]->name;
                $transferRoomInfo = $p_general->getValueOfAnyTable('room_veg', 'id', '=', $selectedVegRoomID);
                $transferRoomInfo = $transferRoomInfo->results();
                $m_transfer_room_text = $transferRoomInfo[0]->name;
                //Calculate Today - lot Date(room date) = Reporting days
                //                $orgDate = $lotInfo[0]->room_date;//room date
                //                $date = str_replace('/', '-', $orgDate);
                //                $new_date = date("m/d/Y", strtotime($date));
                $m_lot_date = date_create($lotInfo[0]->room_date);
                $m_today = date_create(date("m/d/Y"));
                $diff = date_diff($m_lot_date, $m_today);
                $m_days = $diff->format("%a");
                //End//
                $event = $m_lot_ID_text . '(' . $lotInfo[0]->qr_code . ')' .
                    ' was transfer from *Clone(' .
                    $m_current_room_text .
                    ')* to *Vegetation(' .
                    $m_transfer_room_text .
                    ')* after *' .
                    $m_days .
                    '* days Supervisor: '.$adminInfo[0]->name ;
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name,$lotInfo[0] -> qr_code, $m_transfer_room_text);
            }
            Redirect::to('../Views/plantsClone.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    

    //delete for lot id
    if (Input::get('act') == 'delete') {
        $type = Input::get('type');
        if ($type == 'lot') {
            $List = Input::get('idList');
            $res = 0;
            foreach ($List as $lot_id) {
                // verify lot have plants
                $exist = $p_general->getValueOfAnyTable('plants', 'lot_ID', '=', $lot_id);
                if ($exist->count()) {
                    $res = 1;
                    break;
                }
                //Register History
                $deleteInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_id);
                $deleteInfo = $deleteInfo->results();
                $m_lot_ID_text = $p_general->getTextOflotID($lot_id);
                $event = $m_lot_ID_text . ' destroy in clone room.';
                $p_general->registerHistoryLot($lot_id, $user->data()->id, $event, $user->data()->name, $deleteInfo[0] -> qr_code);
                // ...

                //Delete
                $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_id);
                $result = $p_general->deleteValueOfAnyTable('index_clone', 'lot_id', '=', $lot_id);
                // ...
            }
            if($res){
                echo json_encode("exist");
            }else {
                echo json_encode("success");
            }
        } else if ($type == 'plants') {
            $lot_ID = Input::get('lot_id');
            //Delete
            $result = $p_general->deleteValueOfAnyTable('plants', 'lot_ID', '=', $lot_ID);
            // ...

            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $p_general->updateValueOfAnyTable('lot_id', array(
                'number_of_plants' => '0',
            ), $lotInfo[0]->id);
            // ...
            
            //Register History
            $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
            $event = $m_lot_ID_text . ' destroy in clone room.';
            $p_general->registerHistoryLot($lot_id, $user->data()->id, $event, $user->data()->name, $lotInfo[0] -> qr_code);
            // ...
            echo json_encode("success");
        }
    }
    // //delete lots when plants is zero.
    // if (Input::get('act') == 'delete_sub') {
    //     try{
    //         $lot_id = Input::get('lot_id');
    //         $del_numofPlant = Input::get('del_numofPlant');
    //         $del_reason = Input::get('del_reason');
    //         $del_weight = Input::get('del_weight');
    //         //Register History
    //         $deleteInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_id);
    //         $deleteInfo = $deleteInfo->results();
            
    //     }
    //     catch (Exception $e) {
    //         die($e->getMessage());
    //     }
        
    // }
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
            $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            //history
            $event = $lot_ID_text. " waste ".$waste_weightofPlant. " Kg in ". $roomList[0]->name. " room.";
            if(strlen($waste_note) > 0){
                $event = $event. " reason: ".$waste_note;
            }
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name,$prevLotIDInfo[0] -> qr_code, $room_name, $note);
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsClone.php');
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
            $del_numberofPlant = Input::get('del_numberofplant');
            $del_weightofPlant = Input::get('del_weightofplant');
            $del_note = Input::get('del_note');
            $admin_id = Input::get('del_adminID');
            $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
            $adminInfo = $adminInfo->results();

            $currentNumboerofPlant = Input::get('cur_numberofplant');
            $restcount = strval((int)$currentNumboerofPlant - (int)$del_numberofPlant);
            // get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();
            if($del_numberofPlant == $prevLotIDInfo[0]->number_of_plants) {
                // file_put_contents('debug_log.txt', print_r("Thererewqrwqerwqer", true),FILE_APPEND | LOCK_EX);
                $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
                $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $prevLotIDInfo[0] -> location);
                $roomList = $roomList->results();
                
                $event ="The last register is ". $m_lot_ID_text. " deleted in ". $roomList[0]->name. " room successfully.";
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code);
                
                $event = $m_lot_ID_text. " destroy ".$del_numberofPlant." Plants with ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
                if(strlen($del_note) > 0){
                    $event = $event. " reason: ".$del_note;
                }
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code);
                
                // ...
    
                //Delete
                $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
                $result = $p_general->deleteValueOfAnyTable('index_clone', 'lot_id', '=', $lot_ID);
                $result = $p_general->deleteValueOfAnyTable('plants', 'lot_ID', '=', $lot_ID);
                $showRoom = Input::get('showRoom');
                if ($showRoom) {
                    Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
                } else {
                    Redirect::to('../Views/plantsClone.php');
                }
            }
            else {
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'number_of_plants' => $restcount
                ), $prevLotIDInfo[0]->id);
                $roomList = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $prevLotIDInfo[0] -> location);
                $roomList = $roomList->results();
                //history
                $event = $lot_ID_text. " destroy ".$del_numberofPlant." Plants with ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
                if(strlen($del_note) > 0){
                    $event = $event. " reason: ".$del_note;
                }
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code, $room_name, $note);
                // ...
    
                $showRoom = Input::get('showRoom');
                if ($showRoom) {
                    Redirect::to('../Views/plantsClone.php?room=' . $showRoom);
                } else {
                    Redirect::to('../Views/plantsClone.php');
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    if (Input::get('act') == 'getGeneticID') {
        $selectedPlantUID = Input::get('selectedPlantUID');
        $plantInfo = $p_general->getValueOfAnyTable('plants', 'plant_UID', '=', $selectedPlantUID);
        $plantInfo = $plantInfo->results();
        $genetic_ID = $plantInfo[0]->genetic;
        $mother_ID = $plantInfo[0]->mother_id;
        $mother_text = $plantInfo[0]->mother_text;
        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $genetic_ID);
        $geneticInfo = $geneticInfo->results();
        $genetic_name = $geneticInfo[0]->genetic_name;
        $s = [];
        $s[0] = $genetic_ID;
        $s[1] = $genetic_name;
        $s[2] = $mother_ID;
        $s[3] = $mother_text;
        echo json_encode($s);
    }

    if (Input::get('act') == 'packing_new_lotID') {
        // get info
        $lot_ID = Input::get('lot_ID');
        $born_date = Input::get('born_date');
        $genetic_ID = Input::get('packing_genetic_id');
        $genetic_name = Input::get('plant_name_create');
        $note = Input::get('note_create');
        $location = Input::get('location_create'); // clone room id
        $quantity = Input::get('clones_quantity');
        // ...
        // generate info
        $qr_code = $p_general->generateQRCode();
        // $m_today = date("m/d/Y"); // room_date
        $m_today = $p_general->getCurrentDateAndTime();

        $room_date = $m_today;
        // ...
        // generate plant id
        $last_plant_UID = $p_general->getLastPlantUID();
        $startPlant_UID = $last_plant_UID + 1;
        // $endPlant_UID = $last_plant_UID + $quantity;
        $endPlant_UID = "";
        // ...
        try {
            //create lot ID at lot_id table
            $p_general->createValueOfAnyTable('lot_id', array(
                'qr_code' => $qr_code,
                'lot_ID'    => $lot_ID,
                'genetic_ID' => $genetic_ID,
                'start_plant_ID' => $startPlant_UID,
                'end_plant_ID' => $endPlant_UID,
                'born_date' => $born_date, // lot born date
                'room_date' => $room_date, // it is same with born date
                'note' => $note,
                'location' => $location,
                'number_of_plants' => $quantity,

            ));
            // ...
            
            // last number list 
            $numberList = $p_general->getValueOfAnyTable('last_index', '1', '=', '1');
            $numberList = $numberList->results();
            if(count($numberList) == 0) {
                $p_general->createValueOfAnyTable('last_index', array(
                    'mother' => 1,
                    'clone' => 1,
                    'lot' => 1
                ));
            }
            $numberList = $p_general->getValueOfAnyTable('last_index', '1', '=', '1');
            $numberList = $numberList->results();
            // ...

            // update last lot ID
            $p_clone->updateLastIndex(array(
                'lot'    => $lot_ID,
            ), $numberList[0]->id);
            // ...
            //create plants
            $qr_code_plant = $p_general->generateQRCode();
            $plant_UID = $last_plant_UID + 1;
            // $motherInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', Input::get('select_mother_id_add'));
            // $motherInfo = $motherInfo->results();
            // $mother_UID_text = $p_general->getTextOfMotherUID($motherInfo[0]->mother_UID);
            $p_general->createValueOfAnyTable('plants', array(
                'qr_code'    => $qr_code_plant,
                'name'    => $genetic_name,
                'lot_ID'    => $lot_ID,
                // 'location'    => $location,
                'planting_date'    => $born_date,
                // 'mother_text'    => $mother_UID_text,
                // 'mother_id'    => Input::get('select_mother_id_add'),
                'genetic'    => $genetic_ID,
                'plant_UID'    => $plant_UID,
                'room_date'    => $room_date,
            ));
            // ...
            // Register lot in index_clone table - create index -- 'index_clone' table
            if ($location) {
                $p_general->createValueOfAnyTable('index_clone', array(
                    'room_id' => $location,
                    'genetic_id' => $genetic_ID,
                    'lot_id' => $lot_ID
                ));
            }
            //..
            // update last plant UID
            $p_clone->updateLastIndex(array(
                'clone'    => $plant_UID,
            ), $numberList[0]->id);
            // ...

            //history for create lot
            $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
            // $m_start_plant_UID_text = $p_general->getTextOfPlantUID($startPlant_UID);
            // $m_end_plant_UID_text = $p_general->getTextOfPlantUID($endPlant_UID);
            // $motherList = array_unique($motherList);
            // foreach ($motherList as $mother) {
            //     $motherListText = $motherListText . " *" . $mother . "* ";
            // }
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $m_lot_ID_text .
                ' (' .
                $qr_code .
                ') was created in '.$room_name.' with *' .
                $quantity .
                '* Plants by Genetic *' .
                $genetic_name .
                '* with Observation *' .
                $note;
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $qr_code, $room_name, $note);
            // ...
            Redirect::to('../Views/plantsClone.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'packing_exist_lotID') {
        // get info
        $genetic_ID = Input::get('genetic_exist');
        $genetic_name = Input::get('genetic_name_exist');
        $lot_ID = Input::get('lot_ID_exist');
        $born_date = Input::get('born_date_exist');

        //    $note = Input::get('note_create');

        $quantity = Input::get('clones_quantity_exist');
        // ...
        // generate info
        $qr_code = $p_general->generateQRCode();
        $m_today = $p_general->getCurrentDateAndTime(); // $m_today = date("m/d/Y"); // room_date
        $room_date = $m_today;
        // ...
        // generate plant id
        $last_plant_UID = $p_general->getLastPlantUID();
        $startPlant_UID = $last_plant_UID + 1;
        $endPlant_UID = $last_plant_UID + $quantity;
        // ...
        // get exist lot id info
        $lotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
        $lotIDInfo = $lotIDInfo->results();
        // ...
        $location = $lotIDInfo[0]->location; // clone room id
        try {
            //update lot ID's end plant ID at lot_id table
            $p_general->updateValueOfAnyTable('lot_id', array(
                // 'start_plant_ID' => $startPlant_UID, // it is already exist
                'end_plant_ID' => $endPlant_UID,
                'number_of_plants' => $lotIDInfo[0]->number_of_plants + $quantity,

            ), $lotIDInfo[0]->id);
            // ...
            // last number list 
            $numberList = $p_general->getValueOfAnyTable('last_index', '1', '=', '1');
            $numberList = $numberList->results();
            // ...

            //create plants
            $qr_code = $p_general->generateQRCode();
            $plant_UID = $last_plant_UID + 1;
            // $motherInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', Input::get('select_mother_id_add'));
            // $motherInfo = $motherInfo->results();
            // $mother_UID_text = $p_general->getTextOfMotherUID($motherInfo[0]->mother_UID);
            $p_general->createValueOfAnyTable('plants', array(
                'qr_code'    => $qr_code,
                'name'    => $genetic_name,
                'lot_ID'    => $lot_ID,
                'location'    => $location,
                'planting_date'    => $born_date,
                // 'mother_text'    => $mother_UID_text,
                // 'mother_id'    => Input::get('select_mother_id_add'),
                'genetic'    => $genetic_ID,
                'plant_UID'    => $plant_UID,
                'room_date'    => $room_date,
            ));
            // ...
            // update last plant UID
            $p_clone->updateLastIndex(array(
                'clone'    => $plant_UID,
            ), $numberList[0]->id);
            // for ($i = 1; $i <= $quantity; $i++) {
                
            //     // ...

            //     // //Register Event of Clone Plants at History
            //     // $m_plant_UID_text = $p_general->getTextOfPlantUID($plant_UID);
            //     // $m_roomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $location);
            //     // $m_roomInfo = $m_roomInfo->results();
            //     // $m_clone_room_text = $m_roomInfo[0]->name;
            //     // $motherInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', Input::get('select_mother_id_add'));
            //     // $motherInfo = $motherInfo->results();
            //     // $m_mother_UID_text = $p_general->getTextOfMotherUID($motherInfo[0]->mother_UID);
            //     // $event = $m_plant_UID_text . ' was born in *Clone(' . $m_clone_room_text . ')* From *' . $m_mother_UID_text . '* with *Observation(' . Input::get('observation') . ')*';
            //     // $p_general->registerHistoryPlant($plant_UID, $user->data()->id, $event, $m_clone_room_text, null, Input::get('observation'));
            // }
            // ...

            //history for create lot
            $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
            // $m_start_plant_UID_text = $p_general->getTextOfPlantUID($startPlant_UID);
            // $m_end_plant_UID_text = $p_general->getTextOfPlantUID($endPlant_UID);
            // $motherList = array_unique($motherList);
            // foreach ($motherList as $mother) {
            //     $motherListText = $motherListText . " *" . $mother . "* ";
            // }
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_clone', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $m_lot_ID_text .
                ' (' .
                $qr_code .
                ') was included with *' .
                $quantity .
                '* Plants by same Genetic *' .
                $genetic_name .
                '* in ' .
                $room_name;
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $qr_code, $room_name, $note);
            // ...
            Redirect::to('../Views/plantsClone.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'multi_print') {
        $start_ID = Input::get('start_ID');
        $end_ID = Input::get('end_ID');
        $data = array();
        for ($lot_ID = $start_ID; $lot_ID <= $end_ID; $lot_ID++) {
            $lotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotIDInfo = $lotIDInfo->results();
            //verify exist
            $exist = $p_general->getValueOfAnyTable('index_clone', 'lot_id', '=', $lot_ID);
            $exist = $exist->results();
            if (!$exist) {
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
