<?php

require_once('../Controllers/init.php');

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
require('ssp.class.php');
$p_flower = new FlowerPlant();

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
    // $where_custom = $where_custom." AND `event` LIKE '%flower room%';";
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
    // file_put_contents('debug_log.txt', print_r($hist_lot_id, true),FILE_APPEND | LOCK_EX);
    // $historylogs = $p_general->getValueOfAnyTable('history', 'lot_id', '=', $hist_lot_id);
    // $historylogs = $historylogs->results();
    // file_put_contents('debug_log.txt', print_r($historylogs, true),FILE_APPEND | LOCK_EX);
    // echo json_encode($historylogs);
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
if (Input::exists()) {
    if (Input::get('act') == 'generate_lotID') {
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
    // sample button
    if(Input::get('act') == 'sample_weight_Plant') {
        try{
            $sample_ref = Input::get('sample_ref');
            $lot_ID = Input::get('sample_lot_ID');
            $lot_ID_text = Input::get("sample_lot_ID_text");
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "Veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsFlower.php');
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
            $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "veg";
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsFlower.php');
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
            $lot_ID = Input::get('edit_lot_ID');
            $lot_ID_text = Input::get("edit_lot_ID_text");
            //update number of plant in lot id

            $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $lotInfo[0] -> location);
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
                Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsFlower.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
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

        $isSame = $p_flower->isSameQR($validateQR);
        if ($isSame) {
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
    //             $p_flower->create(array(
    //                 'qr_code'	=> $qr_code,
    //                 'name'	=> Input::get('name'),
    //                 'location'	=> $location,
    //                 'planting_date'	=> Input::get('planting_date'),
    //                 'mother_id'	=> Input::get('select_mother_id'),
    //                 'genetic'	=> Input::get('genetic'),
    //                 'observation'	=> Input::get('observation'),
    //                 'plant_UID'	=> $plant_UID,
    //             ));
    //             // Register flower Room : Plant in index_flower table
    //             $createdPlantQRCode =  $qr_code;
    //             if($location){
    //                 $p_flower->CreateRelationFlowerRoomAndPlant($location,$createdPlantQRCode);
    //             }
    //             //Register Plant at History
    //             $p_general->registerHistory($createdPlantQRCode,$user->data()->id,'add');
    //         }
    //         Redirect::to('../Views/plantsFlower.php?room='.$location);
    //     } catch(Exception $e) {
    //         die($e->getMessage());
    //     }
    // }


    if (Input::get('act') == 'edit') {
        try {
            // get info
            $lot_ID = Input::get('lot_ID');
            $lot_ID_text = Input::get('lot_ID_text');

            $born_date = Input::get('born_date');
            $genetic_ID = Input::get('packing_genetic_id');
            $genetic_plant_name = Input::get('plant_name_create');
            $note = Input::get('note_create');
            $location = Input::get('location_create'); // clone room id
            // $quantity = Input::get('clones_quantity');
            // ...

            //get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();
            // ...

            // genetic
            if ($prevLotIDInfo[0]->genetic_ID != $genetic_ID) {
                $prevGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $prevLotIDInfo[0]->genetic_ID);
                $prevGeneticInfo = $prevGeneticInfo->results();
                $updateGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $genetic_ID);
                $updateGeneticInfo = $updateGeneticInfo->results();
                $eventChangeGenetic = $lot_ID_text . ' Changed Genetic from *' . $prevGeneticInfo[0]->genetic_name . '* to *' . $updateGeneticInfo[0]->genetic_name . '* </br>';
            }
            // born data
            if ($prevLotIDInfo[0]->born_date != $born_date) {
                $eventChangeBornDate = $lot_ID_text . ' Changed Born Date from *' . $prevLotIDInfo[0]->born_date . '* to *' . $born_date . '* </br>';
            }
            // location(room)
            if ($prevLotIDInfo[0]->location != $location) {
                $prevLocationInfo = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $prevLotIDInfo[0]->location);
                $prevLocationInfo = $prevLocationInfo->results();
                $updateLocationInfo = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $location);
                $updateLocationInfo = $updateLocationInfo->results();
                $eventChangeLocation = $lot_ID_text . ' Changed Location from *Flower(' . $prevLocationInfo[0]->name . ')* to *Dry(' . $updateLocationInfo[0]->name . ')* </br>';
            }
            // note
            if ($prevLotIDInfo[0]->note != $note) {
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
            $roomInfo = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $location);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...

            //history
            $event =
                $eventChangeGenetic .
                $eventChangeBornDate .
                $eventChangeLocation .
                " in flower room reason: ".
                $eventChangeNote;
            $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code, $room_name, $note);
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsFlower.php');
            }
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
                $event = $m_lot_ID_text . ' destroy in flower room.';
                $p_general->registerHistoryLot($lot_id, $user->data()->id, $event, $user->data()->name, $deleteInfo[0] ->qr_code);
                // ...

                //Delete
                $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_id);
                $result = $p_general->deleteValueOfAnyTable('index_flower', 'lot_id', '=', $lot_id);
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
            $event = $m_lot_ID_text . '* destroy in flower room.';
            $p_general->registerHistoryLot($lot_id, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code);
            // ...
            echo json_encode("success");
        }
    }
    //delete lots when plants is zero.
    if (Input::get('act') == 'delete_sub') {
        try{
            $lot_id = Input::get('lot_id');
            $del_numofPlant = Input::get('del_numofPlant');
            $del_reason = Input::get('del_reason');
            $del_weight = Input::get('del_weight');
            //Register History
            $deleteInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_id);
            $deleteInfo = $deleteInfo->results();
            
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
        
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
            $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $prevLotIDInfo[0] -> location);
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
                Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsFlower.php');
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
                $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
                $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $prevLotIDInfo[0] -> location);
                $roomList = $roomList->results();
    
                $event ="The last register is " .$m_lot_ID_text. " deleted in ". $roomList[0]->name. " room successfully.";
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code);
    
                $event = $m_lot_ID_text. " destroy ".$del_numofPlant." Plants with ". $del_weight. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
                if (strlen($del_reason) > 0) {
                    $event = $event. " reason: ".$del_reason;
                }
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code);
                
                // ...
    
                //Delete
                $result = $p_general->deleteValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
                $result = $p_general->deleteValueOfAnyTable('index_flower', 'lot_id', '=', $lot_ID);
                $result = $p_general->deleteValueOfAnyTable('plants', 'lot_ID', '=', $lot_ID);
                $showRoom = Input::get('showRoom');
                if ($showRoom) {
                    Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
                } else {
                    Redirect::to('../Views/plantsFlower.php');
                }
            }
            else {
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'number_of_plants' => $restcount
                ), $prevLotIDInfo[0]->id);
                $roomList = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $prevLotIDInfo[0] -> location);
                $roomList = $roomList->results();
                //history
                $event = $lot_ID_text. " destroy ".$del_numberofPlant." Plants with ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
                if(strlen($del_note) > 0){
                    $event = $event. " reason: ".$del_note;
                }
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] ->qr_code, $room_name, $note);
                // ...
    
                $showRoom = Input::get('showRoom');
                if ($showRoom) {
                    Redirect::to('../Views/plantsFlower.php?room=' . $showRoom);
                } else {
                    Redirect::to('../Views/plantsFlower.php');
                }
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    if (Input::get('act') == 'selectMother') {
        $selectedMotherID = Input::get('selectedMotherID');
        $MotherInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $selectedMotherID);
        $MotherInfo = $MotherInfo->results();
        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $MotherInfo[0]->genetic);
        $geneticInfo = $geneticInfo->results();
        echo json_encode($geneticInfo[0]);
    }

    if (Input::get('act') == 'transfer_single_lot') {
        $startLot_ID = Input::get('start_lot_ID');
        $endLot_ID = Input::get('end_lot_ID');
        $selectedDryRoomID = Input::get('dry_room_id');
        $admin_id = Input::get('trans_s_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();
        try {
            for ($lot_ID = $startLot_ID; $lot_ID <= $endLot_ID; $lot_ID++) {
                //verify exist
                $exist = $p_general->getValueOfAnyTable('index_flower', 'lot_id', '=', $lot_ID);
                $exist = $exist->results();
                if (!$exist) {
                    continue;
                }

                //get lot info
                $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
                $lotInfo = $lotInfo->results();
                // ...
                $m_lot_ID_text = $p_general->getTextOflotID($lot_ID);
                // Validate
                if ($lotInfo[0] -> weight_out == null || $lotInfo[0] ->weight_out == 0) {
                    $event = $m_lot_ID_text . ' transfer faild reason: not enough weight';
                    $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code);
                    continue;
                }

                // delete to index_flower
                $p_general->deleteValueOfAnyTable('index_flower', 'lot_id', '=', $lot_ID);
                // ...

                //create lot ID to lot_id DB
                $flowerRoomID = $lotInfo[0]->location; // for history
                $weight_out = $lotInfo[0]->weight_out;
                $p_flower->updateLotID(array(
                    'location'    => $selectedDryRoomID,
                    'harvest_date'    => date("d/m/Y"),
                    'room_date' => date("m/d/Y"),
                    'weight_in' => $weight_out,
                    'weight_out' => '0',
                ), $lotInfo[0]->id);
                // ...
                
                //add lot to index_dry
                $p_general->createValueOfAnyTable('index_dry', array(
                    'room_id' => $selectedDryRoomID,
                    'lot_id' => $lot_ID,
                ));
                // ...
                //Register Lot ID at History
                $flowerRoomInfo = $p_general->getValueOfAnyTable('room_flower', 'id', '=', $flowerRoomID);
                $flowerRoomInfo = $flowerRoomInfo->results();
                $m_flower_room_text = $flowerRoomInfo[0]->name;
                $dryRoomInfo = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $selectedDryRoomID);
                $dryRoomInfo = $dryRoomInfo->results();
                $m_dry_room_text = $dryRoomInfo[0]->name;
                //Calculate Today - lot Date(born date) = Reporting days
                //                $orgDate = $lotInfo[0]->room_date; //room date
                //                $date = str_replace('/', '-', $orgDate);
                //                $new_date = date("m/d/Y", strtotime($date));
                $m_lot_date = date_create($lotInfo[0]->room_date);
                $m_today = date_create(date("m/d/Y"));
                $diff = date_diff($m_lot_date, $m_today);
                $m_days = $diff->format("%a");
                //End//
                $event = $m_lot_ID_text . ' was transfer from *Flower(' . $m_flower_room_text . ')* to *Dry(' . $m_dry_room_text . ')* after *' . $m_days . '* days Supervisor: '.$adminInfo[0]->name ;
                $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code, $m_dry_room_text);
                // ...
            }
            Redirect::to('../Views/plantsFlower.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'transfer_compound_lot') {
        //        $startLot_ID = Input::get('start_lot_ID_transfer_compound');
        //        $endLot_ID = Input::get('end_lot_ID_transfer_compound');
        $selectedLotUIDList = Input::get('compound_lotID_list');
        $genetic_ID = Input::get('genetic_id_transfer_compound');
        $newCompoundLot_UID = Input::get('lot_ID_transfer_compound');
        $born_date = Input::get('born_date_compound');
        $selectedDryRoomID = Input::get('dry_room_id_transfer_compound');
        $note = Input::get('note_transfer_compound');
        $admin_id = Input::get('trans_adminID');
        $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();

        try {
            // update last_index
            $numberList = $p_general->getValueOfAnyTable('last_index', '1', '=', '1');
            $numberList = $numberList->results();
            $p_general->updateValueOfAnyTable('last_index', array(
                'lot' =>  $newCompoundLot_UID
            ), $numberList[0]->id);
            // ...
            // create new compound lot id
            $qr_code = $p_general->generateQRCode();
            $p_general->createValueOfAnyTable('lot_id', array(
                'qr_code' => $qr_code,
                'lot_ID'    => $newCompoundLot_UID,
                'genetic_ID' => $genetic_ID,
                //'start_plant_ID'=> $startPlant_UID, // because of compound lot
                //'end_plant_ID' => $endPlant_UID,      // because of compound lot
                'born_date' => $born_date, // lot born date
                'note' => $note,
                'harvest_date'    => date("d/m/Y"),
                'room_date' => date("m/d/Y"),
                'location' => $selectedDryRoomID,
            ));
            // ...
            // update children lot_id info with compound_lot_id
            //            for ($lot_UID = $startLot_ID; $lot_UID <= $endLot_ID; $lot_UID++) {
            foreach ($selectedLotUIDList as $lot_UID) {
                //verify exist and same genetic
                $exist = $p_general->getValueOfAnyTable('index_flower', 'lot_id', '=', $lot_UID);
                $exist = $exist->results();
                if (!$exist) {
                    continue;
                }
                if ($genetic_ID != $exist[0]->genetic_id) {
                    continue;
                }
                // ...
                //get lot info from lot_UID
                $lotInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_UID);
                $lotInfo = $lotInfo->results();
                $lot_id =  $lotInfo[0]->id;
                // ..
                $m_lot_ID_text = $p_general->getTextOflotID($lot_id);
                if ($lotInfo[0] -> weight_out == null || $lotInfo[0] ->weight_out == 0) {
                    $event = $m_lot_ID_text . ' transfer faild reason: not enough weight';
                    $p_general->registerHistoryLot($lot_ID, $user->data()->id, $event, $user->data()->name, $lotInfo[0] ->qr_code);
                    continue;
                }
                // update
                $weight_out = $lotInfo[0]->weight_out;
                $p_general->updateValueOfAnyTable('lot_id', array(
                    'compound_lot_ID' =>  $newCompoundLot_UID,
                    'weight_in' => $weight_out,
                    'weight_out' => '0',
                ), $lot_id);
                // ...
                // delete child lot at index_flower
                $p_general->deleteValueOfAnyTable('index_flower', 'lot_id', '=', $lot_UID);
                // ...
                // for history
                $lot_UID_text = $p_general->getTextOflotID($lot_UID);
                $childLotUiD_text = $childLotUiD_text . $lot_UID_text;
                if ($lot_UID != $endLot_ID) {
                    $childLotUiD_text = $childLotUiD_text . ' ';
                }
                // ...
            }
            // ...
            // add new compound lot to index_dry
            $p_general->createValueOfAnyTable('index_dry', array(
                'room_id' => $selectedDryRoomID,
                'lot_id' => $newCompoundLot_UID,
            ));
            // ...
            // register history
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $selectedDryRoomID);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $newCompoundLot_UID_text = $p_general->getTextOflotID($newCompoundLot_UID);
            $event = $newCompoundLot_UID_text . ' its compound by Lots( ' . $childLotUiD_text . ')' . ' with Note *' . $note . '* at *' . $born_date . '* supervisor: '.$adminInfo[0]->name;
            $p_general->registerHistoryLot($newCompoundLot_UID, $user->data()->id, $event, $user->data()->name, "", $room_name, $note, null);
            // ...

            Redirect::to('../Views/plantsFlower.php');
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
            $exist = $p_general->getValueOfAnyTable('index_flower', 'lot_id', '=', $lot_ID);
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


    if (Input::get('act') == 'getLotIDList') {
        $selectedGeneticID = Input::get('selectedGeneticID');
        $IndexlotIDList = $p_general->getValueOfAnyTable('index_flower', 'genetic_id', '=', $selectedGeneticID, 'lot_id');
        $IndexlotIDList = $IndexlotIDList->results();
        $allowLotIDList = array();
        foreach ($IndexlotIDList as $item) {
            $lot_ID = $item->lot_id;
            $lot_ID_text = $p_general->getTextOflotID($item->lot_id);
            array_push($allowLotIDList, ['lot_id' => $lot_ID, 'lot_id_text' => $lot_ID_text]);
        }
        $allowLotIDList = $p_general->unique_multidim_array($allowLotIDList, 'lot_id');
        echo json_encode($allowLotIDList);
    }
}
