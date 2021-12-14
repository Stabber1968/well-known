<?php
require_once('../Controllers/init.php');

$user = new User();
if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$pTrimmingMethod = new TrimmingMethod();

$act = $_POST['act'];
if($act == 'delete') {
    $trimmingMethod_id_List = Input::get('idList');
    foreach($trimmingMethod_id_List as $trimmingMethod_id){
        $exist = $p_general->getValueOfAnyTable('vault','trimming_method','=',$trimmingMethod_id);
        $exist = $exist->count();
        if($exist){
            break;
        }else{
            $result = $p_general->deleteValueOfAnyTable('trimming_method','id','=',$trimmingMethod_id);
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
        $isSame = $pTrimmingMethod->isSame($validateName);
        if ($isSame){
            echo json_encode('SameName');
        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act_trimmingMethod') == 'add'){
        try {
            $pTrimmingMethod->create(array(
                'name'	=> Input::get('name_trimmingMethod'),
            ));
            Redirect::to('../Views/genetic.php?page=trimmingmethod');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act_trimmingMethod') == 'edit'){
        try {
            $pTrimmingMethod->update(array(
                'name'	=> Input::get('name_trimmingMethod'),
            ), Input::get('id_trimmingMethod'));

            Redirect::to('../Views/genetic.php?page=trimmingmethod');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}


