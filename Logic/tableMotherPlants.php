<?php

require_once('../Controllers/init.php');

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'plants';

// Table's primary key
$primaryKey = 'id';


/** index of join tables */
$sIndexTable = "index_mother"; // table 2

$sTable1_index = "id";
$sTable2_index = "plant_id";


/** join 2 tables
 * SELECT columnnamelist FROM table1 RIGHT JOIN table2 ON table1.col1=table2.col2
 */
$sJoin = "JOIN " . $sIndexTable . " ON " . $table . "." . $sTable1_index . "=" . $sIndexTable . "." . $sTable2_index;

if($_GET["room"]){
    $where_custom = "WHERE location='".$_GET["room"]."'";
}
// var_dump($_GET["room"]);



// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'id', 'dt' => 'checkbox_id'),
    array('db' => 'id',  'dt' => 'td_id'),
    array('db' => 'qr_code',   'dt' => 'td_qr_code'),
    array('db' => 'mother_UID', 'dt' => 'td_mother_UID',),
    array(
        'db' => 'mother_UID', 'dt' => 'td_plant_UID_text',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $res = $p_general->getTextOfMotherUID($d);
            return  $res;
        }
    ),
    array('db' => 'name', 'dt' => 'td_name',),
    array('db' => 'genetic', 'dt' => 'td_genetic_id',),
    array(
        'db' => 'genetic', 'dt' => 'td_genetic',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
            $geneticInfo = $geneticInfo->results();
            return  $geneticInfo[0]->genetic_name;
        }
    ),
    array('db' => 'mother_id', 'dt' => 'td_mother_id',),
    array('db' => 'location', 'dt' => 'td_location_id',),
    array(
        'db' => 'location', 'dt' => 'td_location',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $roomInfo = $p_general->getValueOfAnyTable('room_mother', 'id', '=', $d);
            $roomInfo = $roomInfo->results();
            return  $roomInfo[0]->name;
        }
    ),
    array('db' => 'planting_date', 'dt' => 'td_planting_date',),
    array(
        'db' => 'room_date', 'dt' => 'td_days',
        'formatter' => function ($d, $row) {
            $m_room_date = date_create($d);
            $m_today = date_create(date("m/d/Y"));
            $diff = date_diff($m_room_date, $m_today);
            $days = $diff->format("%a");
            return  $days;
        }
    ),
    array('db' => 'observation', 'dt' => 'td_observation',),
    
    array('db' => 'id', 'dt' => 'buttons',),
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('ssp.mother_class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $sJoin, $where_custom)
);
