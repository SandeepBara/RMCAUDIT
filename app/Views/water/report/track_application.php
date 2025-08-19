<?=$this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<!-- <link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
 -->
	<div id="content-container">
		<div id="page-head">
			<!--Page Title-->
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="page-title">
			   <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
			</div>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<!--End page title-->

			<!--Breadcrumb-->
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Report</a></li>
			<li class="active">Application Track Status</li>
			</ol>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<!--End breadcrumb-->
		</div>
		
	 <div id="page-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Application Track Status</h5>
						</div>
						<div class="panel-body">
							<div class ="row">
								<div class="col-md-12">
									<form class="form-horizontal" method="post" action="<?=base_url('');?>/WaterApplicantionTrackStatus/detail">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
											<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
											<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
											<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
											<label class="control-label" for="Ward"><b>Ward</b><span class="text-danger">*</span> </label>
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
											   <option value="">ALL</option>  
												<?php foreach($wardList as $value):?>
												<option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
												</option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-md-3">
											<label class="control-label" for="holding">&nbsp;</label>
											<button class="btn btn-primary btn-block" id="btn_saf" name="btn_saf" type="submit">Search</button>
										</div>
									</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="table-responsive">
									<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th rowspan="2">#</th>
												<th rowspan="2">Ward No</th>
												<th rowspan="2" rowspan="2">Apply Date</th>
												<th rowspan="2">Application No</th>
												<th rowspan="2">Category</th>
												<th rowspan="2" style="text-align:center;" colspan="2">Applicant Details</th>
												<th rowspan="2">Document Upload Status</th>
												<th rowspan="2">Payment Status</th>
												<!--<th rowspan="2">Holding/SAF No</th>-->
												<th rowspan="2" style="text-align:center;column-width: 50px">Level Status</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(!isset($applyDetailsList))
										{
											?>
											<tr>
												<td colspan="7" style="text-align: center;">Data Not Available!!</td>
											</tr>
											<?php 
										}
										else
										{
											$i=0;
											foreach ($applyDetailsList as $value)
											{ //print_var($value);die;
											?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=isset($value['ward_no']) && $value['ward_no']!=""?$value['ward_no']:"";?></td>
												<td><?=isset($value['apply_date']) && $value['apply_date']!=""?date('d-m-Y',strtotime($value['apply_date'])):"";?></td>
												<td><?=isset($value['application_no']) && $value['application_no']!=""?$value['application_no']:"";?></td>
												<td><?=isset($value['category']) && $value['category']!=""?$value['category']:"";?></td>
															  
											   <td><?=isset($value['applicant']['applicant_name']) && $value['applicant']['applicant_name']!=""?$value['applicant']['applicant_name']:"";?></td>
											  
											   <td><?=isset($value['applicant']['mobile_no']) && $value['applicant']['mobile_no']!=""?$value['applicant']['mobile_no']:"";?></td>															
											
												<td><?=isset($value['doc_status']) && $value['doc_status']!='0'?"<span style='color: green;'>Done</span>":"<span style='color: red;'>Pending</span>";?></td>
												<td><?=$value['payment_status']!='0'?"<span style='color: green;'>Done</span>":"<span style='color: red;'>Pending</span>";?></td>
												<!--<td><?=$value['holding_no']!=""?$value['holding_no']:$value['saf_no'];?></td>-->
												<td>
													<?php 
													if($value['dealingStatus']['verification_status']!="")
													{
														echo "<table>";
                                                        echo "<thead>";
                                                            echo "<tr>";
                                                                echo "<th>";
                                                                echo "Level";
                                                                echo "</th>";
                                                                echo "<th>";
                                                                echo "Status";
                                                                echo "</th>";
                                                            echo "</tr>";
                                                        echo "</thead>";
                                                        echo "<tbody>";
                                                        echo "</tr>";
                                                            echo "<td><span style='color: green;'>Dealing Assistant</span></td>";
                                                            if($value['dealingStatus']['verification_status']=='0')
															{
																echo "<td><span style='color: red;'>Pending (".date('d-m-Y',strtotime($value['dealingStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
                                                            }
															else if($value['dealingStatus']['verification_status']=='1')
															{
																echo "<td><span style='color: green;'>Approved (".date('d-m-Y',strtotime($value['dealingStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['dealingStatus']['verification_status']=='2')
															{
																echo "<td><span style='color: red;'>Back To Citizen (".date('d-m-Y',strtotime($value['dealingStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
                                                        if($value['juniorStatus']['verification_status']!="")
														{
															echo "<tr>";
															echo "<td><span style='color: green;'>Junior Engineer</span></td>";
															if($value['juniorStatus']['verification_status']=='0')
															{
																echo "<td><span style='color: red;'>Pending (".date('d-m-Y',strtotime($value['juniorStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['juniorStatus']['verification_status']=='1')
															{
																echo "<td><span style='color: green;'>Approved,".date('d-m-Y',strtotime($value['juniorStatus']['forward_date']))."</span></td>";
																echo "</tr>";
															}
															else if($value['juniorStatus']['verification_status']=='3')
															{
																echo "<td><span style='color: red;'>Backward (".date('d-m-Y',strtotime($value['juniorStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
	                                                    }
	                                                    if($value['sectionStatus']['verification_status']!="")
														{
	                                                    	echo "<tr>";
                                                    		echo "<td><span style='color: green;'>Section Head</span></td>";
                                                            if($value['sectionStatus']['verification_status']=='0')
															{
																echo "<td><span style='color: red;'>Pending (".date('d-m-Y',strtotime($value['sectionStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
                                                            }
                                                        	else if($value['sectionStatus']['verification_status']=='1')
															{
																echo "<td><span style='color: green;'>Approved (".date('d-m-Y',strtotime($value['sectionStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
                                                        	}
															else if($value['sectionStatus']['verification_status']=='3')
															{
																echo "<td><span style='color: red;'>Backward (".date('d-m-Y',strtotime($value['sectionStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
                                                    	}
														if($value['assistantStatus']['verification_status']!="")
														{
															echo "<tr>";
															echo "<td><span style='color: green;'>Assistant Engineer</span></td>";
															if($value['assistantStatus']['verification_status']=='0')
															{
																echo "<td><span style='color: red;'>Pending (".date('d-m-Y',strtotime($value['assistantStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['assistantStatus']['verification_status']=='1')
															{
																echo "<td><span style='color: green;'>Approved (".date('d-m-Y',strtotime($value['assistantStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['assistantStatus']['verification_status']=='3')
															{
																echo "<td><span style='color: red;'>Backward (".date('d-m-Y',strtotime($value['assistantStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
														}
														if($value['executiveStatus']['verification_status']!="")
														{
															echo "<tr>";
															echo "<td><span style='color: green;'>Executive Officer</span></td>";
															if($value['executiveStatus']['verification_status']=='0')
															{
																echo "<td><span style='color: red;'>Pending (".date('d-m-Y',strtotime($value['executiveStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['executiveStatus']['verification_status']=='1')
															{
																echo "<td><span style='color: green;'>Final Approved (".date('d-m-Y',strtotime($value['executiveStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
															else if($value['executiveStatus']['verification_status']=='3')
															{
																echo "<td><span style='color: red;'>Backward (".date('d-m-Y',strtotime($value['executiveStatus']['forward_date'])).")</span></td>";
																echo "</tr>";
															}
														}
														echo "</tbody>";
														echo "</table>"; 
													}
													else
													{
														echo "<span style='color: red;'>Pending</span>";
													}
													?>
												</td>
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
			</div>
		</div>
		<!--===================================================-->
		<!--End page content-->
	</div>
	<!--===================================================-->
	<!--END CONTENT CONTAINER-->
<?=$this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<!-- <script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script> -->
<script type="text/javascript">
    /*$(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }]
        });
	});*/
	
	$('#btn_saf').click(function(){
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		if(to_date=="")
		{
			$("#to_date").css({"border-color":"red"});
			$("#to_date").focus();
			return false;
		}
		if(to_date<from_date)
		{
			alert("To Date Should Be Greater Than Or Equals To From Date");
			$("#to_date").css({"border-color":"red"});
			$("#to_date").focus();
			return false;
		}
	});
	
	$("#from_date").change(function(){
		$(this).css('border-color','');
	});
	$("#to_date").change(function(){
		$(this).css('border-color','');
	});
    
</script>