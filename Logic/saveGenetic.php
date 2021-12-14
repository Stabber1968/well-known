<?php

require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_genetic = new Genetic();

if(Input::exists()) {

    if (Input::get('act') == 'validate'){

        $validateGenetic = Input::get('genetic_name');

        $isSame = $p_genetic->isSameGenetic($validateGenetic);
        if ($isSame){
            echo json_encode('SameGenetic');
        }else{
            echo json_encode($isSame);

        }
    }

    if(Input::get('act_genetic') == 'add'){
        try {
            // Create
            $p_genetic->create(array(
                'genetic_name'	=> Input::get('genetic_name'),
                'plant_name'	=> Input::get('plant_name'),
                'grams'	=> Input::get('grams'),
                'htc'	=> Input::get('htc'),
                'cbd'	=> Input::get('cbd'),
                'photo_clone'	=> Input::get('photo_clone'),
                'photo_veg'	=> Input::get('photo_veg'),
                'photo_flower'	=> Input::get('photo_flower'),
                'other'	=> Input::get('other'),
            ));

            Redirect::to('../Views/genetic.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }


    if(Input::get('act_genetic') == 'edit'){

        try {
            // Update Genetic

            $p_genetic->update(array(
                'genetic_name'	=> Input::get('genetic_name'),
                'plant_name'	=> Input::get('plant_name'),
                'grams'	=> Input::get('grams'),
                'htc'	=> Input::get('htc'),
                'cbd'	=> Input::get('cbd'),
                'photo_clone'	=> Input::get('photo_clone'),
                'photo_veg'	=> Input::get('photo_veg'),
                'photo_flower'	=> Input::get('photo_flower'),
                'other'	=> Input::get('other'),

            ),Input::get('id_genetic'));

            //Update Plant Name at Plants Table
            $p_genetic->updatePlantNameAtPlantsTable(array(
                'name'	=> Input::get('plant_name'),

            ),Input::get('id_genetic'));

            Redirect::to('../Views/genetic.php');

        } catch(Exception $e) {
            die($e->getMessage());
        }
    }


    if (Input::get('act') == 'delete'){
        $genetic_id_List = Input::get('idList');
        foreach($genetic_id_List as $genetic_id){
            $exist = $p_genetic->getValueOfAnyTable('plants','genetic','=',$genetic_id);
            $exist = $exist->count();
            if($exist){
                break;
            }else{
                $result = $p_genetic->deleteValueOfAnyTable('genetic','id','=',$genetic_id);
            }
        }
        if($exist){
            echo json_encode('exist');
        }else{
            echo json_encode("success");
        }
    }

    if (Input::get('act') == 'getPlantName'){
        $selectedGeneticID = Input::get('selectedGeneticID');
        $selectedGeneticInfo = $p_genetic->getValueOfAnyTable('genetic','id','=',$selectedGeneticID);
        $selectedGeneticInfo = $selectedGeneticInfo->results();
        $plant_name = $selectedGeneticInfo[0]->plant_name;
        echo json_encode($plant_name);
    }
}


