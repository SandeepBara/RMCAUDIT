
<?= $this->include('layout_vertical/header');

?>
<style>
 .row{line-height:25px;}

 .search{


 	padding:10px;



 }
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
					<li><a href="#">Property</a></li>
					<li class="active">Payment Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

<!-- ======= Cta Section ======= -->
<div class="search">
				<div class="row">		
					<div class="col-md-12">
						<form action="" method="post">
							<div class="col-md-1"><span style="color:white;">Date From</span></div>
							<div class="col-md-3">

						 <input type="hidden" name="curr_date" id="curr_date" value="<?php echo $curr_date;?>">
								<input type="date" name="date_from" id="date_from" value="<?php echo $date_from;?>"></div>
							
							<div class="col-md-3">
								<input type="submit" name="Search" id="Search" value="Search" class="btn btn-success">
							</div>
						</form>
					</div>
				</div>

</div>	
								<div id="page-content"> 
									<div class="panel-body">
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<div class="panel-control">
													<a href="" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">List Of Cash Verification Pending Lists</h3>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
															<th scope="col"> Sl No.</th>
															<th scope="col"> Date</th>
															<th scope="col"> Name</th>
															<th scope="col"> Not Verified</th>
															<th scope="col"> Remarks</th>
															<th scope="col"> View</th>
													</thead>
													<tbody>
														<?php
															if($pending_list){
																	$i=1;
																foreach($pending_list as $val)
																{
																?>
																<tr>
																	<td><?php echo $i++;?></td>
																	<td><?php echo date('d-m-Y',strtotime($val['date']));?></td>
																	
																	<td><?php echo $val['emp_name'];?></td>
																	<td><?php echo $val['paid'];?></td>
																	<td><?php echo $val['remarks'];?></td>
																	
																	
															<td><a href="<?php echo base_url('collection_Verification/cash_verf_pending_details/'.md5($val['id']).'/'.md5($val['emp_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>

																</tr>
																<?php	
																}
															}
														?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
			</div>
			
			
  <script type="text/javascript">

  document.getElementById('date_from').onchange = function() {
	
		var date_from=document.getElementById('date_from').value;
		//alert(date_from);
		var curr_date=$("#curr_date").val();

		if(date_from>curr_date)
		{
			alert('Date From Should not be less than Current Date');
			$("#date_from").val("");
		}

	}
	document.getElementById('date_upto').onchange = function() {
	
		var date_upto=document.getElementById('date_upto').value;
		
		var curr_date=$("#curr_date").val();
		
		if(date_upto>curr_date)
		{
			alert('Date Upto Should not be greater than Current Date');
			$("#date_upto").val("");
		}

	}
  </script>
<?= $this->include('layout_vertical/footer');?>
