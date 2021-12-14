<?php
require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$pDryMethod = new DryMethod();

$act = $_POST['act'];
if($act == 'delete') {
    $dryMethod_id_List = Input::get('idList');
    foreach($dryMethod_id_List as $dryMethod_id){
        $exist = $p_general->getValueOfAnyTable('plants','dry_method','=',$dryMethod_id);
        $exist = $exist->count();
        if($exist){
            break;
        }else{
            $result = $p_general->deleteValueOfAnyTable('dry_method','id','=',$dryMethod_id);
        }
    }
    if($exist){
        echo json_encode('exist');
    }else{
        echo json_encode("success");
    }
}

if(Input::exists()) {
    if (Input::get('act') == 'validate'){
        $validateName = Input::get('name');
        $isSame = $pDryMethod->isSame($validateName);
        if ($isSame){
            echo json_encode('SameName');
        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act_dryMethod') == 'add'){
        try {
            $pDryMethod->create(array(
                'name'	=> Input::get('name_dryMethod'),
            ));
            Redirect::to('../Views/genetic.php?page=drymethod');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act_dryMethod') == 'edit'){
        try {
            $pDryMethod->update(array(
                'name'	=> Input::get('name_dryMethod'),
            ), Input::get('id_dryMethod'));

            Redirect::to('../Views/genetic.php?page=drymethod');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}


