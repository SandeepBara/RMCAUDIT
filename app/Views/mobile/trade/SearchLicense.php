<?=$this->include("layout_mobi/header");?>


<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title">
										Search Trade License
										<a class = "pull-right btn btn-info btn_wait_load" href="<?=base_url()?>/Mobi/mobileMenu/trade"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</a>
									</h5>
					            </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="GET">
                                                <div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> </label>
														<div class="input-group date">
															<input type="text" id="from_date" name="from_date" class="form-control mask_date" placeholder="From Date" value="<?=(isset($fromdate))?$fromdate:date('Y-m-d');?>" readonly>
															<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
														</div>
													</div>
													<div class="col-md-3">
														<label class="control-label" for="to_date"><b>To Date</b> </label>
														<div class="input-group date">
															<input type="text" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=(isset($todate))?$todate:date('Y-m-d');?>" readonly>
															<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
														</div>
													</div>
													 <div class="col-md-3">
															<label class="control-label" for="ward No"><b> Keyword </b><span class="text-danger">*</span> </label>
															<input type="text" name="keyword" id="keyword" class="form-control" value="<?=$keyword ?? NULL;?>" />
													</div>
													<div class="col-md-2">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Applicant List</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<th>Ward No.</th>
												<th>Application No.</th>
												<th>License No.</th>
												<th>Firm Name</th>
												<th>Mobile No.</th>
												<th>Application Type</th>
												<th>Action</th>
												<th></th>
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
												<td><?=$value["firm_name"];?></td>
												<td><?=$value["mobile"];?></td>
												<td><?=$value["application_type"];?></td>
												<td>
													<a class="btn btn-primary btn_wait_load" href="<?php echo base_url('mobitradeapplylicence/trade_licence_view/'.md5($value['id']));?>" role="button">View</a>

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
                </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>


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
			}else{

				$('#btn_search').html('Please Wait...');
			}
        });
		
	});
 </script>

