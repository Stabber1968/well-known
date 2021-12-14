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
$table = 'history';

// Table's primary key
$primaryKey = 'id';


/** index of join tables */
// $sIndexTable = "index_packing"; // table 2

// $sTable1_index = "lot_ID";
// $sTable2_index = "lot_id";


/** join 2 tables
 * SELECT columnnamelist FROM table1 RIGHT JOIN table2 ON table1.col1=table2.col2
 */

// if ($sIndexTable) {
//     $sJoin = "JOIN " . $sIndexTable . " ON " . $table . "." . $sTable1_index . "=" . $sIndexTable . "." . $sTable2_index;
// } else {
//     $sJoin = "";
// }


// if ($_GET["room"]) {
//     $where_custom = "WHERE location='" . $_GET["room"] . "'";
// }
$columns = array(
    array('db' => 'date', 'dt' => 'td_date'),
    array(
        'db' => 'user_id',  'dt' => 'td_username',
        'formatter' => function ($d, $row) {
            $p_general = new General();
            $userInfo = $p_general->getValueOfAnyTable('users', 'id', '=', $d);
            $userInfo = $userInfo->results();
            return  $userInfo[0]->name;
        }
    ),
    array('db' => 'event',   'dt' => 'td_event',),
    array('db' => 'plant_UID', 'dt' => 'td_UID_text',),
    array(
        'db' => 'note', 'dt' => 'td_note',
    ),
    array(
        'db' => 'observation', 'dt' => 'td_observation',
    ),
    array('db' => 'room_name', 'dt' => 'td_room_name',),
    array('db' => 'user_name', 'dt' => 'td_user_name',),
    array('db' => 'qr_code', 'dt' => 'td_qr_code',),
    array('db' => 'lot_id', 'dt' => 'td_lot_id',),
    array('db' => 'mother_UID', 'dt' => 'td_mother_id',),
);

if ($_GET["search_lot_id"]) {
    $where_custom = "WHERE lot_id='" . $_GET["search_lot_id"] . "'";
}
$where_custom = "";
$act = $_GET['act'];
if($act == 'fetch_history'){
    $initial_date =$_GET['initial_date'];
    $final_date =$_GET['final_date'];
    
    if(!empty($initial_date) && !empty($final_date)){
        $where_custom = "WHERE `date` BETWEEN '".$initial_date."' AND '".$final_date."' ";
    }else{
        $where_custom = "";
    }
}
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('ssp_histpry.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $sJoin, $where_custom)
);
