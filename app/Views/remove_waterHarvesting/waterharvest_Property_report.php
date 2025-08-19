<?= $this->include('layout_vertical/header');?>
<style>
	.row{line-height: 25px;}
	.wardClass{font-size: medium; font-weight: bold;}
	#tdId{font-size: medium; font-weight: bold; text-align: right;}
	#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
	#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
	#left{font-size: medium; font-weight: bold; text-align: left;}
}
</style>
<!-- <style type="text/css" media="print">
.dontprint{ display:none}
</style> -->
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/ExcelExport.js"></script>
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
                    <li class="active">Water Harvest Removed Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Water Harvest Removed Report</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/remove_waterharvesting/waterharvest_Property_reports">
								<div class="row">
									<div class="col-md-8">
										<div class="col-md-6">
											<div class="col-md-4">
												<label class="control-label" for="from_date">From Date<span class="text-danger">*</span></label>
											</div>
											<div class="col-md-8">
												<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from))?$from:date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-md-4">
												<label class="control-label" for="to_date">To Date<span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-8">
												<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to))?$to:date('Y-m-d');?>">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_harvest_report" name="btn_harvest_report">View Report</button>
									</div>
								</div>
								
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Reports List</h3>
						</div><br/><br/>
						<div class="table-responsive">
							 <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Owner Name</th>
										<th>Mobile No.</th>
										<th>Holding No.</th>
										<th>Quater / Financial Year</th>
										<th>Remove Date</th>
										<th>Remarks</th>
										<th>Submit Document</th>
									</tr>
								</thead>
								<tbody>
									<?php if($waterharvest_reports):
									$i=1;?>
									<?php foreach($waterharvest_reports as $value): ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $value['owner_name']; ?></td>
										<td><?php echo $value['mobile_no']; ?></td>
										<td><?php echo $value['holding_no']; ?></td>
										<td><?php echo $value['qtr']; ?> / <?php echo $value['fy']; ?></td>
										<td><?php echo $value['remove_date']; ?></td>
										<td><?php echo $value['remarks']; ?></td>
										<td><a href="<?=base_url();?>/writable/uploads/<?=$value['remove_doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></td>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="9" style="text-align:center;color:red;"> Data Are Not Available!!</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }]
        });
    });
    $('#btn_harvest_report').click(function(){
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
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});

    
</script>