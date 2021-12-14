<?php
require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_client = new Client();

$act = $_GET['act'];
if($act == 'delete') {

    try {
        $existAtSell = $p_general->getValueOfAnyTable('sell','client','=',Input::get('id'));
        $existAtSell = $existAtSell->count();

        if($existAtSell){

        }else{
            $p_client->delete(Input::get('id'));
        }

        Redirect::to('../Views/client.php');
    }catch(Exception $e) {
        die($e->getMessage());
    }
}

if(Input::exists()) {

    if (Input::get('act') == 'validate'){

        $validateName = Input::get('name');
        $isSame = $p_client->isSame($validateName);
        if ($isSame){

            echo json_encode('SameName');

        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act') == 'add'){

        try {

            // Create Plant
            $p_client->create(array(
                'name'	=> Input::get('name'),
            ));


            Redirect::to('../Views/client.php');

        } catch(Exception $e) {
            die($e->getMessage());
        }
    }


    if(Input::get('act') == 'edit'){

        try {

            $p_client->update(array(
                'name'	=> Input::get('name'),
            ), Input::get('id'));

            Redirect::to('../Views/client.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

}


