<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();

if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

$mList = $p_general->getValueOfAnyTable('history_reportprint_sell','1','=','1');
$mList = $mList->results();
?>


<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" href="plantsSell.php" role="tab" >Sell</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-selected="true">Report Print</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" role="tabpanel" >
                    <!-- Vault Plants Section-->
                    <div class="content-header nopadding" >
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-8" ></div>
                                <div class="col-sm-4" style="text-align:right">
                                    <a class="btn bg-gradient-danger btn-md"  data-toggle="modal" data-target="#modal-danger" href="#modal-danger">
                                        <i class="fas fa-trash"></i>
                                        Delete Report Print
                                    </a>
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
                                    <th><input type="checkbox" name="select_all"></th>
                                    <th style="display:none">id</th>
                                    <th style="display:none">Producer Name</th>
                                    <th style="display:none">Producer Address</th>
                                    <th>Lot Id</th>
                                    <th><?=$_SESSION['lang_packing_number']?></th>
                                    <th>Box Quantity</th>
                                    <th>Product description</th>
                                    <th>Net weight</th>
                                    <th>Shipping date</th>
                                    <th >Recipient Name</th>
                                    <th style="display:none">Recipient Address</th>
                                    <th>Gross weight</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="plant_table">

                                <?php
                                if($mList){
                                    $k = 0;
                                    foreach ($mList as $value):
                                        $k++;
                                        ?>
                                        <tr>
                                            <td>
                                                <input class="plantCheckBox" type="checkbox" id="checkbox_<?=$k?>" value="<?=$value->id?>">
                                            </td>
                                            <td style="display:none"><?=$value->id?></td>
                                            <td style="display:none" class="td_producer_name"><?=$value->producer_name?></td>
                                            <td style="display:none" class="td_producer_address"><?=$value->producer_address?></td>
                                            <td class="td_lot_ID_text"><?=$value->lot_number?></td>
                                            <td class="td_packing_code"><?=$value->packing_code?></td>

                                            <td class="td_packing_quantity"><?=$value->packing_quantity?></td>
                                            <td class="td_product_description"><?=$value->product_description?></td>
                                            <td class="td_net_weight"><?=$value->net_weight?></td>
                                            <td class="td_shipping_date"><?=$value->shipping_date?></td>
                                            <td class="td_recipient_name"><?=$value->recipient_name?></td>
                                            <td style="display:none" class="td_recipient_address"><?=$value->recipient_address?></td>
                                            <td class="td_gross_weight"><?=$value->gross_weight?></td>
                                            <td>
                                                <a class="btn bg-gradient-yellow btn-sm" id="btn_report" data-target="#modal-report" href="#modal-report" data-toggle="modal">
                                                    <i class="fas fa-print"></i>
                                                    Report
                                                </a>
                                            </td>

                                        </tr>
                                    <?php endforeach;} ?>
                                </tbody>
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
                                <input type="text" class="form-control" placeholder="Box Quantity" id="packing_quantity" name="packing_quantity" readonly>
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
                                <input type="text" class="form-control" placeholder="Net weight" id="net_weight" name="net_weight" readonly>
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
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate_1" id="shipping_date" name="shipping_date"/>
                                    <div class="input-group-append" data-target="#reservationdate_1" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Recipient Name</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Recipient Name" id="recipient_name" name="recipient_name" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label>Recipient Address</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Recipient Address" id="recipient_address" name="recipient_address" readonly>
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
                                <input type="text" class="form-control" placeholder="Gross weight" id="gross_weight" name="gross_weight" readonly>
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
                <a href="#" id ="btn_gotoReportPrintLabelPage" class="btn btn-primary" >Print</a>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<!--Modal delete warning-->
<div class="modal fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">Delete Report</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Really Delete &hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-outline-light" id="btn_delete">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<script>
    //Select All , for that let's see https://jsfiddle.net/gyrocode/abhbs4x8/
    function updateDataTableSelectAllCtrl(table){
        var $chkbox_all        = $('#plant_table input[type="checkbox"]');
        var $chkbox_checked    = $('#plant_table input[type="checkbox"]:checked');
        var chkbox_select_all  = $('thead input[name="select_all"]');
        // If none of the checkboxes are checked
        if($chkbox_checked.length === 0){
            chkbox_select_all[0].checked = false;
            // If all of the checkboxes are checked
        } else if ($chkbox_checked.length === $chkbox_all.length){
            chkbox_select_all[0].checked = true;
            // If some of the checkboxes are checked
        } else {
            chkbox_select_all[0].checked = false;
        }
    }

    $(document).ready(function(){

        // For Select All
        var table = $('#example1').dataTable({
            'order': [1, 'asc'],
            "sPaginationType": "full_numbers",
            stateSave: true,
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': [0] }
            ],
        });

        table.on('draw.dt', function(){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('#example1 tbody').on('click', 'input[type="checkbox"]', function(e){
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        $('input[name="select_all"]').on('click', function(e) {
            if(this.checked){
                $('#example1 tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#example1 tbody input[type="checkbox"]:checked').trigger('click');
            }
            e.stopPropagation();
        });
        //END

        var checkedPlantList = [];
        var allPages = table.fnGetNodes();

        //Click Delete Selected Plants Button
        $('#btn_delete').click(function(){
            checkedPlantList = [];
            $.each($("input[class='plantCheckBox']:checked", allPages), function(){
                //push selected Vault plants ID
                checkedPlantList.push($(this).val());
            });
            var RoomID = $('#currentRoomID').val();
            $.ajax({
                method:'POST',
                url: '../Logic/saveSell.php',
                data:{act:'deleteReport', idList:checkedPlantList},
                success:function (data) {
                    $.redirect('../Views/historyReportPrintSell.php',
                        {
                            room: RoomID
                        },
                        'GET');
                }
            })
            console.log('currently checked Plants list');
            console.log(checkedPlantList)
        });

        $('tr #btn_report').click(function() {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var producer_name =  $row.find('.td_producer_name').text();
            var producer_address =  $row.find('.td_producer_address').text();
            var product_description =  $row.find('.td_product_description').text();
            var net_weight =  $row.find('.td_net_weight').text();
            var gross_weight =  $row.find('.td_gross_weight').text();
            var recipient_name =  $row.find('.td_recipient_name').text();
            var recipient_address =  $row.find('.td_recipient_address').text();
            var lot_number =  $row.find('.td_lot_ID_text').text();
            var shipping_date =  $row.find('.td_shipping_date').text();
            var packing_code =  $row.find('.td_packing_code').text();
            var packing_quantity =  $row.find('.td_packing_quantity').text();

            $("#producer_name").val(producer_name);
            $("#producer_address").val(producer_address);
            $("#product_description").val(product_description);
            $("#net_weight").val(net_weight);
            $("#gross_weight").val(gross_weight);
            $("#recipient_name").val(recipient_name);
            $("#recipient_address").val(recipient_address);
            $("#lot_number").val(lot_number);
            $("#shipping_date").val(shipping_date);
            $("#packing_code").val(packing_code);
            $("#packing_quantity").val(packing_quantity);

        })

        //go Report page when click
        $('#btn_gotoReportPrintLabelPage').click(function(){
            var producer_name = $('#producer_name').val();
            var producer_address = $('#producer_address').val();
            var product_description = $('#product_description').val();
            var net_weight = $('#net_weight').val();
            var gross_weight = $('#gross_weight').val();
            var recipient_name = $('#recipient_name').val();
            var recipient_address = $('#recipient_address').val();
            var lot_number = $('#lot_number').val();
            var shipping_date = $('#shipping_date').val();
            var packing_code = $('#packing_code').val();
            var packing_quantity = $('#packing_quantity').val();
            //redirect to report print page
            setTimeout(
                function()
                {
                    $.redirect('../Views/ReportPrintLabelSell.php',{
                        producer_name:producer_name,
                        producer_address:producer_address,
                        product_description:product_description,
                        net_weight:net_weight,
                        gross_weight:gross_weight,
                        recipient_name:recipient_name,
                        recipient_address:recipient_address,
                        lot_number:lot_number,
                        shipping_date:shipping_date,
                        packing_code:packing_code,
                        packing_quantity:packing_quantity
                    }, 'POST','_blank');
                }, 300);
        })


    });
</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
