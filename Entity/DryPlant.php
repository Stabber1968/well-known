<?php

/**
 * Class DryPlant
 */
class DryPlant {

    private $_db,
        $_data,
        $_count;


    /**
     * Group constructor.
     */
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Create data at groups table
     * @param array $fields: array( target => value) to create a field at Database
     * @throws Exception
     */
    public function create($fields = array()) {

        if(!$this->_db->insert('plants', $fields)) {
            throw new Exception('There was a problem creating this menu.');
        }
    }

    //dd
    public function createlotID($fields = array()) {

        if(!$this->_db->insert('lot_id', $fields)) {
            throw new Exception('There was a problem creating this menu.');
        }
    }

    //dd
    public function createRelationTrimmingRoomAndLotID($trimmingRoomID,$lot_ID,$plant_ID) {


        if(!$this->_db->insert('index_trimming', array(
            'room_id' => $trimmingRoomID,
            'lot_id' => $lot_ID,
            'plant_id' => $plant_ID,
        ))) {
            throw new Exception('There was a problem inserting......');
        }
    }

    //update location when change location of lot id
    public function UpdateLocationOfIndexTable($location,$lot_ID) {
        $Info = $this->getValueOfAnyTable('index_dry','lot_id','=',$lot_ID);
        $Info = $Info->results();
        if(!$this->_db->update('index_dry', $Info[0]->id, array(
            'room_id' => $location,
        ))) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function getPlantsListFromDryRoomID($dryRoomID) {

        $dryPlantsIDList = $this->getValueOfAnyTable('index_dry','room_id','=',$dryRoomID,'plant_id');
        $dryPlantsIDList = $dryPlantsIDList->results();

        return $dryPlantsIDList;


    }


    /**
     * register history of user process on site
     * @param null $action : is action of permissions
     * @param null $moduleName : is module name of permissions
     * @param null $channel :  is channel of permissions
     * @param null $type : type of permissions
     * @throws Exception
     */
    public function registerHistoryPlant($plant_UID,$user_id,$event){
        if($event) {
            if (!$this->_db->insert('history', array(
                'plant_UID' => $plant_UID,
                'user_id' => $user_id,
                'event' => $event,
                'date' => date('Y-m-d H:i:s'),
            ))
            ) {
                throw new Exception('There was a problem creating history.');
            }
        }
    }
    /**
     * @param $lot_ID
     * @param $user_id
     * @param $event
     * @throws Exception
     */
    public function registerHistoryLot($lot_ID,$user_id,$event, $user_name){
        if($event) {
            if ($lot_ID && $user_id && $event) {
                if (!$this->_db->insert('history', array(
                    'lot_id' => $lot_ID,
                    'user_id' => $user_id,
                    'event' => $event,
                    'date' => date('Y-m-d H:i:s'),
                    'user_name' => $user_name,
                ))
                ) {
                    throw new Exception('There was a problem creating history.');
                }
            }
        }
    }

    /**
     * Update data at groups table
     * @param array $fields: array( target => value) to update field at Database
     * @param null $id: The id of selected field
     * @throws Exception
     */
    public function update($fields = array(), $id = null) {
        if(!$this->_db->update('lot_id', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    /**
     * @param array $fields
     * @param null $id
     * @throws Exception
     */
    public function updateLastIndex($fields = array(), $id = null) {
        if(!$this->_db->update('last_index', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    /**
     * @param array $fields
     * @param null $id
     * @throws Exception
     */
    public function updateLotID($fields = array(), $id = null) {
        if(!$this->_db->update('lot_id', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    /**
     * Find data following id at table
     * @param null $id: The id is that to find field
     * @return bool|DB|null
     */
    public function find($id = null) {
        if($id) {
            // if user had a numeric username this FAILS...
            $data = $this->_db->get('room_dry', array('id', '=', $id));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return $this->_data;
            }
        }
        return false;
    }


    /**
     * Get data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @return bool|\bool\|DB|null
     */
    public function getValueOfAnyTable($table,$field,$symbol,$key,$order = null){

        $data = $this->_db->get($table, array($field, $symbol, $key),$order);

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
    public function deleteValueOfAnyTable($table,$field,$symbol,$key){
        if(!$this->_db->delete($table,array($field, $symbol, $key))) {
            throw new Exception('There was a problem Deleteing......');
        }
    }

    //dd
    public function transferRelationTrimmingRoomAndPlant($selectedTrimmingRoomID,$PlantID,$lot_ID) {
        if(!$this->_db->insert('index_trimming', array(
            'room_id' => $selectedTrimmingRoomID,
            'plant_id' => $PlantID,
            'lot_id'=>$lot_ID,
        ))) {
            throw new Exception('There was a problem inserting......');
        }
    }

    /**
     * @param $selectedTrimmingRoomID
     * @param $PlantID
     * @param $lot_ID
     * @throws Exception
     */
    public function transferRelationTrimmingRoomAndlotID($selectedTrimmingRoomID,$lot_ID) {
        if(!$this->_db->insert('index_trimming', array(
            'room_id' => $selectedTrimmingRoomID,
            'lot_id'=>$lot_ID,
        ))) {
            throw new Exception('There was a problem inserting......');
        }
    }

    /**
     * Get all data at table
     * @return bool|DB|null
     */
    public function getAllOfInfo(){
        $data = $this->_db->get('room_dry', array('1', '=', '1'));
        if($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }

    /**
     * Count of selected data at table
     * @return mixed
     */
    public function count(){
        return $this->_count;
    }

    /**
     * Test existing of seleted data
     * @return bool
     */
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    /**
     * Delete data at groups and permissions table
     * @param $id: menu id for groups table
     * @return bool|\bool\|DB|null
     */
    public function delete($id){
        return $this->_db->delete('room_dry', array('id', '=', $id));
    }

    /**
     * Get seleted data
     * @return mixed
     */
    public function data() {
        return $this->_data;
    }

    /**
     * Confirm exist same user at group
     * @param $qrcode : is name to confirm
     * @return bool
     */
    public function isSameQR($qrcode) {
        if($qrcode) {
            $data = $this->_db->get('plants', array('qr_code', '=', $qrcode));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
    }
    public function isSamePlantUID($plant_UID) {
        if($plant_UID) {
            $data = $this->_db->get('plants', array('plant_UID', '=', $plant_UID));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
    }

}