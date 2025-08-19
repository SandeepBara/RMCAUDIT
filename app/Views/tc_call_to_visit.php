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
                    <li><a href="#">Internal Inbox</a></li>
                    <li class="active">Citizen request TC for payment collection Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Search </h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/tc_call_for_payment_collection/citizenRequest_list">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<label class="col-md-2 text-bold">From Date</label>
											<div class="col-md-2 has-success pad-btm">
												<input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $from_date;?>" />
											</div>
											<label class="col-md-2 text-bold">Upto Date</label>
											<div class="col-md-2 has-success pad-btm">
												<input type="date" id="upto_date" name="upto_date" class="form-control" value="<?php echo $to_date;?>" />
											</div>
											<div class="col-md-4">
												<button type="submit" class="btn btn-primary btn-labeled" id="btn_harvest_report" name="btn_harvest_report">View Report</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Search List</h3>
						</div><br/><br/>
						<div class="table-responsive">
							 <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Owner Name</th>
										<th>Mobile No.</th>
										<th>Holding No.</th>
										<th>15 Digit Unique No.</th>
										<th>Address</th>
										<th>Request Date</th>
										<th>Request Time</th>
										<th>TC Accepted Date & Time</th>
										<th>Accepted TC Name</th>
										<th>Subject</th>
										<th>Type</th>
										<th>Remarks</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php if($callTcCitizen_list):
									$i=1; $currnty_date= date('Y-m-d');?>
									<?php foreach($callTcCitizen_list as $value): ?>
									
									<tr style="<?php if($value['shedule_date']==$currnty_date){ ?> background-color:#cdf5cd;color:red; <?php } ?>">
										<td><?php echo $i++; ?></td>
										<td><?=$value['owner_name']?$value['owner_name']:"N/A"; ?></td>
										<td><?=$value['mobile_no']?$value['mobile_no']:"N/A"; ?></td>
										<td><?=$value['holding_no']?$value['holding_no']:"N/A"; ?></td>
										<td><?=$value['new_holding_no']?$value['new_holding_no']:"N/A"; ?></td>
										<td><?=$value['address']?$value['address']:"N/A"; ?></td>
										<td><?=$value['shedule_date']?$value['shedule_date']:"N/A"; ?></td>
										<td><?=$value['shedule_time']?$value['shedule_time']:"N/A"; ?></td>
										<td><?=$value['accepted_date_time']?$value['accepted_date_time']:"N/A"; ?></td>
										<td><?=$value['emp_name']?$value['emp_name']:"N/A"; ?></td>
										<td><?=$value['subject']?$value['subject']:"N/A"; ?></td>
										<td><?=$value['type']?$value['type']:"N/A"; ?></td>
										<td><?=$value['remarks']?$value['remarks']:"N/A"; ?></td>
										<td>
											<?php if($value['status']==1){ ?>
												<b style ="color:red;"> TC Not Accepted Yet</b>
											<?php } else if($value['status']==2){ ?>
												<?php if($value['shedule_date']==$currnty_date){ ?>
													<b style ="color:red;">Today Is The Day To Visit TC.</b>
												<?php } else { ?>
													<b style ="color:#ff00f7;">TC Accepted</b>
												<?php } ?>
											<?php } else if($value['status']==3){ ?>
												<b style ="color:#0aa72c;">TC Already Visited To Citizen</b>
											<?php } ?>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="14" style="text-align:center;color:red;"> Data Are Not Available!!</td>
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