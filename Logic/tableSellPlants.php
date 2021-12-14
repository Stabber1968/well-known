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
$table = 'sell';

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
    array('db' => 'id', 'dt' => 'checkbox_id'),
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
    array('db' => 'packing_number', 'dt' => 'td_packing_number_text',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $res = $p_general->getTextOfPackingNumber($d);
        return  $res;
    }),

    array('db' => 'genetic', 'dt' => 'td_genetic_ID',),
    array(
        'db' => 'genetic', 'dt' => 'td_genetic_name',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $d);
            $geneticInfo = $geneticInfo->results();
            return  $geneticInfo[0]->genetic_name;
        }
    ),
    array('db' => 'grams', 'dt' => 'td_grams_amount',),
    array('db' => 'seeds_amount', 'dt' => 'td_seeds_amount',),
    array('db' => 'sell_date', 'dt' => 'td_sell_date',),
    array('db' => 'grams_price', 'dt' => 'td_grams_price',),
    array('db' => 'total_price', 'dt' => 'td_total_price',),
    array('db' => 'client', 'dt' => 'td_client_id',),


    array('db' => 'client', 'dt' => 'td_client_name',
    'formatter' => function ($d, $row) {
        $p_general = new General();
        $client_info = $p_general->getValueOfAnyTable('client', 'id', '=', $d);
        $client_info = $client_info->results();
        return  $client_info[0]->name;
    }),
    array('db' => 'invoice_number', 'dt' => 'td_invoice_number',),
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
