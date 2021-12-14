<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');


$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}
require_once('layout/header.php');

if($_POST['plant_type'] == 'mother'){
    $target = "mother_UID";
}
if($_POST['plant_type'] == 'plant'){
    $target = "plant_UID";
}
if($_POST['plant_type'] == 'lot'){
    $target = "lot_ID";
}
if($_POST['plant_type'] == 'plant_packed'){
    $target = "plant_UID";
}

$start_ID = $_POST['start_ID'];
$end_ID = $_POST['end_ID'];
$location = $_POST['location'];

$pMother = new MotherPlant();


?>
<div class="row" style="padding: 30px">
    <button class="btn btn-primary" style="width: 300px" type="button" onclick="printJS({ printable: 'printJS-form', type: 'html',css: '../dist/css/print.css'})">
        Print
    </button>
</div>

<form method="post" action="#" id="printJS-form" >

    <?php
    $k = 0;
    for($i = $start_ID;$i<=$end_ID;$i++ ){
        if($_POST['plant_type'] == 'lot'){
            $lot_ID_info = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$i);
            $lot_ID_info = $lot_ID_info->results();
            $exist = $p_general->getValueOfAnyTable($location,'lot_id','=',$i);
            if(!$exist->count()){
                continue;
            }
            $geneticInfo = $p_general->getValueOfAnyTable('genetic','id','=',$lot_ID_info[0]->genetic_ID);
            $geneticInfo = $geneticInfo->results();
            $name =  $geneticInfo[0]->plant_name;
        }else{
            $plantInfo = $p_general->getValueOfAnyTable('plants',$target,'=',$i);
            $plantInfo = $plantInfo->results();
            $exist = $p_general->getValueOfAnyTable($location,'plant_id','=',$plantInfo[0]->id);
            if(!$exist->count()){
                continue;
            }
            $name =  $plantInfo[0]->name;
        }
        $k++;
        if($k%3 == 1){
            echo '<div  style="width: 100%">';
        }
        ?>
        <div class="printBorder">
            <div class="print_body">
                <?php
                if($_POST['plant_type'] == 'mother'){
                    $plant_text = $p_general->getTextOfMotherUID($plantInfo[0]->mother_UID);
                    $mother_UID_text = $plantInfo[0]->mother_id;
                    $date = $plantInfo[0]->planting_date;
                    $qr_code = $plantInfo[0]->qr_code;
                }elseif($_POST['plant_type'] == 'plant'){
                    $plant_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                    $motherInfo = $p_general->getValueOfAnyTable('plants','id','=',$plantInfo[0]->mother_id);
                    $motherInfo = $motherInfo->results();
                    $mother_UID = $motherInfo[0]->mother_UID;
                    $mother_UID_text = $p_general->getTextOfMotherUID($mother_UID);
                    $date = $plantInfo[0]->planting_date;
                    $qr_code = $plantInfo[0]->qr_code;
                }elseif($_POST['plant_type'] == 'lot'){
                    $plant_text = $p_general->getTextOflotID($i);
                    $motherInfo = $p_general->getValueOfAnyTable('plants','id','=',$lot_ID_info[0]->mother_ID);
                    $motherInfo = $motherInfo->results();
                    $mother_UID = $motherInfo[0]->mother_UID;
                    $mother_UID_text = $p_general->getTextOfMotherUID($mother_UID);
                    $date = $lot_ID_info[0]->born_date;
                    $qr_code = $lot_ID_info[0]->qr_code;
                }elseif($_POST['plant_type'] == 'plant_packed'){
                    $plant_text = $p_general->getTextOfPlantUID($plantInfo[0]->plant_UID);
                    $motherInfo = $p_general->getValueOfAnyTable('plants','id','=',$plantInfo[0]->mother_id);
                    $motherInfo = $motherInfo->results();
                    $mother_UID = $motherInfo[0]->mother_UID;
                    $mother_UID_text = $p_general->getTextOfMotherUID($mother_UID);
                    $date = $plantInfo[0]->planting_date;
                    $lot_ID = $plantInfo[0]->lot_ID;
                    $lot_ID_text = $p_general->getTextOflotID($lot_ID);
                    $qr_code = $lot_ID_text;
                }
                ?>
                <div class="print_qr"  id="print_qr" >
                    <img src="../QR_Code/<?=$plant_text?>.png" height="124px">
                </div>
                <div class="print_info">
                    <div class="title_font">
                        <div class="print_name" id="print_name"><?=$name?></div>
                    </div>
                    <div class="plant_id_font" id="print_id"><?php echo $_SESSION['label'].'-'.$plant_text?></div>
                    <?php if($_POST['plant_type'] != 'lot'){
                        ?>
                        <div class="print_mother_id" id="print_mother_id"><?=$mother_UID_text?></div>
                        <?php
                    }?>
                    <div class="print_date" id="print_date"><?=$date?></div>
                    <div class="print_qr_code" id="print_qr_code"><?=$qr_code?></div>
                </div>
            </div>
        </div>
        <?php
        if($i != $end_ID){
            ?>
            <div style="height: 39px"></div>
            <?php
        }
        ?>
        <?php
        if($k%3 == 0){
            echo '</div>';
        }

    }
    ?>
</form>


