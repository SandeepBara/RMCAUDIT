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
			<li class="active">Track SAF</li>
			</ol>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<!--End breadcrumb-->
		</div>
		
	 <div id="page-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel">
						<div class="panel-heading">
							<h5 class="panel-title">Track SAF</h5>
						</div>
						<div class="panel-body">
							<div class ="row">
								<div class="col-md-12">
									<form class="form-horizontal" method="post" action="<?=base_url('');?>/TrackSaf/detail">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
											<div class="input-group">
												<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-3">
											<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
											<div class="input-group">
												<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
											</div> 
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
										<div class="col-md-2">
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
												<th rowspan="2">Apply Date</th>
												<th rowspan="2">Application No</th>
												<th rowspan="2">Application Type</th>
												<th style="text-align:center;" colspan="2">Owner Details</th>	
												<th rowspan="2">Payment Status</th>
												<th rowspan="2">Document Upload Status</th>
												<th rowspan="2">Holding No</th>
												<th rowspan="2" style="text-align:center;">Level Status</th>
											</tr>
											<tr>
												
												<th>Owner Name</th>
												<!--<th>Relation Type</th>
												<th>Guardian Name</th>-->
												<th>Mobile No</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(!isset($safDetailsList)):
										?>
										<tr>
											<td colspan="10" style="text-align: center;">Data Not Available!!</td>
										</tr>
										<?php else:
											$i=0;
											foreach ($safDetailsList as $value):
												//print_r($value['ownerdata']);
											//	echo "----".$value['ownerdata']['owner_name'];


										?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$value['ward_no']!=""?$value['ward_no']:"";?></td>
												<td><?=$value['apply_date']!=""?date('d-m-Y',strtotime($value['apply_date'])):"";?></td>
												<td><?=$value['saf_no']!=""?$value['saf_no']:"";?></td>
												<td><?=$value['assessment_type']!=""?$value['assessment_type']:"";?></td>
												
											   <td><?=$value['ownerdata']['owner_name']!=""?$value['ownerdata']['owner_name']:"";?></td>
											  <!-- <td><?=$value['ownerdata']['relation_type']!=""?$value['ownerdata']['relation_type']:"";?></td>
											   <td><?=$value['ownerdata']['guardian_name']!=""?$value['ownerdata']['guardian_name']:"";?></td>-->
											   <td><?=$value['ownerdata']['mobile_no']!=""?$value['ownerdata']['mobile_no']:"";?></td>
											  <!--  <td><?=$val['email']!=""?$val['email']:"";?></td>
											   <td><?=$val['aadhar_no']!=""?$val['aadhar_no']:"";?></td> -->
														
												
												<td>
													<?php if($value['payment_status']==2){ ?>
														Cheque Not Cleared
													<?php }elseif($value['payment_status']==0){ ?>
														<span style="color: red;">Payment Pending</span>
													<?php }else { ?>
														<span style="color: green;">Payment Done</span>
													<?php } ?>
												</td>
												<td><?=$value['doc_upload_status']=='0'?"<span style='color: red;'>Pending</span>":"<span style='color: green;'>Done</span>";?></td>
												<td><?=$value['holding']=="0"?"<span style='color: red;'>Pending</span>":"<span style='color: green;'>".$value['holding']['holding_no']."</span>";?></td>
												<td>
													<?php 
													if($value['dealingStatus']['verification_status']!=""){
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
                                                            if($value['dealingStatus']['verification_status']=='0'){
                                                            echo "<td><span style='color: red;'>Pending</span></td>";
                                                            echo "</tr>";
                                                            }
                                                        else if($value['dealingStatus']['verification_status']=='1'){
                                                            echo "<td><span style='color: green;'>Approved</span></td>";
                                                            echo "</tr>";
                                                        }
                                                        else if($value['dealingStatus']['verification_status']=='2'){
                                                            echo "<td><span style='color: red;'>Back To Citizen</span></td>";
                                                            echo "</tr>";
                                                        }
                                                        if($value['agencyStatus']['verification_status']!=""){
	                                                    echo "<tr>";
	                                                    echo "<td><span style='color: green;'>Agency Tax Collector</span></td>";
	                                                            if($value['agencyStatus']['verification_status']=='0'){
	                                                            echo "<td><span style='color: red;'>Pending</span></td>";
	                                                            echo "</tr>";
	                                                            }
	                                                        else if($value['agencyStatus']['verification_status']=='1'){
	                                                            echo "<td><span style='color: green;'>Approved</span></td>";
	                                                            echo "</tr>";
	                                                        }
	                                                        else if($value['agencyStatus']['verification_status']=='3'){
	                                                            echo "<td><span style='color: red;'>Backward</span></td>";
	                                                            echo "</tr>";
	                                                        }
	                                                    }
	                                                    if($value['ulbTaxStatus']['verification_status']!=""){
	                                                    echo "<tr>";
                                                    echo "<td><span style='color: green;'>ULB Tax Collector</span></td>";
                                                            if($value['ulbTaxStatus']['verification_status']=='0'){
                                                            echo "<td><span style='color: red;'>Pending</span></td>";
                                                            echo "</tr>";
                                                            }
                                                        else if($value['ulbTaxStatus']['verification_status']=='1'){
                                                            echo "<td><span style='color: green;'>Approved</span></td>";
                                                            echo "</tr>";
                                                        }
                                                        else if($value['ulbTaxStatus']['verification_status']=='3'){
                                                            echo "<td><span style='color: red;'>Backward</span></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    if($value['sectionInchargeStatus']['verification_status']!=""){
                                                    echo "<tr>";
                                                    echo "<td><span style='color: green;'>Section Incharge</span></td>";
                                                            if($value['sectionInchargeStatus']['verification_status']=='0'){
                                                            echo "<td><span style='color: red;'>Pending</span></td>";
                                                            echo "</tr>";
                                                            }
                                                        else if($value['sectionInchargeStatus']['verification_status']=='1'){
                                                            echo "<td<span style='color: green;'>Approved</span></td>";
                                                            echo "</tr>";
                                                        }
                                                        else if($value['sectionInchargeStatus']['verification_status']=='3'){
                                                            echo "<td><span style='color: red;'>Backward</span></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    if($value['executiveOfficerStatus']['verification_status']!=""){
                                                    echo "<tr>";
                                                    echo "<td><span style='color: green;'>Executive Officer</span></td>";
                                                            if($value['executiveOfficerStatus']['verification_status']=='0'){
                                                            echo "<td><span style='color: red;'>Pending</span></td>";
                                                            echo "</tr>";
                                                            }
                                                        else if($value['executiveOfficerStatus']['verification_status']=='1'){
                                                            echo "<td><span style='color: green;'>Final Approved</span></td>";
                                                            echo "</tr>";
                                                        }
                                                        else if($value['executiveOfficerStatus']['verification_status']=='3'){
                                                            echo "<td><span style='color: red;'>Backward</span></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    echo "</tbody>";
                                                    echo "</table>"; 
                                                }
                                                else{
                                                	echo "<span style='color:red;'>Pending</span>";
                                                }
													?>
												</td>
											</tr>
											<?php endforeach;?>
											<?php endif;  ?>
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