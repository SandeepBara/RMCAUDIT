
<?= $this->include('layout_vertical/header');
?>
<style>
 .row{line-height:25px;}
 .error{color: red;}
 textarea.form-control{border-color: black;}
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
<script src="jquery-3.5.1.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<script >
	
	$(document).ready(function () {

    $('#myform').validate({ // initialize the plugin
        rules: {
            Verified_amount: {
                required: true,
               
            },
           
        }
    });

});

</script>

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
					<li><a href="#">Account</a></li>
					<li class="active">Collection Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<a href="<?php echo base_url('CashVerification/details'); ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">TC Collection Details</h3>
						</div>											
						<form class="form-horizontal" method="post" id="myform" name="myform">
							<input type="hidden" name="trans_date" id="trans_date" value="<?php echo $trans_date;?>">
							<input type="hidden" name="employee_id" id="employee_id" value="<?php echo 
							md5($cash_collected['tran_by_emp_details_id']);?>">
							<div class="panel-heading" style="background-color:#36a5d0;">
								<div class="col-md-4">
									<h3 class="panel-title">TC Name:<b>&nbsp;&nbsp;&nbsp;<?php echo $cash_verf_details['emp_name']; ?></b></h3>
								</div>
								<div class="col-md-4">
									<h3 class="panel-title">Transaction Date:&nbsp;&nbsp;&nbsp;
									<b><?php echo date('d-m-Y',strtotime($trans_date));?></b>
									</h3>
								</div>
								<div class="col-md-4" style="text-align:right;">
									<h3 class="panel-title">Total Amount :<b >&nbsp;&nbsp;&nbsp;<?php echo round($total_coll); ?>.00</b>
										<input type="hidden" name="total_collection" id="total_collection" value="<?php echo round($total_coll,2);?>">
										<input type="hidden" name="cash_amount" id="cash_amount" value="<?php echo round($cash_collected,2);?>">
									</h3>
								</div>
							</div>
							

							<div class="col-md-12" style="background-color:white;">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
											<thead class="bg-trans-dark text-dark">
													<th scope="col"> Property</th>
													<th scope="col"> GB SAF</th>
													<th scope="col"> Water</th>
													<th scope="col"> Trade</th>
													<th scope="col"> Total</th>
													<th scope="col">Verify Cash</th>
											</thead>
											<tbody>
												<tr>
													<td><?php echo number_format($cash_collected['prop_saf'], 2); ?></td>
													<td><?php echo number_format($cash_collected['gsaf'], 2); ?></td>
													<td><?php echo number_format($cash_collected['water'], 2); ?></td>
													<td><?php echo number_format($cash_collected['trade'], 2); ?></td>
													<td><?php echo number_format($cash_collected['total'], 2); ?></td>
													<td>
														<?php if($cash_collected['total']){?>
															<input type="checkbox" id="cashVerify" name="cashVerify" value="cashVerify" onchange="validate()" class="require_one">

														<?php }?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div><hr>
								<h3 class="panel-title"><b style="color:red;"> Cheque Details</b></h3><hr style="margin-top: 0px;">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
											<thead class="bg-trans-dark text-dark">
													<th scope="col"> Sl No.</th>
													<th scope="col"> Module</th>
													<th scope="col"> Payment Mode</th>
													<th scope="col"> Transaction Date</th>
													<th scope="col"> Transaction No.</th>
													<th scope="col"> Cheque No.</th>
													<th scope="col"> Bank Name</th>
													<th scope="col"> Branch Name</th>
													<th scope="col"> Cheque Date</th>
													<th scope="col"> Amount</th>
													<th scope="col"> Cheque Received </th>
											</thead>
											<tbody>
												<?php 
												$i=1;
												if($prop_cheque_dtls){
												
												foreach($prop_cheque_dtls as $val1){


													 ?>
													
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo "Property"; ?></td>
														<td><?php  if($val1['tran_mode_mstr_id']==2){ echo "CHEQUE";}else{ echo "DD"; }; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val1['tran_date'])); ?></td>
														<td><?php echo $val1['tran_no']; ?></td>
														<td><?php echo $val1['cheque_no']; ?></td>
														<td><?php echo $val1['bank_name']; ?></td>
														<td><?php echo $val1['branch_name']; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val1['cheque_date'])); ?></td>
														<td><?php echo round($val1['payable_amt']); ?>.00</td>
														<td><input type="checkbox" id="checkbox" value="<?php echo $val1['id'].'/property';?>" onchange="validate()" name="amountVerify[]" class="require_one"></td>
													</tr>
												<?php $i++;  } }

												if($gbsaf_cheque_details){
												
												foreach($gbsaf_cheque_details as $val){


													 ?>
													
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo "GBSAF"; ?></td>
														<td><?php  if($val['tran_mode_mstr_id']==2){ echo "CHEQUE";}else{ echo "DD"; }; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val['tran_date'])); ?></td>
														<td><?php echo $val['tran_no']; ?></td>
														<td><?php echo $val['cheque_no']; ?></td>
														<td><?php echo $val['bank_name']; ?></td>
														<td><?php echo $val['branch_name']; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val['cheque_date'])); ?></td>
														<td><?php echo round($val['payable_amt']); ?>.00</td>
														<td><input type="checkbox" id="checkbox" value="<?php echo $val['id'].'/gbsaf';?>" onchange="validate()" name="amountVerify[]"  class="require_one"></td>
													</tr>
												<?php $i++;  } }

												if($water_cheque_dtls){
											
												foreach($water_cheque_dtls as $val2){

													 ?>
													
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo "Water"; ?></td>
														<td><?php echo $val2['payment_mode']; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val2['transaction_date'])); ?></td>
														<td><?php echo $val2['transaction_no']; ?></td>
														<td><?php echo $val2['cheque_no']; ?></td>
														<td><?php echo $val2['bank_name']; ?></td>
														<td><?php echo $val2['branch_name']; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val2['cheque_date'])); ?></td>
														<td><?php echo round($val2['paid_amount']); ?>.00</td>
														<td><input type="checkbox" id="checkbox" value="<?php echo $val2['id'].'/water';?>" onchange="validate()" name="amountVerify[]"  class="require_one"></td>
													</tr>
												<?php $i++;  } }

												if($trade_cheque_dtls){
												
												foreach($trade_cheque_dtls as $val3){

												

													 ?>
													
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo "Trade"; ?></td>
														<td><?php echo $val3['payment_mode']; ?></td>
														
														<td><?php echo date('d-m-Y',strtotime($val3['transaction_date'])); ?></td>
														<td><?php echo $val3['transaction_no']; ?></td>
														<td><?php echo $val3['cheque_no']; ?></td>
														<td><?php echo $val3['bank_name']; ?></td>
														<td><?php echo $val3['branch_name']; ?></td>
														<td><?php echo date('d-m-Y',strtotime($val3['cheque_date'])); ?></td>
														<td><?php echo round($val3['paid_amount']); ?>.00</td>
														<td><input type="checkbox" id="checkbox" value="<?php echo $val3['id'].'/trade';?>" onchange="validate()" name="amountVerify[]"  class="require_one"></td>
													</tr>
												<?php $i++;  } } ?>


											</tbody>
										</table>
									</div>
								</div>
								
								<div class="col-md-12" style="text-align:right;">
									<h4 class="panel-title">
									<input type="checkbox" id="allVerified" value="agreed" onchange="validate()" name="allVerified">
									&nbsp;&nbsp;<label for="checkbox" style="color:red;"><b>All Verified </b></h4>
								</div>
								
								<!--<div class="col-md-12" id="Remarks" >
									<div class="col-md-2" style="color: black; font-weight: bold;">Remarks</div>
									<div class="col-md-10"><textarea name="remarks" id="remarks"  class="form-control" style="display: block;" placeholder="Enter text Here....." required="required"></textarea></div>
								</div>-->
							</div>
							<div class="col-md-12" style="background-color:white;">
								<div class="panel">
									<div class="panel-body text-center">
										<button  type="submit" class="btn btn-primary btn-labeled" name="btnSave" id="btnsave" value="Save" onsubmit="put_data()">Verified</button>  
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

	$(document).ready(function () 
    {

	    $('#myform').validate({ // initialize the plugin
	       

	        rules: {
	            remarks: {
	                required: true,
	               
	            },
	            "amountVerify[]": {
                    require_from_group: [1, ".require_one"]
                },
                "cashVerify": {
                    require_from_group: [1, ".require_one"]
                },

               
	        },
	        
	        

	    });

	});

    function validate()
    {
    	//alert('ddd');
  		var myform='myform';
       if($('#allVerified').is(":checked"))
       {
       		$("#Remarks").hide();
       }
       else
       {
       	    $("#Remarks").show();
       			//alert('hello')
       }
     var anyBoxesChecked = true;
     var cashVerify=$("#cashVerify").is(":checked");
     //alert(cashVerify);

    $('#' + myform + ' input[name="amountVerify[]"]').each(function() {
    	
    	var cash_amount=$("#cash_amount").val();
    	if(cash_amount==0)
    	{
    		cashVerify=true;
    	}
        if (!$(this).is(":checked")) {
            anyBoxesChecked = false;
          
           $("#Remarks").show();
      		$("#allVerified").prop('checked',false);
           
        }
    });
    if (anyBoxesChecked == true && cashVerify==true) {
    		
    		$("#Remarks").hide();
      		$("#allVerified").prop('checked',true);
    } 
    else
    {
    		$("#Remarks").show();
      		$("#allVerified").prop('checked',false);
    }

    }
	document.getElementById('allVerified').onclick = function() {
		var chk=$("#allVerified").is(':checked');
		//alert(chk);
		if(chk==true)
		{
			
			$("#cashVerify").attr('checked',true);
			 var checkboxes = document.getElementsByName('amountVerify[]');
	 			 for (var checkbox of checkboxes) {
				 checkbox.checked = this.checked;
	 		 }
		}
		else
		{

			//alert('dddd');
			$("#cash_verify_amt").val("");
			$("#Verified_amount").val("");
			$("#cashVerify").attr('checked',false);
			 var checkboxes = document.getElementsByName('amountVerify[]');
	 			 for (var checkbox of checkboxes) {
				 checkbox.checked = this.unchecked;
	 		 }

		}
	}

	function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
        if($cashVerification=flashToast('message'))
        {
            echo "modelInfo('".$cashVerification."');";
        }
    ?>

	
</script>
