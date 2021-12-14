<?php
require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$r_trimming = new TrimmingRoom();

$act = $_GET['act'];
if($act == 'delete') {

    try {

        $exist = $p_general->getValueOfAnyTable('index_trimming','room_id','=',Input::get('id'));

        if($exist->count()){
            echo "<script type='text/javascript'>alert('Can not delete because of the room is used');window.location.href='../Views/roomsTrimming.php';</script>";

        }else{

            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_trimming','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $roomName = $room_info[0]->name;

            $event = "Trimming Room *".$roomName."* is deleted";
//            $p_general->registerHistoryRoom(Input::get('id'),$user->data()->id,$event);


            $r_trimming->delete(Input::get('id'));
            Redirect::to('../Views/roomsTrimming.php');
        }



    }catch(Exception $e) {
        die($e->getMessage());
    }
}

if(Input::exists()) {

    if (Input::get('act') == 'validate'){

        $validateName = Input::get('name');
        $isSame = $r_trimming->isSame($validateName);
        if ($isSame){

            echo json_encode('SameName');

        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act') == 'add'){

        try {

            // Create Plant
            $r_trimming->create(array(
                'name'	=> Input::get('name'),
            ));

            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_trimming','name','=',Input::get('name'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->id;

            $event = "Trimming Room *".Input::get('name')."* is created";
            // $p_general->registerHistoryRoom(Input::get('name'),$user->data()->id,$event);

            Redirect::to('../Views/roomsTrimming.php');

        } catch(Exception $e) {
            die($e->getMessage());
        }
    }


    if(Input::get('act') == 'edit'){

        try {

            //for history
            $room_info = $p_general->getValueOfAnyTable('room_trimming','id','=',Input::get('id'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->name;

            $r_trimming->update(array(
                'name'	=> Input::get('name'),
            ), Input::get('id'));

            //Register at History
            $event = "Trimming room name *".$prevRoomName."* is changed to *".Input::get('name')."*";
//            $p_general->registerHistoryRoom(Input::get('id'),$user->data()->id,$event);

            // replace mother room name.
            $r_trimming->updateContentAnyValueOfAnyTable('history','event','Trimming('.$prevRoomName.')','Trimming('.Input::get('name').')');

            Redirect::to('../Views/roomsTrimming.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

}


