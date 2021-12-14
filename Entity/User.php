<?php


/**
 * Class User
 */
class User {

	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
            $_count;

	public $currentLoggedInUser;
	public $_global = [];



    /**
     * User constructor.
     * @param null $user
     */
	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');



        if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				// echo $user;
				if($this->find($user)) {
					$this->_isLoggedIn = true;
                    $this->currentLoggedInUser = $this->data();
				} else {
					// process logout
				}
			}
		} else {
			$this->find($user);
		}
	}

    /**
     * Create data at users table
     * @param array $fields: array( target => value) to create a field at Database
     * @throws Exception
     */
	public function create($fields = array()) {


		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating this account.');
		}
	}

    public function createPermissions($fields = array()) {


        if(!$this->_db->insert('user_permissions', $fields)) {
            throw new Exception('There was a problem creating this account.');
        }
    }

    /**
     * Update data at users table
     * @param array $fields: array( target => value) to update field at Database
     * @param null $id: The id of selected field
     * @throws Exception
     */
	public function update($fields = array(), $id = null) {

		if(!$id && $this->isLoggedIn()) {

            $id = $this->data()->id;
        }

		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

    /**
     * @param array $fields
     * @param $id
     * @throws Exception
     */
    public function updatePermissions($fields = array(), $id) {


        if(!$this->_db->update('user_permissions', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function updateBackup($fields = array(), $id) {
        if(!$this->_db->update('backup', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }



        /**
     * Find data following Id or email at table
     * @param null $user: id or email to find
     * @return bool|\bool\|DB|null
     */
	public function find($user = null) {
		if($user) {
			// if user had a numeric username this FAILS...
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
                $this->_count = $data->count();

                return $data;
			}
		}
		return false;
	}


    /**
     * Get all data at table
     * @return bool|DB|null
     */
	public function getAllOfUsersInfo(){

        $data = $this->_db->get('users', array('1', '=', '1'));
        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }

    public function getAllOfUserPermissionsInfo(){

        $data = $this->_db->get('user_permissions', array('1', '=', '1'));
        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }

    /**
     * Get Id of role
     * @return bool|\bool\|DB|null
     */
    public function getIdOfRole(){

        $data = $this->_db->get('roles', array('name', '=', Input::get('role')));
        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }


    /**
     * Get data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @return bool|\bool\|DB|null
     */
    public function getValueOfAnyTable($table,$field,$symbol,$key){

        $data = $this->_db->get($table, array($field, $symbol, $key));

        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }
    /**
     * Delete data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @throws Exception
     */
    public function validateUser($user_id,$user_password,$admin_password, $admin_id){

        // $adminInfo = $this->getValueOfAnyTable('users', 'superAdmin', '=', '1');
        // $adminInfo = $adminInfo->results();

        $userInfo = $this->getValueOfAnyTable('users', 'id', '=', $user_id);
        $userInfo = $userInfo->results();
        $adminInfo = $this->getValueOfAnyTable('users', 'id', '=', $admin_id);
        $adminInfo = $adminInfo->results();
        $hash_userpass = md5($user_password);
        $hash_adminpass = md5($admin_password);

        $res = "faild";
        if ($hash_userpass == $userInfo[0]->password && $hash_adminpass == $adminInfo[0]->password ) {
            // file_put_contents('debug_log.txt', print_r($adminInfo[0]->password, true),FILE_APPEND | LOCK_EX);
            $res = 'success';
        }
        return $res;
    }

    /**
     * Delete data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @throws Exception
     */
    public function deleteValueOfAnyTable($table,$field,$symbol,$key){

        if(!$this->_db->delete($table,array($field, $symbol, $key))) {
            throw new Exception('There was a problem Deleteing......');
        }

    }

    /**
     * Count of selected data at table
     * @return mixed
     */
    public function count(){
	    return $this->_count;
    }

    /**
     * Login
     * @param null $username: username
     * @param null $password: password
     * @param bool $remember: remember value
     * @return bool
     */
	public function login($username = null, $password = null, $remember = false) {
		
		// print_r($this->_data);
		// check if username has been defined
		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		}else {

			$user = $this->find($username);

			if($user) {

                if($this->data()->password === md5($password)) {

                    Session::put($this->_sessionName, $this->data()->id);

                    if($remember) {
                        $hash = md5($password);

                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                        if(!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));

                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    return true;
                }

			}
		}

		return false;
	}


    /**
     * Test permission
     * @param $key: permission value (ex: admin or user)
     * @return bool
     */
	public function hasGroupPermission($key) {

		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));

        if($group->count()) {
//			$value = json_decode($group->first()->value, true);
			if($group->first()->value == $key) {
                return true;
			}
		}
		return false;
	}


    /**
     * Test existing of seleted data
     * @return bool
     */
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

    /**
     * Logout
     */
	public function logout() {

        session_destroy();

		Session::delete($this->_sessionName);

        Cookie::delete($this->_cookieName);
	}

    /**
     * Delete data following id at table
     * @param $id: The id is that to delete field
     * @return bool|DB|null
     */
	public function delete($id){
        return $this->_db->delete('users', array('id', '=', $id));
    }

    /**
     * Get seleted data
     * @return mixed
     */
	public function data() {
		return $this->_data;
	}

    /**
     * Confirm login status
     * @return bool
     */
	public function isLoggedIn() {

		return $this->_isLoggedIn;
	}


    /**
     * Create Related between User and Role at related_UserRole Table
     * @param $checkedRoleIDList : is Role id List checked by user
     * @param $createEmail : is email for creating user
     * @param null $selectedUserID : is id when user are going to edit user
     */
    public function createRelationUserAndRole($checkedRoleIDList, $createEmail,$selectedUserID = null){
        if($selectedUserID){
            $this->_db->delete('related_UserRole',array('userID','=',$selectedUserID));

            $mChildUserLists = $this->getAllOfChildrenUserInfo($selectedUserID);
            if ($mChildUserLists){
                foreach ($mChildUserLists as $mChild){
                    $roleListForChildUser = $this->getValueOfAnyTable('related_UserRole','userID','=',$mChild->id);
                    $roleListForChildUser = $roleListForChildUser->results();
                    $this->_db->delete('related_UserRole',array('userID','=',$mChild->id));

                    if ($checkedRoleIDList){
                        foreach ($checkedRoleIDList as $checkedRoleID ){
                            foreach ($roleListForChildUser as $role){
                                if ($checkedRoleID == $role->roleID ){
                                    $this->_db->insert('related_UserRole', array(
                                        'userID' => $mChild->id,
                                        'roleID' => $checkedRoleID,
                                    ));
                                }
                            }
                        }
                    }
                }
            }
            if ($checkedRoleIDList){
                foreach ($checkedRoleIDList as $checkedRoleID) {

                    $this->_db->insert('related_UserRole', array(
                        'userID' => $selectedUserID,
                        'roleID' => $checkedRoleID,
                    ));
                }
            }


        }else{
            $createUserID = $this->_db->get('users',array('email', '=', $createEmail));
            $createUserID = $createUserID->first();

            if ($checkedRoleIDList){
                foreach ($checkedRoleIDList as $checkedRoleID){
                    $this->_db->insert('related_UserRole', array(
                        'userID' => $createUserID->id,
                        'roleID' => $checkedRoleID,
                    ));
                }
            }

        }
    }

    /**
     * Create Related between User and Permissions at related_UserRole Table
     * @param $checkedPermissionsIDList : is Permissions id List checked by user
     * @param $createEmail: is email for creating user
     * @param null $selectedUserID: is id when user are going to edit user
     */
    public function createRelationUserAndPermissions($checkedPermissionsIDList, $createEmail,$selectedUserID = null)
    {
        if($selectedUserID){
            $this->_db->delete('related_UserPermissions',array('userID','=',$selectedUserID));

            $mChildUserLists = $this->getAllOfChildrenUserInfo($selectedUserID);
            if ($mChildUserLists){
                foreach ($mChildUserLists as $mChild){
                    $permissionsListForChildUser = $this->getValueOfAnyTable('related_UserPermissions','userID','=',$mChild->id);
                    $permissionsListForChildUser = $permissionsListForChildUser->results();
                    $this->_db->delete('related_UserPermissions',array('userID','=',$mChild->id));

                    if ($checkedPermissionsIDList){
                        foreach ($checkedPermissionsIDList as $checkedPermissionsID ){
                            foreach ($permissionsListForChildUser as $permissions){
                                if ($checkedPermissionsID == $permissions->permissionsID ){
                                    $this->_db->insert('related_UserPermissions', array(
                                        'userID' => $mChild->id,
                                        'permissionsID' => $checkedPermissionsID,
                                    ));
                                }
                            }
                        }

                    }
                }
            }
            if ($checkedPermissionsIDList){
                foreach ($checkedPermissionsIDList as $checkedPermissionsID) {

                    $this->_db->insert('related_UserPermissions', array(
                        'userID' => $selectedUserID,
                        'permissionsID' => $checkedPermissionsID,
                    ));
                }

            }

        }else{
            $createUserID = $this->_db->get('users',array('email', '=', $createEmail));
            $createUserID = $createUserID->first();

            if ($checkedPermissionsIDList){
                foreach ($checkedPermissionsIDList as $checkedPermissionsID){
                    $this->_db->insert('related_UserPermissions', array(
                        'userID' => $createUserID->id,
                        'permissionsID' => $checkedPermissionsID,
                    ));
                }
            }

        }
    }


    /**
     * Get all permissions of current loggined user
     * @throws Exception
     */
    public function getAllPermissionsOfCurrentUser(){

        $this->deleteValueOfAnyTable('related_hasPermissions','1','=','1');

        $currentUserID = $this->data()->id;


        if ($this->data()->parentID == '0'){
            return;
        }

        /**
         * relation table between user and permissions
         */
        $activedPermissionsIDListFromUP = $this->_db->get('related_UserPermissions',array('userID', '=',$currentUserID));
        $activedPermissionsIDListFromUP = $activedPermissionsIDListFromUP->results();
        if($activedPermissionsIDListFromUP){
            foreach ($activedPermissionsIDListFromUP as $p){
                $this->_db->insert('related_hasPermissions', array(
                    'userID' => $currentUserID,
                    'permissionsID' => $p->permissionsID,
                ));
            }
        }

        /**
         * relation table between user and role
         */
        $activedRoleIDListFromUR = $this->_db->get('related_UserRole',array('userID', '=',$currentUserID));
        $activedRoleIDListFromUR = $activedRoleIDListFromUR->results();
        if($activedRoleIDListFromUR){
            foreach ($activedRoleIDListFromUR as $activeRoleID){
                $activedPermissionsList = $this->_db->get('related_RolePermissions',array('roleID', '=',$activeRoleID->roleID));
                $activedPermissionsList = $activedPermissionsList->results();

                foreach ($activedPermissionsList as $p){

                    $this->_db->insert('related_hasPermissions', array(
                        'userID' => $currentUserID,
                        'permissionsID' => $p->permissionsID,
                    ));
                }
            }
        }

        /**
         * if user is in group
         */

        $activedGroupIDListFromGU = $this->_db->get('related_GroupUser',array('userID', '=',$currentUserID));
        $activedGroupIDListFromGU = $activedGroupIDListFromGU->results();
        if($activedGroupIDListFromGU){
            foreach ($activedGroupIDListFromGU as $activedGroup){
                //  permissions at group
                $activedPermissionsIDListFromGP = $this->_db->get('related_GroupPermissions',array('groupID', '=',$activedGroup->groupID));
                $activedPermissionsIDListFromGP = $activedPermissionsIDListFromGP->results();
                foreach ($activedPermissionsIDListFromGP as $p){
                    $this->_db->insert('related_hasPermissions', array(
                        'userID' => $currentUserID,
                        'permissionsID' => $p->permissionsID,
                    ));
                }
                //roles at group
                $activedRoleIDListFromGR = $this->_db->get('related_GroupRole',array('groupID', '=',$activedGroup->groupID));
                $activedRoleIDListFromGR = $activedRoleIDListFromGR->results();
                foreach ($activedRoleIDListFromGR as $acivedRoleID){
                    $activedPermissionsList = $this->_db->get('related_RolePermissions',array('roleID', '=',$acivedRoleID->roleID));
                    $activedPermissionsList = $activedPermissionsList->results();

                    foreach ($activedPermissionsList as $p){

                        $this->_db->insert('related_hasPermissions', array(
                            'userID' => $currentUserID,
                            'permissionsID' => $p->permissionsID,
                        ));
                    }
                }
            }
        }
        $activedAllPermissionsIDList = $this->_db->get('related_hasPermissions',array('userID', '=',$currentUserID));
        $activedAllPermissionsIDList = $activedAllPermissionsIDList->results();

        $activedPermissionsCodeList = array();
        foreach ($activedAllPermissionsIDList as $activedPermissionsID){
            $activedPermissions = $this->_db->get('permissions',array('id', '=',$activedPermissionsID->permissionsID));
            $activedPermissions = $activedPermissions->results();

            array_push($activedPermissionsCodeList,$activedPermissions[0]->code);
        }

        $activedPermissionsCodeList = array_unique($activedPermissionsCodeList);

        $_SESSION['sessionPermissions'] = $activedPermissionsCodeList;


    }

    /**
     * verify permissions according to user
     * @param $code : permissions code
     * @return bool
     */
    public function hasPemissionsOfModule($code){

	    if ($this->currentLoggedInUser->superAdmin == '1'){
	        return true;
        }else{

            $currentSessionList = $_SESSION['sessionPermissions'];
	        foreach ($currentSessionList as $s){
	            if ($s == $code){
	                return true;
                }
            }
	        return false;
        }
    }


    public function hasPemissions($code){

        if ($this->currentLoggedInUser->superAdmin == '1'){
            return true;
        }else{
            $permissionsID =$this->currentLoggedInUser->permissions_id;

            $permissionsList = $this->getValueOfAnyTable('user_permissions','id','=',$permissionsID);
            $permissionsList = $permissionsList->results();

            if($code == 'mother'){
                if($permissionsList[0]->mother == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'clone'){
                if($permissionsList[0]->clone == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'veg'){
                if($permissionsList[0]->veg == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'flower'){
                if($permissionsList[0]->flower == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'dry'){
                if($permissionsList[0]->dry == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'trimming'){
                if($permissionsList[0]->trimming == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'packing'){
                if($permissionsList[0]->packing == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'vault'){
                if($permissionsList[0]->vault == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'sell'){
                if($permissionsList[0]->sell == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'history'){
                if($permissionsList[0]->history == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'client'){
                if($permissionsList[0]->client == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'setting'){
                if($permissionsList[0]->setting == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'genetic'){
                if($permissionsList[0]->genetic == '1'){
                    return true;
                }
                return false;
            }elseif($code == 'user'){
                if($permissionsList[0]->user == '1'){
                    return true;
                }
                return false;
            }
        }
    }



    public function getResultOfSearch($searchText) {

        if(!$searchText){
            return false;
        }

        $result = [];


        //In case of qr_code is a qr_code of a plant or mother
        //search from qr_code at plants and mothers
        $m_resultFromQRcode = $this->getValueOfAnyTable('plants','qr_code','=',$searchText);
        $m_resultFromQRcode = $m_resultFromQRcode->results();

        if($m_resultFromQRcode){
            $id = $m_resultFromQRcode[0]->id;

            if($this->getValueOfAnyTable('index_mother','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsMother.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;


            }elseif($this->getValueOfAnyTable('index_clone','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsClone.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;


            }elseif($this->getValueOfAnyTable('index_veg','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsVeg.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;

            }elseif($this->getValueOfAnyTable('index_flower','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsFlower.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;

            }elseif($this->getValueOfAnyTable('index_dry','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsDry.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;

            }elseif($this->getValueOfAnyTable('index_trimming','plant_id','=',$id)->count()){
                $m_redirect_url = 'plantsTrimming.php';
                $m_roomID = $m_resultFromQRcode[0]->location;
                $m_plantID = $m_resultFromQRcode[0]->id;

            }

            $result[0] = 'qr_code';
            $result[1] ='../Views/'.$m_redirect_url;
            $result[2] = $m_roomID;
            $result[3] = $m_plantID;

            return $result;

        }

        //In case of qr_code is a qr_code of a lot
        //search from lot
        $m_resultFromQRcode = $this->getValueOfAnyTable('lot_id','qr_code','=',$searchText);
        $m_resultFromQRcode = $m_resultFromQRcode->results();
        if($m_resultFromQRcode){
            $lot_ID = $m_resultFromQRcode[0]->lot_ID;
            if($this->getValueOfAnyTable('index_vault','lot_ID','=',$lot_ID)->count()){
                $m_redirect_url = 'plantsVault.php';
                $roomIndexInfo = $this->getValueOfAnyTable('index_vault','lot_id','=',$lot_ID);
                $roomIndexInfo = $roomIndexInfo->results();

                $m_roomID = $roomIndexInfo[0]->room_id;
                $m_search_lotID = $lot_ID;

            }elseif($this->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID)->count()){
                $m_redirect_url = 'plantsTrimming.php';

                $roomIndexInfo = $this->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID);
                $roomIndexInfo = $roomIndexInfo->results();

                $m_roomID = $roomIndexInfo[0]->room_id;
            }elseif($this->getValueOfAnyTable('sell','lot_id','=',$lot_ID)->count()){
                $m_redirect_url = 'plantsSell.php';
                $m_roomID = '0';
                $m_search_lotID = $lot_ID;
            }

            $result[0] = 'qr_code';
            $result[1] ='../Views/'.$m_redirect_url;
            $result[2] = $m_roomID;
            $result[3] = $m_search_lotID;

            return $result;

        }

        //In case of plants UID

        //verify kind :  M0000-00001, 0000-00001, lot.0000-000001
        $first_str = substr($searchText,0,1);
        if($first_str == 'M'){
            $second_str =substr($searchText,5,1);
            if($second_str == '-'){

                $mother_ID = substr($searchText,1,4).substr($searchText,6,5);
                $mother_ID = intval($mother_ID);

                $mother_plantsInfo = $this->getValueOfAnyTable('plants','mother_UID','=',$mother_ID);
                $mother_plantsInfo = $mother_plantsInfo->results();

                if($this->getValueOfAnyTable('index_mother','plant_id','=',$mother_plantsInfo[0]->id)->count()){
                    $roomIndex = $mother_plantsInfo[0]->location;
                    $m_redirect_url = 'plantsMother.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $mother_plantsInfo[0]->id;

                    return $result;
                }elseif($this->getValueOfAnyTable('history','mother_UID','=',$mother_ID)->count()){

                    $kind = 'mother';
                    $m_redirect_url = 'history.php';
                    $result[0] = 'history';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $kind;
                    $result[3] = $mother_ID;

                    return $result;
                }



            }else{
                return 'noExist';
            }

        }elseif($first_str == 'L'){
            $second_str =substr($searchText,8,1);
            if($second_str == '-'){
                $lot_ID = substr($searchText,4,4).substr($searchText,9,5);
                $lot_ID = intval($lot_ID);

                if($this->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID)->count()){
                    $indexTrimmingInfo = $this->getValueOfAnyTable('index_trimming','lot_id','=',$lot_ID);
                    $indexTrimmingInfo = $indexTrimmingInfo->results();

                    $roomIndex = $indexTrimmingInfo[0]->room_id;

                    $m_redirect_url = 'plantsTrimming.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    return $result;

                }elseif($this->getValueOfAnyTable('index_vault','lot_id','=',$lot_ID)->count()){
                    $indexVaultInfo = $this->getValueOfAnyTable('index_vault','lot_id','=',$lot_ID);
                    $indexVaultInfo = $indexVaultInfo->results();

                    $roomIndex = $indexVaultInfo[0]->room_id;

                    $m_redirect_url = 'plantsVault.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $lot_ID;

                    return $result;
                }elseif($this->getValueOfAnyTable('sell','lot_ID','=',$lot_ID)->count()){
                    $sellInfo = $this->getValueOfAnyTable('sell','lot_ID','=',$lot_ID);
                    $sellInfo = $sellInfo->results();

                    $roomIndex = 0;

                    $m_redirect_url = 'plantsSell.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $lot_ID;

                    return $result;

                }elseif($this->getValueOfAnyTable('history','lot_id','=',$lot_ID)->count()){

                    $kind = 'lot';

                    $m_redirect_url = 'history.php';

                    $result[0] = 'history';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $kind;
                    $result[3] = $lot_ID;

                    return $result;
                }

                return 'noExist';

            }else{
                return 'noExist';
            }

        }else{
            $second_str =substr($searchText,4,1);
            if($second_str == '-'){
                $plant_UID = substr($searchText,0,4).substr($searchText,5,5);
                $plant_UID = intval($plant_UID);

                $plantInfo = $this->getValueOfAnyTable('plants','plant_UID','=',$plant_UID);
                $plantInfo = $plantInfo->results();

                $plant_ID = $plantInfo[0]->id;


                if($this->getValueOfAnyTable('index_clone','plant_id','=',$plant_ID)->count()){
                    $indexCloneInfo = $this->getValueOfAnyTable('index_clone','plant_id','=',$plant_ID);
                    $indexCloneInfo = $indexCloneInfo->results();

                    $roomIndex = $indexCloneInfo[0]->room_id;

                    $m_redirect_url = 'plantsClone.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $plant_ID;

                    return $result;
                }elseif($this->getValueOfAnyTable('index_veg','plant_id','=',$plant_ID)->count()){
                    $indexVegInfo = $this->getValueOfAnyTable('index_veg','plant_id','=',$plant_ID);
                    $indexVegInfo = $indexVegInfo->results();

                    $roomIndex = $indexVegInfo[0]->room_id;

                    $m_redirect_url = 'plantsVeg.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $plant_ID;

                    return $result;
                }elseif($this->getValueOfAnyTable('index_flower','plant_id','=',$plant_ID)->count()){
                    $indexFlowerInfo = $this->getValueOfAnyTable('index_flower','plant_id','=',$plant_ID);
                    $indexFlowerInfo = $indexFlowerInfo->results();

                    $roomIndex = $indexFlowerInfo[0]->room_id;

                    $m_redirect_url = 'plantsFlower.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $plant_ID;

                    return $result;

                }elseif($this->getValueOfAnyTable('index_dry','plant_id','=',$plant_ID)->count()){
                    $indexDryInfo = $this->getValueOfAnyTable('index_dry','plant_id','=',$plant_ID);
                    $indexDryInfo = $indexDryInfo->results();

                    $roomIndex = $indexDryInfo[0]->room_id;

                    $m_redirect_url = 'plantsDry.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $plant_ID;

                    return $result;
                }elseif($this->getValueOfAnyTable('index_trimming','plant_id','=',$plant_ID)->count()){
                    $indexTrimmingInfo = $this->getValueOfAnyTable('index_trimming','plant_id','=',$plant_ID);
                    $indexTrimmingInfo = $indexTrimmingInfo->results();

                    $roomIndex = $indexTrimmingInfo[0]->room_id;

                    $m_redirect_url = 'plantsTrimming.php';

                    $result[0] = 'UID';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $roomIndex;
                    $result[3] = $plant_ID;

                    return $result;

                }elseif($this->getValueOfAnyTable('history','plant_UID','=',$plant_UID)->count()){

                    $kind = 'plant';
                    $m_redirect_url = 'history.php';

                    $result[0] = 'history';
                    $result[1] ='../Views/'.$m_redirect_url;
                    $result[2] = $kind;
                    $result[3] = $plant_UID;

                    return $result;

                }

                    return 'noExist';

            }else{
                return 'noExist';
            }

        }

        return 'noExist';
    }


    /**
     * Get all children user id of parent ID
     * @param $parentID : is id got from parent ID
     * @return array
     */
    public function getAllOfChildrenUserInfo($parentID){
        /**
         *   $parentID is $this->data()->id;
         *
         */

        $this->MyFunc($parentID);
        return $this->_global;
    }

    /**
     * recursive function to get childrens from parent ID
     * @param $parentID : is id got from parent ID
     */
    public function MyFunc($parentID){

        $childUserList = $this->getValueOfAnyTable('users','parentID', '=',$parentID);
        $childUserList = $childUserList->results();

        foreach ($childUserList as $childUser){
            array_push($this->_global, $childUser);
        }

        foreach ($childUserList as $parent){
            $this->MyFunc($parent->id);
        }
        return;

    }

    /**
     * register history of user process on site
     * @param null $action : is action of permissions
     * @param null $moduleName : is module name of permissions
     * @param null $channel :  is channel of permissions
     * @param null $type : type of permissions
     * @throws Exception
     */
    public function registerHistory($action = null,$moduleName = null, $channel = null, $type = null){

            if(!$this->_db->insert('history', array(
                'user' => $this->data()->email,
                'module' => $moduleName,
                'action' => $action,
                'channel' =>$channel,
                'type' => $type,
                'time' =>  date('Y-m-d H:i:s'),
            ))) {
                throw new Exception('There was a problem creating history.');
            }
    }

    /**
     *Confirm exist same user at user
     * @param $email
     * @return bool
     */
    public function isSame($email){
        if($email) {
            $data = $this->_db->get('users', array('email', '=', $email));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
	}

}