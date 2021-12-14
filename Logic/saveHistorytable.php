<?php

require_once('../Controllers/init.php');

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}
$p_general = new General();
$act = $_GET['act'];
if($act == 'getPlantUIDfromTxt') {
    $value = $_GET['argument'];
    $plantid = $p_general->getPlantUIDfromTxt($value);
    echo strval($plantid);
}
if($act == 'getLotIDfromTxt') {
    $value = $_GET['argument'];
    $plantid = $p_general->getLotIDfromtxt($value);
    echo strval($plantid);
}
