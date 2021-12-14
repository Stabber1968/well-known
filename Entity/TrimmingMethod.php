<?php

/**
 * Class TrimmingMethod
 */
class TrimmingMethod {

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

        if(!$this->_db->insert('trimming_method', $fields)) {
            throw new Exception('There was a problem creating this method.');
        }
    }

    /**
     * Update data at groups table
     * @param array $fields: array( target => value) to update field at Database
     * @param null $id: The id of selected field
     * @throws Exception
     */
    public function update($fields = array(), $id = null) {
        if(!$this->_db->update('trimming_method', $id, $fields)) {
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
            $data = $this->_db->get('trimming_method', array('id', '=', $id));

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
    public function deleteValueOfAnyTable($table,$field,$symbol,$key){

        if(!$this->_db->delete($table,array($field, $symbol, $key))) {
            throw new Exception('There was a problem Deleteing......');
        }

    }

    /**
     * Get all data at table
     * @return bool|DB|null
     */
    public function getAllOfInfo(){

        $data = $this->_db->get('trimming_method', array('1', '=', '1'));
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
        return $this->_db->delete('trimming_method', array('id', '=', $id));
    }

    /**
     * Get seleted data
     * @return mixed
     */
    public function data() {
        return $this->_data;
    }
    /**
     *Confirm exist same user at user
     * @param $email
     * @return bool
     */
    public function isSame($name){
        if($name) {
            $data = $this->_db->get('trimming_method', array('name', '=', $name));

            if($data->count()) {
                $this->_data = $data->first();
                $this->_count = $data->count();
                return true;
            }
        }
        return false;
    }

}