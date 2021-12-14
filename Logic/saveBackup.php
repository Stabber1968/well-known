<?php
require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

if(Input::exists()) {
    try {
        $exist = $p_general->getValueOfAnyTable('backup','1','=','1');
        $exist = $exist->results();
        if($exist){
            $p_general->updateValueOfAnyTable('backup', array(
                'send_email'	=> Input::get('email'),
                'smtp_host'	=> Input::get('smtp_host'),
                'smtp_encryption'	=> Input::get('smtp_encryption'),
                'smtp_port'	=> Input::get('smtp_port'),
                'smtp_username'	=> Input::get('smtp_username'),
                'smtp_password'	=> Input::get('smtp_password'),
            ), '1');
        }

        Redirect::to('../Views/backup.php');
    } catch(Exception $e) {
        die($e->getMessage());
    }

}


