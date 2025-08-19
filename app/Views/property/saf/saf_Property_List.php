
<?= $this->include('layout_vertical/header');?>

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
					<li><a href="#">Property</a></li>
					<li class="active">Search Application</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

<div id="page-content">

						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Application Search</h3>
							</div>
							<div class="panel-body">
								<div class="row">
								<form action="<?php echo base_url('safDemandPayment/saf_Property_Tax');?>" method="post" role="form" class="php-email-form">
									<div class="col-md-1">
										<label for="exampleInputEmail1">Ward No.</label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($ward): ?>
												<?php foreach($ward as $post): ?>
												<option value="<?=$post['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
								
									<div class="col-md-2">
										<label for="keyword">Enter Keyword <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keyword" value="<?php echo $keyword ?? NULL;?>" required>
											<span class="text-danger">(Enter SAF No. Or Register Mobile No. Or Owner Name)</span>
										</div>
									</div>
									<div class="col-md-3 pad-btm">
										<button type="submit" id="search" name="search" class="btn btn-primary">SEARCH</button>
									</div>
								</form>
								</div>
                            </div>
                        </div>
                    
					<?php
					if(isset($emp_details)){
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Application List :</h3>
						</div>
						<div class="panel-body">
							<div id="saf_distributed_dtl_hide_show">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
												<thead class="bg-trans-dark text-dark">
													
														<th>Sl No. </th>
														<th>Ward No </th>
														<th>Application No </th>
														<th>Owner(s) Name </th>
														<th>Address </th>
														<th>Khata No. </th>
														<th>Plot No. </th>
														<th>Mobile No. </th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php if($emp_details):
													$i=1;  ?>
													<?php foreach($emp_details as $post): ?>
														
													<tr>
														
														
														<td><?php echo $i++; ?></td>
														<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
														<td><?=$post['saf_no']?$post['saf_no']:"N/A"; ?></td>
														
														<td>
															<?php echo $post['owner_name']; ?><br>
														</td>
														<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
														<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
														<td><?=$post['plot_no']?$post['plot_no']:"N/A"; ?></td>
														<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
														<td>
															<a href="<?php echo base_url('safDemandPayment/saf_due_details/'.md5($post['saf_dtl_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
													</tr>
													<?php endforeach; ?>
													<?php else: ?>
													<tr>
														<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
													</tr>

													<?php endif; ?>
												</tbody>
											</table>
											<?=pagination($pager);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>

</div><br><br><!-- End Contact Section -->

<?= $this->include('layout_vertical/footer');?>

<script>
	$("#search").click(function(){
		alert();
		proceed = true;
		var ward_mstr_id = $("#ward_mstr_id").val();
		var saf_no = $("#saf_no").val();
		
		if(ward_mstr_id=="")
		{
			alert("Please Select Ward Number");
			$("#ward_mstr_id").css('border-color', 'red');
			return false;
		}

		if(saf_no=="")
		{
			alert("Please Select Application Number");
			$("#saf_no").css('border-color', 'red');
			return false;
		}
	}
	return process;
	});
	 $("#ward_mstr_id").change(function(){ $(this).css('border-color',''); });
	 $("#saf_no").change(function(){ $(this).css('border-color',''); });
	 
 </script>
 

