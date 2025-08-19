<?= $this->include('layout_vertical/header');?>


<style>
@media print {
    @page {
        margin-top: 0;
        margin-bottom: 0;
        size: portrait;
        size: A4;
        padding-top:15px;
        padding-bottom: 5px ; 
    }
    html,body{
        width:210mm;
        height:297mm;
        
        background:#FFFFFF

    }
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_id;?>.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
        color: #B9290A!important;
    }
    *{
        color: #B9290A!important;
    }
}
#print_watermark
{
	background-image:url('<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_id;?>.png') !important;
	background-repeat:no-repeat;
	background-position:center;
	z-index: 100;
	color:#000;
	
}

</style>
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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade Licence list</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <?php
                            if($emp_mstr_type_id==19)
                            {
                            ?>
                            <a href="<?php echo base_url('Trade_EO/trade_licence_list/');?>" class="btn btn-default">Back</a>
                            <?php
                            }
                        ?>
                        <div class="col-sm-1 noprint text-right">
                            <button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;border:none;">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7,10a1,1,0,1,0,1,1A1,1,0,0,0,7,10ZM19,6H18V3a1,1,0,0,0-1-1H7A1,1,0,0,0,6,3V6H5A3,3,0,0,0,2,9v6a3,3,0,0,0,3,3H6v3a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V18h1a3,3,0,0,0,3-3V9A3,3,0,0,0,19,6ZM8,4h8V6H8Zm8,16H8V16h8Zm4-5a1,1,0,0,1-1,1H18V15a1,1,0,0,0-1-1H7a1,1,0,0,0-1,1v1H5a1,1,0,0,1-1-1V9A1,1,0,0,1,5,8H19a1,1,0,0,1,1,1Z" fill="#6563ff"/></svg>
                            </button>
                        </div>
                    </div>
                    <h3 class="panel-title">Municipal Licence </h3>

                </div>

                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                         <div id="printablediv" style="overflow:hidden;outline-style: dotted;padding:5px;color:#B9290A; ">
						 <div id="print_watermark">
                             <table width="100%">
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;"><strong>Municipal Trade Licence Approval Certificate</strong><br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;"><strong><?=strtoupper($ulb['ulb_name']);?></strong><br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;"><strong>Municipal License</strong><br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;">(This certificate relates to Section 455(i) Jharkhand Municipal Act 2011)<br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                             </table>

                             <table style="width:100%;color:#B9290A;">
								 <tr>
									 <td>Municipal Trade Licence Number : <span style="font-weight:bold;" ><?=$basic_details['license_no'];?></span><br/></td>
									 <td rowspan="4">
										<img style="margin-right:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
									</td>
								 </tr>
								 <tr>
									 <td>Issue date of Municipal Trade Licence : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['license_date']));?></span></td>
								 </tr>
								 <tr>
									 <td>Validity of Municipal Trade Licence : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['valid_upto']));?></span></td>
								 </tr>
								 <tr>
									 <td>Occupancy certificate no : </td>
								 </tr>
								 
                                 <tr>
                                     <td>Owner/ Entity Name : <span style="font-weight:bold;" ><?=$basic_details['firm_name'];?></span></td>
                                     <td>Ward No. : <span style="font-weight:bold;" ><?=$ward['ward_no'];?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Owner Name of Entity : <span style="font-weight:bold;" > <?=$basic_details['owner_name'];?> </span></td>
                                     <td>Holding No. : <span style="font-weight:bold;" ><?=$basic_details['holding_no'] ?? "N/A";?></span></td>
                                 </tr>
                                 <tr>
                                     <td width="700px">Nature of Entity : <span style="font-weight:bold;" ><?=$basic_details['brife_desp_firm'];?> </span></td>
                                     <td>Street Address : <span style="font-weight:bold;" ><?=($basic_details['address'].($basic_details['landmark']??"").($basic_details['pin_code']??""));?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Ownership of business premises : <span style="font-weight:bold;" > <?=$basic_details['premises_owner_name'];?> </span></td>
                                     <td>Application No. : <span style="font-weight:bold;" ><?=$basic_details['application_no'];?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Business code : <span style="font-weight:bold;" >(<?=$basic_details['nature_of_bussiness'];?> )</span></td>
                                     <td>Date & time of Application : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['apply_date']));?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Date of establishment : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['establishment_date']));?></span></td>
                                     <td>Mobile No. : <span style="font-weight:bold;" > <?=$basic_details['mobile'];?> </span></td>
                                 </tr>

                             </table>


                             <table style="width:100%;color:#B9290A;">
                                 <tr>
                                     <td>&nbsp;&nbsp;

										<p >This is to declare that <b><?=$basic_details['firm_name'];?></b> having application number <b><?=$basic_details['application_no'];?></b> has been successfully registered with us 
                                            with satisfactory compliance of registration criteria and to certify that trade licence number <b><?=$basic_details['license_no'];?></b>
                                         has been allocated to
                                         <b><?=$basic_details['firm_name'];?></b> for conducting business which is (
                                            <?php
                                                    if(isset($item_details)):
                                                         if(empty($item_details)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $tradecodeArray = [];
                                                    foreach($item_details as $value) { 
                                                        $tradecodeArray[] = $value['trade_code']; 
                                                    }
                                                    echo implode(', ', $tradecodeArray);
                                                      endif;  
                                                 endif;  ?>
                                            ) as per business code mentioned in Jharkhand Municipal Act 2011 in 
                                            the regime of this local body. The validity of this subject to meeting the terms and conditions as specified in U/S 455 of Jharkahnd 
                                            Municipal Act 2011 and other applicable sections in the act along with following terms and conditions:-</p>

                                     </td>                                     
                                 </tr>                                 
                             </table>

                             <table style="width:100%;color:#B9290A;">
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>

                                 <tr>
                                    <td>
                                        <ol>
                                            <li>The business will run according to the license issued.</li>
                                            <li>Prior Permission from the local body is necessary if the business is changed.</li>
                                            <li>Information to the local body is necessary for the extension of the area.</li>
                                            <li>Prior Information to local body regarding winding of business is necessary.</li>
                                            <li>Application for renewal of the license is necessary one month before the expiry of the license.</li>
                                            <li>In case of delay, a penalty will be levied according to rule 259 of the Jharkhand Municipal Act 2011.</li>
                                            <li>Illegal Parking in front of the firm is non-permissible.</li>

                                            <li>A Sufficient number of containers for disposing of garbage & refuse shall be made available in the premises and the license will co-operate with the ULB for disposal of such waste.</li>
                                            <li>SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</li>
                                        </ol>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
                                 <tr style="display:flex; flex-direction:column;">
                                 <td>
                                 <span style="float:right;">
                                     <?php
                                     $sign = "dmcsign.png"; 
                                     if($basic_details['license_date']>='2025-01-16'){
                                        $sign = "gautam.png"; 
                                    }
                                     ?>
                                    <!-- <img src="<?= base_url("/writable/eo_sign/$sign") ?>" style="width: 100px;height: 50px; float: right;"> -->
                                    <img src="<?= $signature_path ?>" style="width: 100px;height: 50px; float: right;">
                                 </span>
                                 </td>
                                 </tr>
                                 <tr>

                                     <td>
                                        Note: This is a computer generated Licence. This Licence does not require a physical signature.

                                        <span style="float:right;">Signature : </span><br>
                                        
                                     </td>
                                 </tr>
                             </table>
                             <br />

	                    </div>

                        <br />
                        <center>
                            <div class="row"style="margin-bottom:1%;">
                                <div class="col-md-12">
                                    <!-- <button type="button" id="btnPrint" class="noprint btn  btn-primary" onclick="printData()">Print</button> -->
                                </div>
                            </div>
                        </center>
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
<script>
function printData1()
{
   var divToPrint=document.getElementById("printablediv");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}
function printData()
{
    var divToPrint=document.getElementById("printablediv");
    //newWin= window.open("");
   //newWin.document.write(divToPrint.outerHTML);
   divToPrint.style.color='color:#B9290A;';
   window.print(divToPrint);
   newWin.close();
   //newWin.close();
}
</script>
