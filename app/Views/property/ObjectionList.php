
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
						<li><a href="#">Holding</a></li>
						<li class="active">Objection List</li>
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
							<h3 class="panel-title">Objection Mail List</h3>
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
														<th>Holding No </th>
														<th>Objection No </th>
														<th>Owner Name </th>
														<th>Mobile No </th>
														<th>Apply Date</th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php 
													if($result):
														$i=0;
														foreach($result as $post):
															?>
															<tr>
																<td><?=++$i;?></td>
																<td><?=$post['ward_no'];?></td>
																<td><?=$post['new_holding_no'];?></td>
																<td><?=$post['objection_no'];?></td>
																<td><?=$post['owner_name'];?></td>
																<td><?=$post['mobile_no'];?></td>
																<td><?=date('Y-m-d', strtotime($post['timestamp']));?></td>
																<td>
																	<a href="<?=base_url('propDtl/ViewObjection/'.md5($post['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
																</td>
															</tr>
															<?php 
														endforeach;
													else:
														?>
														<tr>
															<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
														</tr>
														<?php 
													endif;
													?>
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
 

