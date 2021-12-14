<?php
require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$r_packing = new PackingRoom();

$act = $_GET['act'];
if($act == 'delete') {
    try {
        $exist = $p_general->getValueOfAnyTable('index_packing','room_id','=',Input::get('id'));
        if($exist->count()){
            echo "<script type='text/javascript'>alert('Can not delete because of the room is used');window.location.href='../Views/roomsPacking.php';</script>";
        }else{
            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_packing','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $roomName = $room_info[0]->name;
            $event = "Packing Room *".$roomName."* is deleted";
//            $p_general->registerHistoryRoom(Input::get('id'),$user->data()->id,$event);
            $r_packing->delete(Input::get('id'));
            Redirect::to('../Views/roomsPacking.php');
        }
    }catch(Exception $e) {
        die($e->getMessage());
    }
}

if(Input::exists()) {
    if (Input::get('act') == 'validate'){
        $validateName = Input::get('name');
        $isSame = $r_packing->isSame($validateName);
        if ($isSame){
            echo json_encode('SameName');
        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act') == 'add'){
        try {
            // Create Plant
            $r_packing->create(array(
                'name'	=> Input::get('name'),
            ));
            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_packing','name','=',Input::get('name'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->id;
            $event = "Packing Room *".Input::get('name')."* is created";
            // $p_general->registerHistoryRoom(Input::get('name'),$user->data()->id,$event);
            Redirect::to('../Views/roomsPacking.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }


    if(Input::get('act') == 'edit'){
        try {
            //for history
            $room_info = $p_general->getValueOfAnyTable('room_packing','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->name;
            $r_packing->update(array(
                'name'	=> Input::get('name'),
            ), Input::get('id'));
            //Register at History
            $event = "Packing room name *".$prevRoomName."* is changed to *".Input::get('name')."*";
//            $p_general->registerHistoryRoom(Input::get('id'),$user->data()->id,$event);
            // replace mother room name.
            $r_packing->updateContentAnyValueOfAnyTable('history','event','Packing('.$prevRoomName.')','Packing('.Input::get('name').')');
            Redirect::to('../Views/roomsPacking.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

}


