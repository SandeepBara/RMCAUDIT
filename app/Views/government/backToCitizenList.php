
<?= $this->include('layout_vertical/header');?>

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
						<li><a href="#">Property</a></li>
						<li><a href="#">GBSAF</a></li>
						<li class="active">Back to citizen list</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

					<div id="page-content">
					<?php
					
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">GB SAF Back to citizen list</h3>
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
														<th>Office Name </th>
														<th>Address </th>
														<th>Assessment Type </th>
														<th>Building Colony Name</th>
														<th>Application Type </th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php if($result):
													$i=1;  ?>
													<?php foreach($result as $post): ?>
														
													<tr>
														<td><?php echo $i++; ?></td>
														<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
														<td><?=$post['application_no']?$post['application_no']:"N/A"; ?></td>
														<td><?=$post['office_name']?$post['office_name']:"N/A"; ?></td>
														<td><?=$post['building_colony_address']?$post['building_colony_address']:"N/A"; ?></td>
														<td><?=$post['assessment_type']?$post['assessment_type']:"N/A"; ?></td>
														<td><?=$post['building_colony_name']?$post['building_colony_name']:"N/A"; ?></td>
														<td><?=$post['application_type']?$post['application_type']:"N/A"; ?></td>
														<td>
															<a href="<?php echo base_url('GsafDocUpload/GBSAFBackToCitizenView/'.md5($post['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
													</tr>
													<?php endforeach; ?>
													<?php else: ?>
													<tr>
														<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
													</tr>

													<?php endif; ?>
												</tbody>
											</table>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					

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
 

