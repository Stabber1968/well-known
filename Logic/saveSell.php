<?php
require_once('../Controllers/init.php');

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

//$p_general = new General();

$p_sell = new Sell();

if (Input::exists()) {

    if (Input::get('act') == 'validate') {
        $validateName = Input::get('name');
        $isSame = $p_sell->isSame($validateName);
        if ($isSame) {
            echo json_encode('SameName');
        } else {
            echo json_encode($isSame);
        }
    }

    if (Input::get('act') == 'add') {
        try {
            $selectedLotIDPackingNumberList = Input::get('packing_number');
            $selectedLotID = Input::get('lot_ID');

            $type = Input::get('type');
            foreach ($selectedLotIDPackingNumberList as $lot_IDPackingNumber) {
                $genetic_id =  Input::get('genetic');
                $lot_ID_Info = $p_general->getValueOfAnyTable('vault', 'packing_number', '=', $lot_IDPackingNumber);
                $lot_ID_Info = $lot_ID_Info->results();
                $sell_date = Input::get('sell_date');
                $grams_price = Input::get('grams_price');
                if ($type == "flower") {
                    $grams_amount = $lot_ID_Info[0]->grams_amount;
                    $total_price = $grams_amount * $grams_price;
                }
                if ($type == "seeds") {
                    $seeds_amount = $lot_ID_Info[0]->seeds_amount;
                    $total_price = $seeds_amount * $grams_price;
                }
                $client = Input::get('client');
                $invoice_number = Input::get('invoice_number');
                // Create sell
                $p_sell->create(array(
                    'lot_ID' => $selectedLotID,
                    'packing_number' => $lot_IDPackingNumber,
                    'genetic' => $genetic_id,
                    'sell_date' => $sell_date,
                    'grams_price' => $grams_price,
                    'total_price' => $total_price,
                    'client' => $client,
                    'invoice_number' => $invoice_number,
                    'grams' => $grams_amount,
                    'seeds_amount' => $seeds_amount,
                ));
                //delete lot at vault room
                $p_general->query("DELETE FROM vault WHERE lot_ID=" . $selectedLotID . " AND packing_number=" . $lot_IDPackingNumber);
                //for history, lot sell to xxx client.
                //                $m_lotID_text = $p_sell->getTextOflotID($selectedLotID);
                //                $clientInfo = $p_sell->getValueOfAnyTable('client','id','=',$client);
                //                $clientInfo = $clientInfo->results();
                //                $event = $m_lotID_text.' is sell to Client *'.$clientInfo[0]->name.'*';
                //                $p_general->registerHistoryLot($selectedLotID,$user->data()->id,$event, $user->data()->name);
            }
            Redirect::to('../Views/plantsSell.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    if (Input::get('act') == 'edit') {
        try {
            $type = Input::get('type');
            $grams = Input::get('grams');
            $sell_date = Input::get('sell_date');
            $grams_price = Input::get('grams_price');
            if ($type == "flower") {
                $grams_amount = Input::get('grams_amount');
                $total_price = $grams_amount * $grams_price;
            }
            if ($type == "seeds") {
                $seeds_amount = Input::get('seeds_amount');
                $total_price = $seeds_amount * $grams_price;
            }
            $client = Input::get('client');
            $invoice_number = Input::get('invoice_number');
            $p_general->updateValueOfAnyTable('sell',array(
                'grams' => $grams_amount,
                'seeds_amount' => $seeds_amount,
                'sell_date' => $sell_date,
                'grams_price' => $grams_price,
                'total_price' => $total_price,
                'client' => $client,
                'invoice_number' => $invoice_number,
            ), Input::get('id'));
            Redirect::to('../Views/plantsSell.php');
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'delete') {
        if ($user->data()->superAdmin) {
            // Register History
            $mInfo = $p_general->getValueOfAnyTable('sell', 'id', '=', Input::get('id'));
            $mInfo = $mInfo->results();
            $packing_number_text = $p_general->getTextOfPackingNumber($mInfo[0]->packing_number);
            $lotID_text = $p_general->getTextOflotID($mInfo[0]->lot_ID);
            $event = 'Deleted '.$_SESSION['lang_packing_number'].'*' . $packing_number_text . '*' . ' of Lot ID*' . $lotID_text . '*';
            $p_general->registerHistoryPacking($mInfo[0]->lot_ID, $user->data()->id, $event, $mInfo[0]->packing_number);
            // ...
            $p_general->deleteValueOfAnyTable('sell', 'id', '=', Input::get('id'));
            echo json_encode('success');
        } else {
            echo json_encode('not_superAdmin');
        }
    }

    if (Input::get('act') == 'deleteReport') {
        $mList = Input::get('idList'); // report print id
        foreach ($mList as $id) {
            //Register History
            //            $m_lotID_text = $p_general->getTextOflotID($lot_ID);
            //            $event = 'Deleted *'.$m_lotID_text.'*';
            //            $p_general->registerHistoryPacking($lot_ID,$user->data()->id,$event,$packingNumber);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('history_reportprint_sell', 'id', '=', $id);
        }
        echo json_encode($lot_ID_List);
    }

    if (Input::get('act') == 'getTotalAmount') {
        $selectedLotIDPackingNumberList = Input::get('selectedLotIDPackingNumberList');
        $type = Input::get('type');
        $selectedLotID = Input::get('selectedLotID');
        $total_amount = 0;
        foreach ($selectedLotIDPackingNumberList as $lotIDPackingNumber) {
            $gramsInfo = $p_general->query("SELECT * FROM vault WHERE `packing_number`=" . $lotIDPackingNumber . " AND `lot_ID`=" . $selectedLotID);
            $gramsInfo = $gramsInfo->results();
            if ($type == "flower") {
                $total_amount += $gramsInfo[0]->grams_amount;
            }
            if ($type == "seeds") {
                $total_amount += $gramsInfo[0]->seeds_amount;
            }
        }
        $s = [];
        $s[0] = $total_amount;
        echo json_encode($s);
    }

    if (Input::get('act') == 'getPackingNumberList') {
        $selectedLotID = Input::get('selectedLotID');
        $type = Input::get('type');
        $lotID_PackingNumberinfoList = $p_general->getValueOfAnyTable('vault', 'lot_ID', '=', $selectedLotID);
        $lotID_PackingNumberinfoList = $lotID_PackingNumberinfoList->results();
        $allowPackingNumberList = array();
        foreach ($lotID_PackingNumberinfoList as $compareLotID) {
            if ($type == "flower") {
                if ($compareLotID->grams_amount) {
                    array_push($allowPackingNumberList, $compareLotID->packing_number);
                }
            }
            if ($type == "seeds") {
                if ($compareLotID->seeds_amount) {
                    array_push($allowPackingNumberList, $compareLotID->packing_number);
                }
            }
        }
        echo json_encode($allowPackingNumberList);
    }

    if (Input::get('act') == 'report_exist') {
        $lot_number_text = Input::get('lot_number_text');
        $packing_code_text = Input::get('packing_code_text');

        // verify the report is already in report page
        $query_text = "SELECT * FROM history_reportprint_sell WHERE `packing_code`=" . "'" . $packing_code_text . "'" . " AND `lot_number`=" . "'" . $lot_number_text . "'";
        $mVerify = $p_general->query($query_text);
        $count = $mVerify->count();
        // $mVerify = $mVerify->results();
        // ...
        if ($count) {
            echo json_encode(true); // exist
        } else {
            echo json_encode(false); // non-exist
        }
    }


    if (Input::get('act') == 'report') {
        $producer_name = Input::get('producer_name');
        $producer_address = Input::get('producer_address');
        $product_description = Input::get('product_description');
        $net_weight = Input::get('net_weight');
        $gross_weight = Input::get('gross_weight');
        $recipient_name = Input::get('recipient_name');
        $recipient_address = Input::get('recipient_address');
        $lot_number_text = Input::get('lot_number');
        $shipping_date = Input::get('shipping_date');
        $packing_code_text = Input::get('packing_code');
        $packing_quantity = Input::get('packing_quantity');

        $p_sell->createHistoryReportPrintSell(array(
            'producer_name' => $producer_name,
            'producer_address' => $producer_address,
            'product_description' => $product_description,
            'net_weight' => $net_weight,
            'gross_weight' => $gross_weight,
            'recipient_name' => $recipient_name,
            'recipient_address' => $recipient_address,
            'lot_number' => $lot_number_text,
            'shipping_date' => $shipping_date,
            'packing_code' => $packing_code_text,
            'packing_quantity' => $packing_quantity,
        ));
        echo json_encode(true);
    }

    if (Input::get('act') == 'isType') {
        $lot_ID = Input::get('lot_ID');
        $lotIDInfo = $p_general->getValueOfAnyTable('vault', 'lot_ID', '=', $lot_ID);
        $lotIDInfo = $lotIDInfo->results();
        $grams_amount = $lotIDInfo[0]->grams_amount;
        $seeds_amount = $lotIDInfo[0]->seeds_amount;
        if ($grams_amount) {
            $type = 'flower';
        }
        if ($seeds_amount) {
            $type = 'seeds';
        }
        echo json_encode($type);
    }
}
