<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
            <!--===================================================-->
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
                    <li class="active">Visiting Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"><b>Search visiting reports</b></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12">
									<form method="post" action="<?php echo base_url('visiting_report_list/getvisitinglist') ?>">
										<div class="col-sm-12">
											<div class="col-sm-3 pad-btm">
												<label class="col-sm-12" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
												<input type="date" id="from_date" name="from_date" class="form-control"  value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
											</div>
										
											<div class="col-sm-3 pad-btm">
												<label class="col-sm-12" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
												<input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
											</div>
											
											<div class="col-sm-3 pad-btm">
												<label class="col-sm-12" for="tc_name"><b>TC Name</b><span class="text-danger">*</span> </label>
												<select id="tc_name" name="tc_name" class="form-control">
													<option value="">All</option>  
													<?php foreach($tax_collector as $value):?>
													<option value="<?=$value['id']?>" <?=(isset($emp_details_id))?($emp_details_id==$value['id'])?"selected":"":"";?>><?=$value['emp_name'];?>
													</option>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col-sm-3 pad-btm">
												<label class="col-sm-12" for="search">&nbsp;</label>
												<input class="col-sm-12 btn btn-primary" id="search" name="search" type="submit" value="Search">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"><b>Visiting reports list</b></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table id="demo_dt_basic" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
											<thead style="background-color: #e88b58;">
												<tr>
													<th>#</th>
													<th>Reference Number</th>
													<th>Responce</th>
													<th>Address</th>
													<th>Latitude</th>
													<th>Longitude</th>
													<th>IP Address</th>
													<th>TC Name</th>
													<th>Date Time</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if(!isset($visiting_list)):
												?>
													<tr>
														<td colspan="9" style="text-align: center;">Data Not Available!!</td>
													</tr>
												<?php else:
													$i=0;
													foreach ($visiting_list as $value):
												?>
													<tr>
														<td><?=++$i;?></td>
														<td><?=$value['type_no']!=""?$value['type_no']:"N/A";?></td>
														<td><?=$value['work_overview']!=""?$value['work_overview']:"N/A";?></td>
														<td><?=$value['address']!=""?$value['address']:"N/A";?></td>
														<td><?=$value['latitude']!=""?$value['latitude']:"N/A";?></td>
														<td><?=$value['longitude']!=""?$value['longitude']:"N/A";?></td>
														<td><?=$value['ip']!=""?$value['ip']:"N/A";?></td>
														<td><?=$value['emp_name']!=""?$value['emp_name']." ".$value['middle_name']." ".$value['last_name']:"N/A";?></td>
														<td><?=$value['created_on']!=""?$value['created_on']:"N/A";?></td>
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
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }]
        });
    });
	
    $("#search").click(function() {
        var process = true;
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if (from_date > to_date) {
            $("#from_date").css({"border-color":"red"});
            $("#from_date").focus();
			$("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
			alert('"To date" must be greater than "From date"');
            process = false;
          }
        
        return process;
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").keyup(function(){$(this).css('border-color','');});
    
</script>