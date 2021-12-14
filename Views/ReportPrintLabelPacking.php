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
$p_Vault = new VaultPlant();
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
                    <thead>
                    <tr>
                        <th colspan="2" style="text-align: center;">
                            <img src="../Logic/image/logo_print.png">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <p>Nome da planta:  <?=$_POST['plant_name']?></p>
                            <p>Nome cientifico: <?=$_POST['scientific_name']?></p>
                        </td>
                        <td>
                            <p>Plant name: <?=$_POST['plant_name']?></p>
                            <p>Scientific name: <?=$_POST['scientific_name']?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>Parte da planta:  <?=$_POST['plant_part']?></div>
                            <div>Nome do produtor: <?=$_POST['producer_name']?></div>
                            <div>Local de origem: <?=$_POST['place_origin']?></div>
                        </td>
                        <td>
                            <div>Plant part: <?=$_POST['plant_part']?></div>
                            <div>Producer name: <?=$_POST['producer_name']?></div>
                            <div>Place of origin: <?=$_POST['place_origin']?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="row_print report_half_size">
                                <div class="print-col-7">
                                    <div>N* Lote: <?=$_POST['lot_ID_text']?></div>
                                    <div>N* Embalagem: <?=$_POST['packaging_number']?></div>
                                </div>
                                <div class="print-col-5">
                                    <div>Teor THC: <?=$_POST['thc_content']?>% </div>
                                    <div>Teor CBD: <?=$_POST['cbd_content']?>%</div>
                                </div>

                            </div>
                        </td>
                        <td>
                            <div class="row_print report_half_size">
                                <div class="print-col-7">
                                    <div>Lot number: <?=$lot_ID_name?></div>
                                    <div>Packaging number: <?=$_POST['packaging_number']?></div>
                                </div>
                                <div class="print-col-5">
                                    <div>THC content: <?=$_POST['thc_content']?>% </div>
                                    <div>CBD content: <?=$_POST['cbd_content']?>%</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="row_print">
                                <div class="print-col-8">
                                    <div>Data cultivo: <?=$_POST['cultivation_date']?></div>
                                    <div>Data colhita: <?=$_POST['harvest_date']?></div>
                                    <div>Data embalamento: <?=$_POST['packing_date']?></div>
                                    <div class="report_small_font">
                                        Dia/Mês/Ano
                                    </div>
                                </div>
                                <div class="print-col-4 report_qr_code">
                                    <img src="../QR_Code/<?=$lot_ID_name?>.png"/>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row_print">
                                <div class="print-col-8">
                                    <div>Cultivation date: <?=$_POST['cultivation_date']?></div>
                                    <div>Harvest date: <?=$_POST['harvest_date']?></div>
                                    <div>Packing date: <?=$_POST['packing_date']?></div>
                                    <div class="report_small_font">
                                        Day/Month/Year
                                    </div>
                                </div>
                                <div class="print-col-4 report_qr_code">
                                    <img src="../QR_Code/<?=$lot_ID_name?>.png"/>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>Quantidade (peso liqido): <?=$_POST['amount']?>g</div>
                            <div>Prazo de validade: <?=$_POST['expiration_date']?></div>
                            <div class="report_small_font">
                                Dia/Mês/Ano
                            </div>
                        </td>
                        <td>
                            <div>Amount (net weight): <?=$_POST['amount']?>g</div>
                            <div>Expiration date: <?=$_POST['expiration_date']?></div>
                            <div class="report_small_font">
                                Day/Month/Year
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</form>


