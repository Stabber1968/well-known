<?php

require_once('../Controllers/init.php');


/* Database connection information */
$gaSql['user']       = "root";
$gaSql['password']   = "";
$gaSql['db']         = "plants";
$gaSql['server']     = "localhost";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */

/* 
	 * MySQL connection
	 */
$connect =  mysqli_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password')) or
    die('Could not open connection to server');

mysqli_select_db($connect, Config::get('mysql/db')) or
    die('Could not select database ' . Config::get('mysql/db'));


/* 
	 * Paging
	 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . mysqli_real_escape_string($connect, $_GET['iDisplayStart']) . ", " .
        mysqli_real_escape_string($connect, $_GET['iDisplayLength']);
}


/*
	 * Ordering
     *     $sOrder = "ORDER BY  ".$sTable.".";
	 */
if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY  ";
    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
            $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysqli_real_escape_string($connect, $_GET['sSortDir_' . $i]) . ", ";
        }
    }

    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
}


/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($connect, $_GET['sSearch']) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}


/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
        if ($sWhere == "") {
            $sWhere = "WHERE ";
        } else {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($connect, $_GET['sSearch_' . $i]) . "%' ";
    }
}


/*
	 * SQL queries
	 * Get data to display
     * 		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
     * 
     *  SELECT SQL_CALC_FOUND_ROWS plants.id, plants.id, plants.qr_code, plants.plant_UID, plants.name
		FROM   plants
        JOIN index_clone ON plants.id=index_clone.plant_id
		
		ORDER BY  id
				 	asc
		LIMIT 0, 10
        *          
	 */

$sQuery = "
        SELECT SQL_CALC_FOUND_ROWS " . $sTable . "." . str_replace(" , ", " ", implode(", " . $sTable . ".", $aColumns)) . "
		FROM   $sTable
        $sJoin
		$sWhere
		$sOrder
		$sLimit
	";
// var_dump("#####test", $sQuery);

$rResult = mysqli_query($connect, $sQuery);

/* Data set length after filtering */
$sQuery = "
		SELECT FOUND_ROWS()
	";
$rResultFilterTotal = mysqli_query($connect, $sQuery);
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
	";
$rResultTotal = mysqli_query($connect, $sQuery);
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];


/*
	 * Output
	 */
$sOutput = '{';
$sOutput .= '"sEcho": ' . intval($_GET['sEcho']) . ', ';
$sOutput .= '"iTotalRecords": ' . $iTotal . ', ';
$sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
$sOutput .= '"aaData": [ ';
while ($aRow = mysqli_fetch_array($rResult)) {
    $sOutput .= "[";
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == "version") {
            /* Special output formatting for 'version' */
            $sOutput .= ($aRow[$aColumns[$i]] == "0") ?
                '"-",' :
                '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
        } else if ($aColumns[$i] != ' ') {
            /* General output */
            $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
        }
    }

    /*
		 * Optional Configuration:
		 * If you need to add any extra columns (add/edit/delete etc) to the table, that aren't in the
		 * database - you can do it here
		 */


    $sOutput = substr_replace($sOutput, "", -1);
    $sOutput .= "],";
}
$sOutput = substr_replace($sOutput, "", -1);
$sOutput .= '] }';

// echo json_encode($sOutput); 
echo $sOutput;
