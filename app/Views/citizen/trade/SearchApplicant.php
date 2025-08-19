
<?php	
echo  $this->include('layout_home/header');	
?>
<style type="text/css">
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
	.error
	{
		color:red;
	}

</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

					<div id="content-container">
					   
						<!--Page content-->
						<div id="page-content">
						
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title">Search Applicant</h5>
					            </div>
                                <div class="panel-body">
                                    
									<div class="col-md-12">
										<form class="form-horizontal" method="post">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
													<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
													   <option value="">Select</option> 
														<?php foreach($ward_list as $value):?>
														<option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
														</option>
														<?php endforeach;?>
													</select>
													<?php if(isset($error))
													{
														echo $error;
													}
													?>
												</div>
												<div class="col-md-4">
													<label class="control-label" for="keyword"><b>Keywords</b>  <i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Firm Name or Licence No. or Application No."></i></label>
													
													<input type="text" id="keyword" name="keyword" class="form-control" placeholder="(Firm Name, Application No., Licence No.)" value="<?php echo $keyword ?? NULL; ?>">
												</div>
												<div class="col-md-2">
													<label class="control-label" for="department_mstr_id">&nbsp;</label>
													<button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h5 class="panel-title">Search List</h5>
								</div>
								
								<div class="table-responsive">
									<table id="demo_dt_basic" class="table table-bordered" cellspacing="0" width="100%">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<th>Ward No.</th>
												<th>Application No.</th>
												<th>Licence No.</th>
												<th>Owner Name</th>
												<th>Firm Name</th>
												<th>Mobile No.</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(isset($application_details)):
										if(empty($application_details)):
										?>
											<tr>
												<td colspan="7" style="text-align: center;">Data Not Available!!</td>
											</tr>
										<?php else:
											$i=0;
											foreach ($application_details as $value):
										?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$value["ward_no"];?></td>
												<td><?=$value["application_no"];?></td>
												<td><?=$value["license_no"];?></td>
												<td><?=$value["applicant_name"];?></td>
												<td><?=$value["firm_name"];?></td>
												<td><?=$value["mobile_no"];?></td>
												<td>
													<a class="btn btn-primary" href="<?php echo base_url('TradeCitizen/trade_licence_view/'.md5($value['id']));?>" role="button">View</a>

												</td>
											</tr>
											<?php endforeach;?>
											<?php endif;  ?>
											<?php endif;  ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?php
	echo $this->include('layout_home/footer');
 ?>

<!--DataTables [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $('#from_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	$(document).ready(function() {
        $("#from_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#from_date").val('');
            }
        });
        $("#to_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#to_date").val('');
            }
        });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if(from_date=="")
			{
				alert("Please Select From Date");
				$('#from_date').focus();
				return false;
			}

			if(to_date=="")
			{
				alert("Please Select To date");
				$('#to_date').focus();
				return false;
			}
        });
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
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}]
		});
	});
 </script>

