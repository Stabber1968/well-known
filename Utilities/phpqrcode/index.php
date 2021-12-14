<?php    
/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */


    if($_REQUEST['act'] == "multi"){

        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../QR_Code'.DIRECTORY_SEPARATOR;
        //html PNG location prefix
        $PNG_WEB_DIR = 'temp/';

        include "qrlib.php";

        foreach($_REQUEST['data'] as $value){

            //get data from Ajax Request
            $data = $value[0];
            $file = $value[1];

            //ofcourse we need rights to create temp dir
            if (!file_exists($PNG_TEMP_DIR))
                mkdir($PNG_TEMP_DIR);

            $filename = $PNG_TEMP_DIR.'test.png';

            //processing form input
            //remember to sanitize user input in real-life solution !!!
            $errorCorrectionLevel = 'L'; // Quality(default)
            if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
                $errorCorrectionLevel = $_REQUEST['level'];

            $matrixPointSize = 2; // Size(default)
            if (isset($_REQUEST['size']))
                $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
            if (isset($data)) {

                //it's very important!
                if (trim($data) == '')
                    die('data cannot be empty! <a href="?">back</a>');

                // user data
                //default  $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
                $filename = $PNG_TEMP_DIR.$file.'.png';

                QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

            } else {
                QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            }

        }
        echo json_encode("ok");




    }else{
        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../../QR_Code'.DIRECTORY_SEPARATOR;

        //get data from Ajax Request
        $data = $_REQUEST['data'];
        $file = $_REQUEST['filename'];
        //html PNG location prefix
        $PNG_WEB_DIR = 'temp/';

        include "qrlib.php";

        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR))
            mkdir($PNG_TEMP_DIR);


        $filename = $PNG_TEMP_DIR.'test.png';

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L'; // Quality(default)
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 2; // Size(default)
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


        if (isset($data)) {

            //it's very important!
            if (trim($data) == '')
                die('data cannot be empty! <a href="?">back</a>');

            // user data
            //default  $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
            $filename = $PNG_TEMP_DIR.$file.'.png';

            QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        } else {
            //default data
            echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';
            QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        }

        echo json_encode("Successful Generated !");
        // benchmark
//    QRtools::timeBenchmark();
    }



    