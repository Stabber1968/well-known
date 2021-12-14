<?php
require_once('../Controllers/init.php');

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../index.php');
}

$p_vault = new VaultPlant();
function generateCode($limit)
{
    $code = '';
    for ($i = 0; $i < $limit; $i++) {
        $code .= mt_rand(0, 9);
    }
    return $code;
}

if (Input::exists()) {
    //unneed because of multi generation
    if (Input::get('act') == 'generate') {
        $qr_code = $p_general->generateQRCode();
        $unique_num_plantUID =  generateCode(8);
        $plant_UID = substr($unique_num_plantUID, 0, 4) . "-" . substr($unique_num_plantUID, 4, 4);
        $s = [];
        $s[0] = $qr_code;
        $s[1] = $plant_UID;
        echo json_encode($s);
    }
    /////////////end////////////
    if (Input::get('act') == 'validate') {
        $validateQR = Input::get('qr_code');
        $validatePlantUID = Input::get('plant_UID');
        $isSame = $p_vault->isSameQR($validateQR);
        if ($isSame) {
            echo json_encode('SameQRCode');
        }
        echo json_encode($isSame);
    }

    if (Input::get('act') == 'edit') {
        try {
            $id_vault = Input::get('id'); // id of lot id (packing number) at vault.
            $VaultLotInfo = $p_general->getValueOfAnyTable('vault', 'id', '=', Input::get('id'));
            $VaultLotInfo = $VaultLotInfo->results();
            $packing_number = $VaultLotInfo[0]->packing_number;
            $packing_number_text = $p_general->getTextOfPackingNumber($packing_number);
            $lot_ID = $VaultLotInfo[0]->lot_ID;
            $LotID_text = Input::get('lot_ID_text');
            //For history --> Compare value between previous value and update value
            $prevGrams_amount = $VaultLotInfo[0]->grams_amount;
            $prevSeeds_amount = $VaultLotInfo[0]->seeds_amount;
            if ($prevGrams_amount) {
                $prevPlantPart = "flower";
            }
            if ($prevSeeds_amount) {
                $prevPlantPart = "seed";
            }
            $prevThc = $VaultLotInfo[0]->thc;
            $prevCbd = $VaultLotInfo[0]->cbd;
            $prevOther = $VaultLotInfo[0]->other;
            $prevVault_room_id = $VaultLotInfo[0]->location;
            $prevNote = $VaultLotInfo[0]->note;
            $historyText = $_SESSION['lang_packing_number'] . ' ' . $packing_number_text . '(' . $LotID_text . ')';
            if ($prevPlantPart != Input::get('plant_part')) {
                $eventChangePlantPart = $historyText . ' is changed Plant Part from *' . $prevPlantPart . '* to *' . Input::get('plant_part') . '* </br>';
                if (Input::get('plant_part') == "flower") {
                    $eventChangeGrams_amount = $historyText . ' is set Flower Grams Amount to *' . Input::get('amount') . '* </br>';
                }
                if (Input::get('plant_part') == "seed") {
                    $eventChangeSeeds_amount = $historyText . ' is changed Seeds Amount to *' . Input::get('amount') . '* </br>';
                }
            } else {
                if ($prevPlantPart == "flower") {
                    if ($prevGrams_amount != Input::get('amount')) {
                        $eventChangeGrams_amount = $historyText . ' is changed Flower Grams Amount from *' . $prevGrams_amount . '* to *' . Input::get('amount') . '* </br>';
                    }
                }
                if ($prevPlantPart == "seed") {
                    if ($prevSeeds_amount != Input::get('amount')) {
                        $eventChangeSeeds_amount = $historyText . ' is changed Seeds Amount from *' . $prevSeeds_amount . '* to *' . Input::get('amount') . '* </br>';
                    }
                }
            }
            if ($prevThc != Input::get('thc_content')) {
                $eventChangeThc = $historyText . ' is changed THC from *' . $prevThc . '* to *' . Input::get('thc_content') . '* </br>';
            }
            if ($prevCbd != Input::get('cbd_content')) {
                $eventChangeCbd = $historyText . ' is changed Cbd from *' . $prevCbd . '* to *' . Input::get('cbd_content') . '* </br>';
            }
            if ($prevOther != Input::get('other')) {
                $eventChangeOther = $historyText . ' is changed Other from *' . $prevOther . '* to *' . Input::get('other') . '* </br>';
            }
            if ($prevVault_room_id != Input::get('location')) {
                $prevRoominfo = $p_general->getValueOfAnyTable('room_vault', 'id', '=', $prevVault_room_id);
                $prevRoominfo = $prevRoominfo->results();
                $prevVault_room_name = $prevRoominfo[0]->name;
                $updateRoominfo = $p_general->getValueOfAnyTable('room_vault', 'id', '=', Input::get('location'));
                $updateRoominfo = $updateRoominfo->results();
                $updateVault_room_name = $updateRoominfo[0]->name;
                $eventChangeVault_room = $historyText . ' is changed Location from *Vault(' . $prevVault_room_name . ')* to *Vault(' . $updateVault_room_name . ')* </br>';
            }
            if ($prevNote != Input::get('note')) {
                $eventChangeNote = $historyText . ' is changed Note from *' . $prevNote . '* to *' . Input::get('note') . '* </br>';
            }
            //for update
            $thc = Input::get('thc_content');
            $cbd = Input::get('cbd_content');
            $other = Input::get('other');
            $vault_room_id = Input::get('location');
            $note = Input::get('note');
            $plant_part = Input::get('plant_part');
            //flower or seed
            if ($plant_part == "flower") {
                $seeds_amount = "";
                $grams_amount = Input::get('amount');
            }
            if ($plant_part == "seed") {
                $grams_amount = "";
                $seeds_amount = Input::get('amount');
            }
            $p_general->updateValueOfAnyTable('vault', array(
                'grams_amount'    => $grams_amount,
                'seeds_amount'    => $seeds_amount,
                'thc'    => $thc,
                'cbd'    => $cbd,
                'other'    => $other,
                'note'    => $note,
                'location'    => $vault_room_id,
            ), $id_vault);
            //Register lot ID packing number at History
            // get room name from room id
            $roomInfo = $p_general->getValueOfAnyTable('room_vault', 'id', '=', $vault_room_id);
            $roomInfo = $roomInfo->results();
            $room_name = $roomInfo[0]->name;
            // ...
            $event = $eventChangePlantPart . $eventChangeGrams_amount . $eventChangeSeeds_amount . $eventChangeThc . $eventChangeTrimming_method . $eventChangeVault_date . $eventChangeCbd . $eventChangeOther . $eventChangeVault_room . $eventChangeNote;
            $p_general->registerHistoryPacking($lot_ID, $user->data()->id, $event, $packing_number, $room_name, $note);
            $showRoom = Input::get('showRoom');
            if ($showRoom) {
                Redirect::to('../Views/plantsVault.php?room=' . $showRoom);
            } else {
                Redirect::to('../Views/plantsVault.php');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    if (Input::get('act') == 'delete') {
        $lotID_PackingNumber_List = Input::get('idList');
        foreach ($lotID_PackingNumber_List as $packingNumber) {
            //Register History
            $m_lotID_text = $p_general->getTextOflotID($lot_ID);
            $event = 'Deleted *' . $m_lotID_text . '*';
            $p_general->registerHistoryPacking($lot_ID, $user->data()->id, $event, $packingNumber);
            //Delete
            $result = $p_general->deleteValueOfAnyTable('vault', 'packing_number', '=', $packingNumber);
            //$result = $p_general->deleteValueOfAnyTable('index_vault','lot_id','=',$lot_ID);
        }
        echo json_encode($lot_ID_List);
    }

    if (Input::get('act') == 'selectMother') {
        $selectedMotherID = Input::get('selectedMotherID');
        $MotherInfo = $p_general->getValueOfAnyTable('plants', 'id', '=', $selectedMotherID);
        $MotherInfo = $MotherInfo->results();
        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $MotherInfo[0]->genetic);
        $geneticInfo = $geneticInfo->results();
        echo json_encode($geneticInfo[0]);
    }

    if (Input::get('act') == 'multi_print') {
        $start_ID = Input::get('start_ID');
        $end_ID = Input::get('end_ID');
        $data = array();
        for ($lot_ID = $start_ID; $lot_ID <= $end_ID; $lot_ID++) {
            //verify exist
            $exist = $p_general->getValueOfAnyTable('index_vault', 'lot_id', '=', $lot_ID);
            $exist = $exist->results();
            if (!$exist) {
                continue;
            }
            $lotIDInfo = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
            $lotIDInfo = $lotIDInfo->results();
            $s = [];
            $s[0] = $lotIDInfo[0]->qr_code; //data = qr code
            $s[1] = $p_general->getTextOflotID($lot_ID); //filename
            array_push($data, $s);
        }
        echo json_encode($data);
    }

    if (Input::get('act') == 'getReportInfo') {
        $lot_ID = Input::get('lot_ID');
        $lot_ID_info = $p_general->getValueOfAnyTable('lot_id', 'lot_ID', '=', $lot_ID);
        $lot_ID_info = $lot_ID_info->results();
        //genetic info for plant name and scientific name
        $geneticID = $lot_ID_info[0]->genetic_ID;
        $geneticInfo = $p_general->getValueOfAnyTable('genetic', 'id', '=', $geneticID);
        $geneticInfo = $geneticInfo->results();
        //lot info at vault for seed or flower
        $vaultInfo = $p_general->getValueOfAnyTable('vault', 'lot_ID', '=', $lot_ID);
        $vaultInfo = $vaultInfo->results();
        if ($vaultInfo[0]->grams_amount) {
            $plant_part = 'flower';
        } else if ($vaultInfo[0]->seeds_amount) {
            $plant_part = 'seed';
        }
        //result
        $plant_name = $geneticInfo[0]->plant_name;
        $scientific_name = $geneticInfo[0]->genetic_name;
        $cultivation_date = $lot_ID_info[0]->date;
        $harvest_date = $lot_ID_info[0]->harvest_date;
        $packing_date = $vaultInfo[0]->date;
        $varTime = DateTime::createFromFormat('d/m/Y', $packing_date);
        $date1 =  $varTime->format('m/d/Y'); // format to standard time format.
        $expiration_date = date('d/m/Y', strtotime($date1 . ' +1 year'));
        $lot_ID_text = $p_general->getTextOflotID($lot_ID);
        $qr_code = $lot_ID_info[0]->qr_code;

        $s = [];
        $s[0] = $plant_name;
        $s[1] = $scientific_name;
        $s[2] = $plant_part;
        $s[3] = $cultivation_date;
        $s[4] = $harvest_date;
        $s[5] = $packing_date;
        $s[6] = $expiration_date;
        $s[7] = $lot_ID_text;
        $s[8] = $qr_code;
        echo json_encode($s);
    }
}
