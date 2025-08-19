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
                    <li class="active"> Bulk Demand Report  </li>
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
                                    <h5 class="panel-title">Bulk Demand Report</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('water_report/bulk_demand')?>">
                                                <div class="form-group">
                                                    <?php 
                                                        if(isset($ward))
                                                        {
                                                            ?>
                                                                <div class="col-md-4">
                                                                    <label class="control-label" for="ward_id"><b>Ward</b><span class="text-danger">*</span> </label>
                                                                    <select id="ward_id" name="ward_id" class="form-control" required>
                                                                    <option value="">ALL</option> 
                                                                    <?php
                                                                    foreach($ward as $value)
                                                                    {
                                                                            ?>
                                                                            <option value="<?=$value['id']?>" <?=isset($_POST) && !empty($_POST) && set_value('ward_id')==$value['id'] ? 'selected':''?>><?=$value['ward_no']?></option>
                                                                            <?php
                                                                    }
                                                                    ?>															
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="control-label" for="ward_id"><b>Consumer No</b><span class="text-danger"></span> </label>
                                                                    <input type="text" name="consumer_no" class="form-control" value="<?=isset($_POST) && !empty($_POST) && set_value('consumer_no')?set_value('consumer_no'):"";?>"/>
                                                                        
                                                                </div>
                                                                
                                                                <div class="col-md-2 ">
                                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                                </div>
                                                            <?php
                                                        }
                                                    ?>
                                                    
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                        <?php 
                                            $i='';
                                            if(isset($bulk_demand['result']) && !empty($bulk_demand['result']))
                                                {
                                        
                                        ?>
                                            <div class="col-sm-12 noprint text-right mar-top">
                                                <button class="btn btn-mint btn-icon" onclick="printDiv('all')" style="height:40px;width:60px; z-index:100;">PRINT</button>
                                            </div><br><br><br>
                                            <?php }?>
                                    <div class="row" id='all'>
                                        <div class="" >
                                            <!-- hear---------------------------------- -->
                                            <?php
                                                if(isset($bulk_demand['result']) && !empty($bulk_demand['result']))
                                                {   
                                            ?>
                                            <?php
                                                foreach($bulk_demand['result'] as $val )
                                                {

                                                    ?>
                                                    
                                            <div id="page-content">
                                                <div class="panel panel-bordered panel-dark">
                                                    <div id="printarea">
                                                    <div class="panel-body"  style="border: solid 2px black;">

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
                                                        <div class="col-sm-10" style="text-align: center;">
                                                            <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
                                                        </div>
                                                        <div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
                                                        <?=$ulb_dtl["ulb_name"];?>
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
                                                                        <div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">WATER USER CHARGE </div>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <!-- <td colspan="3" width='60%'>Demand  No. : <b><?=$val['demand_no']?></b></td> -->
                                                                    <td colspan="3" width='60%'>Demand  No. : <b><?=$val['last_demand_no']?></b></td>
                                                                    <td >Date : <b><?=date('Y-m-d h:i:s a')?></b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">Department / Section : Water<br>
                                                                Account Description : Water User Charge</td>
                                                                    <td>
                                                                        <div >Ward No : <b><?=$val['ward_no']?></b> </div>
                                                                        <!-- <div >Application No : <b><?=$val['application_no']?></b></div> -->
                                                                        <div >Consumer No : <b><?=$val['consumer_no']?></b></div>
                                                                        <!-- <div >Holding No : <b><?=$val['holding_no']?></b></div> -->
                                                                        <div >Area (sqft) : <b><?=$val['area_sqft']?></b></div>
                                                                    </td>
                                                                </tr>
                                                                
                                                            </tbody>
                                                        </table><br>
                                                        <br>
                                                        <table width="100%" border="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Applicant NAME Mr. / Ms. /Mss. : 
                                                                        <?php //foreach($basic_details as $basic_details):?>
                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                            <?=$val['applicant']?>
                                                                        </span>
                                                                        <?php //endforeach; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Mobile No. : 
                                                                        <span style="font-size: 14px; font-weight: bold">
                                                                            <?=$val['mobile_no']//$applicant_details["mobile_no"];?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Address : 
                                                                        <span style="font-size: 14px; font-weight: bold"><?=$val['address']?>
                                                                            <?=isset($val['address']) ? $val['address'] : 'N/A'//$applicant_details["address"];?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    
                                                                </tr>
                                                                
                                                                
                                                                
                                                            </tbody>
                                                        </table><br>
                                                        
                                                        
                                                        <div style="width: 99%; margin: auto; line-height: 35px;"><strong style="font-size: 14px;">WATER USER CHARGE DEMAND DETAILS </strong>
                                                        
                                                        </div>
                                                            <table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td><b>Description</b></td>
                                                                        
                                                                        <td ><b>Amount</b></td>
                                                                        
                                                                    </tr>
                                                                    
                                                                    
                                                                
                                                                    <tr>
                                                                        <td style="text-align:left;">Demand Period </td>
                                                                        <td style="text-align:right;"><?=isset($val['min']) && isset($val['max'])? $val['min'].' - '.$val['max']: 'N/A'?></td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;">Demand</td>
                                                                        <td style="text-align:right;"><?=isset($val['demand']) ? $val['demand']: 'N/A'?></td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;">Advance</td>
                                                                        <td style="text-align:right;"><?=isset($val['advance']) ? number_format($val['advance'],2): '00.00'?></td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;">Fine</td>
                                                                        <td style="text-align:right;"><?=$val['penalty']??0.00?></td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="text-align:left;">Payable Amount</td>
                                                                        <td style="text-align:right;"><?=isset($val['advance']) && isset($val['demand']) && isset($val['max'])? number_format($val['demand']+$val['penalty']-$val['advance'],2) : '00.00'?></td>
                                                                        
                                                                    </tr>
                                                                </tbody>
                                                            </table><br><br>

                                                            <table width="100%" border="0">
                                                            
                                                            </table>
                                                            
                                                            <table width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="2" style="font-size:13px;">
                                                                        <?=receipt_url()?>
                                                                        <!-- For Details Please Visit : 
                                                                            <?php
                                                                            if($ulb_dtl["ulb_mstr_id"] == 1)
                                                                            {
                                                                                ?>
                                                                                www.ranchimunicipal.com
                                                                                <?php
                                                                            }
                                                                            else
                                                                            {
                                                                                ?>
                                                                                udhd.jharkhand.gov.in
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <br>
                                                                            OR Call us at 18001212241 or 0651-7145511 -->
                                                                        </td>
                                                                        <td style="text-align:center; font-size:13px;">In Collaboration with<br>
                                                                            <!-- Sri Publication & Stationers Pvt. Ltd.<br>
                                                                            Ashok Nagar,<br>
                                                                            Ranchi - 834002 -->
                                                                            <?=Collaboration()?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table><br><br>
                                                            <div class="col-sm-12 " style="text-align:center;">
                                                                <b>**This is a computer-generated receipt and does not require a physical signature.**</b>
                                                            </div>
                                                            
                                                    </div>
                                                    <div class="water_mark"><img src="<?=base_url();?>/public/assets/img/logo/<?=$ulb_id?>.png"/></div>
                                                </div>
                                            </div>
                                            
                                            <!-- hear---------------------------------- -->
                                            



                                        </div><?php
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
        $('#form').validate({ // initialize the plugin
            rules: {
                "ward_id":"required",
            }
        });
    });
</script>