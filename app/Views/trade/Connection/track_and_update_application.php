<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li><a href="#">Trade </a></li>
					<li class="active">Track Application No.</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title">Track Application No.</h5>
					            </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?php echo base_url('trade_da/track_and_update_application');?>">
                                                <div class="form-group">
													<div class="col-md-8 col-md-offset-2">
														<div class="col-md-8">
															<label class="control-label" for="from_date"><b>Enter Application Or Licence No .</b> </label>
															<input type="text" value="<?=isset($application_no)?$application_no:null?>" id="applcn_no" name="applcn_no" class="form-control" placeholder="Enter Application No.">
															<span style="color: red">
																<?php if(isset($validation)){ ?>
																<?=   $validation; ?>
																<?php } ?>
															</span>
																<?php if(isset($error)){ ?>
																<?=$error?>
																<?php } ?>
														 </div>
														<div class="col-md-4">
															<label class="control-label" for="srchbtn">&nbsp;</label>
															<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
														</div>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
					        </div>
					    </div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-responsive table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>#.</th>
										<th>Application No.</th>
										<th>Licence No.</th>
										<th>Applicant Name</th>
										<th>Father's Name</th>
										<th>Mobile No.</th>
										<th>Firm Name</th>
										<th>Valid Upto</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

										if(isset($id)){
											$i=0;
											foreach($id as $val){ ?>
												<tr>
													<td><?= ++$i; ?></td>
													<td><?= $val['application_no']; ?></td>
													<td><?= $val['license_no']; ?></td>
													<td><?= $val['applicant_name']; ?></td>
													<td><?= $val['father_name']; ?></td>
													<td><?= $val['mobile_no']; ?></td>
													<td><?= $val['firm_name']; ?></td>
													<td><?= $val['validity']; ?></td>
													<td>
														<a href="<?= base_url('Trade_Apply_Licence/updateNewLicence/'.md5($val['id'])) ?>" class="btn btn-primary">
															Update
														</a>
													</td>
												</tr>

									<?php }} ?>

									 
									
								</tbody>
							</table>
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
    function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php
        if($licence=flashToast('licence'))
        {
            echo "modelInfo('".$licence."');";
        }
    ?>
 </script>
