<?php
require_once('../Controllers/init.php');

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$act = $_GET['act'];
if($act == 'delete') {

    if($_GET['cat'] == "permissions"){
        try {
            $exist = $p_general->getValueOfAnyTable('users','permissions_id','=',Input::get('id'));
            if($exist->count()){
                echo "<script type='text/javascript'>alert('Can not delete because of the permissions is used');window.location.href='../Views/userPermissions.php';</script>";

            }else{
                $p_general->deleteValueOfAnyTable('user_permissions','id','=',Input::get('id'));
                Redirect::to('../Views/userPermissions.php');
            }
        }catch(Exception $e) {
            die($e->getMessage());
        }
    }else{
        try {
            $user->delete(Input::get('id'));
            Redirect::to('../Views/user.php');

        }catch(Exception $e) {
            die($e->getMessage());
        }
    }

}

if(Input::exists()) {

    if (Input::get('act') == 'validate'){

        $validateEmail = Input::get('email');
        $isSame = $user->isSame($validateEmail);
        if ($isSame){

            echo json_encode('SameEmail');

        }else{
            echo json_encode($isSame);
        }
    }

    if(Input::get('act') == 'add'){
        try {
            $user->create(array(
                'name'	=> Input::get('name'),
                'email' => Input::get('email'),
                'password'	=> md5(Input::get('password')),
                'permissions_id' => Input::get('permissions_id'),
                'language' => Input::get('language'),
            ));

            Session::flash('home', 'You have been registered and can now log in!');
            Redirect::to('../Views/user.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act') == 'edit'){

        try {
            $data = $user->find(Input::get('id'))->first();

            if(Input::get('password') == $data->password ){
                $password = Input::get('password');
            }else{
                $password = md5(Input::get('password'));
            }

            $user->update(array(
                'name'	=> Input::get('name'),
                'email' => Input::get('email'),
                'password'	=> $password,
                'permissions_id' => Input::get('permissions_id'),
                'language' => Input::get('language'),

            ), Input::get('id'));

            Redirect::to('../Views/user.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act') == 'addUserPermissions'){
        try {
            $name = Input::get('name');
            $p_general->createValueOfAnyTable('user_permissions',array(
                'name'	=> $name,
                'mother'	=> Input::get('checkPermissions_mother'),
                'clone'	=> Input::get('checkPermissions_clone'),
                'veg'	=> Input::get('checkPermissions_veg'),
                'flower'	=> Input::get('checkPermissions_flower'),
                'dry'	=> Input::get('checkPermissions_dry'),
                'trimming'	=> Input::get('checkPermissions_trimming'),
                'packing'	=> Input::get('checkPermissions_packing'),
                'vault'	=> Input::get('checkPermissions_vault'),
                'sell'	=> Input::get('checkPermissions_sell'),
                'history'	=> Input::get('checkPermissions_history'),
                'client'	=> Input::get('checkPermissions_client'),
                'setting'	=> Input::get('checkPermissions_setting'),
                'genetic'	=> Input::get('checkPermissions_genetic'),
                'user'	=> Input::get('checkPermissions_user'),
            ));
            Redirect::to('../Views/userPermissions.php');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if(Input::get('act') == 'editPermissions'){
        try {
            $name = Input::get('name');
            $p_general->updateValueOfAnyTable('user_permissions',array(
                'name'	=> $name,
                'mother'	=> Input::get('checkPermissions_mother'),
                'clone'	=> Input::get('checkPermissions_clone'),
                'veg'	=> Input::get('checkPermissions_veg'),
                'flower'	=> Input::get('checkPermissions_flower'),
                'dry'	=> Input::get('checkPermissions_dry'),
                'trimming'	=> Input::get('checkPermissions_trimming'),
                'packing'	=> Input::get('checkPermissions_packing'),
                'vault'	=> Input::get('checkPermissions_vault'),
                'sell'	=> Input::get('checkPermissions_sell'),
                'history'	=> Input::get('checkPermissions_history'),
                'client'	=> Input::get('checkPermissions_client'),
                'setting'	=> Input::get('checkPermissions_setting'),
                'genetic'	=> Input::get('checkPermissions_genetic'),
                'user'	=> Input::get('checkPermissions_user'),
            ), Input::get('id'));

            Redirect::to('../Views/userPermissions.php');

        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'search'){

        $searchText = Input::get('text');
        $result = $user->getResultOfSearch($searchText);

        echo json_encode($result);



    }

}


