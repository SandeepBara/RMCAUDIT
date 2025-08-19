
<?= $this->include('layout_vertical/header');
?>
<style>
 .row{line-height:25px;}
 textarea.form-control{border-color: black;}
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
													<a href="<?php echo base_url('collection_Verification/cash_verf_pending_lists')?>" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">TC Collection Details</h3>
											</div>											
											
											
											<form class="form-horizontal" method="post" action="">
											<div class="panel-heading" style="background-color:#36a5d0;">
												<div class="col-md-6">
													<h3 class="panel-title">TC Name:<b>&nbsp;&nbsp;&nbsp;<?php echo $tc_name; ?></b></h3>
												</div>
											
											<div class="col-md-6">
													<h3 class="panel-title" style="text-align: right;">Total Pending Amount:<b>&nbsp;&nbsp;&nbsp;<?php echo round($payable_amt,2); ?></b></h3>
												</div>
											</div>
											

											<div class="col-md-12" style="background-color:white;">
												<h3 class="panel-title"><b> Pending Cheques and DDs</b> </h3>
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
															<thead class="thead-light" style="background-color: blanchedalmond;">
																	<th scope="col"> #</th>
																	<th scope="col"> 
																	Transaction Date </th>
																	<th scope="col"> 
																	Transaction No. </th>
																	<th scope="col"> 
																	Holding No. </th>
																	<th scope="col"> 
																	Owner Name </th>
																	<th scope="col"> 
																	Mobile No. </th>
																	<th scope="col"> Cheque No.</th>
																	<th scope="col"> Cheque Date</th>
																	<th scope="col"> Branch Name</th>
																	<th scope="col"> Bank Name</th>
																	
																	<th scope="col"> Amount</th>
																	
																	
															</thead>
															<tbody>

																<?php
																if($chq_details_prop){
																	$i=1;
																	$cash_mstr_id="";
																	foreach($chq_details_prop as $val)
																	{
																		
																		?>
			<tr>
				<td><?php echo $i++;?> </td>
				<td><?php echo date('d-m-Y',strtotime($val['tran_date']));?> </td>			
				<td><?php echo $val['tran_no'];?></td>
				<td><?php echo $val['holding_no'];?></td>
				<td><?php echo $val['owner_name'];?></td>
				<td><?php echo $val['mobile_no'];?></td>
				<td><?php echo $val['cheque_no'];?></td>
				<td><?php echo $val['cheque_date'];?></td>
				<td><?php echo $val['branch_name'];?></td>
				<td><?php echo $val['bank_name'];?></td>
				
				<td><?php echo round($val['paid'],2);?></td>
				
			</tr>
							<?php
																	}
																}
																
																?>
<?php
																if($chq_details_sad){
																	$i=1;
																	$cash_mstr_id="";
																	foreach($chq_details_prop as $val)
																	{
																		
																		?>
			<tr>
				<td><?php echo $i++;?> </td>
				<td><?php echo date('d-m-Y',strtotime($val['tran_date']));?> </td>			
				<td><?php echo $val['tran_no'];?></td>
				<td><?php echo $val['holding_no'];?></td>
				<td><?php echo $val['owner_name'];?></td>
				<td><?php echo $val['mobile_no'];?></td>
				<td><?php echo $val['cheque_no'];?></td>
				<td><?php echo $val['cheque_date'];?></td>
				<td><?php echo $val['branch_name'];?></td>
				<td><?php echo $val['bank_name'];?></td>
				
				<td><?php echo round($val['paid'],2);?></td>
				
			</tr>
							<?php
																	}
																}
																
																?>
														
															</tbody>
														</table>
														<form method="post">
														
															
															<input type="hidden" name="notification_id" id="notification_id" value="<?php echo $id;?>">
															
															<?php 
if($user_type_mstr_id==3)
{
															?>
															<div class="row">
																<div class="col-md-12">
															<button type="submit" name="verify" id="verify" class="btn btn-success" style="align:center;">Verify</button>
															</div>
															</div>
															<?php
														}
															?>
														</form>
													</div>
												</div>
												
											
										</div>
									</div>
								</div>
			</div>
			
			
  
<?= $this->include('layout_vertical/footer');?>


<script>

/*function validate() {
        var chk = document.getElementById("recheckbox");
        var textarea = document.getElementById("Remarks");
        if (chk.checked == true)
            textarea.hidden = false;
        else
            textarea.hidden = true;
    }*/


    function validate_amt(str)
    {

    	var verified_amt=str;
    	var collection_amount=$("#collection_amount");
    	if(verified_amt>collection_amount)
    	{
    		alert('Verified Amount can not be greater than Collection Amount');
    		$("#Verified_amount").val("");

    	}
    }
    function validate(arg){

    
		var str=($('#cash_checkbox').is(':checked'));
		if(str==true)
		{

			verify_amount();

		}
		//alert(str)
    	if(str==false)
    	{
    		$("#Remarks").show();
    		$("#Verified_amount").prop('disabled',false);
    		$("#Verified_amount").val("");
    		$("#cash_verify_amt").val("");

    	}
       else if($('#allVerified').is(":checked"))
       {
       		$("#Remarks").hide();
       }
       else
       {
       	    $("#Remarks").show();
       			//alert('hello')
       }
      

    }


    function verify_amount(){

    	
    	//alert('hi');
    	$("#Verified_amount").prop('disabled',true);
    	var collection_amount=$("#collection_amount").val();
    	$("#Verified_amount").val(collection_amount);
    	$("#cash_verify_amt").val(collection_amount);

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

document.getElementsByName('amountVerify[]').onclick = function() {
		//alert('hhsdjjsda')
	  var checkboxes = document.getElementsByName('amountVerify[]');
	  for (var checkbox of checkboxes) {
	  	if(this.unchecked)
	  	{
	  		$("#Remarks").show();
	  		$("#allVerified").attr('checked',false);
	  	}
		
	  }
	}

	$(function () {
        $("#btnsave").click(function () { alert(('#Verified_amount').val());
				/*if(('#Verified_amount').val()==""){  alert('bb');
				return false;
			}*/
        });
    });
</script>
