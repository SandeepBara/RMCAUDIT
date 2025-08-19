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
                    <li><a href="#">Property</a></li>
                    <li class="active"> Bulk Notice Print </li>
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
                                    <h5 class="panel-title">Notice List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form  id = "my_form" class="form-horizontal" method="post" action="<?=base_url('propDtl/printBulkNotice')?>">
                                                <div class="form-group">                                                    
                                                    <div class="col-md-2">
                                                        <label  class="control-label" for="search_from_date"><b>From Date </b> <span class="text-danger">*</span></label>
                                                        <input type="date" name="search_from_date" id="search_from_date" class="form-control" value="<?=isset($search_from_date)?$search_from_date:date('Y-m-d');?>" required/>
                                                    </div>
                                                    
                                                    <div class="col-md-2">
                                                        <label  class="control-label" for="search_upto_date"><b>Upto Date</b> <span class="text-danger">*</span></label>
                                                        <input type="date" name="search_upto_date" id="search_upto_date" class="form-control" value="<?=isset($search_upto_date)?$search_upto_date:date('Y-m-d');?>" required/>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label" for="search_ward_mstr_id"><b>Ward No</b></label>
                                                        <select name="search_ward_mstr_id" id="search_ward_mstr_id" class="form-control">
                                                            <option value="">All</option>
                                                            <?php
                                                                foreach($wardList as $val){
                                                                    ?>
                                                                        <option value="<?=$val["id"];?>" <?=(($search_ward_mstr_id??"")==$val["id"] ? "selected" :"");?>><?=$val["ward_no"];?></option>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    

                                                    <div class="col-md-2">
                                                        <label class="control-label" for="property_type_id">Property Type</label>
                                                        <select name="property_type_id" id="property_type_id" class="form-control">
                                                            <option value="">All</option>
                                                            <?php
                                                                foreach($propertyTypeList as $val){
                                                                    ?>
                                                                        <option value="<?=$val["id"];?>"  <?=(($property_type_id??"")==$val["id"] ? "selected" :"");?>><?=$val["property_type"];?></option>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                                                                        
													<div class="col-md-2">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
                                                    
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php 
                                        $i='';
                                        if(isset($noticeList) && !empty($noticeList))
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
                                            if(isset($noticeList) && !empty($noticeList))
                                            {   
                                                ?>
                                                <?php
                                                foreach($noticeList as $key=>$val )
                                                {
                                                    $ulb = $val["ulb"];
                                                    $notice = $val["notice"];
                                                    $property = $val["property"];
                                                    ?>
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
                                                                .border{
                                                                    padding: 0 5px;
                                                                    border-bottom: 1px solid;
                                                                    line-height: 1.4em;
                                                                    font-size: 12px;
                                                                }
                                                                body  {
                                                                padding-top:30px;
                                                                padding-bottom: 5px ;
                                                                background:#FFFFFF;
                                                                font-size: 10px;
                                                                }
                                                                /* Enable Background Graphics(ULB Logo) */
                                                                *{
                                                                    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
                                                                    color-adjust: exact !important;                 /*Firefox*/
                                                                }
                                                            } 
                                                        </style>
                                            
                                                    <div id="page-content">
                                                        <div class="row panel panel-dark" style="border: 1px; border-style: solid; padding: 1px;">
                                                            <?php
                                                                $ulb_exp = explode(' ',trim($ulb['ulb_name']));
                                                                $ulb_short_nm=$ulb_exp[0];
                                                            ?>
                                                            <div class="" style="font-size:14px; border: 4px; border-style: solid; padding: 10px;">
                                                                <table width="100%" >
                                                                    <tr>
                                                                        <td width="10%" style="text-align: left;">
                                                                            <img style="height: 80px;width: 80px;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"];?>'>
                                                                        </td>
                                                                        <td width="90%" style="text-align:center; margin-right:40px;">
                                                                            <p>
                                                                                <strong>
                                                                                    <span style="font-size:30px;">
                                                                                        कार्यालय राँची नगर निगम, राँची
                                                                                        </span>
                                                                                        <br>
                                                                                        <u>
                                                                                            <span>
                                                                                                ( राजस्व शाखा )
                                                                                            </span>
                                                                                        </u>
                                                                                </strong>
                                                                            </p>
                                                                            <!-- <br/> -->
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center" colspan="2">
                                                                            <p>
                                                                                झारखंड नगरपालिका अधिनियम 2011 की धारा 181 के अधीन धृतिकर (होल्डिंग टैक्स) भुगतान हेतु डिमांड नोटिस - I का प्रेषण ।
                                                                            </p>
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                                <table width="100%" stype="font-size:20px;">
                                                                    <tr>
                                                                        <td width="70%">
                                                                            नोटिस संख्या : 
                                                                            <strong class="border">NOTICE/<?=$notice["notice_no"];?></strong>
                                                                        </td>
                                                                        <td width="30%">
                                                                            नोटिस तिथि : 
                                                                            <strong class="border"><?=date('d-m-Y', strtotime($notice["notice_date"]));?></strong>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- <tr> -->
                                                                        <!-- <td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                                        <td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                                                                        <!-- <td width="30%">&#2346;&#2381;&#2352;&#2367;&#2306;&#2335; &#2342;&#2367;&#2344;&#2366;&#2306;&#2325; &nbsp;&nbsp; : <strong class="border"><?=date('d-m-Y');?></strong></td> -->
                                                                    <!-- </tr> -->
                                                                </table>
                                                                <table style="width:100%;" align="left">
                                                                    <tr>
                                                                        <td>
                                                                            <br>
                                                                            प्रेषित 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            श्री/श्रीमती/मेसर्स : 
                                                                            <strong class="border"><?=$property["owner_name"]?></strong><br />
                                                                            पिता/पति का नाम : 
                                                                            <strong class="border"><?=$property["guardian_name"]?></strong><br />
                                                                            होल्डिंग नं० : 
                                                                            <strong class="border"><?=!empty($property["new_holding_no"])?$property["new_holding_no"]:$property["holding_no"]?></strong>  
                                                                            <!-- &nbsp;&nbsp;&nbsp;  
                                                                            वार्ड नं० :- 
                                                                            <strong class="border"><?=!empty($property["new_ward_no"])?$property["new_ward_no"]:$property["ward_no"]?></strong> -->
                                                                            <br>
                                                                            वार्ड नं० :- 
                                                                            <strong class="border"><?=!empty($property["ward_no"])?$property["ward_no"]:""?></strong>

                                                                            &nbsp;&nbsp;&nbsp;  
                                                                            नई वार्ड नं० :- 
                                                                            <strong class="border"><?=!empty($property["new_ward_no"])?$property["new_ward_no"]:""?></strong>
                                                                            <br />
                                                                            पता : 
                                                                            <strong class="border"><?=$property["prop_address"]?></strong>
                                                                            <br/>
                                                                            मोबाइल नंबर :-
                                                                            <strong class="border"><?=$property["mobile_no"]?></strong>
                                                                            &nbsp;&nbsp;&nbsp;
                                                                            ईमेल :-
                                                                            <strong class="border"><?=$property["email"]?></strong>
                                                                        </td> 
                                                                    </tr>
                                                                </table>
                                                                
                                                                <table style="width:100%;">
                                                                    <tr>
                                                                        <td>
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            आपके भवन (होल्डिंग नं० - 
                                                                            <strong class="border"><?=isset($property["new_holding_no"])?$property["new_holding_no"]:$property["holding_no"]?></strong>
                                                                            ) का धृतिकर बकाया आपके द्वारा 
                                                                            <strong class="border"><?= date("d M Y",strtotime(getQtrFistDate($notice["from_fyear"],$notice["from_qtr"])));?></strong> 
                                                                            से 
                                                                            <strong class="border"><?=date("d M Y",strtotime(getQtrLastDate($notice["upto_fyear"],$notice["upto_qtr"]))) ;?></strong> 
                                                                            अवधि तक का धृतिकर जमा नहीं किया गया है। जिसके फल स्वरुप झारखंड नगरपालिका कर भुगतान ( समय,प्रक्रिया तथा वसूली ) विनिमय,- 2017 के नियम 3.1.2 तहत आपको उक्त अवधि का धृतिकर का भुगतान नोटिस प्राप्ति के 30 दिन के अंतर्गत अनिवार्य रूप से करना है। इस मांग पत्र में उल्लेखित राशि की गणना निम्न रूप से की गई है। 
                                                                            <br>
                                                                            <!-- <br> -->
                                                                        
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">                                       
                                                                            <table border="1px" cellspacing="0" width="95%" align="center">
                                                                                <tr align="left">
                                                                                    <td><strong>SL. NO.</strong></td>
                                                                                    <td><strong>Particular ( विवरण )</strong></td>
                                                                                    <td><strong></strong></td>
                                                                                </tr>
                                                                                <tr align="left">
                                                                                    <td>1</td>
                                                                                    <td>Holding Tax to be paid for the period form <strong class="border"><?=date("d M Y",strtotime(getQtrFistDate($notice["from_fyear"],$notice["from_qtr"]))) ?></strong> upto <strong class="border"><?=date("d M Y",strtotime(getQtrLastDate($notice["upto_fyear"],$notice["upto_qtr"]))) ?></strong></td>
                                                                                    <td align="right"><strong><?=$notice["demand_amount"]?></strong></td>
                                                                                </tr>
                                                                                <tr align="left">
                                                                                    <td>2</td>
                                                                                    <td> * Interest amount @ 1% per month under section 182(3) of Jharkhand Municipal Act 2011 section 182(3) 
                                                                                    एवं झारखण्ड नगरपालिका संपत्ति कर (निर्धारण, संग्रहण और वसूली) नियमावली 2013 यथा संशोधित के नियम 12.2 के अनुसार।
                                                                                    </td>
                                                                                    <td align="right"><strong><?=$notice["penalty"]?></strong></td>
                                                                                </tr>
                                                                                <tr align="left">
                                                                                    <td>3</td>
                                                                                    <td>
                                                                                        ** Penalty amount under झारखंड नगरपालिका कर भुगतान ( समय,प्रक्रिया तथा वसूली ) विनिमय,- 2017 के नियम 3.1.4
                                                                                    </td>
                                                                                    <td align="right"><strong><?=$notice["noticePenalty"]?></strong></td>
                                                                                </tr>
                                                                                <tr align="left">
                                                                                    <td colspan="2"><strong>कुल भुगतेय राशि</strong></td>
                                                                                    <td align="right"><strong><?=round($notice["demand_amount"]+$notice["penalty"]+$notice["noticePenalty"], 2);?></strong></td>
                                                                                </tr>
                                                                            </table>
                                                                            <br />
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            अतएव झारखण्ड नगरपालिका कर भुगतान ( समय , प्रक्रिया तथा वसूली ) विनियम,-2017 के विहित प्रावधान के अनुसार आपको उक्त अवधि का धृतिकर का भुगतान अनिवार्य रूप से करना है। 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        
                                                                        <td>
                                                                            <!-- <br/> -->
                                                                                &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;  
                                                                                इस राशि का भुगतान नोटिस प्राप्त होने के 01(एक) माह के अंदर करना सुनिश्चित करेंगे। अन्यथा उक्त विनिमय 2017 की कंडिका 3.1.4 के विहित प्रावधान के अनुसार दण्ड की राशि निम्न प्रकार से अधिरोपित की जायेगी :-
                                                                            </td>
                                                                        
                                                                    </tr>
                                                                </table>
                                                                <!-- <br> -->
                                                                <table border="1px" cellspacing="0" width="95%" align="center">
                                                                    <tr>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                क्रमांक
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                विलम्बित अवधी
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                दण्ड की राशि
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">01.</span></td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                निर्धारित अवधी से एक सप्ताह की अवधी तक
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                भुगतेय राशि का 1 प्रतिशत
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">02.</span></td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                निर्धारित अवधी से दो सप्ताह की अवधी तक
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                भुगतेय राशि का 2 प्रतिशत
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">03.</span></td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                निर्धारित अवधी से एक माह की अवधी तक
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                भुगतेय राशि का 3 प्रतिशत
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">04.</span></td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                निर्धारित अवधी से दो माह की अवधी तक
                                                                            </span>
                                                                        </td>
                                                                        <td style="padding-bottom:5px;font-size:12px;">
                                                                            <span style="font-weight:bold;">
                                                                                भुगतेय राशि का 5 प्रतिशत
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <br/>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            उसके पश्चात प्रत्येक माह के लिए 2 प्रतिशत अतिरिक्त दण्ड की राशि भुगतेय होगी।
                                                                            <br/>
                                                                            अतएव आप कर का भुगतान ससमय करना सुनिश्चित करें। 
                                                                            <br />
                                                                        <strong>
                                                                            * भुगतान के समय अद्यतन ब्याज की गणना की जाएगी
                                                                            <br>
                                                                            ** दण्ड राशि का गणना भुगतान के समय निर्धारित किया जाएगा।
                                                                            <br>
                                                                        </strong>
                                                                    </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center" class="text-sm">( this value of the total payble amount is subject to the date of notice generation. It may vary every month )</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            इसे सख्त ताकीद समझा जाए।
                                                                            <br>
                                                                            ** This is a computer-generated demand notice with facsimile signature and dose not require a physical signature and stamp **      
                                                                            <br>
                                                                            ** Without Prejudice **                                   
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </table>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td width="60%"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                                        <td width="40%" align="center">
                                                                            <p style="position: relative;"><br><br>
                                                                                <?php
                                                                                    // $sign = "dmcsign.png";
                                                                                    // $sign = "";
                                                                                    // $degignation = "अपर प्रशासक";
                                                                                    // if($notice["notice_date"]<'2024-09-28'){
                                                                                    //     $sign="dmcsign_old.png";
                                                                                    // }
                                                                                    // if($notice["notice_date"]<'2024-02-15'){
                                                                                    //     $sign="rajnishkumar_sign.png";
                                                                                    // }
                                                                                    // if($notice["notice_date"]>='2025-01-16'){
                                                                                    //     $sign = "gautam.png"; 
                                                                                    //     $degignation = "उप प्रशासक";
                                                                                    // }
                                                                                ?>
                                                                                 <?php
                                                                                    if($sign){
                                                                                    ?>
                                                                                    <!-- <img src="<?php echo base_url("writable/eo_sign/$sign");?>" style="position: absolute;bottom: 24px;max-height: 90px;" alt="rmc"> -->
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                <img src="<?=$notice['signature_path'];?>" style="position: absolute;bottom: 24px;max-height: 90px;" alt="rmc"/>
                                                                                <?=$notice["degignation"]?>
                                                                                <br />
                                                                                राँची नगर निगम, राँची
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- hear---------------------------------- -->
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