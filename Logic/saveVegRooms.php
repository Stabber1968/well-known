<?php
require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
$r_veg = new VegRoom();

$act = $_GET['act'];
if($act == 'delete') {
    try {
        $exist = $p_general->getValueOfAnyTable('index_veg','room_id','=',Input::get('id'));
        if($exist->count()){
            echo "<script type='text/javascript'>alert('Can not delete because of the room is used');window.location.href='../Views/roomsVeg.php';</script>";
        }else{
            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_veg','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $roomName = $room_info[0]->name;
            $event = "Vegetation Room *".$roomName."* is deleted";
            $r_veg->delete(Input::get('id'));
            Redirect::to('../Views/roomsVeg.php');
        }
    }catch(Exception $e) {
        die($e->getMessage());
    }
}

if(Input::exists()) {
    if (Input::get('act') == 'validate'){
        $validateName = Input::get('name');
        $isSame = $r_veg->isSame($validateName);
        if ($isSame){
            echo json_encode('SameName');
        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act') == 'add'){
        try {
            // Create Plant
            $r_veg->create(array(
                'name'	=> Input::get('name'),
            ));
            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_veg','name','=',Input::get('name'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->id;
            $event = "Vegetation Room *".Input::get('name')."* is created";
            Redirect::to('../Views/roomsVeg.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act') == 'edit'){
        try {
            //for history
            $room_info = $p_general->getValueOfAnyTable('room_veg','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->name;
            $r_veg->update(array(
                'name'	=> Input::get('name'),
            ), Input::get('id'));
            //Register at History
            $event = "Vegetation room name *".$prevRoomName."* is changed to *".Input::get('name')."*";
            // replace mother room name.
            $r_veg->updateContentAnyValueOfAnyTable('history','event','Vegetation('.$prevRoomName.')','Vegetation('.Input::get('name').')');
            Redirect::to('../Views/roomsVeg.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

}


