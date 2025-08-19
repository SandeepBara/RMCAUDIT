<?=$this->include('layout_vertical/header');?>
<?php
$sl_no=0;
$grand_total_amt=0;

$cash_amount=0;
$cash_count=0;
$dd_amount=0;
$dd_count=0;
$cheque_amount=0;
$cheque_count=0;
$rtgs_amount=0;
$rtgs_count=0;
$card_amount=0;
$card_count=0;
$neft_count = 0;
$neft_amount = 0;
$upi_count = 0;
$upi_amount = 0;
$other_count = 0;
$other_amount = 0;

foreach($prop_payment as $payment){
    if(strtoupper($payment["tran_mode"])=="CASH"){
        $cash_amount+= $payment["payable_amt"];
        ++$cash_count;
    }
    else if(strtoupper($payment["tran_mode"])=="DD"){
        $dd_amount+= $payment["payable_amt"];
        ++$dd_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["payable_amt"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["tran_mode"])=="RTGS"){
        $rtgs_amount+= $payment["payable_amt"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CARD"){
        $card_amount+= $payment["payable_amt"];
        ++$card_count;
    }else if (strtoupper($payment["tran_mode"]) == "NEFT") {
        $neft_amount += $payment["payable_amt"];
        ++$neft_count;
    } else if (strtoupper($payment["tran_mode"]) == "UPI") {
        $upi_amount += $payment["payable_amt"];
        ++$upi_count;
    }else {
        $other_amount += $payment["payable_amt"];
        ++$other_count;
    }
}


foreach($saf_payment as $payment){
    if(strtoupper($payment["tran_mode"])=="CASH"){
        $cash_amount+= $payment["payable_amt"];
        ++$cash_count;
    }
    else if(strtoupper($payment["tran_mode"])=="DD"){
        $dd_amount+= $payment["payable_amt"];
        ++$dd_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["payable_amt"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["tran_mode"])=="RTGS"){
        $rtgs_amount+= $payment["payable_amt"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CARD"){
        $card_amount+= $payment["payable_amt"];
        ++$card_count;
    }else if (strtoupper($payment["tran_mode"]) == "NEFT") {
        $neft_amount += $payment["payable_amt"];
        ++$neft_count;
    }else if (strtoupper($payment["tran_mode"]) == "UPI") {
        $upi_amount += $payment["payable_amt"];
        ++$upi_count;
    } else {
        $other_amount += $payment["payable_amt"];
        ++$other_count;
    }
}

foreach($gbsaf_payment as $payment){
    if(strtoupper($payment["tran_mode"])=="CASH"){
        $cash_amount+= $payment["payable_amt"];
        ++$cash_count;
    }
    else if(strtoupper($payment["tran_mode"])=="DD"){
        $dd_amount+= $payment["payable_amt"];
        ++$dd_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["payable_amt"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["tran_mode"])=="RTGS"){
        $rtgs_amount+= $payment["payable_amt"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["tran_mode"])=="CARD"){
        $card_amount+= $payment["payable_amt"];
        ++$card_count;
    } else if (strtoupper($payment["tran_mode"]) == "NEFT") {
        $neft_amount += $payment["payable_amt"];
        ++$neft_count;
    }else if (strtoupper($payment["tran_mode"]) == "UPI") {
        $upi_amount += $payment["payable_amt"];
        ++$upi_count;
    } else {
        $other_amount += $payment["payable_amt"];
        ++$other_count;
    }
}

foreach($water_payment as $payment){
    if(strtoupper($payment["payment_mode"])=="CASH"){
        $cash_amount+= $payment["paid_amount"];
        ++$cash_count;
    }
    else if(strtoupper($payment["payment_mode"])=="DD"){
        $dd_amount+= $payment["paid_amount"];
        ++$dd_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["paid_amount"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["payment_mode"])=="RTGS"){
        $rtgs_amount+= $payment["paid_amount"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CARD"){
        $card_amount+= $payment["paid_amount"];
        ++$card_count;
    }else if (strtoupper($payment["payment_mode"]) == "NEFT") {
        $neft_amount += $payment["paid_amount"];
        ++$neft_count;
    }else if (strtoupper($payment["payment_mode"]) == "UPI") {
        $upi_amount += $payment["paid_amount"];
        ++$upi_count;
    } else {
        $other_amount += $payment["paid_amount"];
        ++$other_count;
    }
}

foreach($water_conn_payment as $payment){
    if(strtoupper($payment["payment_mode"])=="CASH"){
        $cash_amount+= $payment["paid_amount"];
        ++$cash_count;
    }
    else if(strtoupper($payment["payment_mode"])=="DD"){
        $dd_amount+= $payment["paid_amount"];
        ++$dd_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["paid_amount"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["payment_mode"])=="RTGS"){
        $rtgs_amount+= $payment["paid_amount"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CARD"){
        $card_amount+= $payment["paid_amount"];
        ++$card_count;
    }else if (strtoupper($payment["payment_mode"]) == "NEFT") {
        $neft_amount += $payment["paid_amount"];
        ++$neft_count;
    }else if (strtoupper($payment["payment_mode"]) == "UPI") {
        $upi_amount += $payment["paid_amount"];
        ++$upi_count;
    } else {
        $other_amount += $payment["paid_amount"];
        ++$other_count;
    }
}

foreach($trade_payment as $payment){
    if(strtoupper($payment["payment_mode"])=="CASH"){
        $cash_amount+= $payment["paid_amount"];
        ++$cash_count;
    }
    else if(strtoupper($payment["payment_mode"])=="DD"){
        $dd_amount+= $payment["paid_amount"];
        ++$dd_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CHEQUE"){
        $cheque_amount+= $payment["paid_amount"];
        ++$cheque_count;
    }
    else if(strtoupper($payment["payment_mode"])=="RTGS"){
        $rtgs_amount+= $payment["paid_amount"];
        ++$rtgs_count;
    }
    else if(strtoupper($payment["payment_mode"])=="CARD"){
        $card_amount+= $payment["paid_amount"];
        ++$card_count;
    }else if (strtoupper($payment["payment_mode"]) == "NEFT") {
        $neft_amount += $payment["paid_amount"];
        ++$neft_count;
    }else if (strtoupper($payment["payment_mode"]) == "UPI") {
        $upi_amount += $payment["paid_amount"];
        ++$upi_count;
    } else {
        $other_amount += $payment["paid_amount"];
        ++$other_count;
    }
}




$img=["1"=> "correct.png", null=> "incorrect.png", null=> "incorrect.png"];
?>
<style>
    .imgg{
        height: 20px;
        width: 20px;
    }
</style>

<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="demo-pli-home"></i></a></li>
                        <li><a href="#">Accounts</a></li>
                        <li><a href="<?=base_url("CashVerification/List");?>">Cash Verification</a></li>
                        <li class="active">View</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

                <!-- ======= Cta Section ======= -->

                <div id="page-content">
                <form class="form-horizontal" method="post" id="myform" name="myform">
                    <input type="hidden" name="trans_date" id="trans_date" value="<?=$tran_date;?>">
                    <input type="hidden" name="employee_id" id="employee_id" value="<?=$tran_by_emp_details_id;?>">
					
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">TC Collection Details</h3>
						</div>											
						
                        <div class="panel-body">
                            <div class="col-md-4">
                                <table class="table">
                                    <tr>
                                        <td>Collector Name </td>
                                        <td> : <?=$emp["emp_name"];?> <?=$emp["middle_name"];?> <?=$emp["last_name"];?></td>
                                    </tr>
                                    <tr>
                                        <td>Transaction Date </td>
                                        <td> : <?=$tran_date;?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount </td>
                                        <td> : <span id="grand_total_amt"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-8">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel panel-warning panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                <?=$cash_count;?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?=$cash_amount;?></p>
                                                <p class="mar-no">Cash</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-info panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?=$cheque_count;?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?=$cheque_amount;?></p>
                                                <p class="mar-no">Cheque</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-mint panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?=$dd_count;?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?=$dd_amount;?></p>
                                                <p class="mar-no">DD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 pull-right">
                                        <div class="panel panel-danger panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?=$rtgs_count;?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?=$rtgs_amount;?></p>
                                                <p class="mar-no">RTGS</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 pull-right">
                                        <div class="panel panel-danger panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?=$card_count;?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?=$card_amount;?></p>
                                                <p class="mar-no">CARD</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 pull-right">
                                        <div class="panel panel-danger panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?= $neft_count; ?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?= $neft_amount; ?></p>
                                                <p class="mar-no">NEFT</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="panel panel-danger panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?= $upi_count; ?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?= $upi_amount; ?></p>
                                                <p class="mar-no">UPI</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="panel panel-danger panel-colorful media middle pad-all">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <?= $other_count; ?>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold"><?= $other_amount; ?></p>
                                                <p class="mar-no">OTHER</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-12" style="overflow: scroll;">
                                Property Payment - Total No of Transaction: <?=sizeof($prop_payment);?>, Total Amount: <?=array_sum(array_column($prop_payment,'payable_amt'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'property');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> Holding No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Paid Upto</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($prop_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["verify_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="property checkbox" name="property_trxn_id[]" value="<?=$payment["transaction_id"];?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["tran_no"];?></td>
                                                <td> <?=$payment["tran_mode"];?></td>
                                                <td> <?=$payment["new_ward_no"];?></td>
                                                <td> <?=$payment["holding_no"];?></td>
                                                <td> <?=$payment["owner_name"];?></td>
                                                <td> <?=$payment["from_qtr"];?>/<?=$payment["from_fyear"];?> - <?=$payment["upto_qtr"];?>/<?=$payment["upto_fyear"];?></td>
                                                
                                                <td> <?=$payment["payable_amt"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["verify_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["verify_date"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["payable_amt"];
                                        }

                                        ?>
                                    </tbody>
                                </table>

                                SAF Payment  - Total No of Transaction: <?=sizeof($saf_payment);?>, Total Amount: <?=array_sum(array_column($saf_payment,'payable_amt'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'saf');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> SAF No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Paid Upto</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($saf_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["verify_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="saf checkbox" name="property_trxn_id[]" value="<?=$payment["transaction_id"];?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["tran_no"];?></td>
                                                <td> <?=$payment["tran_mode"];?></td>
                                                <td> <?=$payment["ward_no"];?></td>
                                                <td> <?=$payment["saf_no"];?></td>
                                                <td> <?=$payment["owner_name"];?></td>
                                                <td> <?=$payment["from_qtr"];?>/<?=$payment["from_fyear"];?> - <?=$payment["upto_qtr"];?>/<?=$payment["upto_fyear"];?></td>
                                                
                                                <td> <?=$payment["payable_amt"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["verify_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["verify_date"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["payable_amt"];
                                        }

                                        ?>
                                    </tbody>
                                </table>

                                GB SAF Payment  - Total No of Transaction: <?=sizeof($gbsaf_payment);?>, Total Amount: <?=array_sum(array_column($gbsaf_payment,'payable_amt'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'gbsaf');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> SAF No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Paid Upto</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($gbsaf_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["tran_verification_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="gbsaf checkbox" name="gbsaf_trxn_id[]" value="<?=$payment["transaction_id"];?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["tran_no"];?></td>
                                                <td> <?=$payment["tran_mode"];?></td>
                                                <td> <?=$payment["ward_no"];?></td>
                                                <td> <?=$payment["application_no"];?></td>
                                                <td> <?=$payment["building_colony_name"];?></td>
                                                <td> <?=$payment["from_qtr"];?>/<?=$payment["from_fyear"];?> - <?=$payment["upto_qtr"];?>/<?=$payment["upto_fyear"];?></td>
                                                
                                                <td> <?=$payment["payable_amt"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["tran_verification_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["tran_verify_datetime"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["payable_amt"];
                                        }

                                        ?>
                                    </tbody>
                                </table>
                                
                                Water Payment  - Total No of Transaction: <?=sizeof($water_payment);?>, Total Amount: <?=array_sum(array_column($water_payment,'paid_amount'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'water');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> Consumer No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Paid Upto</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($water_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["verify_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="water checkbox" name="water_trxn_id[]" value="<?=$payment["transaction_id"];?>" />

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["transaction_no"];?></td>
                                                <td> <?=$payment["payment_mode"];?></td>
                                                <td> <?=$payment["ward_no"];?></td>
                                                <td> <?=$payment["consumer_no"];?></td>
                                                <td> <?=$payment["applicant_name"];?></td>
                                                <td> <?=$payment["from_month"];?> - <?=$payment["upto_month"];?> </td>
                                                
                                                <td> <?=$payment["paid_amount"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["verify_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["verified_on"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["paid_amount"];
                                        }

                                        ?>
                                    </tbody>
                                </table>

                                Water Connection Payment  - Total No of Transaction: <?=sizeof($water_conn_payment);?>, Total Amount: <?=array_sum(array_column($water_conn_payment,'paid_amount'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'water_connection');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> Application No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Connection Type</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($water_conn_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["verify_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="water_connection checkbox" name="water_trxn_id[]" value="<?=$payment["transaction_id"];?>" />

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["transaction_no"];?></td>
                                                <td> <?=$payment["payment_mode"];?></td>
                                                <td> <?=$payment["ward_no"];?></td>
                                                <td> <?=$payment["application_no"];?></td>
                                                <td> <?=$payment["applicant_name"];?></td>
                                                <td> <?=$payment["transaction_type"];?> </td>
                                                
                                                <td> <?=$payment["paid_amount"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["verify_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["verified_on"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["paid_amount"];
                                        }

                                        ?>
                                    </tbody>
                                </table>

                                Trade Payment  - Total No of Transaction: <?=sizeof($trade_payment);?>, Total Amount: <?=array_sum(array_column($trade_payment,'paid_amount'));?>
                                <table class="table table-responsive table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th scope="col"> <input type="checkbox" onclick="checkModule(this, 'trade');" /></th>
                                            <th scope="col"> #</th>
                                            <th scope="col"> Transaction No</th>
                                            <th scope="col"> Payment Mode</th>
                                            <th scope="col"> Ward No</th>
                                            <th scope="col"> Application No.</th>
                                            <th scope="col"> Owner Nanme</th>
                                            <th scope="col"> Connection Type</th>
                                            <th scope="col"> Paid Amount</th>
                                            <th scope="col"> Verify status</th>
                                            <th scope="col"> Verified By</th>
                                            <th scope="col"> Verified On</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        foreach($trade_payment as $payment)
                                        {
                                            ?>
                                            <tr>
                                                <td> 
                                                    <?php
                                                    
                                                    if($payment["verify_status"]!=1)
                                                    {
                                                        ?>
                                                        <input type="checkbox" class="trade checkbox" name="trade_trxn_id[]" value="<?=$payment["transaction_id"];?>" />

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td> <?=++$sl_no;?></td>
                                                <td> <?=$payment["transaction_no"];?></td>
                                                <td> <?=$payment["payment_mode"];?></td>
                                                <td> <?=$payment["ward_no"];?></td>
                                                <td> <?=$payment["application_no"];?></td>
                                                <td> <?=$payment["applicant_name"];?></td>
                                                <td> <?=$payment["transaction_type"];?> </td>
                                                
                                                <td> <?=$payment["paid_amount"];?></td>
                                                <td> 
                                                    <img src="<?=base_url();?>/public/assets/img/<?=$img[$payment["verify_status"]];?>" class="imgg" />
                                                </td>
                                                <td> <?=$payment["verified_by"];?></td>
                                                <td> <?=$payment["verified_on"];?></td>
                                            <tr>
                                            <?php
                                            $grand_total_amt+=$payment["paid_amount"];
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <h4 class="panel-title">
                                <input type="checkbox" id="allVerified" value="agreed" onchange="checkAll(this)" name="allVerified">
                                &nbsp;&nbsp;<label for="checkbox" style="color:red;"><b> Check All </b></h4>
                            </div>

                            <div class="col-md-12">
                                <div class="panel-body text-center">
                                    <input type="submit" class="btn btn-primary" name="verify" value="Verify Now" onclick="return verifyNow()" />  
                                </div>
                            </div>
                        </div>
					</div>
                </form>
			</div>
<?= $this->include('layout_vertical/footer');?>
<script>
    function checkModule(obj, modulee){
        
        var checked = $(obj).is(":checked");
        if(checked==true)
        {
            $("."+modulee).attr('checked', true);

        }
        else
        {
            $("."+modulee).attr('checked', false);

        }
        
    }


    function checkAll(obj)
    {
        var checked = $(obj).is(":checked");
        if(checked==true)
        {
            $(".checkbox").attr('checked', true);

        }
        else
        {
            $(".checkbox").attr('checked', false);

        }
    }

    function verifyNow()
    {
        if(confirm("Are you want to verify now?"))
        return true;
        else
        return false;
    }

    $("#grand_total_amt").html(parseFloat("<?=$grand_total_amt;?>").toFixed(2));
    
</script>