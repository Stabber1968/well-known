<?php
require_once('../Controllers/init.php');

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$r_mother = new MotherRoom();

$act = $_GET['act'];
if ($act == 'delete') {

    try {

        $exist = $p_general->getValueOfAnyTable('index_mother', 'room_id', '=', Input::get('id'));
        if ($exist->count()) {
            echo "<script type='text/javascript'>alert('Can not delete because of the room is used');window.location.href='../Views/roomsMother.php';</script>";
        } else {

            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_mother', 'id', '=', Input::get('id'));
            $room_info = $room_info->results();
            $roomName = $room_info[0]->name;

            $event = "Mother Room *" . $roomName . "* is deleted";
            $r_mother->registerHistoryRoom(Input::get('id'), $user->data()->id, $event);


            $r_mother->delete(Input::get('id'));
            Redirect::to('../Views/roomsMother.php');
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

if (Input::exists()) {

    if (Input::get('act') == 'validate') {

        $validateName = Input::get('name');
        $isSame = $r_mother->isSame($validateName);
        if ($isSame) {
            echo json_encode('SameName');
        } else {
            echo json_encode($isSame);
        }
    }

    if (Input::get('act') == 'add') {
        try {
            // Create Plant
            $p_general->createValueOfAnyTable('room_mother', array(
                'name'    => Input::get('name'),
            ));
            //Register at History
            $room_info = $p_general->getValueOfAnyTable('room_mother', 'name', '=', Input::get('name'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->id;

            $event = "Mother Room *" . Input::get('name') . "* is created";
            // $p_general->registerHistoryRoom(Input::get('name'),$user->data()->id,$event);

            Redirect::to('../Views/roomsMother.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    if (Input::get('act') == 'edit') {

        try {
            //for history
            $room_info = $p_general->getValueOfAnyTable('room_mother', 'id', '=', Input::get('id'));
            $room_info = $room_info->results();
            $prevRoomName = $room_info[0]->name;

            $r_mother->update(array(
                'name'    => Input::get('name'),
            ), Input::get('id'));

            //Register at History
            $event = "Mother room name *" . $prevRoomName . "* is changed to *" . Input::get('name') . "*";
            //                $p_general->registerHistoryRoom(Input::get('id'),$user->data()->id,$event);

            // replace mother room name.
            $r_mother->updateContentAnyValueOfAnyTable('history', 'event', 'Mother(' . $prevRoomName . ')', 'Mother(' . Input::get('name') . ')');

            Redirect::to('../Views/roomsMother.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
