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

$p_history = new History();

if ($_GET['act']) {
    if ($_GET['act'] == 'remove') {
        $p_history->deleteValueOfAnyTable('history', '1', '=', '1');
        header('location:../Views/history.php');
    }
}

if ($_POST['type']) {
    switch ($_POST['type']) {
        case 'mother':
            $search_tag = 'mother_UID';
            break;
        case 'plant':
            $search_tag = 'plant_UID';
            break;
            //        case 'lot':
            //            $search_tag = 'lot_ID';
            //            break;
    }
}

var_dump($_POST['lot_id']);

if ($_POST['id']) {
    $plant_id =  $_POST['id'];
    $historyList = $p_general->getValueOfAnyTable('history', $search_tag, '=', $plant_id);
    $historyList = $historyList->results();
} elseif ($_POST['lot_id']) {
    if ($_POST['packing_number']) {
        $lot_id =  $_POST['lot_id'];
        $historyList = $p_general->query('SELECT * FROM history WHERE lot_id = ' . $lot_id . ' AND packing_number = ' . $_POST['packing_number']);
        $historyList = $historyList->results();
    } else {
        $lot_id =  $_POST['lot_id'];
        $historyList = $p_general->getValueOfAnyTable('history', 'lot_id', '=', $lot_id);
        $historyList = $historyList->results();
    }
} else {
    $historyList = $p_history->getAllOfInfo();
    $historyList = $historyList->results();
}

//search

if ($_GET['p']) {
    $kind =  $_GET['kind'];
    $search_id = $_GET['p'];
    switch ($kind) {
        case 'lot':
            $search_tag = 'lot_id';
            break;
        case 'mother':
            $search_tag = 'mother_UID';
            break;
        case 'plant':
            $search_tag = 'plant_UID';
            break;
    }
    $historyList = $p_general->getValueOfAnyTable('history', $search_tag, '=', $search_id);
    $historyList = $historyList->results();
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="col-12 row ">
                        <div class="col-11 "> -->
                    <div class="row col-12  mt-4" id="fliterFormView">
                        <div class="form-group mb-3 col-3">
                            <label>User</label>
                            <select class="form-control select2bs4" name="col2_filter" id="col2_filter" style="width: 100%;">
                                <option value="">Select User</option>
                                <?php
                                $userlist = $p_general->query('SELECT * FROM history GROUP BY `user_name`;');
                                $userlist = $userlist->results();
                                foreach ($userlist as $userlist) {
                                    if($userlist->user_name == ""){
                                        continue;
                                ?>
                                <!-- <option value="null">NULL</option> -->
                                <?php
                                    } else {
                                ?>
                                    <option value="<?= $userlist->user_name ?>" ><?= $userlist->user_name ?></option>
                                <?php
                                } }
                                ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Plant ID / Lot ID / Mother ID</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Plant ID / Lot ID / Mother ID" id="col4_filter" name="col4_filter">
                            </div>
                        </div>

                        <div class="col-3">
                            <label>Start Date</label>
                            <div class="form-group input-group mb-3">
                                <input type="date" class="form-control" placeholder="Date" id="initial_date" name="initial_date">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>End Date</label>
                            <div class="form-group input-group mb-3">
                                <input type="date" class="form-control" placeholder="Date" id="final_date" name="final_date">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Note</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Note" id="col5_filter" name="col5_filter">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Observation</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Observation" id="col6_filter" name="col6_filter">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Room</label>
                            <select class="form-control select2bs4" name="col7_filter" id="col7_filter" style="width: 100%;">
                                <option value="">Select Room</option>
                                <?php
                                $userlist = $p_general->query('SELECT * FROM history GROUP BY `room_name`;');
                                $userlist = $userlist->results();
                                foreach ($userlist as $userlist) {
                                    if($userlist->room_name == "" || $userlist->room_name == null){
                                        continue;
                                ?>
                                <!-- <option value="null">NULL</option> -->
                                <?php
                                    } else {
                                ?>
                                    <option value="<?= $userlist->room_name ?>" ><?= $userlist->room_name ?></option>
                                <?php
                                } }
                                ?>
                            </select>
                            <!-- <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="Room" id="col7_filter" name="col7_filter">
                            </div> -->
                        </div>
                        <div class="col-3">
                            <label>QR Code</label>
                            <div class="form-group input-group mb-3">
                                <input type="text" class="form-control" placeholder="QR Code" id="col8_filter" name="col8_filter">
                            </div>
                        </div>
                        <div class="col-sm-12 text-danger" id="error_log"></div>
                    </div>
                    <!-- </div>
                        <div class="col-1 mt-4">
                            <label></label>
                            <button class="btn btn-md bg-gradient-green " id="btn_filter">
                                <i class="fas fa-plus"></i>
                                Fliter
                            </button>
                        </div>
                    </div> -->


                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <!--<th>No</th>-->
                                    <th>Date & Time</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th style="display:none">Plant ID/Lot ID / Mother ID</th>
                                    <th style="display:none">note</th>
                                    <th style="display:none">observation</th>
                                    <th style="display:none">Room Name</th>
                                    <th style="display:none">User Name</th>
                                    <th style="display:none">QR Code</th>
                                    <th style="display:none">LOT ID</th>
                                    <th style="display:none">Mother UID</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End content -->
</div>

<script>
    // function fnFilterGlobal() {
    //     $('#example').dataTable().fnFilter(
    //         $("#global_filter").val(),
    //         null,
    //         true,
    //         true
    //     );
    // }

    function fnFilterColumn(i) {
        // $('#example1').dataTable().fnFilter(
        //     $("#col" + (i + 1) + "_filter").val(),
        //     i,
        //     true,
        //     true
        // );
        
    }

    

    $(document).ready(function() {
        $('#btn_filter').on('click', function(event) {
            $('#fliterFormView').toggle('show');
        });
        var table;
        load_data(); // first load
        // document.getElementById("example1_filter").style.display = "None";
        // START
        // Array holding selected row IDs
        var rows_selected = [];
        // datatable
        function load_data(initial_date, final_date){
            
            table = $('#example1').DataTable({
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'print',
                        customize: function ( win ) {
                            $(win.document.body)
                                .css( 'font-size', '10pt' );
                            $(win.document.body).find( 'table' )
                                .addClass( 'compact' )
                                .css( 'font-size', 'inherit' );
                        }
                    }
                ],
                "ajax": {
                    "url": "../Logic/tableHistory.php",
                    "data": {
                        "act" : "fetch_history",
                        "initial_date" : initial_date, 
                        "final_date" : final_date,  
                        // "search_lot_id": _getHttpGetRequest('lot_id')
                        // "search_lot_id": ""
                    }
                },
                order: [0, 'desc'],
                "aoColumnDefs": [{
                        data: 'td_date',
                        "sClass": "td_date",
                        // "bVisible": false,
                        "aTargets": [0]
                    },
                    {
                        data: 'td_username',
                        "sClass": "td_username",
                        // "bVisible": false,
                        "aTargets": [1]
                    },
                    {
                        data: 'td_event',
                        "sClass": "td_event",
                        // "bVisible": false,
                        "aTargets": [2],
                    },
                    {
                        data: 'td_UID_text',
                        "sClass": "td_UID_text d-none",
                        // "bVisible": false,
                        "aTargets": [3],
                    },
                    {
                        data: 'td_note',
                        "sClass": "td_note d-none",
                        "aTargets": [4],
                    },
                    {
                        data: 'td_observation',
                        "sClass": "td_observation d-none",
                        "aTargets": [5],
                    },
                    {
                        data: 'td_room_name',
                        "sClass": "td_room_name d-none",
                        "aTargets": [6],
                    },
                    {
                        data: 'td_user_name',
                        "sClass": "td_user_name d-none",
                        "aTargets": [7],
                    },
                    {
                        data: 'td_qr_code',
                        "sClass": "td_qr_code d-none",
                        "aTargets": [8],
                    },
                    {
                        data: 'td_lot_id',
                        "sClass": "td_lot_id d-none",
                        "aTargets": [9],
                    },
                    {
                        data: 'td_mother_id',
                        "sClass": "td_mother_id d-none",
                        "aTargets": [10],
                    },
                ],

            });
        }
        // END
        
        // user filter
        $("#col2_filter").on('change', function() {
            var value = this.value ;
            if (table.columns(7).search() !== value) {
                table.columns(7).search(value).draw();
            }
        });

        $("#col4_filter").keyup(function() {
            var value = $("#col4_filter").val();
            if(value.length < 11) return;
            if (table.columns(3).search() !== value) {
                    table.columns(2).search(value).draw();
            }
        });
        // QR Code
        $("#col8_filter").keyup(function() {
            // if (table.search() !== $("#col" + (3 + 1) + "_filter").val()) {
            //     	   table.search($("#col" + (3 + 1) + "_filter").val()).draw();
            //     }
            var value = $("#col8_filter").val();
            if (table.columns(8).search() !== value) {
                    table.columns(8).search(value).draw();
            }
            // fnFilterColumn(3);
        });
        //date filter
        $("#final_date").on('change', function() {
            var initial_date = $("#initial_date").val();
            var final_date = $("#final_date").val();
            console.log(final_date);
            if(initial_date == '' && final_date == ''){
                $('#example1').DataTable().destroy();
                load_data("", ""); // filter immortalize only
            } else {
                var date1 = new Date(initial_date);
                var date2 = new Date(final_date);
                var diffTime = Math.abs(date2 - date1);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if(initial_date == '' || final_date == ''){
                    $("#error_log").html("Warning: You must select both (start and end) date.</span>");
                }else{
                    if(date1 > date2){
                        $("#error_log").html("Warning: End date should be greater then start date.");
                    }else{
                    $("#error_log").html(""); 
                    $('#example1').DataTable().destroy();
                    load_data(initial_date, final_date);
                    }
                }
            }
        });
        $('.input-daterange').datepicker({
            todayBtn:'linked',
            format: "yyyy-mm-dd",
            autoclose: true
        });

        // $("#col1_filter").keyup(function() {
        //     var value = $("#col1_filter").val();
        //     if (table.columns(0).search() !== value) {
        //             	   table.columns(0).search(value).draw();
        //             }
        // });
        //note filter
        $("#col5_filter").keyup(function() {
            // fnFilterColumn(4);
            var value = $("#col5_filter").val();
            if (table.columns(4).search() !== value) {
                    	   table.columns(4).search(value).draw();
            }
        });
        //observation filter
        $("#col6_filter").keyup(function() {
            // fnFilterColumn(5);
            var value = $("#col6_filter").val();
            if (table.columns(5).search() !== value) {
                    	   table.columns(5).search(value).draw();
                    }
        });
        //room filter
        $("#col7_filter").on('change', function() {
            var value = this.value ;
            if (table.columns(6).search() !== value) {
                    table.columns(6).search(value).draw();
            }
        });
    })
</script>

<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>