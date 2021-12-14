<?php

/**
 * Class General
 */
class General
{

    private $_db,
        $_data,
        $_count;

    public $_global = [];

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    /**
     * Get data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @return bool|\bool\|DB|null
     */
    public function getValueOfAnyTable($table, $field, $symbol, $key, $order = null)
    {
        $data = $this->_db->get($table, array($field, $symbol, $key), $order);
        // file_put_contents('debug_log.txt', print_r($symbol, true),FILE_APPEND | LOCK_EX);
        
        if ($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }

    /**
     * @param $table
     * @param array $fields
     * @throws Exception
     */
    public function createValueOfAnyTable($table, $fields = array())
    {
        if (!$this->_db->insert($table, $fields)) {
            throw new Exception('There was a problem createValueOfAnyTable.....');
        }
    }

    /**
     * @param $table
     * @param array $fields
     * @param null $id
     * @throws Exception
     */
    public function updateValueOfAnyTable($table, $fields = array(), $id = null)
    {
        if (!$this->_db->update($table, $id, $fields)) {
            throw new Exception('There was a problem updateValueOfAnyTable.');
        }
    }

    /**
     * Delete data at table
     * @param $table: table name
     * @param $field: target (ex: name)
     * @param $symbol: operator (ex: =)
     * @param $key: value (ex: jone)
     * @throws Exception
     */
    public function deleteValueOfAnyTable($table, $field, $symbol, $key)
    {
        if (!$this->_db->delete($table, array($field, $symbol, $key))) {
            throw new Exception('There was a problem deleteValueOfAnyTable......');
        }
    }

    /**
     * @param $plant_UID
     * @return string
     */
    public function getTextOfPlantUID($plant_UID)
    {
        $unique_num_plantUID = 1000000000 + $plant_UID;
        $plant_UID_text = "P" . substr($unique_num_plantUID, 1, 4) . "-" . substr($unique_num_plantUID, 5, 5);
        return $plant_UID_text;
    }

    public function getPlantUIDfromTxt($plant_UID_text)
    {
        $plant_uid = intval('1'.substr($plant_UID_text, 1, 4).substr($plant_UID_text, 6, 5)) - 1000000000;
        
        // $unique_num_plantUID = 1000000000 + $plant_UID;
        // $plant_UID_text = "P" . substr($unique_num_plantUID, 1, 4) . "-" . substr($unique_num_plantUID, 5, 5);
        return $plant_uid;
    }
    /**
     * @param $plant_UID
     * @return string
     */
    public function getTextOfMotherUID($plant_UID)
    {
        $unique_num_plantUID = 1000000000 + $plant_UID;
        $plant_UID_text = "M" . substr($unique_num_plantUID, 1, 4) . "-" . substr($unique_num_plantUID, 5, 5);

        return $plant_UID_text;
    }

    /**
     * @param $lot_UID
     * @return string
     */
    public function getLotIDfromtxt($lot_UID_txt)
    {
        $plant_uid = intval('1'.substr($lot_UID_txt, 4, 4).substr($lot_UID_txt, 9, 5)) - 1000000000;
        
        // $unique_num_plantUID = 1000000000 + $plant_UID;
        // $plant_UID_text = "P" . substr($unique_num_plantUID, 1, 4) . "-" . substr($unique_num_plantUID, 5, 5);
        return $plant_uid;
    }
    public function getTextOflotID($lot_UID)
    {
        $unique_num_lotID = 1000000000 + $lot_UID;
        $lot_UID_text = "Lot." . substr($unique_num_lotID, 1, 4) . "-" . substr($unique_num_lotID, 5, 5);

        return $lot_UID_text;
    }

    /**
     * @param $lot_ID
     * @return mixed
     */
    public function getLastPackingNumberOfLotID($lot_ID)
    {
        $packing_numberArray_vault = $this->_db->query("SELECT MAX(`packing_number`) AS aaa FROM `vault` WHERE lot_ID IN ('$lot_ID')");
        $packing_numberArray_vault = $packing_numberArray_vault->results();

        $packing_numberArray_sell = $this->_db->query("SELECT MAX(`packing_number`) AS bbb FROM `sell` WHERE lot_ID IN ('$lot_ID')");
        $packing_numberArray_sell = $packing_numberArray_sell->results();

        $packing_number_vault = $packing_numberArray_vault[0]->aaa + 1;
        $packing_number_sell = $packing_numberArray_sell[0]->bbb + 1;
        if ($packing_number_vault < $packing_number_sell) {
            $packing_number = $packing_number_sell;
        } else {
            $packing_number = $packing_number_vault;
        }
        return $packing_number;
    }

    /**
     * @param 
     * @return mixed
     */
    public function getLastPlantUID()
    {
        $numberList = $this->getValueOfAnyTable('last_index', '1', '=', '1');
        $numberList = $numberList->results();
        if ($numberList[0]->clone) {
            $last_plant_UID = $numberList[0]->clone;
        } else {
            $last_plant_UID = 0;
        }
        return $last_plant_UID;
    }

    /**
     * @param $packing_number
     * @return string
     */
    public function getTextOfPackingNumber($packing_number)
    {
        $packing_number_text = substr(1000 + $packing_number, 1, 3);
        //$unique_num_packing_number= 1000000000 + $packing_number;
        //$packing_number_text = "Pack.".substr($unique_num_packing_number,1,4)."-".substr($unique_num_packing_number,5,5);
        return $packing_number_text;
    }

    /**
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = array())
    {
        $data = $this->_db->query($sql, $params);
        return $data;
    }

    /**
     * register history of user process on site
     * @param null $action : is action of permissions
     * @param null $moduleName : is module name of permissions
     * @param null $channel :  is channel of permissions
     * @param null $type : type of permissions
     * @throws Exception
     */
    public function registerHistoryMother($mother_UID, $user_id, $event, $user_name, $qr_code = null, $room_name = null, $note = null, $observation = null)
    {
        if ($event) {
            $currentTime = $this->getCurrentDateAndTime('Y-m-d H:i:s');
            // $currentTime =  date('Y-m-d H:i:s');
            if (!$this->_db->insert('history', array(
                'mother_UID' => $mother_UID,
                'user_id' => $user_id,
                'event' => $event,
                'date' => $currentTime,
                'note' => $note,
                'observation' => $observation,
                'room_name' => $room_name,
                'user_name' => $user_name,
                'qr_code' => $qr_code
            ))) {
                throw new Exception('There was a problem creating history.');
            }
        }
    }

    /**
     * @param $plant_UID
     * @param $user_id
     * @param $event
     * @throws Exception
     */
    public function registerHistoryPlant($plant_UID, $user_id, $event, $room_name = null, $note = null, $observation = null)
    {
        if ($event) {
            $currentTime = $this->getCurrentDateAndTime('Y-m-d H:i:s');
            if (!$this->_db->insert('history', array(
                'plant_UID' => $plant_UID,
                'user_id' => $user_id,
                'event' => $event,
                'date' => $currentTime,
                'note' => $note,
                'observation' => $observation,
                'room_name' => $room_name,
            ))) {
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
    public function registerHistoryLot($lot_ID, $user_id, $event, $user_name, $qr_code = null, $room_name = null, $note = null, $observation = null)
    {
        if ($event) {
            if ($lot_ID && $user_id && $event) {
                $currentTime = $this->getCurrentDateAndTime('Y-m-d H:i:s');
                if (!$this->_db->insert('history', array(
                    'lot_id' => $lot_ID,
                    'user_id' => $user_id,
                    'event' => $event,
                    'date' =>$currentTime,
                    'note' => $note,
                    'observation' => $observation,
                    'room_name' => $room_name,
                    'user_name' => $user_name,
                    'qr_code' => $qr_code
                ))) {
                    throw new Exception('There was a problem creating history.');
                }
            }
        }
    }

    /**
     * @param $lot_ID
     * @param $user_id
     * @param $event
     * @param $packing_number
     * @throws Exception
     */
    //register a packing number
    public function registerHistoryPacking($lot_ID, $user_id, $event, $packing_number, $room_name = null, $note = null, $observation = null)
    {
        if ($event) {
            if ($lot_ID && $user_id && $event && $packing_number) {
                $currentTime = $this->getCurrentDateAndTime('Y-m-d H:i:s');
                if (!$this->_db->insert('history', array(
                    'lot_id' => $lot_ID,
                    'packing_number' => $packing_number,
                    'user_id' => $user_id,
                    'event' => $event,
                    'date' =>$currentTime,
                    'note' => $note,
                    'observation' => $observation,
                    'room_name' => $room_name,
                ))) {
                    throw new Exception('There was a problem creating history.');
                }
            }
        }
    }

    public function registerHistoryRoom($room_id, $user_id, $event)
    {
        if ($event) {
            $currentTime = $this->getCurrentDateAndTime('Y-m-d H:i:s');
            if (!$this->_db->insert('history', array(
                'room_name' => $room_id,
                'user_id' => $user_id,
                'event' => $event,
                'date' =>$currentTime,
            ))) {
                throw new Exception('There was a problem creating history.');
            }
        }
    }

    /**
     * @return string
     */
    public function generateQRCode()
    {
        $limit = 16;
        $code = '';
        for ($i = 0; $i < $limit; $i++) {
            $code .= mt_rand(0, 9);
        }
        $unique_num_qrcode = $code;
        // $qr_code = substr($unique_num_qrcode, 0, 4) . "-" . substr($unique_num_qrcode, 4, 4) . "-" . substr($unique_num_qrcode, 8, 4) . "-" . substr($unique_num_qrcode, 12, 4);
        $qr_code = substr($unique_num_qrcode, 0, 4) . substr($unique_num_qrcode, 4, 4) . substr($unique_num_qrcode, 8, 4) . substr($unique_num_qrcode, 12, 4);

        return $qr_code;
    }

    /**
     * @param $array
     * @param $key
     * @return array
     */
    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }


    public function getNumberListOrderBy($table, $field, $symbol, $key, $order)
    {
        $data = $this->_db->getOrderBy($table, array($field, $symbol, $key), $order);

        if ($data->count()) {
            $this->_data = $data->first();
            $this->_count = $data->count();
        }
        return $data;
    }


    /**
     * @param 
     * @return string
     */
    public function getCurrentDateAndTime($format = null)
    {
        $tz = 'Europe/Lisbon';
        $tz_obj = new DateTimeZone($tz);
        $today = new DateTime("now", $tz_obj);
        if ($format) {
            $today_formatted = $today->format($format);
        } else {
            $today_formatted = $today->format('m/d/Y');
        }

        // $date = new DateTime("now", new DateTimeZone('Europe/Lisbon') );
        return $today_formatted;;
    }
}
