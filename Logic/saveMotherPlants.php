<?php

require_once('../Controllers/init.php');

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
require('ssp.mother_class.php');
$p_mother = new MotherPlant();
$act = $_GET['act'];
if($act == 'gethistory') {

    $mother_uid = $_GET['mother_uid'];
    
    $cons = $_GET['cons'];
    $where_custom = "WHERE mother_UID='" . $mother_uid . "'";
    if(strlen($cons) > 0) {
        $where_custom = $where_custom." AND `event` LIKE '%".$cons."%'" ; 
    }
    // $where_custom = $where_custom." AND `event` LIKE '%mother room%';";
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

    $mother_uid = $_GET['mother_uid'];
    $cons = $_GET['date'];
    $where_custom = "WHERE mother_UID='" . $mother_uid . "'";
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
    // sample button
    if(Input::get('act') == 'sample_weight_Plant') {
        try{
            $sample_ref = Input::get('sample_ref');
            $lot_ID = Input::get('sample_lot_ID');
            $lot_ID_text = Input::get("sample_lot_ID_text");
            //update number of plant in lot id
            $lotInfo = $p_general->getValueOfAnyTable('plants', 'mother_UID', '=', $lot_ID);
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." was sample Doc Ref N° ".$sample_ref." in ".$roomList[0]->name." room." ;
            $note = "mother";
            $p_general->registerHistoryMother($lot_ID, $user->data()->id, $event, $user->data()->name,$lotInfo[0] -> qr_code , "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsMother.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsMother.php');
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
            $lotInfo = $p_general->getValueOfAnyTable('plants', 'mother_UID', '=', $lot_ID);
            
            $lotInfo = $lotInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $lotInfo[0] -> location);
            $roomList = $roomList->results();
        //history
            $event = $lot_ID_text." set pesticide in ".$roomList[0]->name." room : " .$pest_note;
            $note = "veg";
            $p_general->registerHistoryMother($lot_ID, $user->data()->id, $event, $user->data()->name,$lotInfo[0] -> qr_code, "", $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsMother.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsMother.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    if (Input::get('act') == 'generate') {
        $qr_code = $p_general->generateQRCode();
        $numberList = $p_general->getNumberListOrderBy('plants', '1', '=', '1', 'mother_UID');
        $numberList = $numberList->results();
        if ($numberList[0]->mother_UID) {
            $mother_UID = $numberList[0]->mother_UID + 1;
        } else {
            $mother_UID = 1;
        }

        $mother_UID_text = $p_general->getTextOfMotherUID($mother_UID);

        $s = [];
        $s[0] = $qr_code;
        $s[1] = $mother_UID;
        $s[2] = $mother_UID_text;

        echo json_encode($s);
    }

    if (Input::get('act') == 'validate') {
        $validateQR = Input::get('qr_code');
        $validatePlantUID = Input::get('plant_UID');
        $isSame = $p_mother->isSameQR($validateQR);
        if ($isSame) {
            echo json_encode('SameQRCode');
        } else {
            $isSame = $p_mother->isSamePlantUID($validatePlantUID);
            if ($isSame) {
                echo json_encode('SamePlantUID');
            } else {
                echo json_encode($isSame);
            }
        }
    }

    if (Input::get('act') == 'add') {
        try {
            // Create Plant in Plants Table
            $quantity = Input::get('quantity');
            $quantity = intval($quantity);
            $observation = Input::get('observation');

            //location = room
            $room_ID = Input::get('location');

            //generate mother UID
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
            if ($numberList[0]->mother) {
                $last_mother_UID = $numberList[0]->mother;
            } else {
                $last_mother_UID = 0;
            }

            for ($i = 1; $i <= $quantity; $i++) {
                //generate QR Code
                $qr_code = $p_general->generateQRCode();
                $mother_UID = $last_mother_UID + $i;

                //room date
                $orgDate = Input::get('planting_date');
                $date = str_replace('/', '-', $orgDate);
                $room_date = date("m/d/Y", strtotime($date));

                // create
                $p_general->createValueOfAnyTable('plants', array(
                    'qr_code'    => $qr_code,
                    'name'    => Input::get('name'),
                    'location'    => $room_ID,
                    'planting_date'    => Input::get('planting_date'),
                    'mother_id'    => Input::get('seed'),
                    'mother_text'    => Input::get('seed'),
                    'genetic'    => Input::get('genetic'),
                    'observation'    => $observation,
                    'plant_UID'    => '0',
                    'mother_UID'    => $mother_UID,
                    'room_date'    => $room_date,
                ));
                
                // update last mother UID
                $p_general->updateValueOfAnyTable('last_index', array(
                    'mother'    => $mother_UID,
                ), $numberList[0]->id);

                // Register Mother Room : Plant in index_mother table
                $createdPlantQRCode =  $qr_code;
                if ($room_ID) {
                    $p_mother->CreateRelationMotherRoomAndPlant($room_ID, $createdPlantQRCode);
                }

                //Register Event of Mother Plants at History
                $m_mother_UID_text = $p_general->getTextOfMotherUID($mother_UID);
                // get room name from room id
                $roomInfo = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $room_ID);
                $roomInfo = $roomInfo->results();
                $room_name = $roomInfo[0]->name;
                $geneticInfo = $p_general ->getValueOfAnyTable('genetic', 'id', '=', Input::get('genetic'));
                $geneticInfo = $geneticInfo->results();
                // ...
                $event = $m_mother_UID_text . ' was born in *Mother Room(' . $room_name . ')* From *Mother ID/Seed (' . Input::get('seed') . ')* with ('.$geneticInfo[0] ->genetic_name.') *Observation(' . $observation . ')*';
                $p_general->registerHistoryMother($mother_UID, $user->data()->id, $event, $user->data()->name, $qr_code, $room_name, $note, $observation);
            }
            Redirect::to('../Views/plantsMother.php?room=' . $room_ID);
        } catch (Exception $e) {
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
            $prevLotIDInfo = $p_general->getValueOfAnyTable('plants', 'mother_UID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();            
            $roomList = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            //history
            $event = $lot_ID_text. " waste ".$waste_weightofPlant. " Kg in ". $roomList[0]->name. " room.";
            if(strlen($waste_note) > 0){
                $event = $event. " reason: ".$waste_note;
            }
            $p_general->registerHistoryMother($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code, $room_name, $note);
            // ...

            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsMother.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsMother.php');
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
            // get prev lot info
            $prevLotIDInfo = $p_general->getValueOfAnyTable('plants', 'mother_UID', '=', $lot_ID);
            $prevLotIDInfo = $prevLotIDInfo->results();
            $roomList = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $prevLotIDInfo[0] -> location);
            $roomList = $roomList->results();
            $waste_weight = strval($prevLotIDInfo[0]->waste_weight + 0 + ($del_weightofPlant + 0));
            //history
            $adminInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $admin_id);
            $adminInfo = $adminInfo->results();

            $event = $lot_ID_text. " destroy ". $del_weightofPlant. "Kg in ". $roomList[0]->name. " room. supervisor: ".$adminInfo[0]->name;
            if(strlen($del_note) > 0){
                $event = $event. " reason: ".$del_note;
            }
            // file_put_contents('debug_log.txt', print_r($lot_ID, true),FILE_APPEND | LOCK_EX);
            $p_general->registerHistoryMother($lot_ID, $user->data()->id, $event, $user->data()->name, $prevLotIDInfo[0] -> qr_code , $room_name, $note);
            // ...
            // remove plants
            $result = $p_general->deleteValueOfAnyTable('plants', 'id', '=', $prevLotIDInfo[0]->id);
            $result = $p_general->deleteValueOfAnyTable('index_mother', 'plant_id', '=', $prevLotIDInfo[0]->id);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsMother.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsMother.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    if (Input::get('act') == 'edit') {
        try {
            $flg_save =  0;
            //For history --> Compare value between previous value and update value
            $prevMotherPlantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', Input::get('edit_id'));
            $prevMotherPlantInfo = $prevMotherPlantInfo->results();
            $prevGenetic = $prevMotherPlantInfo[0]->genetic;
            $prevLocation = $prevMotherPlantInfo[0]->location;
            $prevMotherID = $prevMotherPlantInfo[0]->mother_id;
            $prevPlantingDate = $prevMotherPlantInfo[0]->planting_date;
            $prevObservation = $prevMotherPlantInfo[0]->observation;
            $prevMotherUID = $prevMotherPlantInfo[0]->mother_UID;
            $prevMotherUID_text = $p_general->getTextOfMotherUID($prevMotherUID);

            //change genetic 
            if ($prevGenetic != Input::get('edit_genetic')) {
                $flg_save = 1;
                $prevGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $prevGenetic);
                $prevGeneticInfo = $prevGeneticInfo->results();
                $updateGeneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', Input::get('edit_genetic'));
                $updateGeneticInfo = $updateGeneticInfo->results();
                $eventChangeGenetic = $prevMotherUID_text . ' Changed Genetic from *' . $prevGeneticInfo[0]->genetic_name . '* to *' . $updateGeneticInfo[0]->genetic_name . '* </br>';
            }
            // change locaion(room)
            if ($prevLocation != Input::get('edit_location')) {
                $flg_save = 1;
                $prevLocationInfo = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $prevLocation);
                $prevLocationInfo = $prevLocationInfo->results();
                $updateLocationInfo = $p_general->getValueOfAnyTable('room_mother', 'id', '=', Input::get('edit_location'));
                $updateLocationInfo = $updateLocationInfo->results();
                $eventChangeLocation = $prevMotherUID_text . ' Changed Location from *Mother(' . $prevLocationInfo[0]->name . ')* to *Mother(' . $updateLocationInfo[0]->name . ')* </br>';
            }
            // change seed
            if ($prevMotherID != Input::get('edit_seed')) {
                $flg_save = 1;
                $eventChangeMotherID = $prevMotherUID_text . ' Changed Mother ID (Seed) from *' . $prevMotherID . '* to *' . Input::get('edit_seed') . '* </br>';
            }
            // change planting date
            if ($prevPlantingDate != Input::get('edit_planting_date')) {
                $flg_save = 1;
                $eventChangePlantingDate = $prevMotherUID_text . ' Changed Planting Date from *' . $prevPlantingDate . '* to *' . Input::get('edit_planting_date') . '* </br>';
            }
            // change observation
            if ($prevObservation != Input::get('edit_observation')) {
                $flg_save = 1;
                $eventChangeObservation = $prevMotherUID_text . ' Changed Observation from *' . $prevObservation . '* to *' . Input::get('edit_observation') . '*';
            }

            //location = room
            $room_ID = Input::get('edit_location');

            //room date
            $orgDate = Input::get('edit_planting_date');
            $date = str_replace('/', '-', $orgDate);
            $room_date = date("m/d/Y", strtotime($date));

            // Update Plant in Plants Table
            $p_mother->update(array(
                'name'    => Input::get('edit_name'),
                'location'    => $room_ID,
                'planting_date'    => Input::get('edit_planting_date'),
                'mother_text'    => Input::get('edit_seed'),
                'mother_id'    => Input::get('edit_seed'),
                'genetic'    => Input::get('edit_genetic'),
                'observation'    => Input::get('edit_observation'),
                'room_date'    => $room_date,
            ), Input::get('edit_id'));

            // Register Mother Room : Plant in index_mother table
            $PlantQRCode =  Input::get('edit_qr_code');
            if ($room_ID) {
                $p_mother->CreateRelationMotherRoomAndPlant($room_ID, $PlantQRCode);
            }
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $room_ID);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...

            //Register at History
            if($flg_save == 1) {
                $event = $eventChangeGenetic . $eventChangeLocation . $eventChangeMotherID . $eventChangePlantingDate. ": " . $eventChangeObservation;
                $p_general->registerHistoryMother($prevMotherUID, $user->data()->id, $event, $user->data()->name, $PlantQRCode , $room_name, null, Input::get('edit_observation'));
            }
            $showRoom = Input::get('edit_showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsMother.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsMother.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'delete') {
        $plant_id_List = Input::get('idList');
        foreach ($plant_id_List as $plant_id) {
            //Register History
            $deletePlantInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $plant_id);
            $deletePlantInfo = $deletePlantInfo->results();
            $m_mother_UID_text = $p_general->getTextOfMotherUID($deletePlantInfo[0]->mother_UID);
            $event = $m_mother_UID_text . ' destroy in mother room.';
            $p_general->registerHistoryMother($deletePlantInfo[0]->mother_UID, $user->data()->id, $event,$user->data()->name, $deletePlantInfo[0]->qr_code);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('plants', 'id', '=', $plant_id);
            $result = $p_general->deleteValueOfAnyTable('index_mother', 'plant_id', '=', $plant_id);
        }
        echo json_encode("success");
    }

    if (Input::get('act') == 'multi_print') {
        $start_ID = Input::get('start_ID');
        $end_ID = Input::get('end_ID');
        $data = array();

        for ($mother_UID = $start_ID; $mother_UID <= $end_ID; $mother_UID++) {
            //get id of plant according to mother UID
            $PlantInfo = $p_general->getValueOfAnyTable('plants', 'mother_UID', '=', $mother_UID);
            $PlantInfo = $PlantInfo->results();

            //verify exist
            $exist = $p_general->getValueOfAnyTable('index_mother', 'plant_id', '=', $PlantInfo[0]->id);
            $exist = $exist->results();
            if (!$exist) {
                continue;
            }

            $s = [];
            $s[0] = $PlantInfo[0]->qr_code; //data = qr code
            $s[1] = $p_general->getTextOfMotherUID($mother_UID); //filename

            array_push($data, $s);
        }

        echo json_encode($data);
    }

    if (Input::get('act') == 'verifyMotherHaveClonePlants') {
        $motherID = Input::get('motherID');

        // verify the mother have clone plants
        $mClonePlantsList = $p_general->getValueOfAnyTable('plants','mother_id','=',$motherID);
        if($mClonePlantsList->count()){
            echo json_encode("exist");
        }else {
            echo json_encode("NoExist");
        }
        // ...

    }
}
