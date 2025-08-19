
<?=$this->include("layout_mobi/header");?>
<style>
	.row{line-height:25px;}
	.error{
		color: red;
	}
	.row{
		line-height:25px;
	}
	.bank_dtl{
		display: none;
	}
	#advance_amt{
		font-size: x-small;
		font-weight: bold;
	}
</style>
<!--CONTENT CONTAINER-->
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">
						
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<div class="panel-control">
						<button type="button" class="btn btn-info btn_wait_load" onclick="history.back()">Back</button>
					</div>
					<h3 class="panel-title">Property Details</h3>
					
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-md-3">Hoding No.</label>
						<div class="col-md-3 text-bold pad-btm">
						<?=$property["holding_no"];?>
						</div>
						
						<label class="col-md-3">New Holding No.</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$property["new_holding_no"];?>
						</div>
					</div>

					<div class="row">
						<label class="col-md-3">Notice No</label>
						<div class="col-md-3 text-bold pad-btm">
						<?=$notice["notice_no"];?>
						</div>
						
						<label class="col-md-3">Ward No</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$property["ward_no"];?>
						</div>
						
					</div>

                    <div class="row">
						<label class="col-md-3">Owner Name</label>
						<div class="col-md-3 text-bold pad-btm">
						<?=$property["owner_name"];?>
						</div>
						
						<label class="col-md-3">Mobile No</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$property["mobile_no"];?>
						</div>
						
					</div>
				
				</div>
                <form  method="post" enctype="multipart/form-data">
                    <div class="modal-body">							
                        
                        <div class="row">
                            <label class="col-md-12 text-bold">Notice Receiving</label>
                            <div class="col-md-12 has-success pad-btm">
                                <!-- <label for="propAmount">Property</label> -->
                                <input type="file" id="notice_recieving" name="notice_recieving" class="magic-radio" accept=".png, .jpg, .jpeg" required />
                                
                            </div>
                            <label class="col-md-12 text-bold">Remarks</label>
                            <div class="col-md-12 has-success pad-btm">
                                <textarea name="remarks" id="remarks" cols="25" required></textarea>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="combinePay" name="combinePay" >Served</button>
                            </div>
                        </div>
                    </div>
    
                </form>		
			</div>

			
  
<?=$this->include("layout_mobi/footer");?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>

	$("#deu_details").click(function(){
		$("#content-container").css('opacity', '0.3');
		$("#loadingDivs").show();
		
	});
	$("#pay_details").click(function(){
		$("#content-container").css('opacity', '0.3');
		$("#loadingDivs").show();
		
	});
	$("#propTax_details").click(function(){
		$("#propTax_details").html("Please Wait...");
		
	});
	$("#consession_details").click(function(){
		$("#consession_details").html("Please Wait...");
		
	});

	$("#combinePaymentForm").validate({
		
        rules: {
			"payment_mode": {
				required: true,
			},
            "remarks": {
                required: true,
            },
			"bank_name": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"branch_name": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"cheque_no1": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"cheque_date": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"total_payable_amount": {
				required: function(element){
                    return $("#pay_advance").is(":checked");
                },
				min : function(element){
                    return Math.ceil($("#total_payable_amount_temp").html());
                },
				number: true,
			}
		},
		messages: {
			"payment_mode": "Please Choose Payment Mode",
			"remarks": "Please Enter Your Remarks",
			"bank_name": "Please Enter Bank Name",
			"branch_name": "Please Enter Branch Name",
			"cheque_no1": "Please Enter Cheque/DD No",
			"cheque_date": "Please Enter Cheque/DD Date",
			"total_payable_amount": {
				required: "Please Enter Payable Amount",
				min : "Amount Should be greter than Total Paybale Amount",
				number : "Please Enter Valid Amount",
			}
		},
		submitHandler: function(form)
		{
			let com =confirm("Are you sure want to pay now?");
			if(com){
				$("#combinePay").hide();
				$("#propTax_details").hide();
				$("#combinePayment").hide();
				$("loadingDiv").show();
			}
			return com;
		}
	});

	function sumTotalAmount(){
		var waterConsumerAmount = parseFloat($("#waterConsumerAmount").val());
		var tradeAmount = parseFloat($("#tradeAmount").val());
		var propAmount = parseFloat($("#propAmount").val());
		var total = (waterConsumerAmount+tradeAmount+propAmount).toFixed(2);
		$("#totalCombine").val(total);
		$("#combine_amount").html(total);
	}
	

	function fetchWaterData() {
		var prop_dtl_id = '<?= isset($prop_dtl_id)?$prop_dtl_id:''; ?>';
		if (prop_dtl_id != null && prop_dtl_id != null) {
			$.ajax({
				url: "<?php echo base_url("WaterUserChargeProceedPaymentCitizen/fetchWaterData"); ?>",
				type: "post", //request type,
				dataType: 'json',
				data: {
					prop_dtl_id: prop_dtl_id
				},
				success: function(result) {
					let waterTaxDescription = "";
					let waterTax = "";
					console.log(result)
					if (result.status) {
						var data = result.data;
						var waterIds= $("#waterIds").val();
						var waterConsumerAmount = 0;
						if(waterIds!=""){
							waterIds+=",";
						}
						for (let i = 0; i < data.length; i++) {
							var row = data[i];
							waterIds+=row?.id + " ,";
							waterTax+=`<div class="row">`;							
							waterTaxDescription+=`<div class="row">`;
							if (row.toalPayableAmount != 0) {
								$("#waterTaxBox").show();
								if(<?=isset($total_due) && $total_due>0;?>){
									$("#combinePayment").show();
								}
								waterConsumerAmount +=parseFloat(row.toalPayableAmount);								
								waterTax+=`<table class="table table-hovered table-responsive">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>From </th>
												<th>Upto </th>
												<th>Amount</th>
												<th>Penalty</th>
												<th>Total Demands</th>
												<th>Connection Type</th>
											</tr>
										</thead>
										<tbody id="result">
								`;
								

								waterTaxDescription+=`<table class="table table-hovered table-responsive">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th style="width: 25%;">From </th>
												<th style="width: 25%;">`+row?.dueDtls?.demand_from+`</th>
												<th style="width: 25%;">Upto </th>
												<th style="width: 25%;">`+row?.dueDtls?.demand_upto+`</th>
											</tr>
										</thead>
										<tbody id="result">
											<tr>
												<td>Demand Amount</td>
												<td>`+row?.dueDtls?.amount+`</td>
												<td>Rebate</td>
												<td>`+row?.dueDtls?.rebate+`</td>
											</tr>
											<tr>
												<td>Penalty</td>
												<td>`+row?.dueDtls?.penalty+`</td>
												<td>Other Penalty</td>
												<td>`+row?.dueDtls?.other_penalty+`</td>
											</tr>
											<tr>
												<td class="text-success">Total Paybale Amount</td>
												<td class="text-success">`+row?.dueDtls?.balance_amount+`</td>
											</tr>
										</tbody>
									</table>`;
								for(let j=0;j< row?.dueDtls?.demand_list.length;j++){
									var due = row?.dueDtls?.demand_list[j];
									waterTax+=`
											<tr>
												<td>`+due?.demand_from+`</td>
												<td>`+due?.demand_upto+`</td>
												<td>`+due?.amount+`</td>
												<td>`+due?.penalty+`</td>
												<td>`+due?.balance_amount+`</td>
												<td>`+due?.connection_type+`</td>
											</tr>
									`;
										
								}
								waterTax+=`</tbody></table>`;
							
							} else {
								waterTax+= `<div class="row pad-btm text-center">
									<b style="color:green;">No Dues Are Available</b>
								</div>`;
								
								waterTaxDescription+= `<div class="row pad-btm text-center">
										<b style="color:green;">No Dues Are Available</b>
									</div>`;
							}
							waterTaxDescription+=`</div>`;
							waterTax+=`</div>`;
						}
						
						$("#waterTaxDescription").html(waterTaxDescription);
						$("#waterTax").html(waterTax);
						// $("#").vasl(waterConsumerAmount);
						$("#waterIds").val((waterIds.replace(/,+$/, '')));
						$("#waterConsumerAmount").val(waterConsumerAmount);						
						sumTotalAmount();
					} 
				}
			});
		}
	}

	function fetchProData()
	{ 
		var fy = "<?=$fy;?>";
		var qtr = "<?=$qtr;?>";

		$.ajax({
				type: "POST",
				url: "<?=base_url('/jsk/Ajax_getPropPayableAmount/'); ?>",
				dataType:"json",
				data:
				{
					fy: fy, qtr: qtr, prop_dtl_id: <?=$prop_dtl_id;?>
				},
				beforeSend: function() {
					$("#loadingPaymentDiv").show();
				},
				success: function(data)
				{
					$("#loadingPaymentDiv").hide();
					console.log(data);
					if(data.response==true)
					{
						var tbl = `<div class="row">
									<table class="table table-hovered table-responsive">`
									+data.html_data+
									`</table></div>`;
						$("#propertyTaxDescription").html(tbl);
						$("#propAmount").val(data?.data?.PayableAmount);
						// $('td').each(function(i){
						// 	$(this).removeClass('pull-right');
						// });
					}
					sumTotalAmount();
				}
			});
	}
	function paymentModeChange()
	{
		var payment_mode = $("#payment_mode").val();
		if(payment_mode=='Cash')
		{
			$(".bank_dtl").hide();
		}
		else if (['Cheque', 'DD'].includes(payment_mode))//in_array
		{
			$(".bank_dtl").show();
		}
	}

	$("document").ready(function(){
		fetchWaterData();
		fetchProData();
		paymentModeChange();
	});

</script>