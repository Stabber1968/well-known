<?php

require_once('../Controllers/init.php');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */

/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
$aColumns = array('id', 'id', 'qr_code', 'plant_UID', 'plant_UID','name', 'genetic', 'genetic', 'mother_id', 'mother_text','mother_id','location','location','planting_date','planting_date','observation','id' );
// $aColumns = array(
//     array( 'db' => 'id', 'dt' => 0 ),
//     array( 'db' => 'id',  'dt' => 1 ),
//     array( 'db' => 'id',   'dt' => 2 ),
//     array( 'db' => 'id', 'dt' => 3,),
//     array( 'db' => 'id','dt' => 4,
//         'formatter' => function( $d, $row ) {
//             return 'aaaa';
//         }
//     )
   
// );

/* Indexed column (used for fast and accurate table cardinality) */
$sIndexColumn = "id";

/* DB table to use */
$sTable = "plants"; // table 1

/** index of join tables */
$sIndexTable = "index_clone"; // table 2

$sTable1_index = "id";
$sTable2_index = "plant_id";




/** join 2 tables
 * SELECT columnnamelist FROM table1 RIGHT JOIN table2 ON table1.col1=table2.col2
 */
$sJoin = "JOIN " . $sIndexTable . " ON " . $sTable . "." . $sTable1_index . "=" . $sIndexTable . "." . $sTable2_index;

require_once('./tableGeneral.php');
