<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>
.water_mark_cover {position: absolute;top: 0;width: 100%;}
.water_mark_cover span {font-size: 160px;color: #cecece;z-index: -10;position: absolute; width: 100%;transform: rotate(-19deg);margin: 245px 0;}
td {line-height: 1.5em;}
.water_mark {
    display: inline-block;
    width: 99%;
    position: absolute;
    top: 33%;
    /*z-index: -1;*/
    text-align: center;
}
.water_mark img {
    opacity: 0.31;
}

</style>   
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Water</a></li>
                    <li class="active"> Bulk Payment Report  </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-bordered panel-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Bulk Payment Report</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form  id = "my_form" class="form-horizontal" method="post" action="<?=base_url('water_report/bulk_payment_ricipt')?>">
                                                <div class="form-group">
                                                    <div class="col-md-4">
														<label class="control-label" for="tc_id"><b>Operators</b><span class="text-danger">*</span> </label>
														<select id="tc_id" name="tc_id" class="form-control" required>
														   <option value="">ALL</option> 
                                                           <?php
                                                           foreach($oprator as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($_POST) && !empty($_POST) && set_value('tc_id')==$value['id'] ? 'selected':''?>><b><?=$value['emp_name']?></b> (<?=$value['user_type'];?>) </option>
                                                                <?php
                                                           }
                                                           ?>															
														</select>
													</div>
                                                    <div class="col-md-2">
                                                        <label  class="control-label" for="from_date"><b>From Date </b> <span class="text-danger">*</span></label>
                                                        <input type="date" name="from_date" id="from_date" class="form-control" value="<?=isset($from_date)?$from_date:date('Y-m-d');?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label  class="control-label" for="upto_date"><b>Upto Date</b> <span class="text-danger">*</span></label>
                                                        <input type="date" name="upto_date" id="upto_date" class="form-control" value="<?=isset($upto_date)?$upto_date:date('Y-m-d');?>">
                                                    </div>
                                                    
													<div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
                                                    
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                        $i='';
                                        if(isset($bulk_payment['result']) && !empty($bulk_payment['result']))
                                            {
                                    
                                                ?>
                                                <div class="col-sm-12 noprint text-right mar-top">
                                                    <button class="btn btn-mint btn-icon" onclick="printDiv('all')" style="height:40px;width:60px; z-index:100;">PRINT</button>
                                                </div><br><br><br>
                                                <?php 
                                            }
                                    ?>
                                    <div class="row" id='all'>
                                        <div class="" >
                                            <!-- hear---------------------------------- -->
                                            <?php
                                            if(isset($bulk_payment['result']) && !empty($bulk_payment['result']))
                                            {   
                                                ?>
                                                <?php
                                                foreach($bulk_payment['result'] as $transaction_details )
                                                {

                                                    ?>
                                            
                                                    <div id="page-content">
                                                        <div class="panel  panel-dark">
                                                            <div id="printarea">
                                                            <div class="panel-body"  style="width:90%;margin-left:5%;outline-style: dotted;padding:2px;color:black; ">

                                                                <style type="text/css" media="print">
                                                                @media print {
                                                                    .dontPrint {display:none;}
                                                                    #page-content {page-break-before: always;}
                                                                }
                                                                .water_mark_cover span {font-size: 80px;}
                                                                .water_mark {
                                                                    display: inline-block;
                                                                    width: 99%;
                                                                    position: absolute;
                                                                    top: 33%;
                                                                    /*z-index: -1;*/
                                                                    text-align: center;
                                                                }
                                                                .water_mark img {
                                                                    opacity: 0.31;
                                                                }
                                                                </style>
                                                                <style type="text/css" media="print">
                                                                @media print
                                                                {
                                                                    /* For Remove Header URL */
                                                                        @page {
                                                                        margin-top: 0;
                                                                        margin-bottom: 0;
                                                                        size: portrait;
                                                                        size: A4;
                                                                        }
                                                                        body  {
                                                                        padding-top:30px;
                                                                        padding-bottom: 5px ; background:#FFFFFF
                                                                        }
                                                                        /* Enable Background Graphics(ULB Logo) */
                                                                        *{
                                                                            -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
                                                                            color-adjust: exact !important;                 /*Firefox*/
                                                                        }
                                                                    } 
                                                                </style>
                                                                <div class="col-sm-1"></div>



                                                                <div class="panel-body"  id="print_watermark" >
                                                                    <!-- <style type="text/css" media="print">
                                                                        @media print
                                                                        {
                                                                            /* For Remove Header URL */
                                                                            @page {
                                                                            margin-top: 0;
                                                                            margin-bottom: 0;
                                                                            size: portrait;
                                                                            size: A4;
                                                                            }
                                                                            body  {
                                                                            padding-top:30px;
                                                                            padding-bottom: 5px ; background:#FFFFFF
                                                                            }
                                                                            /* Enable Background Graphics(ULB Logo) */
                                                                            *{
                                                                            -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
                                                                            color-adjust: exact !important;                 /*Firefox*/
                                                                        }
                                                                        } 
                                                                    </style> -->
                                                                    <div class="col-sm-1"></div>
                                                                        <div class="col-sm-10" style="text-align: center;">
                                                                            <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
                                                                        </div>
                                                                        <div class="col-sm-1 noprint text-right">
                                                                            <!-- <button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;">PRINT</button> -->
                                                                        </div>
                                                                        <div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
                                                                        <?=$ulb_mstr_name["ulb_name"];?>
                                                                        </div>
                                                                        
                                                                        <div class="col-sm-12">
                                                                            <div class="col-sm-8">

                                                                            </div>
                                                                            <div class="">
                                                                            </div>
                                                                        </div>
                                                                        <table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

                                                                            <tbody>
                                                                                <tr>
                                                                                    <td height="71" colspan="4" align="center">
                                                                                        <div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">WATER USER CHARGE RECEIPT </div>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td colspan="3">Receipt No. : <b><?=$transaction_details["transaction_no"];?></b></td>
                                                                                    <td >Date : <b><?=date('d-m-Y',strtotime($transaction_details["transaction_date"]));?></b></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="3">Department / Section : Water<br>
                                                                                Account Description : Water User Charge</td>
                                                                                    <td>
                                                                                        <div >Ward No : <b><?=$transaction_details["ward_no"];?></b> </div>
                                                                                        <div >Consumer No : <b><?=$transaction_details["consumer_no"];?></div>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                            </tbody>
                                                                        </table><br>
                                                                        <br>
                                                                        <table width="100%" border="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>Received From Mr / Mrs / Miss . : &nbsp;
                                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                                        <?php 
                                                                                            echo $transaction_details['applicant'];
                                                                                        ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td> Guardian Name: &nbsp;
                                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                                        <?php 
                                                                                            echo strtoupper($transaction_details["father_name"]);                                                                                          
                                                                                        ?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Mobile No. : &nbsp;
                                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                                        <?php 
                                                                                        echo $transaction_details['mobile_no'];
                                                                                        ?>
                                                                                        </span>
                                                                                    </td>
                                                                                    
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td>Address : 
                                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                                            <?=isset($transaction_details["address"])?$transaction_details["address"]:'N/A';?>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div style="float: left;">A Sum of Rs. </div>
                                                                                        <div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
                                                                                            <?=$transaction_details["paid_amount"];?>
                                                                                            
                                                                                        &nbsp;
                                                                                        </div><br>

                                                                                        <div style="float: left;">(in words) </div>
                                                                                        <div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
                                                                                            &nbsp;
                                                                                        <?php echo ucwords(getIndianCurrency($transaction_details["paid_amount"])); ?>
                                                                                        &nbsp; Only
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php if(in_array(strtoupper($transaction_details["payment_mode"]),["CASH",'ONLINE'])){ ?>
                                                                                <tr>
                                                                                    <td height="35">
                                                                                        <div style="float: left;">
                                                                                            towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide <b><?=$transaction_details["payment_mode"];?> </b> 
                                                                                        </div>
                                                                                <?php } else { ?>
                                                                                <tr>
                                                                                    <td height="35">
                                                                                        <?php if($transaction_details["payment_mode"]=="CHEQUE"){ ?>
                                                                                        <div style="float: left;">
                                                                                            towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide Cheque No
                                                                                        </div>
                                                                                        <?php } else{ ?>
                                                                                        <div style="float: left;">
                                                                                            towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide DD No
                                                                                        </div>
                                                                                        <?php } ?>
                                                                                        <div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
                                                                                            &nbsp;&nbsp; <?=$transaction_details["cheque_no"];?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td height="35">
                                                                                        <div style="float: left;">Dated </div>
                                                                                        <div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
                                                                                            &nbsp;&nbsp; <?=date('d-m-Y',strtotime($transaction_details["cheque_date"]));?>
                                                                                        </div>
                                                                                        <div style="float: left;">Drawn on </div>
                                                                                        <div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px;">
                                                                                            &nbsp;&nbsp; <?=$transaction_details["bank_name"];?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="35">
                                                                                        <div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
                                                                                            &nbsp;&nbsp; <?=$transaction_details["branch_name"];?>
                                                                                        </div>
                                                                                        <div style="float: left;">Place Of The Bank.</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php } ?>
                                                                                
                                                                            </tbody>
                                                                        </table><br><br><br>
                                                                        <div class="col-sm-12">
                                                                                <b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to realisation</b>
                                                                        </div><br><br>
                                                                        
                                                                        <div style="width: 99%; margin: auto; line-height: 35px;"><strong style="font-size: 14px;">WATER USER CHARGE DETAILS </strong>
                                                                        
                                                                        </div>
                                                                            <table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td ><b>Description</b></td>
                                                                                        
                                                                                        <td ><b>Total Amount</b></td>
                                                                                        
                                                                                    </tr>
                                                                                    
                                                                                
                                                                                
                                                                                    <tr>
                                                                                        <td>Period: &nbsp; 

                                                                                        <?php echo date('F',strtotime($transaction_details['from_month'])).' / '.date('Y',strtotime($transaction_details['from_month']));

                                                                                        if($transaction_details['upto_month']!=$transaction_details['from_month'])
                                                                                        {

                                                                                        
                                                                                        ?>

                                                                                            to
                                                                                        
                                                                                        <?php echo date('F',strtotime($transaction_details['upto_month'])).' / '.date('Y',strtotime($transaction_details['upto_month']));


                                                                                        }
                                                                                        ?>
                                                                                            

                                                                                        </td>
                                                                                        
                                                                                        <td><?php echo $transaction_details['total_amount'];?></td>
                                                                                        

                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td >Penalty</td>
                                                                                        <td><?php echo $transaction_details['penalty'];?></td>
                                                                                        
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td >Rebate</td>
                                                                                        <td><?php echo $transaction_details['rebate'];?></td>
                                                                                        
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td >Payable Amount</td>
                                                                                        <td><?php echo $transaction_details['paid_amount'];?></td>
                                                                                        
                                                                                    </tr>
                                                                                    <?php
                                                                                    if(isset($transaction_details['meter_reading']) && !empty($transaction_details['meter_reading']))
                                                                                    {
                                                                                        if(!empty($transaction_details['meter_reading']['final_reading']))
                                                                                        {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td>Meter Payment (<?=$transaction_details['meter_reading']['initial_reading']?> - <?=$transaction_details['meter_reading']['final_reading']?>)</td>
                                                                                                <td><?=$transaction_details['meter_reading']['meter_payment']?></td>

                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                        if(!empty($transaction_details['meter_reading']['demand_upto']))
                                                                                        {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td>Fixed Payment (<?=$transaction_details['meter_reading']['demand_from']?> / <?=$transaction_details['meter_reading']['demand_upto']?>)</td>
                                                                                                <td><?=$transaction_details['meter_reading']['fixed_payment']?></td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        }														
                                                                                    }
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td >Due Amount</td>
                                                                                        <td><?php echo $transaction_details['due_amount'];?></td>
                                                                                        
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table><br><br>
                                                                            <table width="100%" border="0">
                                                                                <?php 
                                                                                $ss = '';
                                                                                $path=base_url('citizenPaymentReceipt/view_transaction_receipt/'.$ulb_mstr_id.'/'.$transaction_details['related_id'].'/'.$transaction_details['transaction_id']);
                                                                                $ss = qrCodeGeneratorFun($path);
                                                                                ?>
                                                                            <img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
                                                                            </table>
                                                                            
                                                                            <table width="100%" border="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td colspan="2" style="font-size:13px;">
                                                                                            <?=receipt_url()?>
                                                                                        </td>
                                                                                        <td style="text-align:center; font-size:13px;">In Association with<br>
                                                                                            <?=Collaboration()?>
                                                                                            
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table><br><br>
                                                                            <div class="col-sm-12 " style="text-align:center;">
                                                                                <b>**This is a computer-generated receipt and it does not require a physical signature.**</b>
                                                                            </div>
                                                                            
                                                                </div>








                                                                
                                                                
                                                                    
                                                            </div>
                                                            <div class="water_mark"><img src="<?=base_url();?>/public/assets/img/logo/<?=$ulb_id?>.png"/></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- hear---------------------------------- -->

                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <?=isset($collection['count'])?pagination($collection['count']):null;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--===================================================-->
                <!--End page content-->
                </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }

    $(document).ready(function(){
        $('#my_form').validate({ // initialize the plugin
            rules: {
                "tc_id":"required",
                "from_date":"required",
                "tc_id":"required",
            }
        });
    });
</script>