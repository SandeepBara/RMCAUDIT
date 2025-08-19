<?= $this->include('layout_vertical/header');?>


<style>
@media print {
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
									 <td>Issue date of Municipal Trade Licence Number : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['license_date']));?></span></td>
								 </tr>
								 <tr>
									 <td>Validity of Municipal Trade Licence Number : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['valid_upto']));?></span></td>
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
                                     <td>Street Address : <span style="font-weight:bold;" ><?=$basic_details['address'];?></span></td>
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
                                         has been allocated <b><?=$basic_details['firm_name'];?></b> for conducting business which is (
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
                                            <li>Business will run according to licence issued.</li>
                                            <li>Prior Permission from local body is necessary if business is changed.</li>
                                            <li>Information to local body is necessary for extension of area.</li>
                                            <li>Prior Information to local body regarding winding of business is necessary.</li>
                                            <li>Application for renewal of licence is necessary one month before expiry of licence.</li>
                                            <li>In case of delay penalty will be levied according to rule 259 of Jharkhand Municipal Act 2011.</li>
                                            <li>Illegal Parking in front of firm in non-permissible.</li>
                                            <li>Sufficient number of containers for disposing-garbage and refuse shall be made available within the premises and the licence will co-operate with the ULB for disposal of such waste.</li>
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
                                 <tr>
                                     <td>Note: This is a computer generated Licence. This Licence does not require a physical signature.</td>
                                 </tr>
                             </table>
                             <br />

	                    </div>

                        <br />
                        <center>
                            <div class="row"style="margin-bottom:1%;">
                                <div class="col-md-12">
                                    <button type="button" id="btnPrint" class="noprint btn  btn-primary" onclick="printData()">Print</button>
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
