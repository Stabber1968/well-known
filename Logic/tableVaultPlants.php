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
$table = 'vault';

// Table's primary key
$primaryKey = 'id';


/** index of join tables */
// $sIndexTable = "index_packing"; // table 2

// $sTable1_index = "lot_ID";
// $sTable2_index = "lot_id";


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
    array('db' => 'lot_ID',   'dt' => 'td_qr_code',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $lot_IDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$d);
        $lot_IDInfo = $lot_IDInfo->results();
        return  $lot_IDInfo[0]->qr_code;
    }),
    array('db' => 'lot_ID', 'dt' => 'td_lot_ID',),
    array(
        'db' => 'lot_ID', 'dt' => 'td_lot_ID_text',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $res = $p_general->getTextOflotID($d);
            return  $res;
        }
    ),
    array('db' => 'packing_number', 'dt' => 'td_packing_number',),
    array('db' => 'packing_number', 'dt' => 'td_packing_number_text',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $res = $p_general->getTextOfPackingNumber($d);
        return  $res;
    }),

    array('db' => 'genetic_ID', 'dt' => 'td_genetic_ID',),
    array(
        'db' => 'genetic_ID', 'dt' => 'td_genetic_text',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
            $geneticInfo = $geneticInfo->results();
            return  $geneticInfo[0]->genetic_name;
        }
    ),
    array(
        'db' => 'genetic_ID', 'dt' => 'td_genetic_name',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
            $geneticInfo = $geneticInfo->results();
            return  $geneticInfo[0]->plant_name;
        }
    ),
    array('db' => 'producer_name', 'dt' => 'td_producer_name',),
    array('db' => 'place_origin', 'dt' => 'td_place_origin',),


    array('db' => 'lot_ID', 'dt' => 'td_born_date',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $lot_IDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$d);
        $lot_IDInfo = $lot_IDInfo->results();
        return  $lot_IDInfo[0]->born_date;
    }),
    array('db' => 'lot_ID', 'dt' => 'td_harvest_date',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $lot_IDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$d);
        $lot_IDInfo = $lot_IDInfo->results();
        return  $lot_IDInfo[0]->harvest_date;
    }),
    array('db' => 'packing_date', 'dt' => 'td_packing_date',),
    array('db' => 'packing_date', 'dt' => 'td_expiration_date',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $varTime = DateTime::createFromFormat('d/m/Y', $d);
        $date1 =  $varTime->format('m/d/Y'); // format to standard time format.
        $expiration_date = date('d/m/Y', strtotime($date1.' +1 year'));

        return  $expiration_date;
    }),
    array('db' => 'grams_amount', 'dt' => 'td_gram_amount',),
    array('db' => 'seeds_amount', 'dt' => 'td_seeds_amount',),
    array('db' => 'thc', 'dt' => 'td_thc_content',),
    array('db' => 'cbd', 'dt' => 'td_cbd_content',),
    array('db' => 'other', 'dt' => 'td_other',),
    array('db' => 'location', 'dt' => 'td_location',),

    array('db' => 'note', 'dt' => 'td_note',),
    
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
