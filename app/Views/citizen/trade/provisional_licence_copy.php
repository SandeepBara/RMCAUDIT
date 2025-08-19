<?php echo  $this->include('layout_home/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id'];?>.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id'];?>.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}

</style>

<head>
    
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script>

    
</head>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
          
                <!--Page content-->
                <!--===================================================-->
	<div id="content-container">
		<div id="page-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel">
							
				<!-- ======= Cta Section ======= -->
						<div class="panel panel-dark">
								
							<div class="panel-body" id="print_watermark">
								<div class="panel-body" style="border: solid 2px black;">
									<div class="col-sm-1"></div>
									<div class="col-sm-10" style="text-align: center;">
										<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
									</div>
									<div class="col-sm-1 noprint text-right">
										<button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button>
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
												
												<td>
													<center>
														<label style="font-size:18px;color:#B9290A;"><strong>Provisional Municipal Trade License</strong><br/></label><br/>
													</center>
												</td>
											</tr>
											<tr>
												<td>
													<center>
														<label style="font-size:14px;color:#B9290A;">(This Certificate relates to  Section 155 (i) and 455 (i) Under Jharkhand Municipal Act of 2011)<br/></label><br/>
													</center>
												</td>
											</tr>
										</tbody>
									</table>

							
								<table style="width:90%;color:#B9290A;">
									<tr>
										<td>
											Application No : <span style="font-weight:bold;" ><?=$basic_details['application_no'];?></span><br/>
										</td>
										<td>
											Provisional License No : <span style="font-weight:bold;" ><?=$basic_details['provisional_license_no'];?></span><br/>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>
											Apply Date : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['apply_date']));?></span><br/>
										</td>
									</tr>
									<tr>
										<td>Mr/Mrs : <span style="font-weight:bold;" ><?=$basic_details['owner_name'];?></span><br/></td>
										<td rowspan="4" style="text-align: right;">
											<img style="margin-right:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
										</td>
									</tr>
									<tr>
										<td>in the : <span style="font-weight:bold;" ><?=$ulb_mstr_name['ulb_name'];?> </span> Municipal Area</td>
									</tr>
									<tr>
										<td>Firm / organization  name : <span style="font-weight:bold;" ><?=$basic_details['firm_name'];?></span></td>
									</tr>
								
									<tr>
										<td>Ward No. : <span style="font-weight:bold;" ><?=$ward['ward_no'];?></span></td>
									</tr>
									<tr>
										<td>Business Address : <span style="font-weight:bold;" >
											<?=strtoupper($basic_details['address']);?>
											</span>
										</td>
									</tr>
									<tr>
										<td>For defined Fee : <span style="font-weight:bold;" ><?=$tranProvDtl['paid_amount'];?></span></td>
									</tr>
									<tr>
										<td>Having  receipt no : <span style="font-weight:bold;" >
											<?=$tranProvDtl['transaction_no'];?>
											</span>
										</td>
									</tr>
									<tr>
										<td>Establishment Date : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details['establishment_date']));?></span>
										</td>
									</tr>
									<tr>
										<td>Valid Upto : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($valid_upto));?></span>
										</td>
									</tr>
									<tr>
										<td>Subject to the following terms, license is granted.</td>
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
												<li>Prior information to local body regarding winding of business is necessary.</li>
												<li>Application for renewal of license is necessary one month before expiry of license.</li>
												<li>In the case of delay penalty will be levied according to section 459 of Jharkhand Municipal Act 2011.</li>
												<li>Illegal Parking in front of firm in non-permissible.</li>
												<li>Sufficient number of containers for disposing-garbage and refuse shall be made available within.</li>
												<li>The premises and the licensee will co-operate with the ULB for disposal of such waste.</li>
												<li>SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</li>
												<li>This provisional license is valid for 20 days from the date of apply . In case of no-objection from
												  <strong><?=$ulb_mstr_name['ulb_name'];?></strong>
												,The license shall be deemed approved. </li>
												<li>The final license can be downloaded from<span style="font-size:12px;color: #980601"> www.modernulb.com</span></li>
											</ol>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" align="left" style="font-size:12px;color: #980601"><p></p>For More Details Please Visit : udhd.jharkhand.gov.in<br />Or Call us at 18008904115 or 0651-3500700</td>
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
							</div>
						
							<br />
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
<?php echo $this->include('layout_home/footer'); ?>
