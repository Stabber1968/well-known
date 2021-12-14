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

$lot_ID = $_POST['lot_ID'];
$p_sell = new Sell();
$lot_ID_name = $p_general->getTextOflotID($lot_ID);
?>

<div class="row" style="padding: 30px">
    <button class="btn btn-primary" style="width: 300px" type="button" onclick="printJS({ printable: 'printJS-form', type: 'html',css: '../dist/css/print.css'})">
        Print
    </button>
</div>

<form method="post" action="#" id="printJS-form">
    <div class="reportPrintContainer">
        <div class="reportPrintCard">
            <div class="reportPrintBody">
                <table style="width:100%">
                    <tbody>
                    <tr>
                        <td rowspan="4" class="reportTextAlignTop">
                            <div class="row_print report_half_size reportTextAlignCenter">
                                Produtor/Producer
                            </div>
                            <div class="reportImageMargin">
                                <img width="300px" src="../Logic/image/logo_print.png">
                            </div>
                            <div class="row_print">
                                Nome/Name: <?=$_POST['producer_name']?>
                            </div>
                            <br>
                            <div class="row_print" style="padding-bottom: 2px">
                                Morada/Address: <?=$_POST['producer_address']?>
                            </div>
                            <br>
                        </td>
                        <td  class="reportTextAlignTop">
                            <div class="report_half_size">
                                <div class="row_print report_half_size reportTextAlignCenter">Destinatário/Recipient</div>
                                <div>Nome/Name: <?=$_POST['recipient_name']?></div>
                                <div>Morada/Adress: <?=$_POST['recipient_address']?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>Número de Lote: <?=$_POST['lot_number']?></div>
                            <div>Lot number: <?=$_POST['lot_number']?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>Data de expedição: <?=$_POST['shipping_date']?> <span class="report_small_font">Dia/Mês/Ano</span></div>
                            <div>Shipping date: <?=$_POST['shipping_date']?> <span class="report_small_font">Day/Month/Year</span> </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>Código da embalagem: <?=$_POST['packing_code']?></div>
                            <div>Packing code: <?=$_POST['packing_code']?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>descrição do produto: <?=$_POST['product_description']?> </div>
                            <div>(Cannabis Sativa L)</div>
                            <div>Porduct description: <?=$_POST['product_description']?> </div>
                            <div>(Cannabis Sativa L)</div>
                        </td>
                        <td>
                            <div>Quantidade de Embalagens: <?=$_POST['packing_quantity']?> unidades</div>
                            <div>Packing Quantity: <?=$_POST['packing_quantity']?> units</div>
                            <div class="row_print">
                                <div class="print-col-6 reportTextAlignCenter report_middle_font ">
                                    <div>Peso Liq/Net weight:</div>
                                    <div><?=$_POST['net_weight']?>kg</div>
                                </div>
                                <div class="print-col-6 reportTextAlignCenter report_middle_font">
                                    <div>Peso Bruto/Gross weight:</div>
                                    <div><?=$_POST['gross_weight']?>kg</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="row_print reportSpaceAround">
                                <img src="../QR_Code/default.png"/>
                                <img src="../QR_Code/default.png"/>
                                <img src="../QR_Code/default.png"/>
                                <img src="../QR_Code/default.png"/>
                            </div>
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
        </div>

    </div>

</form>


