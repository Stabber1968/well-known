<?php

require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_dashboard = new Dashboard();



if(Input::exists()) {

    if (Input::get('act') == 'get_monthly_plant'){
        $room_name = Input::get('room');
        $current_Year = Input::get('current_year');
        $last_Year = $current_Year - 1 ;

        $monthly_mother_plants_current_year = array();
        $monthly_mother_plants_current_year = array_fill(0,13,0);
        $monthly_mother_plants_last_year = array();
        $monthly_mother_plants_last_year = array_fill(0,13,0);


        $total_mother_plants_ID_info = $p_general->getValueOfAnyTable('index_'.$room_name,'1','=','1');
        $total_mother_plants_ID_info = $total_mother_plants_ID_info->results();
        foreach($total_mother_plants_ID_info as $plant_ID_Info){
            $plantInfo = $p_general->getValueOfAnyTable('lot_id','lot_id','=',$plant_ID_Info->lot_id);
            $plantInfo = $plantInfo->results();
            $plantDate = $plantInfo[0]->born_date;
            $d = date_parse_from_format("d/m/Y", $plantDate);
            $plant_date_month = $d["month"];
            $plant_date_year = $d["year"];
            $plant_count = $plantInfo[0]->number_of_plants;
            if($plant_date_year == $current_Year){
                switch ($plant_date_month){
                    case 1:
                        $monthly_mother_plants_current_year['1'] += $plant_count;
                        break;
                    case 2:
                        $monthly_mother_plants_current_year['2'] += $plant_count;
                        break;
                    case 3:
                        $monthly_mother_plants_current_year['3'] += $plant_count;
                        break;
                    case 4:
                        $monthly_mother_plants_current_year['4'] += $plant_count;
                        break;
                    case 5:
                        $monthly_mother_plants_current_year['5'] += $plant_count;
                        break;
                    case 6:
                        $monthly_mother_plants_current_year['6'] += $plant_count;
                        break;
                    case 7:
                        $monthly_mother_plants_current_year['7'] += $plant_count;
                        break;
                    case 8:
                        $monthly_mother_plants_current_year['8'] += $plant_count;
                        break;
                    case 9:
                        $monthly_mother_plants_current_year['9'] += $plant_count;
                        break;
                    case 10:
                        $monthly_mother_plants_current_year['10'] += $plant_count;
                        break;
                    case 11:
                        $monthly_mother_plants_current_year['11'] += $plant_count;
                        break;
                    case 12:
                        $monthly_mother_plants_current_year['12'] += $plant_count;
                        break;

                }
            }
            if($plant_date_year == $last_Year){

                switch ($plant_date_month){
                    case 1:
                        $monthly_mother_plants_last_year['1'] += $plant_count;
                        break;
                    case 2:
                        $monthly_mother_plants_last_year['2'] += $plant_count;
                        break;
                    case 3:
                        $monthly_mother_plants_last_year['3'] += $plant_count;
                        break;
                    case 4:
                        $monthly_mother_plants_last_year['4'] += $plant_count;
                        break;
                    case 5:
                        $monthly_mother_plants_last_year['5'] += $plant_count;
                        break;
                    case 6:
                        $monthly_mother_plants_last_year['6'] += $plant_count;
                        break;
                    case 7:
                        $monthly_mother_plants_last_year['7'] += $plant_count;
                        break;
                    case 8:
                        $monthly_mother_plants_last_year['8'] += $plant_count;
                        break;
                    case 9:
                        $monthly_mother_plants_last_year['9'] += $plant_count;
                        break;
                    case 10:
                        $monthly_mother_plants_last_year['10'] += $plant_count;
                        break;
                    case 11:
                        $monthly_mother_plants_last_year['11'] += $plant_count;
                        break;
                    case 12:
                        $monthly_mother_plants_last_year['12'] += $plant_count;
                        break;

                }
            }
        }
        $result = array();
        $result[0] = $monthly_mother_plants_current_year;
        $result[1] = $monthly_mother_plants_last_year;

        echo json_encode($result);

    }
    if (Input::get('act') == 'get_monthly_plant_mother'){
        $room_name = Input::get('room');
        $current_Year = Input::get('current_year');
        $last_Year = $current_Year - 1 ;

        $monthly_mother_plants_current_year = array();
        $monthly_mother_plants_current_year = array_fill(0,13,0);
        $monthly_mother_plants_last_year = array();
        $monthly_mother_plants_last_year = array_fill(0,13,0);


        $total_mother_plants_ID_info = $p_general->getValueOfAnyTable('index_'.$room_name,'1','=','1');
        $total_mother_plants_ID_info = $total_mother_plants_ID_info->results();

        foreach($total_mother_plants_ID_info as $plant_ID_Info){
            $plantInfo = $p_general->getValueOfAnyTable('plants','id','=',$plant_ID_Info->plant_id);
            $plantInfo = $plantInfo->results();

            $plantDate = $plantInfo[0]->planting_date;
            $d = date_parse_from_format("d/m/Y", $plantDate);
            $plant_date_month = $d["month"];
            $plant_date_year = $d["year"];

            if($plant_date_year == $current_Year){
                switch ($plant_date_month){
                    case 1:
                        $monthly_mother_plants_current_year['1'] += 1;
                        break;
                    case 2:
                        $monthly_mother_plants_current_year['2'] += 1;
                        break;
                    case 3:
                        $monthly_mother_plants_current_year['3'] += 1;
                        break;
                    case 4:
                        $monthly_mother_plants_current_year['4'] += 1;
                        break;
                    case 5:
                        $monthly_mother_plants_current_year['5'] += 1;
                        break;
                    case 6:
                        $monthly_mother_plants_current_year['6'] += 1;
                        break;
                    case 7:
                        $monthly_mother_plants_current_year['7'] += 1;
                        break;
                    case 8:
                        $monthly_mother_plants_current_year['8'] += 1;
                        break;
                    case 9:
                        $monthly_mother_plants_current_year['9'] += 1;
                        break;
                    case 10:
                        $monthly_mother_plants_current_year['10'] += 1;
                        break;
                    case 11:
                        $monthly_mother_plants_current_year['11'] += 1;
                        break;
                    case 12:
                        $monthly_mother_plants_current_year['12'] += 1;
                        break;

                }
            }
            if($plant_date_year == $last_Year){

                switch ($plant_date_month){
                    case 1:
                        $monthly_mother_plants_last_year['1'] += 1;
                        break;
                    case 2:
                        $monthly_mother_plants_last_year['2'] += 1;
                        break;
                    case 3:
                        $monthly_mother_plants_last_year['3'] += 1;
                        break;
                    case 4:
                        $monthly_mother_plants_last_year['4'] += 1;
                        break;
                    case 5:
                        $monthly_mother_plants_last_year['5'] += 1;
                        break;
                    case 6:
                        $monthly_mother_plants_last_year['6'] += 1;
                        break;
                    case 7:
                        $monthly_mother_plants_last_year['7'] += 1;
                        break;
                    case 8:
                        $monthly_mother_plants_last_year['8'] += 1;
                        break;
                    case 9:
                        $monthly_mother_plants_last_year['9'] += 1;
                        break;
                    case 10:
                        $monthly_mother_plants_last_year['10'] += 1;
                        break;
                    case 11:
                        $monthly_mother_plants_last_year['11'] += 1;
                        break;
                    case 12:
                        $monthly_mother_plants_last_year['12'] += 1;
                        break;

                }
            }
        }

        $result = array();
        $result[0] = $monthly_mother_plants_current_year;
        $result[1] = $monthly_mother_plants_last_year;

        echo json_encode($result);

    }

    if(Input::get('act') == 'get_genetic_mother_room'){
        $room_name = Input::get('room');
        $current_year = Input::get('current_year');

        $genetic_list = $p_general->getValueOfAnyTable('genetic','1','=','1');
        $genetic_list = $genetic_list->results();


        $plants_ID_list = $p_general->getValueOfAnyTable('index_'.$room_name,'1','=','1');
        $plants_ID_list = $plants_ID_list->results();


        $plant_count_list = array();
        $result = array();

        foreach($plants_ID_list as $plant_ID){
            $plantInfo = $p_general->getValueOfAnyTable('plants','id','=',$plant_ID->plant_id);
            $plantInfo = $plantInfo->results();

            $plant_genetic_ID = $plantInfo[0]->genetic;

            foreach($genetic_list as $genetic){

                if($genetic->id == $plant_genetic_ID){
                    $plant_count_list[$genetic->genetic_name] += 1;
                }
            }
        }

        foreach($genetic_list as $genetic){
            if($plant_count_list[$genetic->genetic_name]){
                $array = array();
                $array['genetic_name'] = $genetic->genetic_name;
                $array['count_plants'] = $plant_count_list[$genetic->genetic_name];
                array_push($result,$array);
            }
        }

        echo json_encode($result);
    }

    if (Input::get('act') == 'get_genetic_room') {
        $room_name = Input::get('room');
        $current_year = Input::get('current_year');

        $genetic_list = $p_general->getValueOfAnyTable('genetic', '1', '=', '1');
        $genetic_list = $genetic_list->results();

        $indexClonlist = $p_general->getValueOfAnyTable('index_' . $room_name, '1', '=', '1');
        $indexClonlist = $indexClonlist->results();


        $plant_count_list = array();
        $result = array();

        foreach ($indexClonlist as $item) {
            $lotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $item->lot_id);
            $lotIDInfo = $lotIDInfo->results();

            $plant_genetic_ID = $lotIDInfo[0]->genetic_ID;

            foreach ($genetic_list as $genetic) {
                if ($genetic->id == $plant_genetic_ID) {
                    $plant_count_list[$genetic->genetic_name] += $lotIDInfo[0]->number_of_plants;
                }
            }
        }

        foreach ($genetic_list as $genetic) {
            if ($plant_count_list[$genetic->genetic_name]) {
                $array = array();
                $array['genetic_name'] = $genetic->genetic_name;
                $array['count_plants'] = $plant_count_list[$genetic->genetic_name];
                array_push($result, $array);
            }
        }

        echo json_encode($result);
    }



}


