<?php

require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$pUser = new User();
if(!$pUser->isLoggedIn())
{
	header('location:../Logic/login.php?lmsg=true');
	exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

if($pUser->data()->superAdmin == '1'){
	require_once('contentAdmin.php');
}else{
	require_once('contentUser.php');
}

require_once('layout/sidebar.php');
require_once('layout/footer.php');
