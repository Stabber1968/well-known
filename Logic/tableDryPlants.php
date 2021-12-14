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
$table = 'lot_id';

// Table's primary key
$primaryKey = 'id';


/** index of join tables */
$sIndexTable = "index_dry"; // table 2

$sTable1_index = "lot_ID";
$sTable2_index = "lot_id";


/** join 2 tables
 * SELECT columnnamelist FROM table1 RIGHT JOIN table2 ON table1.col1=table2.col2
 */

if ($sIndexTable) {
    $sJoin = "JOIN " . $sIndexTable . " ON " . $table . "." . $sTable1_index . "=" . $sIndexTable . "." . $sTable2_index;
} else {
    $sJoin = "";
}


if ($_GET["room"]) {
    $where_custom = "WHERE location='" . $_GET["room"] . "'";
}




// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'lot_ID', 'dt' => 'checkbox_id'),
    array('db' => 'id',  'dt' => 'td_id'),
    array('db' => 'qr_code',   'dt' => 'td_qr_code'),
    array('db' => 'lot_ID', 'dt' => 'td_lot_ID',),
    array(
        'db' => 'lot_ID', 'dt' => 'td_lot_ID_text',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $res = $p_general->getTextOflotID($d);
            return  $res;
        }
    ),
    array('db' => 'genetic_ID', 'dt' => 'td_genetic_id',),
    array(
        'db' => 'genetic_ID', 'dt' => 'td_genetic_text',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
            $geneticInfo = $geneticInfo->results();
            return  $geneticInfo[0]->genetic_name;
        }
    ),
    array('db' => 'genetic_ID', 'dt' => 'td_genetic_name',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
        $geneticInfo = $geneticInfo->results();
        return  $geneticInfo[0]->plant_name;
    }),
    array('db' => 'location', 'dt' => 'td_location',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $locationInfo = $p_general->getValueOfAnyTable('room_dry', 'id', '=', $d);
        $locationInfo = $locationInfo->results();
        return  $locationInfo[0]->name;
    }),
    array('db' => 'location', 'dt' => 'td_location_id',),
    array('db' => 'born_date', 'dt' => 'td_born_date',),
    array('db' => 'harvest_date', 'dt' => 'td_harvest_date',),
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
    
    array('db' => 'note', 'dt' => 'td_note',),
    array('db' => 'id', 'dt' => 'buttons',),
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $sJoin, $where_custom)
);
