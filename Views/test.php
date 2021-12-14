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
require_once('layout/navbar.php');

$rVeg = new VegRoom();
$mVegRoomList = $rVeg->getAllOfInfo();
$mVegRoomList = $mVegRoomList->results();

$pVeg = new VegPlant();
if($_GET['room']){
    $vegRoomID = $_GET['room'];
    $plantsIDList = $pVeg->getPlantsListFromVegRoomID($vegRoomID);
}else{
    $plantsIDList = $pVeg->getValueOfAnyTable('index_veg','1','=','1','plant_id');
    $plantsIDList = $plantsIDList->results();
}

// For search "p" is id of plant, not plant UID
if($_GET['p']){
    $searchPlantID = $_GET['p'];
    foreach($plantsIDList as $plant){
        $tmp_plantsIDList = array();
        if($plant->plant_id == $searchPlantID){
            array_push($tmp_plantsIDList,$plant);
            break;
        }
    }
    $plantsIDList = $tmp_plantsIDList;
}

$mLotList = $pVeg->getValueOfAnyTable('lot_id','1','=','1');
$mLotList = $mLotList->results();
if($_GET['lot']){
    $lot_ID = $_GET['lot'];
}else{
    $lot_ID = '0';
}

$k = 0;
?>

<div class="content-wrapper">
    register
</div>

<script>



</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
