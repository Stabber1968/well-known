<?php

$pUser = new User();
if(!$pUser->isLoggedIn())
{
    header('location:../Logic/login.php?lmsg=true');
    exit;
}


$mUserInfo = $pUser->data();
$mUserLang = $mUserInfo->language;

// Global value
$_SESSION['label'] = 'Weez';


if($mUserLang == "English"){

    $_SESSION['lang_Dasboard'] = 'Dasboard';
    $_SESSION['lang_Grow_Room'] = 'Grow Room';
    $_SESSION['lang_Mother_Plants'] = 'Mother Plants';
    $_SESSION['lang_Clone_Plants'] = 'Clone Plants';
    $_SESSION['lang_Vegetation_Plants'] = 'Vegetation Plants';
    $_SESSION['lang_Flower_Plants'] = 'Flower Plants';
    $_SESSION['lang_Dry_Plants'] = 'Drying';
    $_SESSION['lang_Trimming_Plants'] = 'Trimming';
    $_SESSION['lang_Packing'] = 'Packing';
    $_SESSION['lang_Vault'] = 'Vault';
    $_SESSION['lang_Havests'] = 'Harvest';
    $_SESSION['lang_Sales'] = 'Sales';
    $_SESSION['lang_History'] = 'History';
    $_SESSION['lang_Client'] = 'Client';
    $_SESSION['lang_Settings'] = 'Settings';
    $_SESSION['lang_Genetic'] = 'Genetic';
    $_SESSION['lang_User_Management'] = 'User Management';
    $_SESSION['lang_User_Permissions'] = 'User Permissions';
    $_SESSION['lang_Log_out'] = 'Log out';
    $_SESSION['lang_backup'] = 'Backup & SMTP';
    $_SESSION['lang_Trimming_method'] = 'Trimming Method';
    $_SESSION['lang_packing_number'] = 'Package ID'; // new

}elseif($mUserLang == "Portgual"){

    $_SESSION['lang_Dasboard'] = 'painel de controle';
    $_SESSION['lang_Grow_Room'] = 'Salas de Plantação';
    $_SESSION['lang_Mother_Plants'] = 'Plantas Mãe';
    $_SESSION['lang_Clone_Plants'] = 'Plantas clone';
    $_SESSION['lang_Vegetation_Plants'] = 'Plantas Vegetação';
    $_SESSION['lang_Flower_Plants'] = 'Plantas Floração';
    $_SESSION['lang_Dry_Plants'] = 'Corte e Secagem';
    $_SESSION['lang_Trimming_Plants'] = 'Embalamento';
    $_SESSION['lang_Packing'] = 'Packing';
    $_SESSION['lang_Vault'] = 'Cofre';
    $_SESSION['lang_Havests'] = 'Colheita';
    $_SESSION['lang_Sales'] = 'Vendas';
    $_SESSION['lang_History'] = 'História';
    $_SESSION['lang_Client'] = 'Cliente';
    $_SESSION['lang_Settings'] = 'Configurações';
    $_SESSION['lang_Genetic'] = 'Genética';
    $_SESSION['lang_User_Management'] = 'Gerenciamento de usuários';
    $_SESSION['lang_User_Permissions'] = 'Permissões do usuário';
    $_SESSION['lang_Log_out'] = 'Sair';
    $_SESSION['lang_backup'] = 'Backup & SMTP';
    $_SESSION['lang_Trimming_method'] = 'Embalamento Método';
}

