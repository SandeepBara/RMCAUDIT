<?php
echo  $this->include('layout_home/header');
?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<!--CONTENT CONTAINER-->
<div id="content-container">

	<!--Page content-->
	<div id="page-content">
		<form id="formname" name="form" method="post">
			<?php if (isset($validation)) { ?>
				<?= $validation->listErrors(); ?>
			<?php } ?>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title"> Licence Application Status</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<span style="font-weight: bold; font-size: 17px; color: #bb4b0a;"> Your Application No. is <span style="color: #179a07;"><?php echo $licencee['application_no']; ?></span>. Application Status: <?php echo $application_status; ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Apply New License</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-md-3">Application Type </label>
						<div class="col-md-3 control-label text-semibold">
							<?php if ($licencee["application_type_id"] == 1) { ?>
								NEW LICENCE
							<?php } elseif ($licencee["application_type_id"] == 2) { ?>
								RENEWAL
							<?php } elseif ($licencee["application_type_id"] == 3) { ?>
								AMENDMENT
							<?php } elseif ($licencee["application_type_id"] == 4) { ?>
								SURRENDER
							<?php } ?>
						</div>
						<label class="col-md-3">Firm Type</label>
						<div class="col-md-3 pad-btm">
							<?= $firmtype['firm_type'] ? $firmtype['firm_type'] : "N/A"; ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Type of Ownership of Business Premises</label>
						<div class="col-md-3 pad-btm">
							<?= $ownershiptype['ownership_type'] ? $ownershiptype['ownership_type'] : "N/A"; ?>
						</div>
						<label class="col-md-3">License No.</label>
						<div class="col-md-3 pad-btm">
							<?= $licencee['license_no'] ? $licencee['license_no'] : "N/A"; ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Category</label>
						<div class="col-md-3 pad-btm">
							<?= $categoryDetails['category_type'] ? $categoryDetails['category_type'] : "N/A"; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Firm Details</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-md-3">Holding / SAF No.</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['holding_no'] ? $licencee['holding_no'] : "N/A"; ?>
						</div>
						<label class="col-md-3">Ward No.</label>
						<div class="col-md-3 pad-btm">
							<?= $ward_no ? $ward_no : "N/A"; ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Firm Name</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['firm_name'] ? $licencee['firm_name'] : "N/A"; ?>
						</div>
						<label class="col-md-3">Total Area(in Sq. Ft)</label>
						<?php echo $licencee['area_in_sqft'] ? $licencee['area_in_sqft'] : "N/A"; ?>
						<input type="hidden" id="area_in_sqft" value="<?= $licencee['area_in_sqft'] ? $licencee['area_in_sqft'] : ""; ?>">
					</div>
					<div class="row">
						<label class="col-md-3">Firm Establishment Date</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['establishment_date'] ? $licencee['establishment_date'] : "N/A"; ?>
                            <?php 
                            if($licencee["application_type_id"] == 1)
                            {
                                ?>
							    <input type="hidden" id="firm_date" value="<?= $licencee['establishment_date'] ? date('d-m-Y', strtotime($licencee['establishment_date'])) : date('d-m-Y'); ?>">
                                <?php
                            }
                            else
                            {
                                ?>
							    <input type="hidden" id="firm_date" value="<?= $licencee['valid_from'] ? date('d-m-Y', strtotime($licencee['valid_from'])) : date('d-m-Y'); ?>">
                                <?php
                            }
                            ?>
                            <input type="hidden" id="notice_date" value="<?= $notice_date ? date('d-m-Y', strtotime($notice_date)) : ""; ?>">
						</div>
						<label class="col-md-3">Licence For</label>
						<div class="col-md-3 pad-btm">
							<?php if (isset($licencee['licence_for_years'])) {
								echo $licencee['licence_for_years'] . ' YEARS';
							} ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Address</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['address'] ? $licencee['address'] : "N/A"; ?>
						</div>
						<label class="col-md-3">Landmark</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['landmark'] ? $licencee['landmark'] : "N/A"; ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Pin</label>
						<div class="col-md-3 pad-btm">
							<?php echo $licencee['pin_code'] ? $licencee['pin_code'] : "N/A"; ?>
						</div>
						<label class="col-md-3">Owner of Business Premises</label>
						<div class="col-md-3 pad-btm">
							<?= $licencee['premises_owner_name'] ? $licencee['premises_owner_name'] : "N/A"; ?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Business Description</label>
						<div class="col-md-3 pad-btm">
							<?= $licencee['brife_desp_firm'] ? $licencee['brife_desp_firm'] : "N/A"; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Firm Owner Details</h3>
				</div>
				<div class="panel-body" style="padding-bottom: 0px;">
					<div class="table-responsive">
						<table class="table table-bordered text-sm">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Owner Name </th>
									<th>Guardian Name</th>
									<th>Mobile No </th>
									<th>Address</th>
									<th>City</th>
									<th>State</th>
									<th>District</th>
								</tr>
							</thead>


							<?php

							if (isset($firm_owner)) {
								if (!empty($firm_owner)) {
									foreach ($firm_owner as  $value) {
							?>
										<tr>
											<td><?= $value["owner_name"] ? $value["owner_name"] : "N/A"; ?></td>
											<td><?= $value["guardian_name"] ? $value["guardian_name"] : "N/A"; ?></td>
											<td><?= $value["mobile"] ? $value["mobile"] : "N/A"; ?></td>
											<td><?= $value["address"] ? $value["address"] : "N/A"; ?></td>
											<td><?= $value["city"] ? $value["city"] : "N/A"; ?></td>
											<td><?= $value["district"] ? $value["district"] : "N/A"; ?></td>
											<td><?= $value["state"] ? $value["state"] : "N/A"; ?></td>
										</tr>
							<?php }
								}
							} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Items of Trade</h3>
				</div>
				<div class="panel-body" style="padding-bottom: 0px;">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered text-sm">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>Trade Code</th>
											<th>Trade Item</th>
										</tr>
									</thead>
									<tbody>
										<?php if (isset($tradedetail)) {
											if (!empty($tradedetail)) {
												foreach ($tradedetail as  $tradedetail) {
										?>
													<tr>
														<td><?= $tradedetail["trade_code"] ? $tradedetail["trade_code"] : "N/A" ?></td>

														<td><?= $tradedetail["trade_item"] ? $tradedetail["trade_item"] : "N/A" ?></td>
													</tr>
										<?php }
											}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>





			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Transaction Details</h3>
				</div>
				<div class="panel-body" style="padding-bottom: 0px;">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered text-sm">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>Transaction Date</th>
											<th>Transaction No.</th>
											<th>Mode</th>
											<th>Amount</th>
											<th>View</th>
										</tr>
									</thead>

									<?php

									if (isset($trans_detail)) {
										if (!empty($trans_detail)) {
											foreach ($trans_detail as  $valuetran) {
									?>
												<tr>
													<td><?= $valuetran["transaction_date"] ?></td>

													<td><?= $valuetran["transaction_no"] ?></td>
													<td><?= $valuetran["payment_mode"] ?></td>

													<td><?= $valuetran["paid_amount"] ?></td>
													<td>
														<!-- <a target="popup" onclick="window.open('<?php echo base_url('TradeCitizen/viewTransactionReceipt/' . md5($licencee['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('TradeCitizen/provisionalCertificate/' . ''); ?>" type="button" class="btn btn-primary" style="color:white;">View Payment Receipt</a> -->
														<a target="popup" onclick="window.open('<?php echo base_url('TradeCitizen/viewTransactionReceipt/' . md5($valuetran['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('TradeCitizen/viewTransactionReceipt/' . md5($valuetran['id'])); ?>" type="button" class="btn btn-primary" style="color:white;">View Payment Receipt</a>
													
													</td>
												</tr>
									<?php }
										}
									} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="panel panel-dark" style="text-align: center; background-color: #ecf0f5;">
				<?php 
				if ($licencee['application_type_id'] != 4) 
				{ 
					?>
					<?php 
					if ($licencee['pending_status'] == 5) 
					{ 
						?>
						<a href="<?php echo base_url('TradeCitizen/municipalLicence/' . md5($licencee['id'])); ?>" target="popup" type="button" class="btn btn-primary" style="color:white;" onclick="window.open('<?php echo base_url('TradeCitizen/municipalLicence/' . md5($licencee['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">
							View Trade Licence
						</a>
						<?php 
					} 
					elseif(in_array($licencee['payment_status'], [1,2])) 
					{ 
						?>
						<a target="popup" onclick="window.open('<?php echo base_url('TradeCitizen/provisionalCertificate/' . md5($licencee['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('TradeCitizen/provisionalCertificate/' . md5($licencee['id'])); ?>" type="button" class="btn btn-primary" style="color:white;">View Provisional Certificate</a>
						<?php 
					}
					if($licencee['document_upload_status']==0)
					{
						?>
						<a href="<?=base_url('TradeCitizen/doc_upload/'.md5($licencee['id']))?>" class="btn btn-primary">Upload Document</a>
						<?php
					}
					if($licencee['payment_status']==0)
					{
						?>
						<button data-target="#demo-lg-modalss" data-toggle="modal" class="btn btn-warning" type="button" onclick="denial_carcge()">Pay Now</button>
						<?php
					}
				} 
				?>
			</div>
		</form>
	</div>
	<!--End page content-->
</div>

<div id="demo-lg-modalss" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <!-- check bounce payment -->
                <div class="panel  panel-dark" id="model" style="display: '';">
                    <div class="panel-body">
                        <div class="row">
                            <form id = 'payment' name = 'payment' action="<?php echo base_url(); ?>/TradeCitizen/check_bounce_payment/<?= md5($licencee['id']) ?>" method='post'>
                                <?php
                                //print_var($application_type);
                                if ($application_type["id"] <> 4) {
                                ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Licence Required for the Year</h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            # Renewal
                                            if ($application_type["id"] == 2) {
                                            ?>
                                                <div class="row">
                                                    <label class="col-md-2">License Expire</label>
                                                    <div class="col-md-3 pad-btm"> <b> <?= $licencee['valid_from']; ?> </b> </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="row">
                                                <label class="col-md-2">License For<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <?php

                                                    if ($application_type["id"] == 3) {
                                                    ?>
                                                        <select id="licence_for" name="licence_for" class="form-control" onclick="show_charge()" required>
                                                            <option value="1">1 Year</option>
                                                        </select>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()" required>
                                                            <option value="">--Select--</option>
                                                            <option value="1">1 Year</option>
                                                            <option value="2">2 Year</option>
                                                            <option value="3">3 Year</option>
                                                            <option value="4">4 Year</option>
                                                            <option value="5">5 Year</option>
                                                            <option value="6">6 Year</option>
                                                            <option value="7">7 Year</option>
                                                            <option value="8">8 Year</option>
                                                            <option value="9">9 Year</option>
                                                            <option value="10">10 Year</option>
                                                        </select>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="charge" disabled="disabled" class="form-control" value="<?php echo $rate ?? 0; ?>" onkeypress="return isNum(event);" required/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="penalty" disabled="disabled" class="form-control" value="<?php echo $penalty ?? 0; ?>" onkeypress="return isNum(event);" required/>
                                                </div>

                                                <label class="col-md-2">Denial Amount<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="denialAmnt" disabled="disabled" class="form-control" value="0" onkeypress="return isNum(event);" required />
                                                </div>
                                            </div>


                                            <div class="row">

                                                <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="total_charge" disabled="disabled" class="form-control" value="<?php echo $total_charge ?? 0; ?>" onkeypress="return isNum(event);" min="299" required />
                                                </div>


                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="panel panel-bordered panel-dark">
                                    <div class="col-md-10" id="dd"></div>
                                    <div class="panel-body demo-nifty-btn text-center">
                                        <?php
                                        $onclick = '';
                                        if ($application_type['id'] != 4) // Surrender
                                        {
                                            $onclick = 'onclick="return confirmsubmit()"';
                                        }
                                        ?>
                                        <input type="hidden" name="apply_from" value="JSK" />
                                        <button type="submit" id="btn_review" name="btn_review" <?= $onclick; ?> class="btn btn-primary">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END CONTENT CONTAINER-->
<script>
    function form_validate()
    { 
        $("#payment").validate({
            rules:{                         
                chq_date:{
                    required:true
                },                
                chq_no:{
                    required:true
                },                
                bank_name:{
                    required:true
                },                
                branch_name:{
                    required:true
                },                
                
                applyWith:{
                    required:true
                },                  
               
                licence_for:{
                    required:true
                }, 
                charge:{
                    required:true
                }, 
                total_charge:{
                    required:true
                }, 
                payment_mode:{
                    required:true
                },

            },
            messages:{  
                applyWith:{
                    required:"Please Select Apply With",
                },
                               
                chq_date:{
                    required:"Please Select date"  
                },                
                chq_no:{
                    required:"Please Enter Cheque/DD No."  
                },                
                bank_name:{
                    required:"Please Enter Bank Name"  
                },                
                branch_name:{
                    required:"Please Enter Branch Name"  
                },                
                licence_for:{
                    required:"Please Enter Licence For"  
                }, 

                payment_mode:{
                    required:"Please Enter Payment Mode"  
                },
                               
            }
        });
    } 

    function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }

    

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isDateFormatYYYMMDD(value){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value) ) {
            return false;
        }else{
            return true;
        }
    }

    function confirmsubmit()
    {   
        var totalcharge = document.getElementById('total_charge').value;
        var deAmnt = document.getElementById('denialAmnt').value;
        var pen = document.getElementById('penalty').value;
        var char = document.getElementById('charge').value;
        var lfor = document.getElementById('licence_for').value;        
        $('#btn_review').hide();
        
        if((totalcharge=='' ||deAmnt==''||pen==''||char==''||lfor==''))
        {
            $('#btn_review').show();
            alert('Enter All Filed');
            return false;
        }
        return true;
        
        var val =form_validate();
        //alert($("#payment").valid());
        alert(val);
        if($("#payment").valid())
        {
            var amt = $('#total_charge').val();
            var del=confirm("Are you sure you want to confirm Payment of Rs "+amt+"?");
            return del;
        }
        else
        {
            return false;
        }
        
    }
    document.ready(function(){
        $('#btn_review').click('on',function(){
            alert();
        });
    })
    function denial_carcge() {       
        var notice_date=$('#notice_date').val();   
        if (notice_date != "") 
        {
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/dinial_charge"); ?>',
                dataType: "json",
                data: {
                    "notice_date": notice_date,                   
                    
                },

                success: function(data) {
                    //alert(JSON.parse(data)) ;
                    console.log(typeof(data));
                    console.log(data);
                    
                    if (data.response == true) 
                    {
                        console.log('inside true')
                        //var cal = data.rate * timefor;
                        $("#denialAmnt").val(data.amount);                      
                        $('#btn_review').show();
                    } 
                    
                }

            });
        }

    }

    function show_charge() {
        var timefor = $("#licence_for").val();
        var str = $("#area_in_sqft").val();
        var edate = $("#firm_date").val();
        var noticedate = $("#noticedate").val();
        if (<?= $application_type['id'] ?> == 1) 
		{
            if (edate > noticedate && noticedate != "") {
                $(".hideNotice").css("display", "none");
                $("#denialAmnt").val(0);
                alert("Notice date should not be smaller then Firm establishment date");
                $("#applyWith option:selected").prop("selected", false);
                $("#noticeNo").val("");
                $("#noticedate").val("");
                $("#owner_business_premises").val("");
            }
        }
        if (str != "" && timefor != "") 
		{
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/getcharge"); ?>',
                dataType: "json",
                data: {
                    "areasqft": str,
                    "applytypeid": <?= $application_type["id"] ?>,
                    "estdate": edate,
                    "licensefor": timefor,
                    "tobacco_status": 0,
                    "nature_of_business":<?= $licencee['nature_of_bussiness']; ?>,
                    apply_licence_id: <?= $licencee['id'] ?>
                },

                success: function(data) {
                    console.log(data);
                    // alert(data);
                    if (data.response == true) {
                        var cal = data.rate * timefor;
                        $("#charge").val(data.rate);
                        $("#penalty").val(data.penalty);
                        $("#total_charge").val(data.total_charge);
                        var ttlamnt = parseInt(data.total_charge) + parseInt($("#denialAmnt").val());
                        $("#total_charge").val(ttlamnt);
                        $('#btn_review').show();
                    } else {

                        $("#charge").val(0);
                        $("#penalty").val(0);
                        $("#total_charge").val(0);
                        $("#denialAmnt").val(0);

                    }
                }

            });
        }

        <?php
        if ($application_type["id"] == 21) {
        ?>
            var for_year = $('#licence_for').val();
            var valid_from = $('#firm_date').val();
            //alert(for_year);alert(valid_from); 
            $('#btn_review').display = 'none';
            $('#btn_review').hide();
            jQuery.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/re_day_diff"); ?>' + '/' + valid_from + '/' + for_year + '/' + 'ajax',
                dataType: "json",

                success: function(data) {
                    console.log(data);
                    if (parseInt(data.diff_day) < 0) {
                        $("#licence_for option:selected").prop("selected", false);
                        $("#charge").val('');
                        $("#penalty").val('');
                        $("#total_charge").val('');
                    }

                    $('#btn_review').show();

                }
            });
        <?php
        }
        ?>

    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH') {
            $('#chqno').hide();
            $('#chqbank').hide();
        } else {
            $('#chqno').show();
            $('#chqbank').show();
        }
    }
</script>
<?= $this->include('layout_home/footer'); ?>