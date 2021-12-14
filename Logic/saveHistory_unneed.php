<?php

require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$history = new History();


if(Input::exists()) {

    if (Input::get('act') == 'get'){
        $plant_id = Input::get('id');

        $historyList = $p_general->getValueOfAnyTable('history','plant_id','=',$plant_id);
        $historyList = $historyList->results();
        echo json_encode($historyList);

    }



}


