<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();

if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

$num = 1;

$p_dashboard = new Dashboard();
//get Total count of Mother
$index_mother_info = $p_general->getValueOfAnyTable('index_mother','1','=','1');
$m_count_mother = $index_mother_info = $index_mother_info->count();

//get Total count of clone
$m_count_clone = 0;
$index_clone_info = $p_general->getValueOfAnyTable('index_clone','1','=','1');
$index_clone_info = $index_clone_info ->results();
foreach($index_clone_info as $item) {
    $lotIDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$item -> lot_id);
    $lotIDInfo = $lotIDInfo -> results();
    $m_count_clone += $lotIDInfo[0] -> number_of_plants; 
}

//get Total count of veg
$m_count_veg = 0;
$index_veg_info = $p_general->getValueOfAnyTable('index_veg','1','=','1');
$index_veg_info = $index_veg_info -> results();
foreach($index_veg_info as $item) {
    $lotIDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$item -> lot_id);
    $lotIDInfo = $lotIDInfo -> results();
    $m_count_veg += $lotIDInfo[0] -> number_of_plants; 
}

//get Total count of flower
$m_count_flower = 0;
$index_flower_info = $p_general->getValueOfAnyTable('index_flower','1','=','1');
$index_flower_info = $index_flower_info -> results();
foreach($index_flower_info as $item) {
    $lotIDInfo = $p_general->getValueOfAnyTable('lot_id','lot_ID','=',$item -> lot_id);
    $lotIDInfo = $lotIDInfo -> results();
    $m_count_flower += $lotIDInfo[0] -> number_of_plants; 
}



//get sales according to Month in Current Year.
$current_Year = date("Y");
$last_Year = $current_Year - 1;

$monthly_sale_current_year = array();
$monthly_sale_current_year = array_fill(0,13,0);
$monthly_sale_last_year = array();
$monthly_sale_last_year = array_fill(0,13,0);

$total_sales_info = $p_general->getValueOfAnyTable('sell','1','=','1');
$total_sales_info = $total_sales_info->results();

$year_total_grams_sale = 0;
$year_total_seeds_sale = 0;

foreach($total_sales_info as $sale_info){
    //verify date of sale is current year or last year
    $sale_date = $sale_info->sell_date;
    $d = date_parse_from_format("d/m/Y", $sale_date);
    $sale_date_month = $d["month"];
    $sale_date_year = $d["year"];

    if($sale_date_year == $current_Year){
        // get year total grams
        $year_total_grams_sale += $sale_info->grams;
        $year_total_seeds_sale += $sale_info->seeds_amount;

        switch ($sale_date_month){
            case 1:
                $monthly_sale_current_year['1'] += $sale_info->total_price;
                break;
            case 2:
                $monthly_sale_current_year['2'] += $sale_info->total_price;
                break;
            case 3:
                $monthly_sale_current_year['3'] += $sale_info->total_price;
                break;
            case 4:
                $monthly_sale_current_year['4'] += $sale_info->total_price;
                break;
            case 5:
                $monthly_sale_current_year['5'] += $sale_info->total_price;
                break;
            case 6:
                $monthly_sale_current_year['6'] += $sale_info->total_price;
                break;
            case 7:
                $monthly_sale_current_year['7'] += $sale_info->total_price;
                break;
            case 8:
                $monthly_sale_current_year['8'] += $sale_info->total_price;
                break;
            case 9:
                $monthly_sale_current_year['9'] += $sale_info->total_price;
                break;
            case 10:
                $monthly_sale_current_year['10'] += $sale_info->total_price;
                break;
            case 11:
                $monthly_sale_current_year['11'] += $sale_info->total_price;
                break;
            case 12:
                $monthly_sale_current_year['12'] += $sale_info->total_price;
                break;
        }
    }elseif($sale_date_year == $last_Year){

        switch ($sale_date_month){
            case 1:
                $monthly_sale_last_year['1'] += $sale_info->total_price;
                break;
            case 2:
                $monthly_sale_last_year['2'] += $sale_info->total_price;
                break;
            case 3:
                $monthly_sale_last_year['3'] += $sale_info->total_price;
                break;
            case 4:
                $monthly_sale_last_year['4'] += $sale_info->total_price;
                break;
            case 5:
                $monthly_sale_last_year['5'] += $sale_info->total_price;
                break;
            case 6:
                $monthly_sale_last_year['6'] += $sale_info->total_price;
                break;
            case 7:
                $monthly_sale_last_year['7'] += $sale_info->total_price;
                break;
            case 8:
                $monthly_sale_last_year['8'] += $sale_info->total_price;
                break;
            case 9:
                $monthly_sale_last_year['9'] += $sale_info->total_price;
                break;
            case 10:
                $monthly_sale_last_year['10'] += $sale_info->total_price;
                break;
            case 11:
                $monthly_sale_last_year['11'] += $sale_info->total_price;
                break;
            case 12:
                $monthly_sale_last_year['12'] += $sale_info->total_price;
                break;
        }
    }
}

//get Year totol sales
$year_total_price = 0;
$month_count = 0;
foreach($monthly_sale_current_year as $value){
    if($value){
        $month_count ++;
        $year_total_price += $value;
    }
}
//get Monthly Sale
if($year_total_price){
    $monthly_sale = $year_total_price/$month_count;
}else{
    $monthly_sale = 0;
}

//for Genetic
$genetic_list = $p_general->getValueOfAnyTable('genetic','1','=','1');
$genetic_list = $genetic_list->results();

//get total grams, seeds on vault
$total_grams_on_vault = 0;
$total_seeds_on_vault = 0;

$vaultinfo = $p_general->getValueOfAnyTable('vault','1','=','1');
$vaultinfo = $vaultinfo->results();
foreach($vaultinfo as $vault){
    $total_grams_on_vault += intval($vault->grams_amount);
    $total_seeds_on_vault += intval($vault->seeds_amount);
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard Administrator</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cannabis"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Mother Plants</span>
                                <span class="info-box-number">
                                  <?=$m_count_mother?>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cannabis"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Clone Plants</span>
                            <span class="info-box-number"><?=$m_count_clone?></span>
                        </div>
                    </div>
                </div>

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cannabis"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Vegatation Plants</span>
                            <span class="info-box-number"><?=$m_count_veg?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cannabis"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Flower Plants</span>
                            <span class="info-box-number"><?=$m_count_flower?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Sales Report(&euro;)</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>Sales: 1 Jan, <?=$current_Year?> - 30 Dec, <?=$current_Year?></strong>
                                    </p>
                                    <div class="chart">
                                        <canvas id="salesChart" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <p class="text-center">
                                            <strong>Genetic on Vault / Grams</strong>
                                        </p>
                                        <?php
                                        foreach($genetic_list as $genetic){
                                            //get grams of a genetic on vault
                                            $total_grams = 0;
                                            $geneticinfoListonVault = $p_general->getValueOfAnyTable('vault','genetic_ID','=',$genetic->id);
                                            $geneticinfoListonVault = $geneticinfoListonVault->results();
                                            foreach($geneticinfoListonVault as $geneticinfo){
                                                $total_grams += intval($geneticinfo->grams_amount);
                                            }
                                            if($total_grams){
                                                ?>
                                                <div class="progress-group">
                                                    <?=$genetic->genetic_name?>
                                                    <span class="float-right"><b><?=$total_grams?></b>/<?=$total_grams_on_vault?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?=$p_dashboard->get_percentage($total_grams_on_vault,$total_grams)?>%"></div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div>
                                        <p class="text-center">
                                            <strong>Genetic on Vault / Seeds</strong>
                                        </p>
                                        <?php
                                        foreach($genetic_list as $genetic){
                                            //get seeds of a genetic on vault
                                            $total_seeds = 0;
//                                            $geneticinfoListonVault = $p_general->getValueOfAnyTable('vault','genetic_ID','=',$genetic->id);
//                                            $geneticinfoListonVault = $geneticinfoListonVault->results();
                                            foreach($geneticinfoListonVault as $geneticinfo){
                                                $total_seeds += intval($geneticinfo->seeds_amount);
                                            }
                                            if($total_seeds){
                                                ?>
                                                <div class="progress-group">
                                                    <?=$genetic->genetic_name?>
                                                    <span class="float-right"><b><?=$total_seeds?></b>/<?=$total_seeds_on_vault?></span>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?=$p_dashboard->get_percentage($total_grams_on_vault,$total_grams)?>%"></div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-2 col-6">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header">&euro;<?=$monthly_sale?></h5>
                                        <span class="description-text">Monthly Sales</span>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header">&euro;<?=$year_total_price?></h5>
                                        <span class="description-text">Year Sales</span>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header"><?=$year_total_grams_sale?></h5>
                                        <span class="description-text">Year Total Grams Sales</span>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header"><?=$year_total_seeds_sale?></h5>
                                        <span class="description-text">Year Total Seeds Sales</span>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header"><?=$total_grams_on_vault?></h5>
                                        <span class="description-text">Total Grams on Vault</span>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-6">
                                    <div class="description-block">
                                        <br>
                                        <h5 class="description-header"><?=$total_seeds_on_vault?></h5>
                                        <span class="description-text">Total Seeds on Vault</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Section -->
            <div class="row">
                <div class="col-md-12" >
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Count Each Room - <span id="room_name"></span></h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>Productions: 1 Jan, <?=$current_Year?> - 30 Dec, <?=$current_Year?></strong>
                                    </p>
                                    <div class="chart">
                                        <canvas id="productionChart" height="300" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <a href="#" class="col-sm-3 col-6" id="btn_monthly_mother_plant">
                                    <div class="description-block border-right">
                                        <br>
                                        <h5 class="description-header"><?=$m_count_mother?></h5>
                                        <span class="description-text">Mother Room</span>
                                    </div>
                                </a>
                                <div class="col-sm-3 col-6">
                                    <a href="#"  class="description-block border-right" id="btn_monthly_clone_plant">
                                        <br>
                                        <h5 class="description-header"><?=$m_count_clone?></h5>
                                        <span class="description-text">Clone Room</span>
                                    </a>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <a href="#" class="description-block border-right" id="btn_monthly_veg_plant">
                                        <br>
                                        <h5 class="description-header"><?=$m_count_veg?></h5>
                                        <span class="description-text">Vegetation Room</span>
                                    </a>
                                </div>
                                <a href="#" class="col-sm-3 col-6" id="btn_monthly_flower_plant">
                                    <div class="description-block">
                                        <br>
                                        <h5 class="description-header"><?=$m_count_flower?></h5>
                                        <span class="description-text">Flower Room</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Mother chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Mother Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_mother" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Clone chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Clone Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_clone" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Veg chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Vegetation Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_veg" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Flower chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Flower Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_flower" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Dry chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Dry Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_dry" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Trimming chart -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Trimming Room
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="genetic_chart_trimming" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>


<script>
    $(document).ready(function (){
        //User Section
        //for chart of monthly count of room
        var current_year = new Date().getFullYear();
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_monthly_plant_mother',room:'mother',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                if(obj){
                    var m_p_c_y = obj[0];
                    var m_p_l_y = obj[1];
                    document.getElementById("room_name").innerHTML = "Mother Room";
                    productionChart.data.datasets[0].data = [m_p_c_y[1], m_p_c_y[2], m_p_c_y[3], m_p_c_y[4], m_p_c_y[5], m_p_c_y[6], m_p_c_y[7], m_p_c_y[8], m_p_c_y[9], m_p_c_y[10], m_p_c_y[11], m_p_l_y[12]];
                    productionChart.data.datasets[1].data = [m_p_l_y[1], m_p_l_y[2], m_p_l_y[3], m_p_l_y[4], m_p_l_y[5], m_p_l_y[6], m_p_l_y[7], m_p_l_y[8], m_p_l_y[9], m_p_l_y[10], m_p_l_y[11], m_p_l_y[12]];
                    productionChart.update();
                }
            }
        })

        //for genetic chart of mother room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_mother_room',room:'mother',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });
                genetic_chart_mother.data.labels = label_data;
                genetic_chart_mother.data.datasets[0].data = count_data;
                genetic_chart_mother.update();
            }
        })

        //for genetic chart of clone room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_room',room:'clone',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });
                genetic_chart_clone.data.labels = label_data;
                genetic_chart_clone.data.datasets[0].data = count_data;
                genetic_chart_clone.update();
            }
        })

        //for genetic chart of veg room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_room',room:'veg',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });
                genetic_chart_veg.data.labels = label_data;
                genetic_chart_veg.data.datasets[0].data = count_data;
                genetic_chart_veg.update();
            }
        })

        //for genetic chart of flower room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_room',room:'flower',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });
                genetic_chart_flower.data.labels = label_data;
                genetic_chart_flower.data.datasets[0].data = count_data;
                genetic_chart_flower.update();
            }
        })

        //for genetic chart of dry room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_room',room:'dry',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);

                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });

                genetic_chart_dry.data.labels = label_data;
                genetic_chart_dry.data.datasets[0].data = count_data;
                genetic_chart_dry.update();
            }
        })

        //for genetic chart of trimming room
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_genetic_room',room:'trimming',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                var length = obj.length;
                var label_data = [];
                var count_data = [];
                obj.forEach(function(entry){
                    label_data.push(entry.genetic_name);
                    count_data.push(entry.count_plants);
                });
                genetic_chart_trimming.data.labels = label_data;
                genetic_chart_trimming.data.datasets[0].data = count_data;
                genetic_chart_trimming.update();
            }
        })
    });

    //-----------------------
    //- MONTHLY SALES CHART -
    //-----------------------

    var array = '<?=json_encode($monthly_sale_current_year)?>';
    var monthly_sale_current_year = JSON.parse(array);

    var array = '<?=json_encode($monthly_sale_last_year)?>';
    var monthly_sale_last_year = JSON.parse(array);

    console.log(monthly_sale_current_year);
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');

    var salesChartData = {
        labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
        datasets: [
            {
                label               : 'Current Year Sales',
                backgroundColor     : 'rgba(255,99,132,0.2)',
                borderColor         : 'rgba(255,99,132,1)',
//                    pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [monthly_sale_current_year['1'], monthly_sale_current_year['2'], monthly_sale_current_year['3'], monthly_sale_current_year['4'], monthly_sale_current_year['5'], monthly_sale_current_year['6'], monthly_sale_current_year['7'], monthly_sale_current_year['8'], monthly_sale_current_year['9'], monthly_sale_current_year['10'], monthly_sale_current_year['11'], monthly_sale_current_year['12']]
//                    data                : [60, 0, 0,0, 0, 0, 0, 0, 0, 230, 0, 0]

            },
            {
                label               : 'Last Year Sales',
//                    backgroundColor     : 'rgba(40, 167, 69, 0.62)',
//                    borderColor         : 'rgba(210, 214, 222, 1)',
//                    pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [monthly_sale_last_year['1'], monthly_sale_last_year['2'], monthly_sale_last_year['3'], monthly_sale_last_year['4'], monthly_sale_last_year['5'], monthly_sale_last_year['6'], monthly_sale_last_year['7'], monthly_sale_last_year['8'], monthly_sale_last_year['9'], monthly_sale_last_year['10'], monthly_sale_last_year['11'], monthly_sale_last_year['12']]
            },
        ]
    }

    var salesChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
            display: true
        },
        scales: {
            xAxes: [{
                gridLines : {
                    display : false,
                }
            }],
            yAxes: [{
                gridLines : {
                    display : false,
                },
                ticks: {
                    min: 0,
                }
            }]
        }
    }

    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas, {
            type: 'line',
            data: salesChartData,
            options: salesChartOptions
        }
    )
    //---------------------------
    //- END MONTHLY SALES CHART -
    //---------------------------

    //User Section
    //click Mother Room
    $('#btn_monthly_mother_plant').click(function(){
        event.preventDefault();
        var current_year = new Date().getFullYear();
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_monthly_plant_mother',room:'mother',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                if(obj){
                    var m_p_c_y = obj[0];
                    var m_p_l_y = obj[1];
                    document.getElementById("room_name").innerHTML = "Mother Room";
                    productionChart.data.datasets[0].data = [m_p_c_y[1], m_p_c_y[2], m_p_c_y[3], m_p_c_y[4], m_p_c_y[5], m_p_c_y[6], m_p_c_y[7], m_p_c_y[8], m_p_c_y[9], m_p_c_y[10], m_p_c_y[11], m_p_l_y[12]];
                    productionChart.data.datasets[1].data = [m_p_l_y[1], m_p_l_y[2], m_p_l_y[3], m_p_l_y[4], m_p_l_y[5], m_p_l_y[6], m_p_l_y[7], m_p_l_y[8], m_p_l_y[9], m_p_l_y[10], m_p_l_y[11], m_p_l_y[12]];
                    productionChart.update();
                }
            }
        })
    });

    //Click Clone Room
    $('#btn_monthly_clone_plant').click(function(){
        event.preventDefault();
        var current_year = new Date().getFullYear();
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_monthly_plant',room:'clone',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);
                if(obj){
                    var m_p_c_y = obj[0];
                    var m_p_l_y = obj[1];
                    document.getElementById("room_name").innerHTML = "Clone Room";
                    productionChart.data.datasets[0].data = [m_p_c_y[1], m_p_c_y[2], m_p_c_y[3], m_p_c_y[4], m_p_c_y[5], m_p_c_y[6], m_p_c_y[7], m_p_c_y[8], m_p_c_y[9], m_p_c_y[10], m_p_c_y[11], m_p_l_y[12]];
                    productionChart.data.datasets[1].data = [m_p_l_y[1], m_p_l_y[2], m_p_l_y[3], m_p_l_y[4], m_p_l_y[5], m_p_l_y[6], m_p_l_y[7], m_p_l_y[8], m_p_l_y[9], m_p_l_y[10], m_p_l_y[11], m_p_l_y[12]];
                    productionChart.update();
                }
            }
        })
    });

    //Click veg Room
    $('#btn_monthly_veg_plant').click(function(){
        event.preventDefault();
        var current_year = new Date().getFullYear();
        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_monthly_plant',room:'veg',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);

                if(obj){
                    var m_p_c_y = obj[0];
                    var m_p_l_y = obj[1];

                    document.getElementById("room_name").innerHTML = "Vegetation Room";
                    productionChart.data.datasets[0].data = [m_p_c_y[1], m_p_c_y[2], m_p_c_y[3], m_p_c_y[4], m_p_c_y[5], m_p_c_y[6], m_p_c_y[7], m_p_c_y[8], m_p_c_y[9], m_p_c_y[10], m_p_c_y[11], m_p_l_y[12]];
                    productionChart.data.datasets[1].data = [m_p_l_y[1], m_p_l_y[2], m_p_l_y[3], m_p_l_y[4], m_p_l_y[5], m_p_l_y[6], m_p_l_y[7], m_p_l_y[8], m_p_l_y[9], m_p_l_y[10], m_p_l_y[11], m_p_l_y[12]];
                    productionChart.update();
                }
//                            alert(obj);
            }
        })
    });

    //Click flower Room
    $('#btn_monthly_flower_plant').click(function(){
        event.preventDefault();

        var current_year = new Date().getFullYear();

        $.ajax({
            method:'POST',
            url: '../Logic/saveDashboard.php',
            data: {act:'get_monthly_plant',room:'flower',current_year:current_year},
            success:function(data){
                var obj = JSON.parse(data);

                if(obj){
                    var m_p_c_y = obj[0];
                    var m_p_l_y = obj[1];
                    document.getElementById("room_name").innerHTML = "Flower Room";
                    productionChart.data.datasets[0].data = [m_p_c_y[1], m_p_c_y[2], m_p_c_y[3], m_p_c_y[4], m_p_c_y[5], m_p_c_y[6], m_p_c_y[7], m_p_c_y[8], m_p_c_y[9], m_p_c_y[10], m_p_c_y[11], m_p_l_y[12]];
                    productionChart.data.datasets[1].data = [m_p_l_y[1], m_p_l_y[2], m_p_l_y[3], m_p_l_y[4], m_p_l_y[5], m_p_l_y[6], m_p_l_y[7], m_p_l_y[8], m_p_l_y[9], m_p_l_y[10], m_p_l_y[11], m_p_l_y[12]];
                    productionChart.update();
                }
//                            alert(obj);
            }
        })
    });

    //-----------------------
    //- MONTHLY Production CHART -
    //-----------------------


    // Get context with jQuery - using jQuery's .get() method.
    var productionChartCanvas = $('#productionChart').get(0).getContext('2d');

    var productionChartData = {
        labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December'],
        datasets: [
            {
                label               : 'Current Year Productions',
                backgroundColor     : 'rgba(255,99,132,0.2)',
                borderColor         : 'rgba(255,99,132,1)',
//                    pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]

            },
            {
                label               : 'Last Year Productions',
//                    backgroundColor     : 'rgba(40, 167, 69, 0.62)',
//                    borderColor         : 'rgba(210, 214, 222, 1)',
//                    pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data                : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0],

            },
        ]
    }

    var productionChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
            display: true
        },
        scales: {
            xAxes: [{
                gridLines : {
                    display : false,
                }
            }],
            yAxes: [{
                gridLines : {
                    display : false,
                },
                ticks: {
                    min: 0,
                }
            }]
        }
    }

    // This will get the first returned node in the jQuery collection.
    var productionChart = new Chart(productionChartCanvas, {
            type: 'line',
            data: productionChartData,
            options: productionChartOptions
        }
    )
    //---------------------------
    //- END MONTHLY Production CHART -
    //---------------------------


    //--------------
    //- AREA CHART -
    //--------------
    var donutChartCanvas_mother = $('#genetic_chart_mother').get(0).getContext('2d');
    var donutChartCanvas_clone = $('#genetic_chart_clone').get(0).getContext('2d');
    var donutChartCanvas_veg = $('#genetic_chart_veg').get(0).getContext('2d');
    var donutChartCanvas_flower = $('#genetic_chart_flower').get(0).getContext('2d');
    var donutChartCanvas_dry = $('#genetic_chart_dry').get(0).getContext('2d');
    var donutChartCanvas_trimming = $('#genetic_chart_trimming').get(0).getContext('2d');

    var Data_mother  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }

    var Data_clone  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }

    var Data_veg  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }

    var Data_flower  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }

    var Data_dry  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }

    var Data_trimming  = {
        labels: [
            'genetic',
        ],
        datasets: [
            {
                data: [0],
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
        ]
    }


    var Options     = {
        maintainAspectRatio : false,
        responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var genetic_chart_mother = new Chart(donutChartCanvas_mother, {
        type: 'doughnut',
        data: Data_mother,
        options: Options
    })

    var genetic_chart_clone = new Chart(donutChartCanvas_clone, {
        type: 'doughnut',
        data: Data_clone,
        options: Options
    })

    var genetic_chart_veg = new Chart(donutChartCanvas_veg, {
        type: 'doughnut',
        data: Data_veg,
        options: Options
    })

    var genetic_chart_flower = new Chart(donutChartCanvas_flower, {
        type: 'doughnut',
        data: Data_flower,
        options: Options
    })

    var genetic_chart_dry = new Chart(donutChartCanvas_dry, {
        type: 'doughnut',
        data: Data_dry,
        options: Options
    })

    var genetic_chart_trimming = new Chart(donutChartCanvas_trimming, {
        type: 'doughnut',
        data: Data_trimming,
        options: Options
    })

    //--------------
    //- End AREA CHART -
    //--------------


</script>

