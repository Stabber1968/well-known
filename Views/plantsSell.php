<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if (!$user->isLoggedIn()) {
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

$pSell = new Sell();
$mSellList = $p_general->getValueOfAnyTable('sell', '1', '=', '1');
$mSellList = $mSellList->results();

// For search "p" is id of plant, not plant UID
if ($_GET['p']) {
    $mSellList = $p_general->getValueOfAnyTable('sell', 'lot_ID', '=', $_GET['p']);
    $mSellList = $mSellList->results();
}
?>

<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="vault" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="vault_plants_tab" data-toggle="pill" href="#sell_content" role="tab" aria-controls="vault_plants_tab" aria-selected="true">Sell</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="historyReportPrintSell.php">Report Print</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="sell_content" role="tabpanel" aria-labelledby="sell_content">
                    <!-- Vault Plants Section-->
                    <div class="content-header nopadding">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <a class="btn btn-md bg-gradient-green " href="#" id="btn_addSell">
                                        <i class="fas fa-plus"></i>
                                        Add New Sell
                                    </a>
                                    <!--                    <a class="btn bg-gradient-green btn-md" id="btn_reportPrint" data-target="#modal-report-print-label" href="#modal-report-print-label" data-toggle="modal">-->
                                    <!--                        <i class="fas fa-print"></i>-->
                                    <!--                        Report Print-->
                                    <!--                    </a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.content-header -->
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="display:none">id</th>
                                        <th style="display:none">qr code really</th>
                                        <th style="display:none">Lot ID really</th>
                                        <th>Lot ID</th>
                                        <th><?=$_SESSION['lang_packing_number']?></th>
                                        <th style="display:none">Genetic id really</th>
                                        <th>Genetic</th>
                                        <th>Grams Amount</th>
                                        <th>Seeds Amount</th>
                                        <th>Date of sell</th>
                                        <th>price €</th>
                                        <th>Total Price €</th>
                                        <th style="display:none">client id really</th>
                                        <th>Client</th>
                                        <th>invoice number</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <!-- <tbody>
                                    <?php
                                    if ($mSellList) {
                                        $k = 0;
                                        foreach ($mSellList as $mSell) :
                                            $k++;
                                    ?>
                                            <tr>
                                                <td><?php echo $k ?></td>
                                                <td style="display:none" class="td_id"><?php echo $mSell->id ?></td>
                                                <td style="display:none" class="td_qr_code"><?php
                                                                                            $lot_id_info = $pSell->getValueOfAnyTable('lot_id', 'lot_ID', '=', $mSell->lot_ID);
                                                                                            $lot_id_info = $lot_id_info->results();
                                                                                            echo $lot_id_info[0]->qr_code;
                                                                                            ?></td>
                                                <td style="display:none" class="td_lot_ID"><?= $mSell->lot_ID ?></td>
                                                <td class="td_lot_ID_text"><?php
                                                                            $lot_ID_text = $p_general->getTextOflotID($mSell->lot_ID);
                                                                            echo $lot_ID_text ?></td>
                                                <td class="td_packing_number_text"><?php echo $p_general->getTextOfPackingNumber($mSell->packing_number) ?></td>

                                                <td style="display:none" class="td_genetic_ID"><?= $mSell->genetic ?></td>
                                                <td class="td_genetic_name"><?php
                                                                            $genetic_name = $p_general->getValueOfAnyTable('genetic', 'id', '=', $mSell->genetic);
                                                                            $genetic_name = $genetic_name->results();
                                                                            echo $genetic_name[0]->genetic_name ?></td>
                                                <td class="td_grams_amount"><?php echo $mSell->grams ?></td>
                                                <td class="td_seeds_amount"><?php echo $mSell->seeds_amount ?></td>
                                                <td class="td_sell_date"><?php echo $mSell->sell_date ?></td>
                                                <td class="td_grams_price"><?php echo $mSell->grams_price ?></td>
                                                <td class="td_total_price"><?php echo $mSell->total_price ?></td>
                                                <td style="display:none" class="td_client_id"><?php echo $mSell->client ?></td>
                                                <td class="td_client_name"><?php
                                                                            $client_info = $p_general->getValueOfAnyTable('client', 'id', '=', $mSell->client);
                                                                            $client_info = $client_info->results();
                                                                            echo $client_info[0]->name ?></td>
                                                <td class="td_invoice_number"><?php echo $mSell->invoice_number ?></td>
                                                <td style="text-align: center">
                                                    <a class="btn bg-gradient-green btn-sm" id="btn_editSell" data-target="#modal-add-sell" href="#modal-add-sell" data-toggle="modal">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Edit
                                                    </a>
                                                    <a class="btn bg-gradient-danger btn-sm" id="btn_deleteOnTable" data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>
                                                    <a class="btn bg-gradient-yellow btn-sm" id="btn_report">

                                                        <i class="fas fa-print"></i>
                                                        Report
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php endforeach;
                                    } ?>
                                </tbody> -->
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- modal (Add/Edit) -->
<div class="modal fade" id="modal-add-sell">
    <div class="modal-dialog width-modal-add-sell">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Add New Sell</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveSell.php" enctype='multipart/form-data' id="editSellFormValidate">
                <!--body-->
                <div class="modal-body">
                    <input name="cat" type="hidden" value="users">
                    <input name="id" id="id" type="hidden" value="">
                    <input name="act" id="act" type="hidden" value="">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Flower & Seeds</label>
                                <select class="form-control select2bs4" name="type" id="type" style="width: 100%;">
                                    <option value="flower">Flower</option>
                                    <option value="seeds">Seeds</option>
                                </select>
                            </div>
                            <div id="add_section">
                                <label>Genetic</label>
                                <div class="form-group">
                                    <select class="form-control select2bs4" name="genetic" id="genetic" style="width: 100%;">
                                        <option value="">Select Genetic</option>
                                        <?php
                                        $mGeneticList = $p_general->getValueOfAnyTable('genetic', '1', '=', '1');
                                        $mGeneticList = $mGeneticList->results();
                                        foreach ($mGeneticList as $mGenetic) {
                                            $existGenetic = $p_general->getValueOfAnyTable('vault', 'genetic_ID', '=', $mGenetic->id);
                                            $existGenetic = $existGenetic->results();
                                            if ($existGenetic) {
                                        ?>
                                                <option value="<?= $mGenetic->id ?>"><?= $mGenetic->genetic_name ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Lot ID</label>
                                    <select class="form-control select2bs4" name="lot_ID" id="lot_ID" style="width: 100%;">
                                        <option value="" selected>Select Lot ID</option>
                                        <?php
                                        $lotIDList = $p_general->query('SELECT * FROM vault GROUP BY lot_ID;');
                                        $lotIDList = $lotIDList->results();
                                        foreach ($lotIDList as $lot_ID) {
                                            $grams_amount = $lot_ID->grams_amount;
                                            $seeds_amount = $lot_ID->seeds_amount;
                                            if ($grams_amount) {
                                                $type = 'flower';
                                            }
                                            if ($seeds_amount) {
                                                $type = 'seeds';
                                            }
                                        ?>
                                            <option value="<?= $lot_ID->lot_ID ?>" data-genetic="<?= $lot_ID->genetic_ID ?>" data-type="<?= $type ?>"><?= $p_general->getTextOflotID($lot_ID->lot_ID) ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label><?=$_SESSION['lang_packing_number']?></label>
                                    <select class="form-control select2bs4" multiple="multiple" data-placeholder="Select <?=$_SESSION['lang_packing_number']?>s" name="packing_number[]" id="packing_number" style="width: 100%;">

                                    </select>
                                </div>

                            </div>
                            <div id="edit_section">
                                <label>QR Code</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="QR Code" id="qr_code" name="qr_code" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <label>Lot ID</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Lot ID" id="lot_id_text" name="lot_id_text" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="flower_section">
                                <label>Grams amount</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Grams amount" id="grams_amount" name="grams_amount" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="seeds_section">
                                <label>Seeds amount</label>
                                <div class="form-group input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Seeds amount" id="seeds_amount" name="seeds_amount" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label>Client</label>
                            <div class="form-group">
                                <select class="form-control select2bs4" name="client" id="client" style="width: 100%;">
                                    <option value="">Select Client</option>
                                    <?php
                                    $mClientList = $p_general->getValueOfAnyTable('client', '1', '=', '1');
                                    $mClientList = $mClientList->results();
                                    foreach ($mClientList as $mClient) {
                                    ?>
                                        <option value="<?= $mClient->id ?>"><?= $mClient->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Date of Sell</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="sell_date" name="sell_date" />
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <label>Price €</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Price for Gram €" id="grams_price" name="grams_price">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <label>Total Price €</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Grams" id="total_price" name="total_price" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <label>Invoice Numeber</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Invoice Numeber" id="invoice_number" name="invoice_number">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="btn_saveSell" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->

<!--warning modal when delete-->
<div class="modal fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete Plants</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Really Delete Plants&hellip;</p>
                <input type="hidden" id="delete_id" name="delete_id">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-outline-light" id="btn_deleteSell">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Report Print Label-->
<div class="modal fade" id="modal-report">
    <div class="modal-dialog width-modal-report-print-label">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Report Print</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--body-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Producer Name</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Producer Name" id="producer_name" name="producer_name" value="WEEZGARDEN" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Producer Address</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Producer Address" id="producer_address" name="producer_address" value="AAAAAA" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Lot number</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Lot number" id="lot_number" name="lot_number" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Box Quantity</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Box Quantity" id="packing_quantity" name="packing_quantity">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div class="form-group">-->
                        <!--                            <label>Product description</label>-->
                        <!--                            <div class="form-group">-->
                        <!--                                <select class="form-control select2bs4" id="product_description"  name="product_description"  style="width: 100%;">-->
                        <!--                                    <option value="Flower">Flower</option>-->
                        <!--                                    <option value="Seed">Seed</option>-->
                        <!---->
                        <!--                                </select>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="form-group">
                            <label>Product description</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Product description" id="product_description" name="product_description" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Net weight</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Net weight" id="net_weight" name="net_weight">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shipping date</label>
                            <div class="form-group input-group mb-3">
                                <div class="input-group date" id="reservationdate_1" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate_1" id="shipping_date_sell" name="shipping_date" />
                                    <div class="input-group-append" data-target="#reservationdate_1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Recipient Name</label>
                            <!-- <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Recipient Name" id="recipient_name" name="recipient_name">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <select class="form-control select2bs4" name="recipient_name" id="recipient_name" style="width: 100%;">
                                    <option value="">Select Client</option>
                                    <?php
                                    $mClientList = $p_general->getValueOfAnyTable('client', '1', '=', '1');
                                    $mClientList = $mClientList->results();
                                    foreach ($mClientList as $mClient) {
                                    ?>
                                        <option value="<?= $mClient->name ?>"><?= $mClient->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label>Recipient Address</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Recipient Address" id="recipient_address" name="recipient_address">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label><?=$_SESSION['lang_packing_number']?></label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="<?=$_SESSION['lang_packing_number']?>" id="packing_code" name="packing_code" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gross weight</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Gross weight" id="gross_weight" name="gross_weight">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--footer-->
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="#" id="btn_gotoReportPrintLabelPage" class="btn btn-primary">Save & Print</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<script>
    $(document).ready(function() {
        // START
        // Array holding selected row IDs
        var rows_selected = [];
        // datatable
        var table = $('#example1').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "../Logic/tableSellPlants.php",
                
            },
            order: [1, 'asc'],
            // "bInfo": false, // hidden showing entires of bottom
            "aoColumnDefs": [{
                    data: 'checkbox_id',
                    "aTargets": [0], // Column number which needs to be modified
                    "mRender": function(nRow, aData, iDisplayIndex) { // o, v contains the object and value for the column
                        $("td:first", nRow).html(iDisplayIndex +1);
                        return nRow;
                    },
                    "sClass": 'dt-body-center', // Optional - class to be applied to this table cell
                    "bSearchable": false,
                    "bSortable": true,
                    "sWidth": "1%",
                    // 'checkboxes': {
                    //     'selectRow': true
                    // },
                },
                {
                    data: 'td_id',
                    "sClass": "td_id d-none",
                    // "bVisible": false,
                    "aTargets": [1]
                },
                {
                    data: 'td_qr_code',
                    "sClass": "td_qr_code d-none",
                    // "bVisible": false,
                    "aTargets": [2],
                },
                {
                    data: 'td_lot_ID',
                    "sClass": "td_lot_ID d-none",
                    // "bVisible": false,
                    "aTargets": [3],
                },
                {
                    data: 'td_lot_ID_text',
                    "sClass": "td_lot_ID_text",
                    "aTargets": [4],
                },
                
                {
                    data: 'td_packing_number_text',
                    "sClass": "td_packing_number_text",
                    // "bVisible": false,
                    "aTargets": [5],
                },
                {
                    data: 'td_genetic_ID',
                    "sClass": "td_genetic_ID d-none",
                    // "bVisible": false,
                    "aTargets": [6],
                },
                
                {
                    data: 'td_genetic_name',
                    "sClass": "td_genetic_name ",
                    "aTargets": [7],
                },
                {
                    data: 'td_grams_amount',
                    "sClass": "td_grams_amount",
                    "aTargets": [8],
                },
                {
                    data: 'td_seeds_amount',
                    "sClass": "td_seeds_amount",
                    "aTargets": [9],
                },

                {
                    data: 'td_sell_date',
                    "sClass": "td_sell_date",
                    "aTargets": [10],
                },
                {
                    data: 'td_grams_price',
                    "sClass": "td_grams_price",
                    "aTargets": [11],
                },
                {
                    data: 'td_total_price',
                    "sClass": "td_total_price",
                    "aTargets": [12],
                },
                {
                    data: 'td_client_id',
                    "sClass": "td_client_id d-none",
                    "aTargets": [13],
                },
                
                {
                    data: 'td_client_name',
                    "sClass": "td_client_name",
                    "aTargets": [14],
                },
                {
                    data: 'td_invoice_number',
                    "sClass": "td_invoice_number",
                    "aTargets": [15],
                },
                
                {
                    data: 'buttons',
                    "aTargets": [16],
                    "mRender": function(data, type, row) { // o, v contains the object and value for the column
                        return '<a class="btn bg-gradient-green btn-sm" id="btn_editSell" data-target="#modal-add-sell" href="#modal-add-sell" data-toggle="modal">' +
                            '<i class="fas fa-pencil-alt"></i>' +
                            'Edit' +
                            '</a>' +
                            '<a class="btn bg-gradient-danger btn-sm" id="btn_deleteOnTable" data-toggle="modal" data-target="#modal-danger" href="#modal-danger">' +
                            '<i class="fas fa-trash"></i>' +
                            'Delete' +
                            '</a>' +
                            '<a class="btn bg-gradient-yellow btn-sm" id="btn_report">' +
                            '<i class="fas fa-print"></i>' +
                            'Report'
                        '</a>'
                    },
                }
            ],
        });
        // END

        $(document).on('click', '#btn_report', function () {
            //get Current Date
            var currentDate = _getCurrentDate();
            
            $("#shipping_date_sell").val(currentDate);
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var lot_number_text = $row.find('.td_lot_ID_text').text();
            var packing_number_text = $row.find('.td_packing_number_text').text();
            var grams_amount = $row.find('.td_grams_amount').text();
            var seeds_amount = $row.find('.td_seeds_amount').text();
            var product_description;
            if (grams_amount) {
                product_description = "flower";
            }
            if (seeds_amount) {
                product_description = "seed";
            }
            // verify it is already reported 
            $.ajax({
                method: 'POST',
                url: '../Logic/saveSell.php',
                data: {
                    act: 'report_exist',
                    lot_number_text: lot_number_text,
                    packing_code_text: packing_number_text
                },
                success: function(results) {
                    var exist = JSON.parse(results);
                    console.log(exist);
                    if (exist) {
                        //alert
                        swal.fire({
                            title: 'Report already exist',
                            // text: "Report already exist",
                            icon: 'warning',
                            // showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                            if (result.value) {
                            }
                        })
                    } else {
                        $("#lot_number").val(lot_number_text);
                        $("#packing_code").val(packing_number_text);
                        $("#product_description").val(product_description);
                        $('#modal-report').modal('show');
                    }
                }
            })
            // ...
        })

        //go Report page when click
        $('#btn_gotoReportPrintLabelPage').click(function() {
            var producer_name = $('#producer_name').val();
            var producer_address = $('#producer_address').val();
            var product_description = $('#product_description').val();
            var net_weight = $('#net_weight').val();
            var gross_weight = $('#gross_weight').val();
            var recipient_name = $('#recipient_name').val();
            var recipient_address = $('#recipient_address').val();
            var lot_number_text = $('#lot_number').val();
            var shipping_date = $('#shipping_date_sell').val();
            var packing_code = $('#packing_code').val();
            var packing_quantity = $('#packing_quantity').val();
            //register at history of report print of sell
            $.ajax({
                method: 'POST',
                url: '../Logic/saveSell.php',
                data: {
                    act: 'report',
                    producer_name: producer_name,
                    producer_address: producer_address,
                    product_description: product_description,
                    net_weight: net_weight,
                    gross_weight: gross_weight,
                    recipient_name: recipient_name,
                    recipient_address: recipient_address,
                    lot_number: lot_number_text,
                    shipping_date: shipping_date,
                    packing_code: packing_code,
                    packing_quantity: packing_quantity
                },
                success: function(results) {
                    var obj = JSON.parse(results);
                    console.log(obj);
                }
            })
            // ...
            //redirect to report print page
            setTimeout(
                function() {
                    $.redirect('../Views/ReportPrintLabelSell.php', {
                        producer_name: producer_name,
                        producer_address: producer_address,
                        product_description: product_description,
                        net_weight: net_weight,
                        gross_weight: gross_weight,
                        recipient_name: recipient_name,
                        recipient_address: recipient_address,
                        lot_number: lot_number_text,
                        shipping_date: shipping_date,
                        packing_code: packing_code,
                        packing_quantity: packing_quantity
                    }, 'POST', '_blank');
                }, 300);
            // ...

        })

        //calculate total price from  grams per price input button event
        $("#grams_price").on("change paste keyup", function() {
            var perPrice = $(this).val();
            var grams_amount = $('#grams_amount').val();
            var seeds_amount = $('#seeds_amount').val();
            var type = $('#type').val();
            var grams_totalPrice = perPrice * grams_amount;
            var seeds_totalPrice = perPrice * seeds_amount;
            if (type == "flower") {
                totalPrice = grams_totalPrice;
            }
            if (type == "seeds") {
                totalPrice = seeds_totalPrice;
            }
            $('#total_price').val(totalPrice);
        });

        //When add new sell ->
        $('select#type').change(function() {
            var type = $(this).val();
            if (type == "flower") {
                $('#flower_section').show();
                $('#seeds_section').hide();
            }
            if (type == "seeds") {
                $('#seeds_section').show();
                $('#flower_section').hide();
            }
            $('#packing_number').empty();
            $("#genetic").val('');
            $('#genetic').select2().trigger('change');
            $("#lot_ID").val('');
            $('#lot_ID').select2().trigger('change');
            $('#grams_price').val('');
            $('#total_price').val('');
        })

        //All lot ID list
        var origina_options = $('#lot_ID option').clone();
        //when Add new sell -> filter lot ID
        $('select#genetic').change(function() {
            $("#lot_ID").val('');
            $('#lot_ID').select2().trigger('change');
            var selectedType = $('#type').val();
            var selectedGeneticID = $('#genetic').val();
            if (selectedGeneticID) {
                options = origina_options;
                var cnt = options.length;
                var option_array = [];
                for (var i = 0; i < cnt; i++) {
                    var lot_ID = options[i].value;
                    var geneticID = options[i].dataset.genetic;
                    var type = options[i].dataset.type;
                    if (i == 0) {
                        //option -> select lot ID
                        option_array.push(options[i]);
                    } else {
                        if (parseInt(geneticID) == parseInt(selectedGeneticID)) {
                            //verify type
                            if (type == selectedType) {
                                option_array.push(options[i]);
                            }
                        }
                    }
                }
                console.log(option_array);
                $('#lot_ID').html(option_array);
                $('#lot_ID').select2();
            }
        });

        // change lot_ID select box ==> put genetic name automatically
        $("select#lot_ID").change(function() {
            var selectedLotID = $(this).val();
            $('#packing_number').empty();
            var type = $('#type').val();
            $.ajax({
                method: 'POST',
                url: '../Logic/saveSell.php',
                data: {
                    act: 'getPackingNumberList',
                    type: type,
                    selectedLotID: selectedLotID
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    html = '';
                    $.each(obj, function(key, value) {
                        console.log(value);
                        //make packing number text
                        var packing_number_text = '';
                        var unique_num_lotID = 1000 + parseInt(value);
                        packing_number_text = unique_num_lotID.toString().substring(1, 4);
                        var data = {
                            packing_number: value,
                            packing_number_text: packing_number_text
                        };
                        var newOption = new Option(data.packing_number_text, data.packing_number, false, false);
                        $('#packing_number').append(newOption).trigger('change');
                    });
                }
            })

        })

        //when add new sell,
        $("select#packing_number").change(function() {
            var selectedLotIDPackingNumberList = $(this).val();
            var type = $('#type').val();
            var selectedLotID = $('#lot_ID').val();

            if (selectedLotIDPackingNumberList[0]) {
                console.log(selectedLotIDPackingNumberList);
                $.ajax({
                    method: 'POST',
                    url: '../Logic/saveSell.php',
                    data: {
                        act: 'getTotalAmount',
                        type: type,
                        selectedLotID: selectedLotID,
                        selectedLotIDPackingNumberList: selectedLotIDPackingNumberList
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (type == "flower") {
                            $('#grams_amount').val(obj[0]);
                        }
                        if (type == "seeds") {
                            $('#seeds_amount').val(obj[0]);
                        }
                    }
                })
            } else {
                $('#grams_amount').val("");
                $('#seeds_amount').val("");
            }

        });

        $("#btn_deleteSell").click(function() {
            var id = $('#delete_id').val();
            $.ajax({
                method: 'POST',
                url: '../Logic/saveSell.php',
                data: {
                    act: 'delete',
                    id: id
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    console.log(obj);
                    if (obj == 'not_superAdmin') {
                        swal.fire({
                            title: 'You are not Super Admin',
                            // text: "Report already exist",
                            icon: 'warning',
                            // showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                        }).then((result) => {
                            if (result.value) {
                            }
                        })
                    }else{
                        $.redirect('../Views/plantsSell.php');
                    }


                }
            })
        });

        $(document).on('click', '#btn_deleteOnTable', function () {
            var $row = $(this).closest('tr');
            var id = $row.find('.td_id').text();
            $('#delete_id').val(id);
        });

        $("#btn_addSell").click(function() {
            var act = "add"
            //get Current Date
            var currentDate = _getCurrentDate();
            $("#sell_date").val(currentDate);
            $("#act").val(act);
            //hidden edit section when edit
            $("#edit_section").prop("hidden", true);
            $('#flower_section').show();
            $('#seeds_section').hide();
            $("#modal-add-sell").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $(document).on('click', '#btn_editSell', function () {
            var act = "edit";
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var qr_code = $row.find('.td_qr_code').text();
            var lot_ID_text = $row.find('.td_lot_ID_text').text();
            var genetic_name = $row.find('.td_genetic_name').text();
            var sell_date = $row.find('.td_sell_date').text();
            var grams_price = $row.find('.td_grams_price').text();
            var total_price = $row.find('.td_total_price').text();
            var client_id = $row.find('.td_client_id').text();
            var invoice_number = $row.find('.td_invoice_number').text();
            var grams_amount = $row.find('.td_grams_amount').text();
            var seeds_amount = $row.find('.td_seeds_amount').text();
            var type = "";
            $("#id").val(id);
            $("#act").val(act);
            $("#qr_code").val(qr_code);
            $("#lot_id_text").val(lot_ID_text);
            $("#sell_date").val(sell_date);
            $("#invoice_number").val(invoice_number);
            $('#client').val(client_id);
            $('#client').select2().trigger('change');
            $("#grams_amount").val(grams_amount);
            $("#seeds_amount").val(seeds_amount);
            if (grams_amount) {
                type = "flower";
                $('#flower_section').show();
                $('#seeds_section').hide();
                $("#grams_amount").val(grams_amount);
                $("#seeds_amount").val("");
            }
            if (seeds_amount) {
                type = "seeds";
                $('#flower_section').hide();
                $('#seeds_section').show();
                $("#seeds_amount").val(seeds_amount);
                $("#grams_amount").val("");
            }
            $("#type").val(type);
            $('#type').select2().trigger('change');
            $("#grams_price").val(grams_price);
            $("#total_price").val(total_price);
            //hidden Add section when edit
            $("#add_section").prop("hidden", true);
        });

        //modal close
        $('#modal-add-sell').on('hidden.bs.modal', function() {
            $("#qr_code").val('');
            $("#lot_id_text").val('');
            $("#grams").val('');
            $("#sell_date").val('');
            $("#grams_price").val('');
            $("#total_price").val('');
            $("#invoice_number").val('');
            $('#client').val('');
            $('#client').select2().trigger('change');
            //add
            $('#genetic').val('');
            $('#genetic').select2().trigger('change');
            $("#type").val('flower');
            $('#type').select2().trigger('change');
            //Show QR code input section when create clone plants
            $("#add_section").prop("hidden", false);
            $("#edit_section").prop("hidden", false);
        })
    });

    //Date range picker
    $('#reservationdate').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
    $('#reservationdate_1').datetimepicker({
        //format: 'L',
        format: "DD/MM/YYYY"
    });
</script>

<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>