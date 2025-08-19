
<?= $this->include('layout_vertical/header');
?>
<style>
 .row{line-height:25px;}
 textarea.form-control{border-color: black;}
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
<script src="jquery-3.5.1.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

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
									<div class="panel-body">
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<div class="panel-control">
													<a href="<?php echo base_url('collection_Verification/account_Verification'); ?>" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">TC Collection Details</h3>
											</div>											
											
											
											<form class="form-horizontal" method="post" action="" id="myform" name="myform">
												<input type="hidden" name="trans_date" id="trans_date" value="<?php echo $date_from;?>">
												<input type="hidden" name="ward_id" id="ward_id" value="<?php echo 
												$ward_id;?>">
											<div class="panel-heading" style="background-color:#36a5d0;">
												<div class="col-md-4">
													<h3 class="panel-title">TC Name:<b>&nbsp;&nbsp;&nbsp;<?php echo $tc_name; ?></b></h3>
												</div>
												<div class="col-md-4">
													<h3 class="panel-title">Transaction Date:&nbsp;&nbsp;&nbsp;

													<b><?php echo date('d-m-Y',strtotime($date_from));?></b>
													</h3>
												</div>
												<div class="col-md-4" style="text-align:right;">
													<h3 class="panel-title">Total Amount :<b >&nbsp;&nbsp;&nbsp;<?php echo round($collection_amount,2); ?></b>
														<input type="hidden" name="total_collection" id="total_collection" value="<?php echo round($collection_amount,2);?>">
													</h3>
												</div>
											</div>
											<?php
											if($error_message)
											{?>
											<div class="bg-danger" style="text-align: center;"><span><?php echo $error_message; unset($error_message); ?></span></div>

											<?php
											}
											?>

											<div class="col-md-12" style="background-color:white;">
												<h3 class="panel-title"><b> Cash</b> </h3>
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
															<thead class="thead-light" style="background-color: blanchedalmond;">
																	<th scope="col"> #</th>
																	<th scope="col"> Total Cash Collected </th>
																	<th scope="col"> Total Cash Received</th>
															</thead>
															<tbody>
																<tr>
																	<td>Total Cash Amount</td>
																	<td><?php echo round($cash_amount,2); ?></td>
																	<td><?php echo round($cash_amount,2); ?>
																		
																	</td>
																	
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<h3 class="panel-title"><b> Cheque Details</b> </h3>
												<div class="col-md-12">
													<div class="table-responsive">

														<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
															<thead class="thead-light" style="background-color: blanchedalmond;">
																	<th scope="col"> Sl No.</th>
																	<th scope="col">Transaction Date</th>
																	<th scope="col">Transaction No.</th>

																	<th scope="col"> Cheque No.</th>
																	<th scope="col"> Bank Name</th>
																	<th scope="col"> Branch Name</th>
																	<th scope="col"> Cheque Date</th>
																	
																	<th scope="col"> Amount</th>
																	<th scope="col"> Cheque Received </th>
																	
															</thead>
															<tbody>
																<?php if($tc_coll_by_cheque){
																$i=1;
																foreach($tc_coll_by_cheque as $val){
																	
																	 ?>
																	<tr>
																		<td><?php echo $i; ?></td>
																		<td><?php echo date('d-m-Y',strtotime($val['tran_date'])); ?></td>
																		
																		<td><?php echo $val['tran_no']; ?></td>
																		<td><?php echo $val['cheque_no']; ?></td>

																		<td><?php echo $val['bank_name']; ?></td>
																		<td><?php echo $val['branch_name']; ?></td>
																		<td><?php echo $val['cheque_date']; ?></td>

																		<td><?php echo round($val['payable_amt'],2); ?></td>
																		<td><input type="checkbox" id="checkbox" value="<?php echo $val['transaction_id'];?>" onchange="validate()" name="amountVerify[]"></td>
																	</tr>
																<?php $i++;} }
																else{

																	?>
																	<div class="bg bg-danger" style="text-align: center;">No Data Found.</div>
																	<?php
																} ?>
															</tbody>
														</table>
													</div>
												</div>
												<h3 class="panel-title"><b> DD Details</b> </h3>
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
															<thead class="thead-light" style="background-color: blanchedalmond;">
																	<th scope="col"> Sl No.</th>
																	<th scope="col"> Transaction Date</th>
																	<th scope="col"> DD No.</th>
																	<th scope="col"> Bank Name</th>
																	<th scope="col"> Branch Name</th>
																	<th scope="col"> DD Date</th>
																	<th scope="col"> Amount</th>
																	<th scope="col"> DD Received</th>
																	
															</thead>
															<tbody>
																<?php if($tc_collection_by_dd){
																	$i=1;
																		foreach($tc_collection_by_dd AS $val)
																		{
																	 ?>
																	
																		<tr>
																			<td><?php echo $i; ?></td>
																			<td><?php echo $val['tran_date']; ?></td>
																			<td><?php echo $val['cheque_no']; ?></td>
																			<td><?php echo $val['bank_name']; ?></td>
																			<td><?php echo $val['branch_name']; ?></td>
																			<td><?php echo $val['cheque_date']; ?></td>
																			<td><?php echo round($val['payable_amt'],2); ?></td>
																			<td><input type="checkbox" id="checkbox" value="<?php echo $val['transaction_id']; ?>" onchange="validate()" name="amountVerify[]"></td>
																		</tr>
																	
																		
																	<?php } } 
																else{

																	?>
																	<tr>
																		<td colspan="8" class="bg bg-danger" style="text-align: center; background-color: red;">No Data Found.</td>
																	<?php
																} 
																	?>
															
															</tbody>
														</table>
													</div>
												</div>
												<div class="col-md-12" style="text-align:right;">
													<h4 class="panel-title">
													<input type="checkbox" id="allVerified" value="agreed" onchange="validate()" name="allVerified">
													&nbsp;&nbsp;<label for="checkbox" style="color:red;"><b>All Verified </b></h4>
												</div>
												
												<div class="col-md-12" id="Remarks" >
													<div class="col-md-2" style="color: black; font-weight: bold;">Remarks</div>
													<div class="col-md-10"><textarea name="remarks" id="remarks"  class="form-control" style="display: block;" placeholder="Enter text Here....." ></textarea></div>
												</div>
												<input type="hidden" name="tran_by_emp_details_id" value="<?php echo $tc_collection_details[0]['tran_by_emp_details_id'];?>" >
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
			</div>
			
			
  
<?= $this->include('layout_vertical/footer');?>


<script>

    function validate()
    {
  		

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
    $('#' + myform + ' input[name="amountVerify[]"]').each(function() {
        if (!$(this).is(":checked")) {
            anyBoxesChecked = false;
          
           $("#Remarks").show();
      		$("#allVerified").prop('checked',false);
           
        }
    });
 	
    if (anyBoxesChecked == true) {
    		
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
			$("#cash_checkbox").attr('checked',true);
			 var checkboxes = document.getElementsByName('amountVerify[]');
	 			 for (var checkbox of checkboxes) {
				 checkbox.checked = this.checked;
	 		 }

		}
		else
		{
				
			$("#cash_verify_amt").val("");
			$("#Verified_amount").val("");
			$("#cash_checkbox").attr('checked',false);


			 var checkboxes = document.getElementsByName('amountVerify[]');
	 			 for (var checkbox of checkboxes) {
				 checkbox.checked = this.unchecked;
	 		 }
		}
		

	 
	}



</script>
